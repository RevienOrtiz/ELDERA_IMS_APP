<x-sidebar>
  <x-header title="SOCIAL PENSION" icon="fas fa-hand-holding-usd">
    @include('message.popup_message')
    <div class="main">
        <div class="form">
            <div class="form-content">
                <div class="form-section">
                   
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <img src="{{ asset('images/DSWD_LOGO.png') }}" alt="DSWD Logo" class="logo-dswd" style="max-height: 80px;">
                            <div class="text-center flex-grow-1">
                                <div class="title-main" style="color: #e31575; font-size: 24px; font-weight: 800;">APPLICATION FORM</div>
                                <div class="title-main" style="color: #e31575; font-size: 20px; font-weight: 800;">SOCIAL PENSION FOR INDIGENT SENIOR CITIZENS</div>
                            </div>
                            <div class="d-flex gap-2">
                                
                                <img src="{{ asset('images/SOCIAL_PENSION.png') }}" alt="Pension_logo" class="logo-pension" style="max-height: 80px;">
                                <img src="{{ asset('images/Bagong_Pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="logo-bagong-pilipinas" style="max-height: 80px;">
                            </div>
                        </div>
                        <!-- Pink line separator -->
                        <div style="height: 5px; background-color: #e31575; width: 100%; margin: 0 0 30px 0;"></div>

                            <form method="POST" action="{{ route('forms.pension.store') }}" enctype="multipart/form-data" id="pensionForm">
                            @csrf
                            <input type="hidden" name="senior_id" id="selected-senior-id" value="">
                            
                            <!-- Senior Selection Section -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Choose Senior Citizen *</label>
                                <div class="position-relative">
                                    <input type="text" id="senior-search" class="form-control form-control-sm" placeholder="Type to search seniors..." autocomplete="off">
                                    <div id="senior-dropdown" class="dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto; z-index: 1000;">
                                        <!-- Search results will appear here -->
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-start mb-0">
                                <div class="flex-grow-1">
                                    <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">OSCA ID Number*</label>
                                            <input type="text" name="osca_id" id="senior_osca_id" class="form-control form-control-sm" placeholder="OSCA ID Number" required>
                                        </div>
                                        <div>
                                            <label class="form-label fw-bold small">NCSC Registration Reference Number (RRN)</label>
                                            <input type="text" name="rrn" class="form-control form-control-sm" placeholder="(RRN optional)">
                                        </div>
                                    </div>
                                </div>

                                </div>
                                <x-photo-upload id="photo_upload" name="photo" />
                            </div>
                            
                            <!-- I. PERSONAL INFORMATION -->
                            <div class="section-header">I. PERSONAL INFORMATION</div>

                <div class="mb-4">

                <!-- Name Fields -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Last Name*</label>
                        <input type="text" name="last_name" id="senior_last_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">First Name*</label>
                        <input type="text" name="first_name" id="senior_first_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Middle Name*</label>
                        <input type="text" name="middle_name" id="senior_middle_name" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Extension</label>
                        <input type="text" name="name_extension" id="senior_name_extension" class="form-control form-control-sm">
                    </div>
                </div>

                <!-- PERMANENT ADDRESS Section -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">PERMANENT ADDRESS IN THE PHILIPPINES*</h6>
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">House No./Zone/Purok/Sitio</label>
                            <input type="text" name="house_no" class="form-control form-control-sm" placeholder="House No./Zone/Purok/Sitio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Street</label>
                            <input type="text" name="street" class="form-control form-control-sm" placeholder="Street">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Province*</label>
                            <select name="province" class="form-select form-select-sm" required>
                                <option value="pangasinan">Pangasinan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">City/Municipality*</label>
                            <select name="city" class="form-select form-select-sm">
                                <option value="lingayen">Lingayen</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Barangay*</label>
                            <select name="res_barangay" class="form-select form-select-sm">
                                <option value="">Select Barangay</option>
                                <option value="aliwekwek">Aliwekwek</option>
                                <option value="baay">Baay</option>
                                <option value="balangobong">Balangobong</option>
                                <option value="balococ">Balococ</option>
                                <option value="bantayan">Bantayan</option>
                                <option value="basing">Basing</option>
                                <option value="capandanan">Capandanan</option>
                                <option value="domalandan-center">Domalandan Center</option>
                                <option value="domalandan-east">Domalandan East</option>
                                <option value="domalandan-west">Domalandan West</option>
                                <option value="dorongan">Dorongan</option>
                                <option value="dulag">Dulag</option>
                                <option value="estanza">Estanza</option>
                                <option value="lasip">Lasip</option>
                                <option value="libsong-east">Libsong East</option>
                                <option value="libsong-west">Libsong West</option>
                                <option value="malawa">Malawa</option>
                                <option value="malimpuec">Malimpuec</option>
                                <option value="maniboc">Maniboc</option>
                                <option value="matalava">Matalava</option>
                                <option value="naguelguel">Naguelguel</option>
                                <option value="namolan">Namolan</option>
                                <option value="pangapisan-north">Pangapisan North</option>
                                <option value="pangapisan-sur">Pangapisan Sur</option>
                                <option value="poblacion">Poblacion</option>
                                <option value="quibaol">Quibaol</option>
                                <option value="rosario">Rosario</option>
                                <option value="sabangan">Sabangan</option>
                                <option value="talogtog">Talogtog</option>
                                <option value="tonton">Tonton</option>
                                <option value="tumbar">Tumbar</option>
                                <option value="wawa">Wawa</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- DATE OF BIRTH and PLACE OF BIRTH -->
                <div class="row g-2 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Date of Birth*</label>
                        <input type="date" name="date_of_birth" id="senior_date_of_birth" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Place of Birth*</label>
                        <input type="text" name="place_of_birth" id="senior_place_of_birth" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Age*</label>
                        <input type="number" name="age" id="senior_age" class="form-control form-control-sm" required>
                    </div>
                </div>

                <!-- GENDER and CIVIL STATUS -->
                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Gender*</label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="male" id="male" required>
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="female" id="female" required>
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Civil Status*</label>
                        <select name="civil_status" id="senior_civil_status" class="form-select form-select-sm" required>
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                </div>

                <!-- CONTACT NUMBER -->
                <div class="mb-4">
                    <label class="form-label fw-bold small">Contact Number</label>
                    <input type="tel" name="contact_number" id="senior_contact_number" class="form-control form-control-sm">
                </div>

                <!-- MONTHLY INCOME -->
                <div class="mb-4">
                    <label class="form-label fw-bold small">Monthly Income <span class="text-danger">*</span></label>
                    <input type="number" name="monthly_income" class="form-control form-control-sm" placeholder="Enter monthly income" required>
                </div>
                </div>

                                <!-- II. ECONOMIC STATUS -->
                                <div class="section-header">II. ECONOMIC STATUS</div>

                                <div class="row g-2 mb-3">
                                    <!-- Living Arrangement Column -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Living Arrangement <span class="text-danger">*</span></label>
                                        <div class="mt-2">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="living_arrangement[]" value="owned" id="owned">
                                                <label class="form-check-label" for="owned">Owned</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="living_arrangement[]" value="rent" id="rent">
                                                <label class="form-check-label" for="rent">Rent</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="living_arrangement[]" value="living alone" id="living_alone">
                                                <label class="form-check-label" for="living_alone">Living Alone</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="living_arrangement[]" value="living with children or relatives" id="living_with">
                                                <label class="form-check-label" for="living_with">Living With Children Or Relatives</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Receiving Pension Column -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Receiving Pension <span class="text-danger">*</span></label>
                                        <div class="mt-2">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="has_pension" value="1" id="pension_yes" onchange="togglePensionFields()">
                                                <label class="form-check-label small" for="pension_yes">Yes</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="has_pension" value="0" id="pension_no" onchange="togglePensionFields()">
                                                <label class="form-check-label small" for="pension_no">No</label>
                                            </div>
                                            <div id="pensionFields" class="ms-4 mb-3" style="display: none;">
                                                <div class="mb-2">
                                                    <label class="form-label small">How Much <span class="text-danger">*</span></label>
                                                    <input type="text" name="pension_amount" class="form-control form-control-sm">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small">Source <span class="text-danger">*</span></label>
                                                    <input type="text" name="pension_source" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            {{-- <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="pension" value="No" id="pension_no" onchange="togglePensionFields()">
                                                <label class="form-check-label small" for="pension_no">No</label>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <!-- Permanent Income Column -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Permanent Income <span class="text-danger">*</span></label>
                                        <div class="mt-2">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="permanent_income" value="Yes" id="income_yes" onchange="toggleIncomeFields()">
                                                <label class="form-check-label small" for="income_yes">Yes</label>
                                            </div>
                                            <div id="incomeFields" class="ms-4 mb-3" style="display: none;">
                                                <div class="mb-2">
                                                    <label class="form-label small">How Much <span class="text-danger">*</span></label>
                                                    <input type="text" name="income_amount" class="form-control form-control-sm">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small">Source <span class="text-danger">*</span></label>
                                                    <input type="text" name="income_source" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="permanent_income" value="No" id="income_no" onchange="toggleIncomeFields()">
                                                <label class="form-check-label small" for="income_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                
                                <!-- III. HEALTH CONDITION -->
                                <div class="section-header">III. HEALTH CONDITION</div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">With Existing Illness <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="existing_illness" value="yes" id="illness_yes" required>
                                                <label class="form-check-label small" for="illness_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="existing_illness" value="no" id="illness_no" required>
                                                <label class="form-check-label small" for="illness_no">No</label>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label fw-bold small">Specify:</label>
                                            <input type="text" name="illness_specify" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">With Disability <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="with_disability" value="yes" id="disability_yes" required>
                                                <label class="form-check-label small" for="disability_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="with_disability" value="no" id="disability_no" required>
                                                <label class="form-check-label small" for="disability_no">No</label>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label fw-bold small">Specify:</label>
                                            <input type="text" name="disability_specify" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                
                                <!-- CERTIFICATION -->
                                <div class="alert alert-light border mb-3">
                                    <div class="form-check" style="display: flex; align-items: top; gap: 8px;">
                                        <input class="form-check-input" type="checkbox" name="certification" id="certification" required style="margin-top: 0; margin-bottom: 0;">
                                        <label class="form-check-label small mb-0" for="certification" style="display: flex; align-items: top;">
                                            I hereby certify that the above-mentioned information is true and correct to the best of my knowledge, and I hereby authorize the verification of the information provided in this application form. <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Save Button -->
                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold" onclick="confirmSubmit()">SAVE APPLICATION</button>
                                </div>
                            </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        body { margin: 0; }

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
        
        .section-header {
            background: #e31575;
            color: #fff;
            padding: 8px 15px;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 15px;
            border-radius: 4px;
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
        
        .logo-osca {
            max-height: 60px;
        }
        
        .logo-bagong-pilipinas {
            max-height: 80px;
        }
        
        .title-main {
            font-size: 20px;
            font-weight: 800;
        }
        
        .form-section-bg {
            background: #f5faff;
        }
        
        .photo-upload-hidden {
            display: none;
        }
        
        .photo-upload-label {
            cursor: pointer;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .photo-icon {
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* Senior Search Dropdown Styles */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #ffb7ce;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(227, 21, 117, 0.15);
            z-index: 1000;
        }

        .dropdown-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .senior-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .senior-details {
            font-size: 12px;
            color: #6c757d;
        }

        .no-results {
            padding: 16px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        .deceased-senior {
            background-color: #f8f9fa;
            color: #6c757d;
            pointer-events: none !important;
        }

        .deceased-badge {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-left: 8px;
        }
    </style>
    
    <script>
        let allSeniors = [];

        // Load all seniors data
        function loadAllSeniors() {
            // Load seniors data from PHP with properly formatted dates
            const seniorsData = {!! json_encode(\App\Models\Senior::orderBy('last_name')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'middle_name', 'name_extension', 'osca_id', 'barangay', 'sex', 'date_of_birth', 'birth_place', 'marital_status', 'contact_number', 'monthly_income', 'status'])) !!};
            
            allSeniors = seniorsData.map(senior => ({
                ...senior,
                date_of_birth: senior.date_of_birth ? senior.date_of_birth.split('T')[0] : null,
                is_deceased: senior.status === 'deceased'
            }));
            
            // Seniors loaded successfully
        }

        function setupSearchableDropdown() {
            const searchInput = document.getElementById('senior-search');
            const dropdown = document.getElementById('senior-dropdown');

            if (!searchInput || !dropdown) return;

            // Show all seniors when input is focused
            searchInput.addEventListener('focus', function() {
                if (this.value === '') {
                    displaySearchResults(allSeniors);
                }
            });

            // Search as user types
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                if (query === '') {
                    displaySearchResults(allSeniors);
                    return;
                }

                const filteredSeniors = allSeniors.filter(senior => {
                    const fullName = `${senior.last_name} ${senior.first_name} ${senior.middle_name || ''}`.toLowerCase();
                    const oscaId = senior.osca_id ? senior.osca_id.toLowerCase() : '';
                    const barangay = senior.barangay ? senior.barangay.toLowerCase() : '';
                    
                    return fullName.includes(query) || 
                           oscaId.includes(query) || 
                           barangay.includes(query);
                });

                displaySearchResults(filteredSeniors);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }

        function displaySearchResults(seniors) {
            const dropdown = document.getElementById('senior-dropdown');
            if (seniors.length === 0) {
                dropdown.innerHTML = '<div class="no-results">No seniors found matching your search.</div>';
            } else {
                dropdown.innerHTML = seniors.map(senior => {
                    const isDeceased = senior.is_deceased;
                    let cssClasses = 'dropdown-item';
                    if (isDeceased) cssClasses += ' deceased-senior';

                    let badges = '';
                    if (isDeceased) badges += '<span class="deceased-badge">(DECEASED)</span>';

                    return `
                        <div class="${cssClasses}" data-senior-id="${senior.id}" data-name="${senior.first_name} ${senior.last_name}" data-age="${senior.age || ''}" data-gender="${senior.sex || ''}" data-address="${senior.barangay || ''}" data-birth-date="${senior.date_of_birth || ''}" data-osca-id="${senior.osca_id || ''}">
                            <div class="senior-name">
                                ${senior.last_name}, ${senior.first_name} ${senior.middle_name || ''}
                                ${badges}
                            </div>
                            <div class="senior-details">
                                OSCA ID: ${senior.osca_id || 'N/A'} | Barangay: ${senior.barangay || 'N/A'} | Age: ${senior.age || 'N/A'}
                            </div>
                        </div>
                    `;
                }).join('');
                
                // Add click event listeners to dropdown items
                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    if (!item.classList.contains('deceased-senior')) {
                        item.addEventListener('click', function() {
                            selectSenior(this);
                        });
                    }
                });
            }
            dropdown.style.display = 'block';
        }

        function selectSenior(element) {
            const seniorId = element.getAttribute('data-senior-id');
            const seniorName = element.getAttribute('data-name');
            
            // Update the search input
            document.getElementById('senior-search').value = seniorName;
            
            // Update the hidden senior_id field
            document.getElementById('selected-senior-id').value = seniorId;
            
            // Hide the dropdown
            document.getElementById('senior-dropdown').style.display = 'none';
            
            // Load senior data
            loadSeniorData(seniorId);
        }

        function loadSeniorData(seniorId) {
            if (!seniorId) {
                // Clear all fields if no senior selected
                document.getElementById('senior_osca_id').value = '';
                document.getElementById('senior_last_name').value = '';
                document.getElementById('senior_first_name').value = '';
                document.getElementById('senior_middle_name').value = '';
                document.getElementById('senior_name_extension').value = '';
                document.getElementById('senior_date_of_birth').value = '';
                document.getElementById('senior_place_of_birth').value = '';
                document.getElementById('senior_age').value = '';
                document.getElementById('senior_civil_status').value = '';
                document.getElementById('senior_contact_number').value = '';
                
                // Clear barangay and monthly income
                document.querySelector('select[name="res_barangay"]').value = '';
                document.querySelector('input[name="monthly_income"]').value = '';
                
                // Clear gender radio buttons
                document.querySelectorAll('input[name="gender"]').forEach(radio => {
                    radio.checked = false;
                });
                
                return;
            }

            // Find the senior in our data
            const senior = allSeniors.find(s => s.id == seniorId);
            
            if (senior) {
                console.log('Senior data for auto-fill:', senior);
                // Populate the form fields with senior data
                document.getElementById('senior_osca_id').value = senior.osca_id || '';
                document.getElementById('senior_last_name').value = senior.last_name || '';
                document.getElementById('senior_first_name').value = senior.first_name || '';
                document.getElementById('senior_middle_name').value = senior.middle_name || '';
                document.getElementById('senior_name_extension').value = senior.name_extension || '';
                
                // Set gender radio button
                if (senior.sex) {
                    const genderValue = senior.sex.toLowerCase();
                    const genderRadio = document.querySelector(`input[name="gender"][value="${genderValue}"]`);
                    if (genderRadio) {
                        genderRadio.checked = true;
                    }
                }
                
                // Calculate age from birth date
                if (senior.date_of_birth) {
                    const dateField = document.getElementById('senior_date_of_birth');
                    dateField.value = senior.date_of_birth;
                    calculateAgeFromDate(senior.date_of_birth);
                }
                
                document.getElementById('senior_place_of_birth').value = senior.birth_place || 'Lingayen, Pangasinan';
                document.getElementById('senior_civil_status').value = senior.marital_status || '';
                document.getElementById('senior_contact_number').value = senior.contact_number || '';
                
                // Auto-fill barangay from senior data
                document.querySelector('select[name="res_barangay"]').value = senior.barangay || '';
                
                // Auto-fill monthly income from senior data (convert text range to numeric)
                const monthlyIncomeField = document.querySelector('input[name="monthly_income"]');
                if (monthlyIncomeField) {
                    // Convert text range to numeric value for form
                    let numericIncome = 0;
                    if (senior.monthly_income) {
                        if (senior.monthly_income.includes('below')) {
                            numericIncome = 500; // Default for below 1000
                        } else if (senior.monthly_income.includes('to')) {
                            const parts = senior.monthly_income.split(' to ');
                            numericIncome = parseInt(parts[0].replace(/[^\d]/g, '')) || 0;
                        } else if (senior.monthly_income.includes('above')) {
                            numericIncome = parseInt(senior.monthly_income.replace(/[^\d]/g, '')) || 0;
                        } else if (senior.monthly_income === 'None') {
                            numericIncome = 0;
                        }
                    }
                    monthlyIncomeField.value = numericIncome;
                    console.log('Auto-filled monthly income:', numericIncome, 'from:', senior.monthly_income);
                } else {
                    console.log('Monthly income field not found');
                }
                
                // Senior data loaded successfully
            }
        }

        function calculateAgeFromDate(dateString) {
            if (!dateString) return;
            
            const birthDate = new Date(dateString);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            // Adjust age if birthday hasn't occurred this year
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            document.getElementById('senior_age').value = age;
        }

        // Add event listener for date picker changes
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('senior_date_of_birth');
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    calculateAgeFromDate(this.value);
                });
            }
            
            // Initialize senior search functionality
            loadAllSeniors();
            setupSearchableDropdown();
        });

        function togglePensionFields() {
            const pensionYes = document.querySelector('input[name="has_pension"][value="1"]').checked;
            const pensionFields = document.getElementById('pensionFields');
            pensionFields.style.display = pensionYes ? 'block' : 'none';
        }

        function toggleIncomeFields() {
            const incomeYes = document.querySelector('input[name="permanent_income"][value="Yes"]').checked;
            const incomeFields = document.getElementById('incomeFields');
            incomeFields.style.display = incomeYes ? 'block' : 'none';
        }

        // Add form submission validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Check if senior is selected
                    const seniorId = document.getElementById('selected-senior-id').value;
                    if (!seniorId) {
                        e.preventDefault();
                        alert('Please select a senior citizen first.');
                        return false;
                    }
                    
                    // Check for empty required fields
                    const requiredFields = ['senior_id', 'last_name', 'first_name', 'date_of_birth', 'place_of_birth', 'age', 'gender', 'civil_status', 'monthly_income'];
                    const emptyFields = [];
                    
                    requiredFields.forEach(field => {
                        if (field === 'senior_id') {
                            if (!seniorId) emptyFields.push('senior selection');
                        } else if (field === 'gender') {
                            const genderSelected = document.querySelector('input[name="gender"]:checked');
                            if (!genderSelected) emptyFields.push('gender');
                        } else {
                            const fieldElement = document.querySelector(`[name="${field}"]`);
                            if (fieldElement && (!fieldElement.value || fieldElement.value.trim() === '')) {
                                emptyFields.push(field.replace('_', ' '));
                            }
                        }
                    });
                    
                    if (emptyFields.length > 0) {
                        e.preventDefault();
                        alert('Please fill out the following required fields: ' + emptyFields.join(', '));
                        return false;
                    }
                });
            }
        });
        
        // Confirmation function for submitting pension application
        function confirmSubmit() {
            const seniorNameField = document.querySelector('#selected-senior-name');
            const seniorName = seniorNameField ? seniorNameField.value : 'Senior Citizen';
            
            showConfirmModal(
                'Submit Pension Application',
                `Are you sure you want to submit the pension application for ${seniorName}? This will create a new application record.`,
                '{{ route("forms.pension.store") }}',
                'POST'
            );
        }
    </script>
  </x-head>
</x-sidebar>