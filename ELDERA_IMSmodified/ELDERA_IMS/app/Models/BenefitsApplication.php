<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BenefitsApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'senior_id',
        'milestone_age',
        // Personal Information
        'rrn', 'osca_id', 'last_name', 'first_name', 'middle_name', 'name_extension',
        'date_of_birth', 'age',
        // Address Information
        'res_house_number', 'res_street', 'res_barangay', 'res_city', 'res_province', 'res_zip',
        'perm_house_number', 'perm_street', 'perm_barangay', 'perm_city', 'perm_province', 'perm_zip',
        // Personal Details
        'sex', 'civil_status', 'civil_status_others', 'citizenship', 'dual_citizenship_details',
        // Family Information
        'spouse_name', 'spouse_citizenship', 'children', 'authorized_reps',
        // Contact Information
        'contact_number', 'email',
        // Beneficiaries
        'primary_beneficiary', 'contingent_beneficiary',
        // Utilization
        'utilization', 'utilization_others',
        // Certification
        'certification',
        // Validation Assessment
        'findings_concerns', 'initial_assessment',
        // Documentary Requirements
        'applicant_type', 'local_annex_a', 'local_annex_a_remarks', 'local_primary_docs', 'local_primary_docs_remarks',
        'local_id_picture', 'local_id_picture_remarks', 'local_full_body', 'local_full_body_remarks',
        'local_endorsed_list', 'local_endorsed_list_remarks', 'abroad_annex_a', 'abroad_annex_a_remarks',
        'abroad_primary_docs', 'abroad_primary_docs_remarks', 'abroad_id_picture', 'abroad_id_picture_remarks',
        'abroad_full_body', 'abroad_full_body_remarks', 'abroad_endorsed_list', 'abroad_endorsed_list_remarks'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'children' => 'array',
        'authorized_reps' => 'array',
        'utilization' => 'array',
        'certification' => 'boolean',
    ];

    // Relationships
    public function senior(): BelongsTo
    {
        return $this->belongsTo(Senior::class);
    }

    // Note: application() relationship removed since we now use senior_id directly

    // Scopes
    public function scopeByMilestoneAge($query, $age)
    {
        return $query->where('milestone_age', $age);
    }

    public function getMilestoneAgeTextAttribute(): string
    {
        return $this->milestone_age ? "{$this->milestone_age} years old" : 'Not specified';
    }

    // Business Logic
    public function isEligibleForMilestoneBenefit(): bool
    {
        $milestoneAges = [80, 85, 90, 95, 100];
        return in_array($this->milestone_age, $milestoneAges);
    }

    public function getPriorityLevel(): string
    {
        if ($this->milestone_age >= 90) {
            return 'High';
        } elseif ($this->milestone_age >= 80) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }
}

