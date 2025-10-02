@props(['id' => 'photo_upload', 'name' => 'photo', 'class' => '', 'style' => '', 'value' => null])

<div class="photo-upload-wrapper {{ $class }}" style="{{ $style }}">
    <!-- Photo Display Area -->
    <div class="photo-placeholder" id="{{ $id }}_placeholder">
        <input type="file" id="{{ $id }}" name="{{ $name }}" accept="image/*" class="photo-upload-hidden" style="display: none;">
        <div class="photo-preview" id="{{ $id }}_preview" style="display: {{ $value ? 'block' : 'none' }};">
            <img id="{{ $id }}_image" src="{{ $value ? asset('storage/' . $value) : '' }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
            <button type="button" class="photo-remove-btn" onclick="removePhoto('{{ $id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="photo-upload-prompt" id="{{ $id }}_prompt" onclick="showPhotoOptions('{{ $id }}')" style="display: {{ $value ? 'none' : 'flex' }};">
            <div class="photo-icon"><i class="fas fa-camera"></i></div>
            <div class="photo-text">{{ $value ? 'Change Photo' : 'Upload Photo' }}</div>
        </div>
        <!-- Overlay for changing photo when one exists -->
        <div class="photo-change-overlay" id="{{ $id }}_overlay" onclick="showPhotoOptions('{{ $id }}')" style="display: {{ $value ? 'flex' : 'none' }};">
            <div class="photo-change-text">
                <i class="fas fa-camera me-2"></i>
                Change Photo
            </div>
        </div>
    </div>
</div>

