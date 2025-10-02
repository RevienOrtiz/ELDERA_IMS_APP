<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    protected ApplicationService $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Store a new senior ID application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeIdApplication(Request $request)
    {
        try {
            $data = $this->applicationService->validateSeniorIdApplication($request->all());
            $files = $request->allFiles();
            $uploadedBy = Auth::id();

            $application = $this->applicationService->createSeniorIdApplication($data, $files, $uploadedBy);

            return response()->json([
                'success' => true,
                'message' => 'Senior ID application submitted successfully',
                'application_id' => $application->id,
                'status' => $application->status,
                'data' => $application->load(['seniorIdApplication', 'documents'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new pension application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePensionApplication(Request $request)
    {
        try {
            $data = $this->applicationService->validatePensionApplication($request->all());
            $files = $request->allFiles();
            $uploadedBy = Auth::id();

            $application = $this->applicationService->createPensionApplication($data, $files, $uploadedBy);

            return response()->json([
                'success' => true,
                'message' => 'Pension application submitted successfully',
                'application_id' => $application->id,
                'status' => $application->status,
                'data' => $application->load(['pensionApplication', 'documents'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new benefits application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeBenefitsApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'senior_id' => 'required|integer',
            'milestone_age' => 'required|integer|in:80,85,90,95,100',
            'supporting_documents' => 'required|array',
            'supporting_documents.*' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // In a real app, this would store the application in the database
        // and handle file uploads

        return response()->json([
            'success' => true,
            'message' => 'Benefits application submitted successfully',
            'application_id' => rand(1000, 9999),  // Mock application ID
            'status' => 'Pending',
        ], 201);
    }

    /**
     * Check the status of an application.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus($id)
    {
        // Mock data - in a real app, this would fetch from the database
        $application = [
            'id' => $id,
            'type' => 'Senior ID',
            'submitted_at' => '2023-11-10 14:30:00',
            'status' => 'Received',
            'notes' => 'Your application is being processed. Please wait for further updates.',
            'estimated_completion' => '2023-11-25',
        ];

        return response()->json([
            'success' => true,
            'data' => $application,
        ]);
    }
}