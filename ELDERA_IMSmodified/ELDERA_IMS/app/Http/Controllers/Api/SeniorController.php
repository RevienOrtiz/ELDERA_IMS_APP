<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Senior;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeniorController extends Controller
{
    /**
     * Display a listing of seniors.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $cacheKey = "api_seniors_page_{$page}";
        
        $data = Cache::remember($cacheKey, 300, function () {
            $seniors = Senior::active()
                ->select(['id', 'osca_id', 'first_name', 'last_name', 'middle_name', 'sex', 'date_of_birth', 'barangay', 'contact_number', 'status'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return [
                'success' => true,
                'data' => $seniors->items(),
                'pagination' => [
                    'current_page' => $seniors->currentPage(),
                    'last_page' => $seniors->lastPage(),
                    'per_page' => $seniors->perPage(),
                    'total' => $seniors->total(),
                ]
            ];
        });

        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=300')
            ->header('ETag', md5(json_encode($data)));
    }

    /**
     * Search seniors by name or OSCA ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $seniors = Senior::active()
            ->where(function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('osca_id', 'LIKE', "%{$query}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
            })
            ->select(['id', 'osca_id', 'first_name', 'last_name', 'barangay'])
            ->limit(10)
            ->get();

        return response()->json($seniors);
    }

    /**
     * Display the specified senior.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $senior = Senior::with(['applications', 'documents', 'events'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new \App\Http\Resources\SeniorResource($senior),
        ]);
    }

    /**
     * Update the specified senior.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $senior = Senior::findOrFail($id);

            // Validate the request data
            $validatedData = $request->validate([
                'last_name' => 'sometimes|required|string|max:255',
                'first_name' => 'sometimes|required|string|max:255',
                'middle_name' => 'sometimes|required|string|max:255',
                'name_extension' => 'nullable|string|max:10',
                'region' => 'sometimes|required|string|max:255',
                'province' => 'sometimes|required|string|max:255',
                'city' => 'sometimes|required|string|max:255',
                'barangay' => 'sometimes|required|string|max:255',
                'residence' => 'sometimes|required|string|max:255',
                'street' => 'nullable|string|max:255',
                'date_of_birth' => 'sometimes|required|date|before:today',
                'birth_place' => 'sometimes|required|string|max:255',
                'marital_status' => 'sometimes|required|string|in:Single,Married,Widowed,Separated,Others',
                'sex' => 'sometimes|required|string|in:Male,Female',
                'contact_number' => 'sometimes|required|string|max:20',
                'email' => 'nullable|email|max:255',
                'religion' => 'nullable|string|max:255',
                'ethnic_origin' => 'nullable|string|max:255',
                'language' => 'sometimes|required|string|max:255',
                'osca_id' => 'sometimes|required|string|max:50|unique:seniors,osca_id,' . $id,
                'gsis_sss' => 'nullable|string|max:50',
                'tin' => 'nullable|string|max:50',
                'philhealth' => 'nullable|string|max:50',
                'sc_association' => 'nullable|string|max:50',
                'other_govt_id' => 'nullable|string|max:50',
                'can_travel' => 'nullable|string|in:Yes,No',
                'employment' => 'nullable|string|max:255',
                'has_pension' => 'nullable|string|in:Yes,No',
                'status' => 'sometimes|required|string|in:active,inactive,deceased',
            ]);

            // Convert boolean fields properly
            if (isset($validatedData['can_travel'])) {
                $validatedData['can_travel'] = $validatedData['can_travel'] === 'Yes' ? true : false;
            }
            if (isset($validatedData['has_pension'])) {
                $validatedData['has_pension'] = $validatedData['has_pension'] === 'Yes' ? true : false;
            }

            $senior->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Senior citizen record updated successfully',
                'data' => new \App\Http\Resources\SeniorResource($senior->fresh())
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update senior record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload document for a senior.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadDocument(Request $request, $id)
    {
        try {
            $senior = Senior::findOrFail($id);

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
                'document_type' => 'required|string|in:photo,id_document,supporting_document,medical_certificate,other',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fileUploadService = app(\App\Services\FileUploadService::class);
            $document = $fileUploadService->uploadDocument(
                $request->file('file'),
                $request->input('document_type'),
                \Illuminate\Support\Facades\Auth::id(),
                null, // application_id
                null, // event_id
                $senior->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'id' => $document->id,
                    'filename' => $document->filename,
                    'original_name' => $document->original_name,
                    'file_path' => $document->file_path,
                    'file_size' => $document->file_size,
                    'mime_type' => $document->mime_type,
                    'document_type' => $document->document_type,
                    'uploaded_at' => $document->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get documents for a senior.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDocuments($id)
    {
        try {
            $senior = Senior::findOrFail($id);
            $documents = $senior->documents()->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $documents->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'filename' => $document->filename,
                        'original_name' => $document->original_name,
                        'file_path' => $document->file_path,
                        'file_size' => $document->file_size,
                        'mime_type' => $document->mime_type,
                        'document_type' => $document->document_type,
                        'uploaded_at' => $document->created_at,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve documents: ' . $e->getMessage()
            ], 500);
        }
    }
}