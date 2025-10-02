<x-sidebar>
  <x-header title="EDIT SENIOR ID APPLICATION" icon="fas fa-id-card">
    @include('message.popup_message')
    <div class="main">
        <div class="form">
            <div class="form-content">
               <div class="form-section">
                   
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <img src="{{ asset('images/DSWD_LOGO.png') }}" alt="DSWD Logo" class="logo-dswd" style="max-height: 80px;">
                            <div class="text-center flex-grow-1">
                                <div class="title-main" style="color: #e31575; font-size: 24px; font-weight: 800;">APPLICATION FORM</div>
                                <div class="title-main" style="color: #e31575; font-size: 20px; font-weight: 800;">SENIOR CITIZEN ID</div>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <img src="{{ asset('images/Bagong_Pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="logo-bagong-pilipinas" style="max-height: 80px;">
                            </div>
                        </div>
                        <!-- Pink line separator -->
                        <div style="height: 5px; background-color: #e31575; width: 100%; margin: 0 0 -30px 0;"></div>
            </div>
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 20px;">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 20px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 20px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
                <form action="{{ route('seniors.id.update', $application->id) }}" method="POST" id="editSeniorIdForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-step active">
                        <div class="form-section-content">
                            <div class="mb-4">
                                <label class="input-label">Senior Citizen Information</label>
                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <label class="form-label small">Senior Citizen <span class="text-danger">*</span></label>
                                        <div class="form-control-plaintext bg-light p-2">
                                            <strong>{{ $senior->last_name }}, {{ $senior->first_name }} {{ $senior->middle_name ?? '' }}</strong>
                                            <br><small class="text-muted">OSCA ID: {{ $senior->osca_id }} | Barangay: {{ $senior->barangay }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Application Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="">Select Status</option>
                                            <option value="pending" {{ old('status', $application->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="received" {{ old('status', $application->status) == 'received' ? 'selected' : '' }}>Received</option>
                                            <option value="approved" {{ old('status', $application->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="senior_address" placeholder="Address" required class="form-control form-control-sm" value="{{ old('address', $application->seniorIdApplication->address ?? $senior->barangay) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Gender <span class="text-danger">*</span></label>
                                        <input type="text" name="gender" id="senior_gender" required class="form-control form-control-sm" value="{{ old('gender', $senior->sex) }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label small">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" id="senior_date_of_birth" name="date_of_birth" required class="form-control form-control-sm date-picker" value="{{ old('date_of_birth', $senior->date_of_birth ? \Carbon\Carbon::parse($senior->date_of_birth)->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Age</label>
                                        <input type="number" id="senior_age" name="age" readonly class="form-control form-control-sm" value="{{ old('age', $senior->date_of_birth ? \Carbon\Carbon::parse($senior->date_of_birth)->age : '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Birth Place <span class="text-danger">*</span></label>
                                        <input type="text" name="birth_place" id="senior_birth_place" placeholder="Birth Place" required class="form-control form-control-sm" value="{{ old('birth_place', $application->seniorIdApplication->birth_place ?? $senior->birth_place) }}">
                                    </div>
                                </div>
                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label small">Occupation</label>
                                        <input type="text" name="occupation" placeholder="Occupation" class="form-control form-control-sm" value="{{ old('occupation', $application->seniorIdApplication->occupation ?? $senior->employment) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Civil Status <span class="text-danger">*</span></label>
                                        <select name="civil_status" id="senior_civil_status" required class="form-select form-select-sm">
                                            <option value="">Select Civil Status</option>
                                            <option value="Single" {{ old('civil_status', $application->seniorIdApplication->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('civil_status', $application->seniorIdApplication->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widowed" {{ old('civil_status', $application->seniorIdApplication->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="Divorced" {{ old('civil_status', $application->seniorIdApplication->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Separated" {{ old('civil_status', $application->seniorIdApplication->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Annual Income <span class="text-danger">*</span></label>
                                        <input type="number" name="annual_income" id="senior_annual_income" placeholder="Annual Income" required class="form-control form-control-sm" value="{{ old('annual_income', $application->seniorIdApplication->annual_income ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Pension Source</label>
                                        <select name="pension_source" class="form-select form-select-sm">
                                            <option value="">Select Pension Source</option>
                                            <option value="SSS" {{ old('pension_source', $application->seniorIdApplication->pension_source ?? '') == 'SSS' ? 'selected' : '' }}>SSS</option>
                                            <option value="GSIS" {{ old('pension_source', $application->seniorIdApplication->pension_source ?? '') == 'GSIS' ? 'selected' : '' }}>GSIS</option>
                                            <option value="Private" {{ old('pension_source', $application->seniorIdApplication->pension_source ?? '') == 'Private' ? 'selected' : '' }}>Private</option>
                                            <option value="None" {{ old('pension_source', $application->seniorIdApplication->pension_source ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">C.T.C. Number</label>
                                        <input type="text" name="ctc_number" placeholder="Community Tax Certificate Number" class="form-control form-control-sm" value="{{ old('ctc_number', $application->seniorIdApplication->ctc_number ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="input-label">Application Details</label>
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Date of Application</label>
                                        <input type="date" name="date_of_application" value="{{ old('date_of_application', $application->seniorIdApplication->date_of_application ? \Carbon\Carbon::parse($application->seniorIdApplication->date_of_application)->format('Y-m-d') : date('Y-m-d')) }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Place of Issuance <span class="text-danger">*</span></label>
                                        <input type="text" name="place_of_issuance" value="{{ old('place_of_issuance', $application->seniorIdApplication->place_of_issuance ?? 'Municipality of Lingayen, Pangasinan') }}" required class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Date Issued <span class="text-danger">*</span></label>
                                        <input type="date" name="date_of_issued" value="{{ old('date_of_issued', $application->seniorIdApplication->date_of_issued ? \Carbon\Carbon::parse($application->seniorIdApplication->date_of_issued)->format('Y-m-d') : date('Y-m-d')) }}" required class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Date Received <span class="text-danger">*</span></label>
                                        <input type="date" name="date_of_received" value="{{ old('date_of_received', $application->seniorIdApplication->date_of_received ? \Carbon\Carbon::parse($application->seniorIdApplication->date_of_received)->format('Y-m-d') : date('Y-m-d')) }}" required class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="submit-button-container">
                        <div class="d-flex gap-3 justify-content-center">
                            <button type="button" class="btn btn-primary" onclick="confirmUpdate()">SAVE CHANGES</button>
                            <a href="{{ route('seniors') }}" class="btn btn-secondary">BACK</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* ===== MAIN LAYOUT ===== */
        .main {
            margin-left: 250px;
            margin-top: 60px;
            height: calc(100vh - 60px);
            padding: 0;
            display: flex;
            flex-direction: column;
            background: #f5faff;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            border-radius: px;
        }
        
        .form {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .form-content {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }
        .form-section {
            border-radius: 0;
            padding: 24px;
            margin: 0;
        }
        
        /* ===== FORM HEADER ===== */
        .form-header {
            background: linear-gradient(135deg, #e31575 0%, #c01060 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 2px 10px rgba(227, 21, 117, 0.2);
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        /* ===== FORM STEP STYLES ===== */
        .form-step {
            display: block;
            padding: 2.5rem;
        }
        
        .form-section-content {

        }
        
        /* ===== INPUT LABELS ===== */
        .input-label {
            font-size: 16px;
            font-weight: 700 !important;
            display: block !important;
            margin-bottom: 15px !important;
            color: #2c3e50 !important;
            letter-spacing: 0.3px !important;
        }
        
        .form-label.small {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        
        /* ===== ENHANCED FORM CONTROLS WITH INNER SHADOW ===== */
        .form-control, .form-select, .form-control-sm, .form-select-sm {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #ffb7ce;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            margin-bottom: 12px;
            line-height: 1.5;
            background-color: #ffffff;
            transition: all 0.3s ease;
            /* Inner shadow effect */
            box-shadow: inset 0 2px 4px rgba(227, 21, 117, 0.1), inset 0 1px 2px rgba(227, 21, 117, 0.15);
        }

        .form-control:focus, .form-select:focus, .form-control-sm:focus, .form-select-sm:focus {
            outline: none;
            border-color: #e31575;
            background-color: #fefefe;
            /* Enhanced inner shadow on focus */
            box-shadow: inset 0 3px 6px rgba(227, 21, 117, 0.15), inset 0 2px 4px rgba(227, 21, 117, 0.2), 0 0 0 3px rgba(227, 21, 117, 0.12);
            transform: translateY(-1px);
        }

        .form-control:hover, .form-select:hover, .form-control-sm:hover, .form-select-sm:hover {
            border-color: #c01060;
            box-shadow: inset 0 2px 5px rgba(227, 21, 117, 0.12), inset 0 1px 3px rgba(227, 21, 117, 0.18);
        }

        /* Enhanced Select Dropdown Styling */
        .form-select, .form-select-sm {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23e31575' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 45px;
            cursor: pointer;
        }

        .form-select:focus, .form-select-sm:focus {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23c01060' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        }

        /* Textarea Styling */
        textarea.form-control {
            resize: vertical;
            min-height: 90px;
            font-family: inherit;
        }

        /* Radio and Checkbox Enhanced Styling */
        input[type="radio"], input[type="checkbox"] {
            margin-right: 10px;
            accent-color: #e31575;
            transform: scale(1.2);
            cursor: pointer;
        }

        .form-check-input {
            border: 2px solid #e31575;
            box-shadow: inset 0 1px 2px rgba(227, 21, 117, 0.1);
        }

        .form-check-input:checked {
            background-color: #e31575;
            border-color: #e31575;
            box-shadow: inset 0 1px 3px rgba(227, 21, 117, 0.2), 0 0 0 2px rgba(227, 21, 117, 0.1);
        }

        .form-check-input:focus {
            border-color: #c01060;
            box-shadow: inset 0 1px 3px rgba(227, 21, 117, 0.15), 0 0 0 3px rgba(227, 21, 117, 0.12);
        }

        /* Form Labels Enhancement */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .form-label.fw-bold {
            font-weight: 700;
        }
        
        .btn-primary{
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #e31575;
            border-color: #e31575;
            color: white;
            font-weight: bold;
        }
        .btn-primary:hover{
            background-color: #ffb7ce;
            border-color: #ffb7ce;
            color: #e31575;
            font-weight: bold;
        }

        .btn-secondary{
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #333;
            border-color: #333;
            color: white;
            font-weight: bold;
        }
        .btn-secondary:hover{
            background-color: #555;
            border-color: #555;
            color: #fff;
            font-weight: bold;
        }
        
        .submit-button-container {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .main {
                margin: 0;
            }
            
            .form {
                margin: 10px;
                border-radius: 8px;
            }
            
            .form-header {
                padding: 1.5rem 1rem;
                border-radius: 8px 8px 0 0;
            }
            
            .form-title {
                font-size: 1.1rem;
            }
            
            .form-step {
                padding: 1.5rem 1rem;
            }
            
            .row.g-4 {
                --bs-gutter-x: 1rem;
            }
            
            .step-navigation {
                padding: 1rem;
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .step-navigation .btn {
                width: 100%;
                padding: 0.875rem 1rem;
                font-size: 0.85rem;
            }
        }

        /* Date Picker Styles */
        .date-picker {
            position: relative;
        }

        .date-picker::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }

        .date-picker {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23666'%3e%3cpath fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 35px;
        }

        .date-picker:focus {
            border-color: #e31575;
            box-shadow: 0 0 0 0.2rem rgba(227, 21, 117, 0.25);
        }

        .date-picker:hover {
            border-color: #e31575;
        }
    </style>

    <script>
        // Auto-calculate age from date of birth
        function calculateAge() {
            const dateOfBirthInput = document.getElementById('senior_date_of_birth');
            const ageInput = document.getElementById('senior_age');
            
            if (dateOfBirthInput && ageInput && dateOfBirthInput.value) {
                const birthDate = new Date(dateOfBirthInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                // Adjust age if birthday hasn't occurred this year
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                ageInput.value = age;
                console.log('Age calculated:', age, 'for birth date:', dateOfBirthInput.value);
            }
        }

        // Load all seniors on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add age calculation for date of birth
            const dateOfBirthInput = document.getElementById('senior_date_of_birth');
            if (dateOfBirthInput) {
                dateOfBirthInput.addEventListener('change', calculateAge);
                // Calculate age on page load if date is already filled
                if (dateOfBirthInput.value) {
                    calculateAge();
                }
            }
            
            // Add form submission debugging
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Form validation complete
                });
            }
        });

        // Additional event listener for direct input changes
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('senior_date_of_birth');
            if (dateInput) {
                dateInput.addEventListener('input', function() {
                    if (this.value) {
                        calculateAge();
                    }
                });
            }
        });
        
        // Add form submission validation
        const form = document.querySelector('form[method="POST"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Check if status is selected
                const statusSelect = document.querySelector('select[name="status"]');
                if (!statusSelect || !statusSelect.value) {
                    e.preventDefault();
                    document.getElementById('errorMessage').innerText = 'Please select an application status.';
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                    return false;
                }
            });
        }
        
        // Confirmation function for updating senior ID application
        function confirmUpdate() {
            console.log('confirmUpdate called');
            
            // Check if all required fields are filled
            const requiredFields = document.querySelectorAll('input[required], select[required]');
            let missingFields = [];
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    missingFields.push(field.name || field.id);
                }
            });
            
            if (missingFields.length > 0) {
                document.getElementById('errorMessage').innerText = `Please fill in the following required fields: ${missingFields.join(', ')}`;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
                return;
            }
            
            const seniorName = '{{ $senior->first_name }} {{ $senior->last_name }}';
            console.log('Showing confirmation modal for:', seniorName);
            
            showConfirmModal(
                'Update Senior ID Application',
                `Are you sure you want to update ${seniorName}'s senior ID application? This will save all changes made to the form.`,
                '{{ route("seniors.id.update", $application->id) }}',
                'PUT'
            );
        }
    </script>

</x-head>
</x-sidebar>
