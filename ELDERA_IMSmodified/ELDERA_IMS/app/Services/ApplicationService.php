<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Senior;
use App\Models\SeniorIdApplication;
use App\Models\PensionApplication;
use App\Models\BenefitsApplication;
use App\Models\Notification;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationService
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function createSeniorIdApplication(array $data, array $files, int $uploadedBy): Application
    {
        return DB::transaction(function () use ($data, $files, $uploadedBy) {
            // Create main application
            
            // Create main application
            $application = Application::create([
                'senior_id' => $data['senior_id'] ?? null,
                'application_type' => 'senior_id',
                'submitted_by' => $uploadedBy,
                'estimated_completion_date' => now()->addDays(30),
            ]);
            
            // Main application created

            // Get senior data for full name
            $senior = null;
            if ($data['senior_id']) {
                $senior = Senior::find($data['senior_id']);
            }

            // Create senior ID application details
            // Create SeniorIdApplication record
            
            $seniorIdApp = SeniorIdApplication::create([
                'application_id' => $application->id,
                'full_name' => $senior ? $senior->full_name : 'N/A',
                'address' => $data['address'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'birth_place' => $data['birth_place'],
                'occupation' => $data['occupation'] ?? null,
                'civil_status' => $data['civil_status'],
                'annual_income' => $data['annual_income'] ?? null,
                'pension_source' => $data['pension_source'] ?? null,
                'ctc_number' => $data['ctc_number'] ?? null,
                'place_of_issuance' => $data['place_of_issuance'] ?? null,
                'date_of_application' => $data['date_of_application'] ?? null,
                'date_of_issued' => $data['date_of_issued'] ?? null,
                'date_of_received' => $data['date_of_received'] ?? null,
            ]);

            // Handle file uploads
            $this->handleFileUploads($files, $application, $uploadedBy);

            // Create notification
            $this->createApplicationNotification($application, 'Senior ID application submitted successfully');

            return $application->load(['seniorIdApplication', 'documents']);
        });
    }

    public function createPensionApplication(array $data, array $files, int $uploadedBy): Application
    {
        return DB::transaction(function () use ($data, $files, $uploadedBy) {
            // Update senior information with form data
            $senior = Senior::findOrFail($data['senior_id']);
            $senior->update([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'name_extension' => $data['name_extension'],
                'date_of_birth' => $data['date_of_birth'],
                'birth_place' => $data['place_of_birth'],
                'sex' => ucfirst($data['gender']),
                'marital_status' => $data['civil_status'],
                'contact_number' => $data['contact_number'],
                'osca_id' => $data['osca_id'],
                'other_govt_id' => $data['rrn'],
                // Pension-specific fields
                'permanent_income' => $data['permanent_income'] ?? null,
                'income_amount' => $data['income_amount'] ?? null,
                'income_source' => $data['income_source'] ?? null,
                'existing_illness' => $data['existing_illness'] ?? null,
                'illness_specify' => $data['illness_specify'] ?? null,
                'with_disability' => $data['with_disability'] ?? null,
                'disability_specify' => $data['disability_specify'] ?? null,
                'living_with' => $data['living_arrangement'] ?? null, // Map living_arrangement to living_with
                'certification' => $data['certification'] ?? false,
            ]);

            // Create main application
            $application = Application::create([
                'senior_id' => $data['senior_id'],
                'application_type' => 'pension',
                'submitted_by' => $uploadedBy,
                'estimated_completion_date' => now()->addDays(15),
            ]);

            // Create pension application details
            $pensionApp = PensionApplication::create([
                'application_id' => $application->id,
                'senior_id' => $data['senior_id'],
                'rrn' => $data['rrn'] ?? null,
                'monthly_income' => $data['monthly_income'],
                'has_pension' => $data['has_pension'] ?? false,
                'pension_source' => $data['pension_source'] ?? null,
                'pension_amount' => $data['pension_amount'] ?? null,
            ]);

            // Handle file uploads
            $this->handleFileUploads($files, $application, $uploadedBy);

            // Create notification
            $this->createApplicationNotification($application, 'Pension application submitted successfully');

            return $application->load(['pensionApplication', 'documents']);
        });
    }

    public function createBenefitsApplication(array $data, array $files, int $uploadedBy): Application
    {
        return DB::transaction(function () use ($data, $files, $uploadedBy) {
            // Create main application
            $application = Application::create([
                'senior_id' => $data['senior_id'],
                'application_type' => 'benefits',
                'submitted_by' => $uploadedBy,
                'estimated_completion_date' => now()->addDays(20),
            ]);

            // Create benefits application with all form data
            $benefitsApp = BenefitsApplication::create([
                'application_id' => $application->id,
                'senior_id' => $data['senior_id'],
                'milestone_age' => $data['milestone_age'],
                // Personal Information
                'rrn' => $data['rrn'] ?? null,
                'osca_id' => $data['osca_id'],
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'name_extension' => $data['name_extension'] ?? null,
                'date_of_birth' => $data['date_of_birth'],
                'age' => $data['age'],
                // Address Information
                'res_house_number' => $data['res_house_number'] ?? null,
                'res_street' => $data['res_street'] ?? null,
                'res_barangay' => $data['res_barangay'] ?? null,
                'res_city' => $data['res_city'] ?? null,
                'res_province' => $data['res_province'] ?? null,
                'res_zip' => $data['res_zip'] ?? null,
                'perm_house_number' => $data['perm_house_number'] ?? null,
                'perm_street' => $data['perm_street'] ?? null,
                'perm_barangay' => $data['perm_barangay'] ?? null,
                'perm_city' => $data['perm_city'] ?? null,
                'perm_province' => $data['perm_province'] ?? null,
                'perm_zip' => $data['perm_zip'] ?? null,
                // Personal Details
                'sex' => $data['sex'],
                'civil_status' => $data['civil_status'],
                'civil_status_others' => $data['civil_status_others'] ?? null,
                'citizenship' => $data['citizenship'],
                'dual_citizenship_details' => $data['dual_citizenship_details'] ?? null,
                // Family Information
                'spouse_name' => $data['spouse_name'] ?? null,
                'spouse_citizenship' => $data['spouse_citizenship'] ?? null,
                'children' => $data['children'] ?? null,
                'authorized_reps' => $data['authorized_reps'] ?? null,
                // Contact Information
                'contact_number' => $data['contact_number'] ?? null,
                'email' => $data['email'] ?? null,
                // Beneficiaries
                'primary_beneficiary' => $data['primary_beneficiary'] ?? null,
                'contingent_beneficiary' => $data['contingent_beneficiary'] ?? null,
                // Utilization
                'utilization' => $data['utilization'] ?? null,
                'utilization_others' => $data['utilization_others'] ?? null,
                // Certification
                'certification' => $data['certification'],
                // Validation Assessment
                'findings_concerns' => $data['findings_concerns'] ?? null,
                'initial_assessment' => $data['initial_assessment'] ?? null,
            ]);

            // Handle file uploads
            $this->handleFileUploads($files, $application, $uploadedBy);

            // Create notification
            $this->createApplicationNotification($application, 'Benefits application submitted successfully');

            return $application->load(['benefitsApplication', 'documents']);
        });
    }

    public function updateApplicationStatus(Application $application, string $status, ?string $notes = null, ?int $reviewedBy = null): Application
    {
        $oldStatus = $application->status;
        
        $application->update([
            'status' => $status,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
            'notes' => $notes,
        ]);

        // Create notification for status change
        $this->createApplicationNotification(
            $application,
            "Application status updated from {$oldStatus} to {$status}"
        );

        return $application->fresh();
    }

    public function getApplicationStatistics(): array
    {
        $total = Application::count();
        $pending = Application::pending()->count();
        $underReview = Application::underReview()->count();
        $approved = Application::approved()->count();
        $rejected = Application::rejected()->count();
        $completed = Application::completed()->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'under_review' => $underReview,
            'approved' => $approved,
            'rejected' => $rejected,
            'completed' => $completed,
            'completion_rate' => $total > 0 ? round((($approved + $completed) / $total) * 100, 2) : 0,
        ];
    }

    public function getApplicationsByType(): array
    {
        return [
            'senior_id' => Application::byType('senior_id')->count(),
            'pension' => Application::byType('pension')->count(),
            'benefits' => Application::byType('benefits')->count(),
        ];
    }

    public function getApplicationsByStatus(): array
    {
        return [
            'pending' => Application::pending()->count(),
            'under_review' => Application::underReview()->count(),
            'approved' => Application::approved()->count(),
            'rejected' => Application::rejected()->count(),
            'completed' => Application::completed()->count(),
        ];
    }

    protected function handleFileUploads(array $files, Application $application, int $uploadedBy): void
    {
        // Handle photo upload
        if (isset($files['photo'])) {
            $this->fileUploadService->uploadDocument(
                $files['photo'],
                'photo',
                $uploadedBy,
                $application->id,
                null,
                $application->senior_id
            );
        }

        // Handle ID documents
        if (isset($files['id_documents'])) {
            $this->fileUploadService->uploadMultipleDocuments(
                $files['id_documents'],
                'id_document',
                $uploadedBy,
                $application->id,
                null,
                $application->senior_id
            );
        }

        // Handle supporting documents
        if (isset($files['supporting_documents'])) {
            $this->fileUploadService->uploadMultipleDocuments(
                $files['supporting_documents'],
                'supporting_document',
                $uploadedBy,
                $application->id,
                null,
                $application->senior_id
            );
        }
    }

    protected function createApplicationNotification(Application $application, string $message): void
    {
        Notification::createApplicationUpdate($application, $message);
    }

    public function validateSeniorIdApplication(array $data): array
    {
        return Validator::make($data, [
            'senior_id' => 'required|integer|exists:seniors,id',
            'address' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
            'date_of_birth' => 'required|date|before:today',
            'birth_place' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'civil_status' => 'required|string|max:50',
            'annual_income' => 'required|numeric|min:0',
            'pension_source' => 'nullable|string|max:255',
            'ctc_number' => 'nullable|string|max:50',
            'place_of_issuance' => 'required|string|max:255',
            'date_of_application' => 'nullable|date',
            'date_of_issued' => 'required|date',
            'date_of_received' => 'required|date',
        ])->validate();
    }

    public function validatePensionApplication(array $data): array
    {
        return Validator::make($data, [
            'senior_id' => 'required|integer|exists:seniors,id',
            'rrn' => 'nullable|string|max:50',
            'monthly_income' => 'required|numeric|min:0',
            'has_pension' => 'required|boolean',
            'pension_source' => 'nullable|string|max:255',
            'pension_amount' => 'nullable|numeric|min:0',
            // Personal information validation
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'age' => 'required|integer|min:60|max:120',
            'gender' => 'required|string|in:male,female',
            'civil_status' => 'required|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'osca_id' => 'required|string|max:50',
            // Pension-specific fields validation
            'permanent_income' => 'nullable|string',
            'income_amount' => 'nullable|string',
            'income_source' => 'nullable|string',
            'existing_illness' => 'required|string|in:yes,no',
            'illness_specify' => 'nullable|string',
            'with_disability' => 'required|string|in:yes,no',
            'disability_specify' => 'nullable|string',
            'living_arrangement' => 'nullable|array',
            'certification' => 'required|boolean',
        ])->validate();
    }

    public function validateBenefitsApplication(array $data): array
    {
        return Validator::make($data, [
            'senior_id' => 'nullable|integer|exists:seniors,id',
            'milestone_age' => 'required|integer|in:80,85,90,95,100',
            // Personal information
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:0|max:150',
            'sex' => 'required|string|in:Male,Female',
            'civil_status' => 'required|string|max:50',
            'civil_status_others' => 'nullable|string|max:255',
            'citizenship' => 'required|string|in:Filipino,Dual',
            'dual_citizenship_details' => 'nullable|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'osca_id' => 'required|string|max:50',
            'rrn' => 'nullable|string|max:50',
            // Address information
            'res_house_number' => 'nullable|string|max:255',
            'res_street' => 'nullable|string|max:255',
            'res_barangay' => 'nullable|string|max:255',
            'res_city' => 'nullable|string|max:255',
            'res_province' => 'nullable|string|max:255',
            'res_zip' => 'nullable|string|max:10',
            'perm_house_number' => 'nullable|string|max:255',
            'perm_street' => 'nullable|string|max:255',
            'perm_barangay' => 'nullable|string|max:255',
            'perm_city' => 'nullable|string|max:255',
            'perm_province' => 'nullable|string|max:255',
            'perm_zip' => 'nullable|string|max:10',
            // Family information
            'spouse_name' => 'nullable|string|max:255',
            'spouse_citizenship' => 'nullable|string|max:100',
            'children' => 'nullable|array',
            'authorized_reps' => 'nullable|array',
            // Beneficiaries
            'primary_beneficiary' => 'nullable|string|max:255',
            'contingent_beneficiary' => 'nullable|string|max:255',
            // Utilization
            'utilization' => 'nullable|array',
            'utilization_others' => 'nullable|string|max:500',
            // Certification
            'certification' => 'required|boolean',
            // Validation Assessment
            'findings_concerns' => 'nullable|string|max:1000',
            'initial_assessment' => 'nullable|in:eligible,ineligible',
        ])->validate();
    }
}

