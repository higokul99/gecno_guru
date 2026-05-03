<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_pe_transactions', function (Blueprint $table) {
            $table->id();

            // Relation to the booking this payment is for
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Our generated merchant transaction ID (e.g. MT1776218025BK2)
            $table->string('merchant_transaction_id', 100)->unique();

            // PhonePe's own transaction reference (returned after payment)
            $table->string('phonepe_transaction_id', 100)->nullable()->index();

            // Order ID used for PhonePe v2 (merchantOrderId)
            $table->string('merchant_order_id', 100)->nullable();

            // Amount in rupees (decimal), and in paise (integer) for reference
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('amount_paise');

            // Payment status: initiated, completed, failed, pending, cancelled
            $table->string('status', 30)->default('initiated')->index();

            // Payment method details returned by PhonePe (UPI, card, netbanking, etc.)
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_instrument', 100)->nullable();

            // Error details on failure
            $table->string('error_code', 50)->nullable();
            $table->string('error_message')->nullable();

            // Full raw API response from PhonePe (status check)
            $table->json('raw_response')->nullable();

            // Environment: PRODUCTION or UAT
            $table->string('environment', 20)->default('PRODUCTION');

            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phonepe_transactions');
    }
};
