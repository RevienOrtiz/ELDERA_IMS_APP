<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'document_type' => $this->document_type,
            'document_type_text' => $this->document_type_text,
            'file_name' => $this->file_name,
            'file_extension' => $this->file_extension,
            'file_size' => $this->file_size,
            'formatted_file_size' => $this->formatted_file_size,
            'mime_type' => $this->mime_type,
            'is_image' => $this->is_image,
            'is_pdf' => $this->is_pdf,
            'url' => $this->getUrl(),
            'download_url' => $this->getDownloadUrl(),
            'thumbnail_url' => $this->getThumbnailUrl(),
            'uploaded_by' => $this->when($this->uploadedBy, [
                'id' => $this->uploadedBy->id,
                'name' => $this->uploadedBy->name,
            ]),
            'uploaded_at' => $this->uploaded_at->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

























