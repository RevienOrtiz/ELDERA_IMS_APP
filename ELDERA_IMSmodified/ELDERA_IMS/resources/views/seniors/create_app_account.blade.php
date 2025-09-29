<x-sidebar>
    <x-header title="Create App Account" icon="fas fa-user-plus">
        <div class="main">
            <div class="app-account-creation-container">
                <div class="header-banner">
                    <h2>ELDERA APP ACCOUNT CREATION</h2>
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
                    <form action="{{ route('senior.app_account.store', $senior->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="osca_id">OSCA ID:</label>
                            <input type="text" id="osca_id" name="osca_id" value="{{ $senior->osca_id }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Provide a password:</label>
                            <div class="password-container">
                                <input type="password" id="password" name="password" placeholder="Min 8 chars: 1 uppercase, 1 lowercase, 1 number, 1 special char (@$!%*?&_)" required>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                                    <i class="fa fa-eye" id="password-eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Re-enter the password:</label>
                            <div class="password-container">
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="fa fa-eye" id="password_confirmation-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="create-btn">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
            .app-account-creation-container {
                max-width: 800px;
                margin: 0 auto;
            }
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
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 5px;
            }
            
            .profile-image-container {
                flex: 0 0 150px;
                margin-right: 20px;
            }
            
            .profile-image {
                width: 150px;
                height: 150px;
                background-color: #e0e0e0;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 5px;
                overflow: hidden;
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
                font-size: 1.8rem;
                font-weight: bold;
                margin: 0 0 10px 0;
            }
            
            .osca-id, .address {
                margin: 5px 0;
                font-size: 1rem;
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
            }
            
            .form-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 1rem;
            }
            
            .form-group input[readonly] {
                background-color: #f0f0f0;
            }
            
            .error-message {
                color: #e91e63;
                font-size: 0.85rem;
                margin-top: 5px;
                display: block;
            }
            
            .form-actions {
                text-align: center;
                margin-top: 30px;
            }
            
            .create-btn {
                background-color: #e91e63;
                color: white;
                border: none;
                padding: 10px 30px;
                font-size: 1rem;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            
            .create-btn:hover {
                background-color: #d81b60;
            }
        </style>
    </x-header>
</x-sidebar>