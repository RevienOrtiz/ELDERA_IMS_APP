<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_type' => $this->application_type,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'status_badge_class' => $this->getStatusBadgeClass(),
            'senior' => $this->when($this->senior, new SeniorResource($this->senior)),
            'submitted_by' => $this->when($this->submittedBy, [
                'id' => $this->submittedBy->id,
                'name' => $this->submittedBy->name,
                'email' => $this->submittedBy->email,
            ]),
            'reviewed_by' => $this->when($this->reviewedBy, [
                'id' => $this->reviewedBy->id,
                'name' => $this->reviewedBy->name,
                'email' => $this->reviewedBy->email,
            ]),
            'submitted_at' => $this->submitted_at->format('Y-m-d H:i:s'),
            'reviewed_at' => $this->reviewed_at?->format('Y-m-d H:i:s'),
            'estimated_completion_date' => $this->estimated_completion_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'documents' => $this->when($this->documents, DocumentResource::collection($this->documents)),
            'specific_data' => $this->getSpecificApplicationData(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    protected function getSpecificApplicationData(): ?array
    {
        return match($this->application_type) {
            'senior_id' => $this->seniorIdApplication ? [
                'full_name' => $this->seniorIdApplication->full_name,
                'address' => $this->seniorIdApplication->address,
                'gender' => $this->seniorIdApplication->gender,
                'date_of_birth' => $this->seniorIdApplication->date_of_birth->format('Y-m-d'),
                'birth_place' => $this->seniorIdApplication->birth_place,
                'occupation' => $this->seniorIdApplication->occupation,
                'civil_status' => $this->seniorIdApplication->civil_status,
                'annual_income' => $this->seniorIdApplication->annual_income,
                'formatted_income' => $this->seniorIdApplication->formatted_income,
                'pension_source' => $this->seniorIdApplication->pension_source,
                'ctc_number' => $this->seniorIdApplication->ctc_number,
                'place_of_issuance' => $this->seniorIdApplication->place_of_issuance,
                'contact_number' => $this->seniorIdApplication->contact_number,
                'is_eligible' => $this->seniorIdApplication->isEligible(),
                'income_category' => $this->seniorIdApplication->getIncomeCategory(),
            ] : null,
            'pension' => $this->pensionApplication ? [
                'rrn' => $this->pensionApplication->rrn,
                'monthly_income' => $this->pensionApplication->monthly_income,
                'formatted_monthly_income' => $this->pensionApplication->formatted_monthly_income,
                'has_pension' => $this->pensionApplication->has_pension,
                'pension_source' => $this->pensionApplication->pension_source,
                'pension_amount' => $this->pensionApplication->pension_amount,
                'formatted_pension_amount' => $this->pensionApplication->formatted_pension_amount,
                'is_eligible_for_social_pension' => $this->pensionApplication->isEligibleForSocialPension(),
                'pension_status' => $this->pensionApplication->getPensionStatus(),
                'recommended_pension_amount' => $this->pensionApplication->calculateRecommendedPensionAmount(),
            ] : null,
            'benefits' => $this->benefitsApplication ? [
                'milestone_age' => $this->benefitsApplication->milestone_age,
                'milestone_age_text' => $this->benefitsApplication->milestone_age_text,
                'is_eligible_for_milestone_benefit' => $this->benefitsApplication->isEligibleForMilestoneBenefit(),
                'priority_level' => $this->benefitsApplication->getPriorityLevel(),
            ] : null,
            default => null,
        };
    }
}




