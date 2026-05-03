<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonePeRefund extends Model
{
    use HasFactory;

    protected $table = 'phone_pe_refunds';

    protected $fillable = [
        'phonepe_transaction_id',
        'booking_id',
        'user_id',
        'merchant_refund_id',
        'phonepe_refund_id',
        'amount',
        'amount_paise',
        'status',
        'reason',
        'initiated_by',
        'raw_response',
        'error_code',
        'error_message',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'amount'       => 'decimal:2',
    ];

    const STATUS_INITIATED  = 'initiated';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_FAILED     = 'failed';
    const STATUS_PENDING    = 'pending';

    public function transaction()
    {
        return $this->belongsTo(PhonePeTransaction::class, 'phonepe_transaction_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }
}
