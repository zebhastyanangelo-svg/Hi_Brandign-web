<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'is_featured',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'price' => 'decimal:2',
            'capacity' => 'integer',
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

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    /**
     * Métodos de pago activos aplicables al evento.
     * Si el evento no tiene métodos asignados, usa los métodos activos globales.
     *
     * @return Collection<int, PaymentMethod>
     */
    public function availablePaymentMethods(): Collection
    {
        $assigned = $this->paymentMethods()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $assigned->isNotEmpty()
            ? $assigned
            : PaymentMethod::query()->active()->orderBy('sort_order')->orderBy('name')->get();
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
}
