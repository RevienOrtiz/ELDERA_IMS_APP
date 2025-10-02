<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'what',
        'when',
        'where',
        'category',
        'department',
        'hasListen',
        'is_active',
        'postedDate',
    ];

    protected $casts = [
        'hasListen' => 'boolean',
        'is_active' => 'boolean',
        'postedDate' => 'string',
    ];
}