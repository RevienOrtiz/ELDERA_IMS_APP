<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Link AppUser to Senior via shared OSCA ID.
     */
    public function senior(): HasOne
    {
        return $this->hasOne(Senior::class, 'osca_id', 'osca_id');
    }
}
