<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'senior_id',
        'application_type',
        'status',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'notes',
        'metadata',
        'estimated_completion_date'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'estimated_completion_date' => 'date',
        'metadata' => 'array',
    ];

    // Relationships
    public function senior(): BelongsTo
    {
        return $this->belongsTo(Senior::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function seniorIdApplication(): HasOne
    {
        return $this->hasOne(SeniorIdApplication::class);
    }

    public function pensionApplication(): HasOne
    {
        return $this->hasOne(PensionApplication::class);
    }

    public function benefitsApplication(): HasOne
    {
        return $this->hasOne(BenefitsApplication::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('application_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }


    // Business Logic Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReceived(): bool
    {
        return $this->status === 'received';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }


    public function canBeReviewed(): bool
    {
        return in_array($this->status, ['pending', 'received']);
    }

    public function markAsReceived(User $reviewer): void
    {
        $this->update([
            'status' => 'received',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    public function approve(User $reviewer, string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'notes' => $notes,
        ]);
    }

    public function reject(User $reviewer, string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'notes' => $notes,
        ]);
    }


    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'received' => 'badge-info',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function getStatusText(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'received' => 'Received',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown'
        };
    }

    // Clear dashboard cache when application data changes
    protected static function booted()
    {
        static::created(function () {
            Cache::forget('dashboard_statistics');
        });

        static::updated(function () {
            Cache::forget('dashboard_statistics');
        });

        static::deleted(function () {
            Cache::forget('dashboard_statistics');
        });
    }
}

