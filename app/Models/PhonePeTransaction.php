<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonePeTransaction extends Model
{
    use HasFactory;

    protected $table = 'phone_pe_transactions';

    protected $fillable = [
        'booking_id',
        'user_id',
        'merchant_transaction_id',
        'phonepe_transaction_id',
        'merchant_order_id',
        'amount',
        'amount_paise',
        'status',
        'payment_method',
        'payment_instrument',
        'error_code',
        'error_message',
        'raw_response',
        'environment',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'amount'       => 'decimal:2',
    ];

    // Statuses
    const STATUS_INITIATED  = 'initiated';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_FAILED     = 'failed';
    const STATUS_PENDING    = 'pending';
    const STATUS_CANCELLED  = 'cancelled';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refunds()
    {
        return $this->hasMany(PhonePeRefund::class, 'phonepe_transaction_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
