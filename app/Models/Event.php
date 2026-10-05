<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'place',
        'starts_at',
        'price',
        'capacity',
        'image',
        'bank_details',
        'is_featured',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'price' => 'decimal:2',
            'capacity' => 'integer',
            'bank_details' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function approvedBookings(): HasMany
    {
        return $this->hasMany(Booking::class)->where('status', 'aprobado');
    }

    public function getSoldTicketsCountAttribute(): int
    {
        return $this->approvedBookings()->sum('tickets_count');
    }

    public function getAvailableTicketsAttribute(): int
    {
        return max(0, $this->capacity - $this->sold_tickets_count);
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->available_tickets <= 0;
    }

    public function getBankDetailsArrayAttribute(): array
    {
        return $this->bank_details ?? [
            'bank' => '',
            'account' => '',
            'holder' => '',
            'document' => '',
        ];
    }
}