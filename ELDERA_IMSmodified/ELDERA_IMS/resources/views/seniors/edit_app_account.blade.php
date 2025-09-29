<x-sidebar>
    <x-header title="Change App Account Password" icon="fas fa-key">
        <div class="main">
            <div class="app-account-creation-container">
                <div class="header-banner">
                    <h2>ELDERA APP ACCOUNT PASSWORD CHANGE</h2>
                </div>

                <div class="senior-profile-container">
                    <div class="profile-image-container">
                        <div class="profile-image">
                            @if($senior->profile_picture)
                                <img src="{{ asset('storage/' . $senior->profile_picture) }}" alt="Profile Image">
                            @else
                                <div class="image-placeholder">Image</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="senior-info">
                        <h2 class="senior-name">{{ strtoupper($senior->first_name . ' ' . $senior->last_name) }}</h2>
                        <p class="osca-id">OSCA ID: <strong>{{ $senior->osca_id }}</strong></p>
                        <p class="address">ADDRESS: {{ $senior->address }}, {{ ucfirst($senior->barangay) }}, Pangasinan</p>
                    </div>
                </div>

                <div class="account-form-container">
                    <form action="{{ route('senior.app_account.update', $senior->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="osca_id">OSCA ID (Username)</label>
                            <input type="text" id="osca_id" name="osca_id" value="{{ $senior->osca_id }}" readonly class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div class="password-container">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Min 8 chars: 1 uppercase, 1 lowercase, 1 number, 1 special char (@$!%*?&_)" required>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                                    <i class="fa fa-eye" id="password-eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <div class="password-container">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your new password" required>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="fa fa-eye" id="password_confirmation-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                            <a href="{{ route('seniors') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-header>
</x-sidebar>

<script>
    function togglePasswordVisibility(inputId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(inputId + '-eye');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

<style>
    .password-container {
        position: relative;
        display: flex;
        align-items: center;
    }
    .password-toggle {
        position: absolute;
        right: 10px;
        cursor: pointer;
    }
    .password-toggle i {
        color: #666;
    }
    .app-account-creation-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .header-banner {
        background-color: #e91e63;
        color: white;
        padding: 15px;
        text-align: center;
        border-radius: 5px 5px 0 0;
        margin-bottom: 20px;
    }
    
    .header-banner h2 {
        margin: 0;
        font-size: 1.5rem;
    }
    
    .senior-profile-container {
        display: flex;
        margin-bottom: 30px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 5px;
    }
    
    .profile-image-container {
        flex: 0 0 120px;
        margin-right: 20px;
    }
    
    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 5px;
        overflow: hidden;
        background-color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-placeholder {
        color: #777;
        font-size: 14px;
    }
    
    .senior-info {
        flex: 1;
    }
    
    .senior-name {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 1.5rem;
        color: #333;
    }
    
    .osca-id, .address {
        margin: 5px 0;
        color: #555;
    }
    
    .account-form-container {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    .form-actions {
        margin-top: 30px;
        text-align: center;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin: 0 5px;
    }
    
    .btn-primary {
        background-color: #e91e63;
        color: white;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .text-danger {
        color: #dc3545;
        font-size: 14px;
        display: block;
        margin-top: 5px;
    }
</style>