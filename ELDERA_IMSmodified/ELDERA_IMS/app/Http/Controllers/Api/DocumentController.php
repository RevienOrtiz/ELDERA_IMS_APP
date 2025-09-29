<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload a document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240', // 10MB max
                'document_type' => 'required|string|in:photo,id_document,supporting_document,medical_certificate,other',
                'senior_id' => 'nullable|integer|exists:seniors,id',
                'application_id' => 'nullable|integer|exists:applications,id',
                'event_id' => 'nullable|integer|exists:events,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $documentType = $request->input('document_type');
            $uploadedBy = Auth::id();
            $seniorId = $request->input('senior_id');
            $applicationId = $request->input('application_id');
            $eventId = $request->input('event_id');

            $document = $this->fileUploadService->uploadDocument(
                $file,
                $documentType,
                $uploadedBy,
                $applicationId,
                $eventId,
                $seniorId
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
     * Upload multiple documents.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array|min:1|max:10',
                'files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:10240',
                'document_type' => 'required|string|in:photo,id_document,supporting_document,medical_certificate,other',
                'senior_id' => 'nullable|integer|exists:seniors,id',
                'application_id' => 'nullable|integer|exists:applications,id',
                'event_id' => 'nullable|integer|exists:events,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $files = $request->file('files');
            $documentType = $request->input('document_type');
            $uploadedBy = Auth::id();
            $seniorId = $request->input('senior_id');
            $applicationId = $request->input('application_id');
            $eventId = $request->input('event_id');

            $documents = $this->fileUploadService->uploadMultipleDocuments(
                $files,
                $documentType,
                $uploadedBy,
                $applicationId,
                $eventId,
                $seniorId
            );

            $documentData = collect($documents)->map(function ($document) {
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
            });

            return response()->json([
                'success' => true,
                'message' => 'Documents uploaded successfully',
                'data' => $documentData,
                'count' => count($documents)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get documents for a specific entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = \App\Models\Document::query();

            if ($request->filled('senior_id')) {
                $query->where('senior_id', $request->senior_id);
            }

            if ($request->filled('application_id')) {
                $query->where('application_id', $request->application_id);
            }

            if ($request->filled('event_id')) {
                $query->where('event_id', $request->event_id);
            }

            if ($request->filled('document_type')) {
                $query->where('document_type', $request->document_type);
            }

            $documents = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $documents->items(),
                'pagination' => [
                    'current_page' => $documents->currentPage(),
                    'last_page' => $documents->lastPage(),
                    'per_page' => $documents->perPage(),
                    'total' => $documents->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $document = \App\Models\Document::findOrFail($id);
            
            // Check if user has permission to delete this document
            if ($document->uploaded_by !== Auth::id() && !Auth::user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this document'
                ], 403);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }
}
