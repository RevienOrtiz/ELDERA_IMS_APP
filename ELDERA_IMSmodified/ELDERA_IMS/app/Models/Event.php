<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'organizer',
        'contact_person',
        'contact_number',
        'status',
        'max_participants',
        'current_participants',
        'requirements',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
    ];

    // Relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Senior::class, 'event_participants')
                    ->withPivot(['registered_at', 'attended', 'attendance_notes'])
                    ->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_date', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('event_date', now()->month)
                    ->whereYear('event_date', now()->year);
    }

    // Accessors
    public function getEventTypeTextAttribute(): string
    {
        return match($this->event_type) {
            'general' => 'General Meeting',
            'pension' => 'Pension Distribution',
            'health' => 'Health Check-up',
            'id_claiming' => 'ID Claiming',
            default => 'Unknown'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'upcoming' => 'Upcoming',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => 'Unknown'
        };
    }

    public function getFormattedDateTimeAttribute(): string
    {
        return $this->event_date->format('M d, Y') . ' at ' . $this->start_time->format('g:i A');
    }

    public function getAvailableSlotsAttribute(): int
    {
        if (!$this->max_participants) {
            return -1; // Unlimited
        }
        return max(0, $this->max_participants - $this->current_participants);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->max_participants && $this->current_participants >= $this->max_participants;
    }

    public function getCanRegisterAttribute(): bool
    {
        return $this->status === 'upcoming' && !$this->is_full;
    }

    // Business Logic Methods
    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming';
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canRegister(Senior $senior): bool
    {
        return $this->can_register && !$this->participants()->where('senior_id', $senior->id)->exists();
    }

    public function registerParticipant(Senior $senior): bool
    {
        if (!$this->canRegister($senior)) {
            return false;
        }

        $this->participants()->attach($senior->id, [
            'registered_at' => now(),
            'attended' => false
        ]);

        $this->increment('current_participants');
        return true;
    }

    public function unregisterParticipant(Senior $senior): bool
    {
        $detached = $this->participants()->detach($senior->id);
        if ($detached) {
            $this->decrement('current_participants');
        }
        return $detached > 0;
    }

    public function markAsOngoing(): void
    {
        $this->update(['status' => 'ongoing']);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getAttendanceRate(): float
    {
        if ($this->current_participants === 0) {
            return 0;
        }

        $attendedCount = $this->participants()->wherePivot('attended', true)->count();
        return ($attendedCount / $this->current_participants) * 100;
    }
}

