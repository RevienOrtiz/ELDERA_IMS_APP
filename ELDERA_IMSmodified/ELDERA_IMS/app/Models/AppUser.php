<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AppUser extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'app_users';

    protected $fillable = [
        'osca_id',
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
