<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'billing_period', 'capacity', 'featured', 'features', 'sort_order'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'capacity' => 'integer',
            'featured' => 'boolean',
            'features' => 'array',
        ];
    }
}
