<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['name', 'email', 'focus', 'scheduled_at', 'meeting_type', 'notes'];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime'];
    }
}
