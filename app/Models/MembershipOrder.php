<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipOrder extends Model
{
    public const STATUS_PROCESSING = 'procesando';

    public const STATUS_PAID = 'pagado';

    public const STATUS_REJECTED = 'rechazado';

    protected $fillable = [
        'reference',
        'membership_slug',
        'membership_name',
        'membership_price',
        'customer_name',
        'customer_email',
        'tax_id',
        'country',
        'city',
        'address',
        'payment_method',
        'card_last4',
        'paypal_email',
        'transfer_reference',
        'discount_code',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total',
        'status',
        'paid_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'membership_price' => 'integer',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function getIsProcessingAttribute(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'tarjeta' => 'Tarjeta Bancaria',
            'paypal' => 'PayPal Express',
            'transferencia' => 'Transferencia VIP',
            default => $this->payment_method,
        };
    }
}
