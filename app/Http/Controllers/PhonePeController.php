<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PhonePeTransaction;
use App\Models\PhonePeWebhookLog;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PhonePeController extends Controller
{
    protected PhonePeService $phonePeService;

    public function __construct(PhonePeService $phonePeService)
    {
        $this->phonePeService = $phonePeService;
    }

    // -------------------------------------------------------------------------
    // SHOW PAYMENT FORM
    // -------------------------------------------------------------------------

    public function showPaymentForm()
    {
        return view('payment');
    }

    // -------------------------------------------------------------------------
    // INITIATE PAYMENT
    // -------------------------------------------------------------------------

    public function initiatePayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Security: ensure the booking belongs to the logged-in user
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        Log::info('PhonePe: Initiating payment', [
            'booking_id' => $booking->id,
            'amount'     => $booking->amount,
            'user_id'    => auth()->id(),
        ]);

        $merchantTransactionId = 'MT' . time() . 'BK' . $booking->id;

        try {
            $result = $this->phonePeService->initiatePayment(
                amount:                $booking->amount,
                merchantTransactionId: $merchantTransactionId,
                redirectUrl:           route('payment.callback', ['transaction_id' => $merchantTransactionId]),
                bookingId:             $booking->id,
                userId:                auth()->id()
            );

            if ($result['success']) {
                // Link the transaction ID to the booking for callback lookup
                $booking->update(['transaction_id' => $merchantTransactionId]);

                Log::info('PhonePe: Redirect issued', [
                    'booking_id'             => $booking->id,
                    'merchant_transaction_id'=> $merchantTransactionId,
                    'redirect_url'           => $result['redirectUrl'],
                ]);

                return redirect()->away($result['redirectUrl']);
            }

            Log::error('PhonePe: Payment initiation failed', [
                'booking_id' => $booking->id,
                'message'    => $result['message'] ?? 'Unknown error',
            ]);

            return back()->with('error', $result['message'] ?? 'Payment initiation failed. Please try again.');

        } catch (Throwable $e) {
            Log::error('PhonePe: Unexpected exception in initiatePayment controller', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Something went wrong while initiating payment. Please try again.');
        }
    }

    // -------------------------------------------------------------------------
    // HANDLE CALLBACK (PhonePe redirects user here after payment)
    // -------------------------------------------------------------------------

    public function handleCallback(Request $request)
    {
        // --- 1. Log the raw webhook/callback immediately ---
        $webhookLog = null;
        try {
            $webhookLog = PhonePeWebhookLog::create([
                'merchant_transaction_id' => $request->input('merchantOrderId')
                                          ?? $request->input('merchantTransactionId'),
                'event_type'              => 'payment_callback',
                'http_method'             => $request->method(),
                'payload'                 => $request->all(),
                'headers'                 => $request->headers->all(),
                'processing_status'       => PhonePeWebhookLog::STATUS_RECEIVED,
                'ip_address'              => $request->ip(),
            ]);
        } catch (Throwable $e) {
            Log::error('PhonePe: Failed to log webhook', [
                'error' => $e->getMessage(),
            ]);
        }

        // --- 2. Extract transaction ID ---
        $merchantTransactionId = $request->input('transaction_id')
                              ?? $request->input('merchantOrderId')
                              ?? $request->input('merchantTransactionId');

        if (!$merchantTransactionId) {
            Log::warning('PhonePe Callback: No transaction ID in request', $request->all());

            $this->markWebhookFailed($webhookLog, 'No merchant transaction ID in callback payload');

            return redirect('/')->with('error', 'Invalid payment reference. Please contact support.');
        }

        // --- 3. Verify status server-side (never trust redirect params alone) ---
        try {
            $statusData = $this->phonePeService->verifyStatus($merchantTransactionId);
        } catch (Throwable $e) {
            Log::error('PhonePe Callback: Exception during status verification', [
                'merchant_transaction_id' => $merchantTransactionId,
                'error'                   => $e->getMessage(),
            ]);

            $this->markWebhookFailed($webhookLog, 'Status verification exception: ' . $e->getMessage());

            return redirect('/')->with('error', 'We could not verify your payment. Please contact support.');
        }

        Log::info('PhonePe Callback: Status verified', [
            'merchant_transaction_id' => $merchantTransactionId,
            'status_data'             => $statusData,
        ]);

        // --- 4. Extract state ---
        $state = $statusData['state'] ?? ($statusData['data']['state'] ?? null);

        // --- 5. Find the booking ---
        $booking = Booking::where('transaction_id', $merchantTransactionId)->first();

        if (!$booking) {
            Log::error('PhonePe Callback: Booking not found', [
                'merchant_transaction_id' => $merchantTransactionId,
            ]);

            $this->markWebhookFailed($webhookLog, 'Booking not found for merchant_transaction_id: ' . $merchantTransactionId);

            return redirect('/')->with('error', 'Booking not found for this transaction.');
        }

        // --- 6. Update booking + phonepe_transactions atomically ---
        try {
            DB::transaction(function () use ($booking, $state, $statusData, $merchantTransactionId) {
                // Update booking status
                $bookingStatus = ($state === 'COMPLETED') ? 'paid' : strtolower($state ?? 'failed');
                $booking->update([
                    'status'        => $bookingStatus,
                    'response_data' => json_encode($statusData),
                ]);

                // Update phonepe_transactions record (service already updated it, but ensure consistency)
                PhonePeTransaction::where('merchant_transaction_id', $merchantTransactionId)
                    ->update([
                        'status'      => $this->mapPhonePeState($state),
                        'raw_response'=> $statusData,
                    ]);
            });
        } catch (Throwable $e) {
            Log::error('PhonePe Callback: DB update failed', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            $this->markWebhookFailed($webhookLog, 'DB update exception: ' . $e->getMessage());

            return redirect('/')->with('error', 'Payment was received but could not update records. Please contact support.');
        }

        // --- 7. Mark webhook as processed ---
        $this->markWebhookProcessed($webhookLog);

        // --- 8. Return appropriate view ---
        if ($state === 'COMPLETED') {
            Log::info('PhonePe: Payment COMPLETED', [
                'booking_id'             => $booking->id,
                'merchant_transaction_id'=> $merchantTransactionId,
            ]);

            return view('session_booking.status', [
                'status'  => 'success',
                'booking' => $booking->load('sessionType'),
            ]);
        }

        Log::warning('PhonePe: Payment NOT completed', [
            'booking_id'             => $booking->id,
            'merchant_transaction_id'=> $merchantTransactionId,
            'state'                  => $state,
        ]);

        return redirect()->route('session-booking.payment', ['booking' => $booking->id])
            ->with('error', 'Payment was not successful or was cancelled. Please try again or contact support.');
    }

    // -------------------------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------------------------

    private function markWebhookProcessed(?PhonePeWebhookLog $log): void
    {
        if (!$log) return;
        try {
            $log->markProcessed();
        } catch (Throwable $e) {
            Log::error('PhonePe: Could not mark webhook as processed', ['error' => $e->getMessage()]);
        }
    }

    private function markWebhookFailed(?PhonePeWebhookLog $log, string $reason): void
    {
        if (!$log) return;
        try {
            $log->markFailed($reason);
        } catch (Throwable $e) {
            Log::error('PhonePe: Could not mark webhook as failed', ['error' => $e->getMessage()]);
        }
    }

    private function mapPhonePeState(?string $state): string
    {
        return match (strtoupper($state ?? '')) {
            'COMPLETED' => PhonePeTransaction::STATUS_COMPLETED,
            'PENDING'   => PhonePeTransaction::STATUS_PENDING,
            'CANCELLED' => PhonePeTransaction::STATUS_CANCELLED,
            default     => PhonePeTransaction::STATUS_FAILED,
        };
    }
}
