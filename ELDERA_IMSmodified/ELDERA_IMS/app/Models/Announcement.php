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
        'postedDate',
    ];

    protected $casts = [
        'hasListen' => 'boolean',
        'postedDate' => 'string',
    ];
}