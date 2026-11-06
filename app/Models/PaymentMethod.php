<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentMethod extends Model
{
    public const TYPE_PAGO_MOVIL = 'pago_movil';

    public const TYPE_TRANSFERENCIA = 'transferencia';

    public const TYPE_BINANCE = 'binance_pay';

    public const TYPE_ZELLE = 'zelle';

    protected $fillable = [
        'name',
        'type',
        'bank',
        'tax_id',
        'account_number',
        'account_holder',
        'notes',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_PAGO_MOVIL => 'Pago Móvil',
            self::TYPE_TRANSFERENCIA => 'Transferencia Bancaria',
            self::TYPE_BINANCE => 'Binance Pay',
            self::TYPE_ZELLE => 'Zelle',
            'otro' => 'Otro',
        ];
    }
}