<!-- OCR Processing Modal -->
<div id="{{ $id }}_ocr_modal" class="photo-modal" style="display: none;">
    <div class="photo-modal-content">
        <div class="photo-modal-header">
            <h3>Scan ID Card with OCR</h3>
            <button type="button" class="photo-modal-close" onclick="closeOCRModal('{{ $id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <style>
            .ocr-scanner-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
                padding: 10px;
            }
            .ocr-preview-container {
                width: 100%;
                max-width: 400px;
                height: 300px;
                border: 2px dashed #e31575;
                border-radius: 8px;
                overflow: hidden;
                position: relative;
            }
            .ocr-video, .ocr-canvas {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .ocr-controls {
                display: flex;
                gap: 10px;
                margin-top: 10px;
            }
            .ocr-results {
                width: 100%;
                margin-top: 15px;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }
            .ocr-result-fields {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
            .ocr-result-field {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .ocr-result-field label {
                font-weight: bold;
                min-width: 100px;
            }
            .ocr-result-value {
                flex: 1;
                padding: 5px 10px;
                background-color: white;
                border: 1px solid #ced4da;
                border-radius: 4px;
                min-height: 30px;
            }
            .ocr-apply-btn {
                white-space: nowrap;
            }
        </style>
        <div class="photo-modal-body">
            <div class="ocr-scanner-container">
                <div id="{{ $id }}_ocr_preview" class="ocr-preview-container">
                    <video id="{{ $id }}_ocr_video" class="ocr-video" autoplay playsinline></video>
                    <canvas id="{{ $id }}_ocr_canvas" class="ocr-canvas" style="display: none;"></canvas>
                </div>
                <div class="ocr-controls">
                    <button type="button" class="btn btn-primary" id="{{ $id }}_ocr_capture">
                        <i class="fas fa-camera"></i> Capture
                    </button>
                    <button type="button" class="btn btn-success" id="{{ $id }}_ocr_process" style="display: none;">
                        <i class="fas fa-magic"></i> Process with OCR
                    </button>
                </div>
                <div id="{{ $id }}_ocr_results" class="ocr-results" style="display: none;">
                    <h4>OCR Results</h4>
                    <div class="ocr-result-fields">
                        <div class="ocr-result-field">
                            <label>Name:</label>
                            <div id="{{ $id }}_ocr_name" class="ocr-result-value"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary ocr-apply-btn" data-field="name">Apply</button>
                        </div>
                        <div class="ocr-result-field">
                            <label>ID Number:</label>
                            <div id="{{ $id }}_ocr_id" class="ocr-result-value"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary ocr-apply-btn" data-field="id">Apply</button>
                        </div>
                        <div class="ocr-result-field">
                            <label>Date of Birth:</label>
                            <div id="{{ $id }}_ocr_dob" class="ocr-result-value"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary ocr-apply-btn" data-field="dob">Apply</button>
                        </div>
                        <div class="ocr-result-field">
                            <label>Address:</label>
                            <div id="{{ $id }}_ocr_address" class="ocr-result-value"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary ocr-apply-btn" data-field="address">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// OCR Functionality
function scanWithOCR(id) {
    closePhotoModal(id);
    
    // Show OCR modal
    const ocrModal = document.getElementById(`${id}_ocr_modal`);
    ocrModal.style.display = 'flex';
    
    // Initialize camera
    const video = document.getElementById(`${id}_ocr_video`);
    const canvas = document.getElementById(`${id}_ocr_canvas`);
    const captureBtn = document.getElementById(`${id}_ocr_capture`);
    const processBtn = document.getElementById(`${id}_ocr_process`);
    const resultsDiv = document.getElementById(`${id}_ocr_results`);
    
    // Get user media
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(error) {
                console.error("Error accessing camera:", error);
                alert("Could not access the camera. Please check your permissions.");
            });
    }
    
    // Capture image
    captureBtn.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Show canvas and process button
        canvas.style.display = 'block';
        video.style.display = 'none';
        processBtn.style.display = 'inline-block';
        captureBtn.style.display = 'none';
    });
    
    // Process with OCR
    processBtn.addEventListener('click', function() {
        // Show loading indicator
        processBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Get image data
        const imageData = canvas.toDataURL('image/jpeg');
        
        // Send to backend for OCR processing
        fetch('/api/ocr/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ image: imageData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display OCR results
                document.getElementById(`${id}_ocr_name`).textContent = data.data.name || 'Not detected';
                document.getElementById(`${id}_ocr_id`).textContent = data.data.id_number || 'Not detected';
                document.getElementById(`${id}_ocr_dob`).textContent = data.data.date_of_birth || 'Not detected';
                document.getElementById(`${id}_ocr_address`).textContent = data.data.address || 'Not detected';
                
                // Show results
                resultsDiv.style.display = 'block';
                
                // Set up apply buttons
                setupApplyButtons(id, data.data);
            } else {
                alert('OCR processing failed: ' + data.message);
            }
            
            // Reset button
            processBtn.innerHTML = '<i class="fas fa-magic"></i> Process with OCR';
        })
        .catch(error => {
            console.error('Error processing OCR:', error);
            alert('Error processing OCR. Please try again.');
            processBtn.innerHTML = '<i class="fas fa-magic"></i> Process with OCR';
        });
    });
    
    // Apply OCR results to form fields
    function setupApplyButtons(id, ocrData) {
        document.querySelectorAll('.ocr-apply-btn').forEach(button => {
            button.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                
                switch(field) {
                    case 'name':
                        if (ocrData.name) {
                            const nameParts = ocrData.name.split(' ');
                            if (nameParts.length >= 2) {
                                // Assume last part is last name, first part is first name
                                document.querySelector('input[name="last_name"]').value = nameParts[nameParts.length - 1];
                                document.querySelector('input[name="first_name"]').value = nameParts[0];
                                
                                // If more than 2 parts, assume middle parts are middle name
                                if (nameParts.length > 2) {
                                    document.querySelector('input[name="middle_name"]').value = nameParts.slice(1, -1).join(' ');
                                }
                            }
                        }
                        break;
                    case 'id':
                        if (ocrData.id_number) {
                            document.querySelector('input[name="osca_id"]').value = ocrData.id_number;
                        }
                        break;
                    case 'dob':
                        if (ocrData.date_of_birth) {
                            document.querySelector('input[name="date_of_birth"]').value = ocrData.date_of_birth;
                        }
                        break;
                    case 'address':
                        if (ocrData.address) {
                            document.querySelector('input[name="street"]').value = ocrData.address;
                        }
                        break;
                }
                
                // Use the captured image as the senior's photo
                const photoInput = document.getElementById(id);
                canvas.toBlob(function(blob) {
                    const file = new File([blob], "ocr-captured-image.jpg", { type: "image/jpeg" });
                    
                    // Create a FileList-like object
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                    
                    // Update preview
                    const preview = document.getElementById(`${id}_preview`);
                    const image = document.getElementById(`${id}_image`);
                    const prompt = document.getElementById(`${id}_prompt`);
                    
                    image.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                    prompt.style.display = 'none';
                }, 'image/jpeg');
                
                // Close OCR modal
                closeOCRModal(id);
            });
        });
    }
}

function closeOCRModal(id) {
    const ocrModal = document.getElementById(`${id}_ocr_modal`);
    ocrModal.style.display = 'none';
    
    // Stop camera stream
    const video = document.getElementById(`${id}_ocr_video`);
    if (video.srcObject) {
        const tracks = video.srcObject.getTracks();
        tracks.forEach(track => track.stop());
        video.srcObject = null;
    }
    
    // Reset OCR UI
    document.getElementById(`${id}_ocr_video`).style.display = 'block';
    document.getElementById(`${id}_ocr_canvas`).style.display = 'none';
    document.getElementById(`${id}_ocr_capture`).style.display = 'inline-block';
    document.getElementById(`${id}_ocr_process`).style.display = 'none';
    document.getElementById(`${id}_ocr_results`).style.display = 'none';
}
</script>

