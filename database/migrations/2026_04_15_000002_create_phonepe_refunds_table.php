<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_pe_refunds', function (Blueprint $table) {
            $table->id();

            // Link to the original transaction being refunded
            $table->foreignId('phonepe_transaction_id')->constrained('phone_pe_transactions')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Our unique refund reference
            $table->string('merchant_refund_id', 100)->unique();

            // PhonePe's refund reference
            $table->string('phonepe_refund_id', 100)->nullable()->index();

            // Refund amount (can be partial)
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('amount_paise');

            // Status: initiated, completed, failed, pending
            $table->string('status', 30)->default('initiated')->index();

            // Reason for refund
            $table->string('reason', 255)->nullable();

            // Admin/staff who initiated the refund
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();

            // Full raw API response
            $table->json('raw_response')->nullable();

            // Error details on failure
            $table->string('error_code', 50)->nullable();
            $table->string('error_message', 255)->nullable();

            $table->timestamps();

            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phonepe_refunds');
    }
};
