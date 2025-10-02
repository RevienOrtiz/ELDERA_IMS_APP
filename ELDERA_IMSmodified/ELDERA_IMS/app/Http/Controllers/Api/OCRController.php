<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OCRController extends Controller
{
    /**
     * Process an image with OCR to extract information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'image' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the image data
            $imageData = $request->input('image');
            
            // Remove the data URL prefix
            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            
            // Decode the base64 image
            $imageData = base64_decode($imageData);
            
            // Save the image temporarily
            $tempFile = tempnam(sys_get_temp_dir(), 'ocr_');
            file_put_contents($tempFile, $imageData);
            
            // In a real implementation, you would use a proper OCR library here
            // For this demo, we'll simulate OCR results
            
            // Simulate OCR processing delay
            sleep(1);
            
            // Simulate OCR results
            $ocrResults = [
                'name' => 'Juan Dela Cruz',
                'id_number' => 'OSCA-' . rand(10000, 99999),
                'date_of_birth' => '1950-' . rand(1, 12) . '-' . rand(1, 28),
                'address' => 'Barangay ' . rand(1, 10) . ', Lingayen, Pangasinan'
            ];
            
            // Clean up the temporary file
            @unlink($tempFile);
            
            return response()->json([
                'success' => true,
                'message' => 'OCR processing completed successfully',
                'data' => $ocrResults
            ]);
            
        } catch (\Exception $e) {
            Log::error('OCR processing error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'OCR processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
}