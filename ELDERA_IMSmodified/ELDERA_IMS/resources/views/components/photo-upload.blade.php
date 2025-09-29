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