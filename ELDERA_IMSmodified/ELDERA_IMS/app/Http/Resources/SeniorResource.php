<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeniorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'osca_id' => $this->osca_id,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'name_extension' => $this->name_extension,
            'age' => $this->age,
            'gender' => $this->sex,
            'marital_status' => $this->marital_status,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'address' => [
                'region' => $this->region,
                'province' => $this->province,
                'city' => $this->city,
                'barangay' => $this->barangay,
                'residence' => $this->residence,
                'street' => $this->street,
            ],
            'personal_info' => [
                'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
                'birth_place' => $this->birth_place,
                'religion' => $this->religion,
                'ethnic_origin' => $this->ethnic_origin,
                'language' => $this->language,
            ],
            'government_ids' => [
                'gsis_sss' => $this->gsis_sss,
                'tin' => $this->tin,
                'philhealth' => $this->philhealth,
                'sc_association' => $this->sc_association,
                'other_govt_id' => $this->other_govt_id,
            ],
            'employment' => [
                'can_travel' => $this->can_travel,
                'employment' => $this->employment,
                'has_pension' => $this->has_pension,
            ],
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

























