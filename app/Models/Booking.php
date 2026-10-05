<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'event_id',
        'reference',
        'total_amount',
        'tickets_count',
        'status',
        'payment_reference',
        'payment_date',
        'proof_path',
        'admin_notes',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'tickets_count' => 'integer',
            'payment_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pendiente';
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'aprobado';
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rechazado';
    }
}