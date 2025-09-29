<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeniorIdApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'full_name',
        'address',
        'gender',
        'date_of_birth',
        'birth_place',
        'occupation',
        'civil_status',
        'annual_income',
        'pension_source',
        'ctc_number',
        'place_of_issuance',
        'date_of_application',
        'date_of_issued',
        'date_of_received'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_application' => 'date',
        'date_of_issued' => 'date',
        'date_of_received' => 'date',
        'annual_income' => 'decimal:2',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    // Accessors
    public function getFormattedIncomeAttribute(): string
    {
        return $this->annual_income ? '₱' . number_format($this->annual_income, 2) : 'Not specified';
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    // Business Logic
    public function isEligible(): bool
    {
        return $this->age >= 60;
    }

    public function getIncomeCategory(): string
    {
        if (!$this->annual_income) {
            return 'Not specified';
        }

        if ($this->annual_income < 50000) {
            return 'Low income';
        } elseif ($this->annual_income < 100000) {
            return 'Medium income';
        } else {
            return 'High income';
        }
    }
}

