<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class IncomeNotification extends Model
{
    public const STATUS_PENDING = 'pendiente';

    public const STATUS_APPROVED = 'aprobado';

    public const STATUS_REJECTED = 'rechazado';

    public const RELATIONSHIP_OWN = 'propia';

    public const RELATIONSHIP_THIRD = 'tercero';

    protected $fillable = [
        'reference',
        'membership_slug',
        'membership_name',
        'amount_usd',
        'amount_ves',
        'bcv_rate',
        'payment_method_id',
        'payment_method_name',
        'customer_name',
        'customer_email',
        'customer_phone',
        'bank_reference',
        'payment_date',
        'account_holder_name',
        'account_holder_relationship',
        'proof_path',
        'status',
        'processed_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_usd' => 'decimal:2',
            'amount_ves' => 'decimal:2',
            'bcv_rate' => 'decimal:4',
            'payment_date' => 'date',
            'processed_at' => 'datetime',
        ];
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pendiente de verificación',
            self::STATUS_APPROVED => 'Aprobado',
            self::STATUS_REJECTED => 'Rechazado',
            default => ucfirst($this->status),
        };
    }

    public function getRelationshipLabelAttribute(): string
    {
        return $this->account_holder_relationship === self::RELATIONSHIP_THIRD
            ? 'Cuenta de un tercero'
            : 'Cuenta propia';
    }

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_path ? Storage::disk('public')->url($this->proof_path) : null;
    }

    public function getProofIsPdfAttribute(): bool
    {
        return $this->proof_path !== null && str_ends_with(strtolower($this->proof_path), '.pdf');
    }
}
