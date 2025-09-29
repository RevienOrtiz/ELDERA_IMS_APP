<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isValid()
    {
        return !$this->used && !$this->isExpired();
    }

    public static function generateCode($email)
    {
        // Delete any existing codes for this email
        self::where('email', $email)->delete();
        
        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create new login code (expires in 5 minutes)
        return self::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);
    }
}
