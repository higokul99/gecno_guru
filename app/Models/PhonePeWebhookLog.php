<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonePeWebhookLog extends Model
{
    use HasFactory;

    protected $table = 'phone_pe_webhook_logs';

    protected $fillable = [
        'merchant_transaction_id',
        'event_type',
        'http_method',
        'payload',
        'headers',
        'processing_status',
        'processing_error',
        'ip_address',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
    ];

    const STATUS_RECEIVED   = 'received';
    const STATUS_PROCESSED  = 'processed';
    const STATUS_FAILED     = 'failed';
    const STATUS_IGNORED    = 'ignored';

    public function transaction()
    {
        return $this->belongsTo(PhonePeTransaction::class, 'merchant_transaction_id', 'merchant_transaction_id');
    }

    public function markProcessed(): void
    {
        $this->update(['processing_status' => self::STATUS_PROCESSED]);
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'processing_status' => self::STATUS_FAILED,
            'processing_error'  => $error,
        ]);
    }
}