<!-- Photo Upload Modal -->
<div id="{{ $id }}_modal" class="photo-modal" style="display: none;">
    <div class="photo-modal-content">
        <div class="photo-modal-header">
            <h3>Select Photo Source</h3>
            <button type="button" class="photo-modal-close" onclick="closePhotoModal('{{ $id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="photo-modal-body">
            <div class="photo-option-grid">
                <button type="button" class="photo-option-btn" onclick="selectFromGallery('{{ $id }}')">
                    <i class="fas fa-images"></i>
                    <span>Gallery</span>
                </button>
                <button type="button" class="photo-option-btn" onclick="selectFromCamera('{{ $id }}')">
                    <i class="fas fa-camera"></i>
                    <span>Camera</span>
                </button>
                <button type="button" class="photo-option-btn" onclick="scanWithOCR('{{ $id }}')">
                    <i class="fas fa-id-card"></i>
                    <span>Scan ID Card</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div id="{{ $id }}_camera_modal" class="photo-modal" style="display: none;">
    <div class="photo-modal-content camera-modal-content">
        <div class="photo-modal-header">
            <h3>Take Photo</h3>
            <button type="button" class="photo-modal-close" onclick="closeCameraModal('{{ $id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="photo-modal-body">
            <div class="camera-container">
                <video id="{{ $id }}_video" autoplay playsinline style="width: 100%; max-width: 400px; border-radius: 8px;"></video>
                <canvas id="{{ $id }}_canvas" style="display: none;"></canvas>
            </div>
            <div class="camera-controls">
                <button type="button" class="camera-btn capture-btn" onclick="capturePhoto('{{ $id }}')">
                    <i class="fas fa-camera"></i> Capture
                </button>
                <button type="button" class="camera-btn cancel-btn" onclick="closeCameraModal('{{ $id }}')">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.photo-upload-wrapper {
    position: relative;
}

.photo-placeholder {
    width: 170px;
    height: 170px;
    border: 2px dashed #ccc;
    border-radius: 8px;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.photo-placeholder:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.photo-upload-prompt {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #666;
    cursor: pointer;
}

.photo-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.photo-text {
    font-size: 12px;
    font-weight: 500;
}

.photo-preview {
    position: relative;
    width: 100%;
    height: 100%;
}

.photo-remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.photo-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-modal-content {
    background: white;
    border-radius: 12px;
    padding: 0;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.camera-modal-content {
    max-width: 500px;
}

.photo-modal-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.photo-modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.photo-modal-close {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #666;
    padding: 5px;
}

.photo-modal-body {
    padding: 20px;
}

.photo-option-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.photo-option-btn {
    padding: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    font-size: 14px;
    color: #333;
}

.photo-option-btn:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
    color: #007bff;
}

.photo-option-btn i {
    font-size: 24px;
}

.camera-container {
    text-align: center;
    margin-bottom: 20px;
}

.camera-controls {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.camera-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.capture-btn {
    background: #007bff;
    color: white;
}

.capture-btn:hover {
    background: #0056b3;
}

.cancel-btn {
    background: #6c757d;
    color: white;
}

.cancel-btn:hover {
    background: #545b62;
}

.photo-change-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    cursor: pointer;
}

.photo-placeholder:hover .photo-change-overlay {
    opacity: 1;
}

.photo-change-text {
    color: white;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
}
</style>

<script>
function showPhotoOptions(id) {
    document.getElementById(id + '_modal').style.display = 'flex';
}

function closePhotoModal(id) {
    document.getElementById(id + '_modal').style.display = 'none';
}

function selectFromGallery(id) {
    closePhotoModal(id);
    document.getElementById(id).click();
}

function selectFromCamera(id) {
    closePhotoModal(id);
    openCamera(id);
}

function openCamera(id) {
    const modal = document.getElementById(id + '_camera_modal');
    const video = document.getElementById(id + '_video');
    
    modal.style.display = 'flex';
    
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            window[id + '_stream'] = stream;
        })
        .catch(err => {
            console.error('Error accessing camera:', err);
            alert('Unable to access camera. Please check permissions.');
            closeCameraModal(id);
        });
}

function closeCameraModal(id) {
    const modal = document.getElementById(id + '_camera_modal');
    const video = document.getElementById(id + '_video');
    
    modal.style.display = 'none';
    
    if (window[id + '_stream']) {
        window[id + '_stream'].getTracks().forEach(track => track.stop());
        delete window[id + '_stream'];
    }
    
    video.srcObject = null;
}

function capturePhoto(id) {
    const video = document.getElementById(id + '_video');
    const canvas = document.getElementById(id + '_canvas');
    const context = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);
    
    canvas.toBlob(blob => {
        const file = new File([blob], 'camera-photo.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById(id).files = dataTransfer.files;
        
        displayPreview(id, URL.createObjectURL(blob));
        closeCameraModal(id);
    }, 'image/jpeg', 0.8);
}

function displayPreview(id, src) {
    const preview = document.getElementById(id + '_preview');
    const prompt = document.getElementById(id + '_prompt');
    const image = document.getElementById(id + '_image');
    
    image.src = src;
    preview.style.display = 'block';
    prompt.style.display = 'none';
}

function removePhoto(id) {
    const preview = document.getElementById(id + '_preview');
    const prompt = document.getElementById(id + '_prompt');
    const input = document.getElementById(id);
    const image = document.getElementById(id + '_image');
    
    input.value = '';
    image.src = '';
    preview.style.display = 'none';
    prompt.style.display = 'flex';
}

// Handle file input change for gallery selection
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
        input.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    displayPreview(input.id, e.target.result);
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
});
</script>