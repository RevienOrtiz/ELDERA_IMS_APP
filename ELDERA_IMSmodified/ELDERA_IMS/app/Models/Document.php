<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'event_id',
        'senior_id',
        'document_type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function senior(): BelongsTo
    {
        return $this->belongsTo(Senior::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopePhotos($query)
    {
        return $query->where('document_type', 'photo');
    }

    public function scopeIdDocuments($query)
    {
        return $query->where('document_type', 'id_document');
    }

    public function scopeSupportingDocuments($query)
    {
        return $query->where('document_type', 'supporting_document');
    }

    // Accessors
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDocumentTypeTextAttribute(): string
    {
        return match($this->document_type) {
            'photo' => 'Photo',
            'id_document' => 'ID Document',
            'supporting_document' => 'Supporting Document',
            'event_document' => 'Event Document',
            default => 'Unknown'
        };
    }

    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    // Business Logic Methods
    public function getUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getDownloadUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function deleteFile(): bool
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->delete($this->file_path);
        }
        return true;
    }

    public function moveFile(string $newPath): bool
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            $moved = Storage::disk('public')->move($this->file_path, $newPath);
            if ($moved) {
                $this->update(['file_path' => $newPath]);
            }
            return $moved;
        }
        return false;
    }

    public function getThumbnailUrl(): ?string
    {
        if (!$this->is_image) {
            return null;
        }

        $pathInfo = pathinfo($this->file_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }
        
        return $this->getUrl();
    }

    public function generateThumbnail(): bool
    {
        if (!$this->is_image) {
            return false;
        }

        try {
            // For now, we'll skip thumbnail generation without Intervention Image
            // This can be implemented later with a proper image processing library
            Log::info('Thumbnail generation skipped - Intervention Image not available');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to generate thumbnail: ' . $e->getMessage());
            return false;
        }
    }

    public function validateFile(): array
    {
        $errors = [];

        // Check file size (max 2MB)
        if ($this->file_size > 2 * 1024 * 1024) {
            $errors[] = 'File size exceeds 2MB limit';
        }

        // Check MIME type
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($this->mime_type, $allowedMimeTypes)) {
            $errors[] = 'Invalid file type. Only JPEG, PNG, and PDF files are allowed';
        }

        // Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array(strtolower($this->file_extension), $allowedExtensions)) {
            $errors[] = 'Invalid file extension';
        }

        return $errors;
    }
}
