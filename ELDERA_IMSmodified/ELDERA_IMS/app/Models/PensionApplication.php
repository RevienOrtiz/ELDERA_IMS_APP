<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'senior_id',
        'rrn',
        'monthly_income',
        'has_pension',
        'pension_source',
        'pension_amount',
        'permanent_income',
        'income_amount',
        'income_source',
        'existing_illness',
        'illness_specify',
        'with_disability',
        'disability_specify',
        'living_arrangement',
        'certification'
    ];

    protected $casts = [
        'income_amount' => 'decimal:2',
        'permanent_income' => 'string',
        'existing_illness' => 'string',
        'with_disability' => 'string',
        'living_arrangement' => 'array',
        'certification' => 'boolean',
    ];

    // Relationships
    public function senior(): BelongsTo
    {
        return $this->belongsTo(Senior::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    // Accessors
    public function getFormattedMonthlyIncomeAttribute(): string
    {
        return '₱' . number_format($this->monthly_income, 2);
    }

    public function getFormattedPensionAmountAttribute(): string
    {
        return $this->pension_amount ? '₱' . number_format($this->pension_amount, 2) : 'Not specified';
    }

    // Business Logic
    public function isEligibleForSocialPension(): bool
    {
        // Social pension eligibility criteria
        return $this->monthly_income <= 5000 && !$this->has_pension;
    }

    public function getPensionStatus(): string
    {
        if ($this->has_pension) {
            return 'Already receiving pension from ' . ($this->pension_source ?? 'unknown source');
        }
        
        if ($this->isEligibleForSocialPension()) {
            return 'Eligible for social pension';
        }
        
        return 'Not eligible for social pension';
    }

    public function calculateRecommendedPensionAmount(): float
    {
        if ($this->has_pension) {
            return 0; // Already receiving pension
        }

        // Basic social pension amount (this should be configurable)
        $baseAmount = 500;
        
        // Adjust based on income level
        if ($this->monthly_income <= 2000) {
            return $baseAmount;
        } elseif ($this->monthly_income <= 3500) {
            return $baseAmount * 0.8;
        } elseif ($this->monthly_income <= 5000) {
            return $baseAmount * 0.6;
        }
        
        return 0; // Not eligible
    }
}
