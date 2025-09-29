<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadService
{
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/jpg',
        'application/pdf'
    ];

    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    protected int $maxFileSize = 2 * 1024 * 1024; // 2MB

    public function uploadDocument(
        UploadedFile $file,
        string $documentType,
        int $uploadedBy,
        ?int $applicationId = null,
        ?int $eventId = null,
        ?int $seniorId = null
    ): Document {
        $this->validateFile($file);

        $fileName = $this->generateFileName($file);
        $filePath = $this->getStoragePath($documentType, $fileName);
        
        // Store the file
        $storedPath = $file->storeAs(
            dirname($filePath),
            basename($filePath),
            'public'
        );

        // Create document record
        $document = Document::create([
            'application_id' => $applicationId,
            'event_id' => $eventId,
            'senior_id' => $seniorId,
            'document_type' => $documentType,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $storedPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $uploadedBy,
        ]);

        // Generate thumbnail for images
        if ($this->isImage($file)) {
            $this->generateThumbnail($document);
        }

        return $document;
    }

    public function uploadMultipleDocuments(
        array $files,
        string $documentType,
        int $uploadedBy,
        ?int $applicationId = null,
        ?int $eventId = null,
        ?int $seniorId = null
    ): array {
        $documents = [];

        foreach ($files as $file) {
            $documents[] = $this->uploadDocument(
                $file,
                $documentType,
                $applicationId,
                $eventId,
                $seniorId,
                $uploadedBy
            );
        }

        return $documents;
    }

    public function deleteDocument(Document $document): bool
    {
        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete thumbnail if exists
        $this->deleteThumbnail($document);

        // Delete the database record
        return $document->delete();
    }

    public function getDocumentUrl(Document $document): string
    {
        return asset('storage/' . $document->file_path);
    }

    public function getDocumentDownloadUrl(Document $document): string
    {
        return asset('storage/' . $document->file_path);
    }

    protected function validateFile(UploadedFile $file): void
    {
        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException('File size exceeds 2MB limit');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, and PDF files are allowed');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \InvalidArgumentException('Invalid file extension');
        }
    }

    protected function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $randomString = Str::random(10);
        
        return "{$timestamp}_{$randomString}.{$extension}";
    }

    protected function getStoragePath(string $documentType, string $fileName): string
    {
        $year = now()->year;
        $month = now()->format('m');
        
        return "documents/{$documentType}/{$year}/{$month}/{$fileName}";
    }

    protected function isImage(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg']);
    }

    protected function generateThumbnail(Document $document): void
    {
        try {
            // For now, we'll skip thumbnail generation without Intervention Image
            // This can be implemented later with a proper image processing library
            Log::info('Thumbnail generation skipped - Intervention Image not available');
        } catch (\Exception $e) {
            Log::error('Failed to generate thumbnail: ' . $e->getMessage());
        }
    }

    protected function deleteThumbnail(Document $document): void
    {
        if (!$this->isImageByMimeType($document->mime_type)) {
            return;
        }

        $pathInfo = pathinfo($document->file_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }

    protected function isImageByMimeType(string $mimeType): bool
    {
        return in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg']);
    }

    public function getThumbnailUrl(Document $document): ?string
    {
        if (!$this->isImageByMimeType($document->mime_type)) {
            return null;
        }

        $pathInfo = pathinfo($document->file_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }
        
        return $this->getDocumentUrl($document);
    }

    public function cleanupOrphanedFiles(): int
    {
        $deletedCount = 0;
        $documents = Document::all();

        foreach ($documents as $document) {
            if (!Storage::disk('public')->exists($document->file_path)) {
                $this->deleteThumbnail($document);
                $document->delete();
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}
