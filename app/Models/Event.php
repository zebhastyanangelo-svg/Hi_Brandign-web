<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'category', 'description', 'place', 'starts_at', 'is_featured', 'is_published'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }
}
