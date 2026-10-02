<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityMember extends Model
{
    protected $fillable = ['name', 'slug', 'company', 'role', 'category', 'initials', 'tone', 'email', 'photo_url'];
}
