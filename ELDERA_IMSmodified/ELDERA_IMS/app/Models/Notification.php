<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'senior_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function senior(): BelongsTo
    {
        return $this->belongsTo(Senior::class);
    }

    public static function createApplicationUpdate(Application $application, string $message): self
    {
        return self::create([
            'user_id' => $application->submitted_by,
            'senior_id' => $application->senior_id,
            'title' => 'Application Update',
            'message' => $message,
            'type' => 'application_update',
        ]);
    }
}
