<?php

namespace App\Http\Controllers;

use App\Services\ApplicationService;
use App\Models\Senior;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    protected ApplicationService $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Store a new senior ID application from web form.
     */
    public function storeSeniorIdApplication(Request $request): RedirectResponse
    {
        try {
            // Process Senior ID application
            
            // Check for existing Senior ID application for this senior
            if ($request->senior_id) {
                $existingIdApp = $this->hasExistingApplication($request->senior_id, 'senior_id');
                if ($existingIdApp) {
                    return redirect()->back()
                        ->with('error', 'This senior already has an existing Senior ID application (ID: ' . $existingIdApp->id . '). Please edit the existing application instead of creating a new one.');
                }
            }

            // Prepare data for validation
            $data = [
                'senior_id' => $request->senior_id ?: null,
                'address' => $request->address,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'birth_place' => $request->birth_place ?? 'Not specified',
                'occupation' => $request->occupation,
                'civil_status' => $request->civil_status,
                'annual_income' => $request->annual_income,
                'pension_source' => $request->pension_source,
                'ctc_number' => $request->ctc_number,
                'place_of_issuance' => $request->place_of_issuance,
                'date_of_application' => $request->date_of_application,
                'date_of_issued' => $request->date_of_issued,
                'date_of_received' => $request->date_of_received,
            ];

            // Validate application data
            
            // Validate the data
            $validatedData = $this->applicationService->validateSeniorIdApplication($data);
            
            // Create application
            
            // Handle file uploads
            $files = [];
            if ($request->hasFile('photo')) {
                $files['photo'] = $request->file('photo');
            }
            if ($request->hasFile('id_documents')) {
                $files['id_documents'] = $request->file('id_documents');
            }
            if ($request->hasFile('supporting_documents')) {
                $files['supporting_documents'] = $request->file('supporting_documents');
            }

            // Create the application
            $application = $this->applicationService->createSeniorIdApplication(
                $validatedData, 
                $files, 
                Auth::check() ? Auth::user()->id : 1 // Default to user ID 1 if not authenticated
            );

            return redirect()->back()
                ->with('success', 'Senior ID application submitted successfully! Application ID: ' . $application->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if (str_contains($e->getMessage(), 'cannot be null')) {
                return redirect()->back()
                    ->with('error', 'Please fill out all required fields completely. Some information is missing.')
                    ->withInput();
            } elseif (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()
                    ->with('error', 'This application already exists. Please check for duplicate entries.')
                    ->withInput();
            } else {
                return redirect()->back()
                    ->with('error', 'Unable to save the application. Please check all required fields and try again.')
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to submit the application. Please fill out all required fields and try again.')
                ->withInput();
        }
    }

    /**
     * Store a new pension application from web form.
     */
    public function storePensionApplication(Request $request): RedirectResponse
    {
        try {
            // Check for existing pension application for this senior
            if ($request->senior_id) {
                $existingPensionApp = $this->hasExistingApplication($request->senior_id, 'pension');
                if ($existingPensionApp) {
                    return redirect()->back()
                        ->with('error', 'This senior already has an existing pension application (ID: ' . $existingPensionApp->id . '). Please edit the existing application instead of creating a new one.');
                }
            }

            // Prepare data for validation
            $data = [
                'senior_id' => $request->senior_id ?: null,
                'rrn' => $request->rrn,
                'monthly_income' => $request->monthly_income ?? 0,
                'has_pension' => $request->has_pension == '1',
                'pension_source' => $request->pension_source,
                'pension_amount' => $request->pension_amount ?? 0,
                // Personal information fields
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'name_extension' => $request->name_extension,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'age' => $request->age,
                'gender' => $request->gender,
                'civil_status' => $request->civil_status,
                'contact_number' => $request->contact_number,
                'osca_id' => $request->osca_id,
                // Pension-specific fields for senior record
                'permanent_income' => $request->permanent_income,
                'income_amount' => $request->income_amount,
                'income_source' => $request->income_source,
                'existing_illness' => $request->existing_illness,
                'illness_specify' => $request->illness_specify,
                'with_disability' => $request->with_disability,
                'disability_specify' => $request->disability_specify,
                'living_arrangement' => $request->living_arrangement, // Array of checkboxes
                'certification' => $request->certification == '1', // Convert to boolean
            ];

            // Validate the data
            $validatedData = $this->applicationService->validatePensionApplication($data);
            
            // Handle file uploads
            $files = [];
            if ($request->hasFile('photo')) {
                $files['photo'] = $request->file('photo');
            }
            if ($request->hasFile('id_documents')) {
                $files['id_documents'] = $request->file('id_documents');
            }
            if ($request->hasFile('supporting_documents')) {
                $files['supporting_documents'] = $request->file('supporting_documents');
            }

            // Create the application
            $application = $this->applicationService->createPensionApplication(
                $validatedData, 
                $files, 
                Auth::check() ? Auth::user()->id : 1
            );

            return redirect()->route('seniors')
                ->with('success', 'Pension application submitted successfully! Application ID: ' . $application->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if (str_contains($e->getMessage(), 'cannot be null')) {
                return redirect()->back()
                    ->with('error', 'Please fill out all required fields completely. Some information is missing.')
                    ->withInput();
            } elseif (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()
                    ->with('error', 'This application already exists. Please check for duplicate entries.')
                    ->withInput();
            } else {
                return redirect()->back()
                    ->with('error', 'Unable to save the application. Please check all required fields and try again.')
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to submit the application. Please fill out all required fields and try again.')
                ->withInput();
        }
    }

    /**
     * Store a new benefits application from web form.
     */
    public function storeBenefitsApplication(Request $request): RedirectResponse
    {
        try {
            // Check if senior has complete data before allowing benefits application
            if ($request->senior_id) {
                $senior = Senior::find($request->senior_id);
                if (!$senior || !$this->hasCompleteSeniorData($senior)) {
                    return redirect()->back()
                        ->with('error', 'Cannot create benefits application. Senior data is incomplete. Please ensure all essential information is filled.');
                }

                // Check for existing benefits application for this senior
                $existingBenefitsApp = $this->hasExistingApplication($request->senior_id, 'benefits');
                if ($existingBenefitsApp) {
                    return redirect()->back()
                        ->with('error', 'This senior already has an existing benefits application (ID: ' . $existingBenefitsApp->id . '). Please edit the existing application instead of creating a new one.');
                }

                // Validate milestone age matches senior's actual age
                $seniorAge = Carbon::parse($senior->date_of_birth)->age;
                $milestoneAge = (int) $request->milestone_age;
                
                if ($seniorAge < $milestoneAge) {
                    return redirect()->back()
                        ->with('error', 'Invalid milestone age. Senior is ' . $seniorAge . ' years old but milestone age is ' . $milestoneAge . '. Milestone age cannot be higher than actual age.');
                }
            }

            // Prepare data for validation
            $data = [
                'senior_id' => $request->senior_id ?: null,
                'milestone_age' => $request->milestone_age,
            ];

            // Validate the data
            $validatedData = $this->applicationService->validateBenefitsApplication($data);
            
            // Handle file uploads
            $files = [];
            if ($request->hasFile('photo')) {
                $files['photo'] = $request->file('photo');
            }
            if ($request->hasFile('id_documents')) {
                $files['id_documents'] = $request->file('id_documents');
            }
            if ($request->hasFile('supporting_documents')) {
                $files['supporting_documents'] = $request->file('supporting_documents');
            }

            // Create the application
            $application = $this->applicationService->createBenefitsApplication(
                $validatedData, 
                $files, 
                Auth::check() ? Auth::user()->id : 1
            );

            return redirect()->back()
                ->with('success', 'Benefits application submitted successfully! Application ID: ' . $application->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if (str_contains($e->getMessage(), 'cannot be null')) {
                return redirect()->back()
                    ->with('error', 'Please fill out all required fields completely. Some information is missing.')
                    ->withInput();
            } elseif (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()
                    ->with('error', 'This application already exists. Please check for duplicate entries.')
                    ->withInput();
            } else {
                return redirect()->back()
                    ->with('error', 'Unable to save the application. Please check all required fields and try again.')
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to submit the application. Please fill out all required fields and try again.')
                ->withInput();
        }
    }

    /**
     * Check if senior has complete essential data for benefits application.
     */
    private function hasCompleteSeniorData(Senior $senior): bool
    {
        return $senior->first_name &&
               $senior->last_name &&
               $senior->date_of_birth &&
               $senior->barangay &&
               $senior->sex &&
               $senior->marital_status &&
               $senior->contact_number;
    }

    /**
     * Check if senior already has an existing application of the given type.
     */
    private function hasExistingApplication(int $seniorId, string $applicationType): ?Application
    {
        return Application::where('senior_id', $seniorId)
            ->where('application_type', $applicationType)
            ->whereIn('status', ['pending', 'under_review', 'approved'])
            ->first();
    }
}
