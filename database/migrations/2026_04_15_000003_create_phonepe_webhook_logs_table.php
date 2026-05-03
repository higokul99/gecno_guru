<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_pe_webhook_logs', function (Blueprint $table) {
            $table->id();

            // The merchant transaction ID extracted from the event payload (if available)
            $table->string('merchant_transaction_id', 100)->nullable()->index();

            // Type of event: payment_callback, refund_callback, s2s_notification, etc.
            $table->string('event_type', 50)->default('payment_callback')->index();

            // HTTP method PhonePe used: GET or POST
            $table->string('http_method', 10)->nullable();

            // Full raw payload (query params or POST body) received from PhonePe
            $table->json('payload')->nullable();

            // Headers from PhonePe (useful for verifying authenticity)
            $table->json('headers')->nullable();

            // Status of processing: received, processed, failed, ignored
            $table->string('processing_status', 30)->default('received')->index();

            // Any error that occurred while handling this webhook
            $table->text('processing_error')->nullable();

            // IP address PhonePe sent the request from
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phonepe_webhook_logs');
    }
};
