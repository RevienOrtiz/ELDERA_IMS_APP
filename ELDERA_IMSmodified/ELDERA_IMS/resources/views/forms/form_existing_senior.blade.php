<x-sidebar>
  <x-header title="ADD EXISTING SENIOR" icon="fas fa-user-plus">
    @include('message.popup_message')
    <div class="main">
        <div class="form">
            <div class="form-content">
                <div class="form-section">
                   
                        <div class="d-flex justify-content-between align-items-center mb-3">
                           <img src="{{ asset('images/OSCA.png') }}" alt="OSCA Logo" class="logo-osca" style="max-height: 60px;">
                            <div class="text-center flex-grow-1">
                                <div class="title-main" style="color: #e31575; font-size: 24px; font-weight: 800;">APPLICATION FORM</div>
                                <div class="title-main" style="color: #e31575; font-size: 20px; font-weight: 800;">OCTOGENARIAN, NONAGENARIAN, AND CENTENARIAN BENEFIT PROGRAM</div>
                            </div>
                            <div class="d-flex gap-2">
                                <img src="{{ asset('images/Bagong_Pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="logo-bagong-pilipinas" style="max-height: 80px;">
                                
                            </div>
                        </div>
                        <!-- Pink line separator -->
                        <div style="height: 5px; background-color: #e31575; width: 100%; margin: 0 0 30px 0;"></div>

                        <div class="mb-3">
                            <div class="fw-bold">PURPOSE: To claim the benefits under Republic Act (R.A.) No. 11982.</div>
                        </div>

                        <form id="benefitsForm" method="POST" action="{{ route('forms.benefits.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="senior_id" id="selected-senior-id" value="">
                            
                            <!-- Senior Selection Section -->
                            <div class="mb-4">
                                <label class="form-label fw-bold small">Select Senior Citizen <span class="text-danger">*</span></label>
                                <div class="searchable-dropdown">
                                    <input type="text" id="senior-search" class="form-control form-control-sm" placeholder="Type to search seniors..." autocomplete="off">
                                    <div id="senior-dropdown" class="dropdown-menu" style="display: none; max-height: 300px; overflow-y: auto; width: 100%;">
                                        <!-- Search results will be populated here -->
                                    </div>
                                </div>
                                <small class="form-text text-muted">Type to search for senior citizens. Only existing senior citizens can apply for benefits.</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-start mb-0">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">INSTRUCTION:</div>
                                    1. Fill out this form completely and correctly.<br>
                                    2. Do not leave blank space. If not applicable, kindly indicate "N/A".<br>


                                <div class="fw-bold mt-4 mb-2">Applicant for milestone age: (Kindly select one)</div>
                                <div class="d-flex gap-4">
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="80" class="form-check-input me-1 milestone-radio"> 80</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="85" class="form-check-input me-1 milestone-radio"> 85</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="90" class="form-check-input me-1 milestone-radio"> 90</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="95" class="form-check-input me-1 milestone-radio"> 95</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="100" class="form-check-input me-1 milestone-radio"> 100</label>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>Note:</strong> Select one milestone age. Seniors can apply for future milestone benefits even if they haven't reached that age yet.
                                    </small>
                                </div>
                                </div>
                                <x-photo-upload id="photo_upload" name="photo" />
                            </div>
                            
                            <!-- A. PERSONAL INFORMATION -->
                            <div class="section-header">A. PERSONAL INFORMATION</div>

                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">NCSC REGISTRATION REFERENCE NUMBER (RRN)</label>
                            <input type="text" name="rrn" class="form-control form-control-sm" placeholder="(RRN optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">OSCA ID NUMBER*</label>
                            <input type="text" name="osca_id" class="form-control form-control-sm" placeholder="OSCA ID Number">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A.1 LAST NAME*</label>
                            <input type="text" name="last_name" class="form-control form-control-sm" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A.2 GIVEN NAME*</label>
                            <input type="text" name="first_name" class="form-control form-control-sm" placeholder="Given Name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A.3 MIDDLE NAME</label>
                            <input type="text" name="middle_name" class="form-control form-control-sm" placeholder="Middle Name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A. NAME EXTENSION</label>
                            <input type="text" name="name_extension" class="form-control form-control-sm" placeholder="Jr.">
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">A.4 DATE OF BIRTH*</label>
                            <input type="date" name="date_of_birth" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">A.5 AGE*</label>
                            <input type="number" name="age" min="0" class="form-control form-control-sm" placeholder="age" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">A.6 RESIDENTIAL ADDRESS/ ADDRESS ABROAD*</label>
                        <div class="row g-2 mb-4">
                            <div class="col-md-4">
                                <input type="text" name="res_house_number" class="form-control form-control-sm" placeholder="House Number">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="res_street" class="form-control form-control-sm" placeholder="Street">
                            </div>
                            <div class="col-md-4">
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
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="res_city" class="form-select form-select-sm">
                                    <option value="Lingayen" selected>Lingayen</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="res_province" class="form-select form-select-sm">
                                    <option value="Pangasinan" selected>Pangasinan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="res_zip" class="form-control form-control-sm" placeholder="Zip Code">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">A.7 PERMANENT ADDRESS IN THE PHILIPPINES*</label>
                        <div class="row g-2 mb-4">
                            <div class="col-md-4">
                                <input type="text" name="perm_house_number" class="form-control form-control-sm" placeholder="House Number">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="perm_street" class="form-control form-control-sm" placeholder="Street">
                            </div>
                            <div class="col-md-4">
                                <select name="perm_barangay" class="form-select form-select-sm">
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
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="perm_city" class="form-select form-select-sm">
                                    <option value="Lingayen" selected>Lingayen</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="perm_province" class="form-select form-select-sm">
                                    <option value="Pangasinan" selected>Pangasinan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="perm_zip" class="form-control form-control-sm" placeholder="Zip Code">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">A.8 SEX</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" value="Male" id="sexMale" required>
                                    <label class="form-check-label" for="sexMale">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" value="Female" id="sexFemale" required>
                                    <label class="form-check-label" for="sexFemale">Female</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">A.9 CIVIL STATUS</label>
                            <select name="civil_status" class="form-select form-select-sm" required>
                                <option value="">Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Specify if Others</label>
                            <input type="text" name="civil_status_others" class="form-control form-control-sm" placeholder="Specify">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">A.10 CITIZENSHIP*</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="citizenship" value="Filipino" id="citizenFilipino" required>
                                    <label class="form-check-label" for="citizenFilipino">Filipino</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="citizenship" value="Dual" id="citizenDual">
                                    <label class="form-check-label" for="citizenDual">Dual Citizen</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">If Dual Citizen, kindly indicate the details</label>
                            <input type="text" name="dual_citizenship_details" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>

                <!-- B. FAMILY INFORMATION -->
                <div class="section-header">B. FAMILY INFORMATION</div>
                
                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.1 NAME OF SPOUSE</label>
                            <input type="text" name="spouse_name" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.2 CITIZENSHIP</label>
                            <input type="text" name="spouse_citizenship" class="form-control form-control-sm">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.3 NAME OF CHILDREN</label>
                            <div id="childrenContainer" class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="childrenAddInlineBtn" class="btn btn-add-child">
                                        <i class="fas fa-plus"></i>
                                        <span>ADD MORE</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                       <script>
                        (function() {
                            const container = document.getElementById('childrenContainer');
                            const addBtn = document.getElementById('childrenAddInlineBtn');
                            if (addBtn && container) {
                                addBtn.addEventListener('click', function () {
                                    // Create a new col-md-6 div
                                    const colDiv = document.createElement('div');
                                    colDiv.className = 'col-md-6';
                                    // Create the input
                                    const input = document.createElement('input');
                                    input.type = 'text';
                                    input.name = 'children[]';
                                    input.className = 'form-control form-control-sm';
                                    input.placeholder = 'Last Name, Given Name, Middle Name, Ext.';
                                    // Append input to colDiv
                                    colDiv.appendChild(input);
                                    // Insert colDiv before the button's parent div
                                    container.insertBefore(colDiv, addBtn.parentNode);
                                });
                                const syncButtonHeight = () => {
                                    const firstInput = container.querySelector('input.form-control');
                                    if (firstInput) {
                                        addBtn.style.height = firstInput.offsetHeight + 'px';
                                    }
                                };
                                syncButtonHeight();
                                window.addEventListener('resize', syncButtonHeight);
                            }
                        })();
                        </script>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.4 AUTHORIZED REPRESENTATIVES (LIST NAME AND RELATIONSHIP)</label>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" name="authorized_reps[0][name]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="authorized_reps[0][relationship]" class="form-control form-control-sm" placeholder="Relationship">
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="authorized_reps[1][name]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext.">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="authorized_reps[1][relationship]" class="form-control form-control-sm" placeholder="Relationship">
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- C. CONTACT INFORMATION -->
                <div class="section-header">C. CONTACT INFORMATION</div>
                
                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">C.1 CONTACT NUMBER</label>
                            <input type="text" name="contact_number" class="form-control form-control-sm" placeholder="Contact Number">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">C.2 EMAIL ADDRESS (IF AVAILABLE)</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="Email Address">
                        </div>
                    </div>
                </div>
                
                <!-- D. DESIGNATED BENEFICIARY -->
                <div class="section-header">D. DESIGNATED BENEFICIARY</div>
                
                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">D.1 PRIMARY</label>
                            <input type="text" name="primary_beneficiary" class="form-control form-control-sm" placeholder="Primary">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">D.2 CONTINGENT</label>
                            <input type="text" name="contingent_beneficiary" class="form-control form-control-sm" placeholder="Contingent">
                        </div>
                    </div>
                </div>
                
                <!-- E. UTILIZATION OF CASH GIFTS -->
                <div class="section-header">E. UTILIZATION OF CASH GIFTS</div>
                
                <div class="mb-4">
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="food" id="util_food" class="form-check-input">
                            <label for="util_food" class="form-check-label">FOOD</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="medical_checkup" id="util_medical" class="form-check-input">
                            <label for="util_medical" class="form-check-label">MEDICAL CHECK-UP</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="medicines" id="util_medicines" class="form-check-input">
                            <label for="util_medicines" class="form-check-label">MEDICINE/VITAMINS</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="livelihood" id="util_livelihood" class="form-check-input">
                            <label for="util_livelihood" class="form-check-label">LIVELIHOOD ENTREPRENEURIAL ACTIVITIES</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="others" id="util_others" class="form-check-input" onclick="toggleOthersInput()">
                            <div class="d-flex flex-column w-100">
                                <label for="util_others" class="form-check-label">OTHERS:</label>
                                <div class="d-flex gap-2 mt-1">
                                    <input type="text" name="utilization_others" id="utilization_others_input" class="form-control form-control-sm flex-grow-1" style="display: none;" placeholder="Kindly specify">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script>
                    function toggleOthersInput() {
                        const checkbox = document.getElementById('util_others');
                        const input = document.getElementById('utilization_others_input');
                        
                        if (checkbox.checked) {
                            input.style.display = 'block';
                            input.focus();
                        } else {
                            input.style.display = 'none';
                            input.value = '';
                        }
                    }
                    
                    function toggleApplicantType() {
                        const applicantType = document.querySelector('input[name="applicant_type"]:checked')?.value;
                        const localRequirements = document.getElementById('localRequirements');
                        const abroadRequirements = document.getElementById('abroadRequirements');
                        
                        if (applicantType === 'local') {
                            localRequirements.style.display = 'block';
                            abroadRequirements.style.display = 'none';
                        } else if (applicantType === 'abroad') {
                            localRequirements.style.display = 'none';
                            abroadRequirements.style.display = 'block';
                        }
                    }
                </script>
                
                <!-- F. CERTIFICATION -->
                <div class="section-header">F. CERTIFICATION</div>
                
                <div class="certification-box">
                    <div style="margin-bottom: 8px;">
                        <label style="display: flex; align-items: flex-start; gap: 8px; font-weight: normal;">
                            <input type="checkbox" name="certification[]" value="information_correct" id="cert_info_correct" class="form-check-input" required>
                            <span style="flex: 1;">I hereby certify under oath that all the information in this application form are true and correct. I authorize the verification of the information provided in this application form by the Office of the Senior Citizen Affairs in accordance with the R.A. 10173, otherwise known as the "Data Privacy Act of 2012", its Implementing Rules and Regulations, and issuances of the National Privacy Commission. I further warrant that I have provided my personal information voluntarily and I am giving my full consent for the use of these data. I understand that my application shall not processed if any statement herein made is found to be false, or if any document I submitted is found to have been falsified, or if I fail to comply with all the requirements with respect to my application, without prejudice to any administrative, civil, or criminal liability that may be imposed upon me under existing laws of the Republic of the Philippines. Further, I hereby certify that I have not commenced the application/processing for the cash benefits as provided for under R.A. No. 11982 before any government agency.</span>
                        </label>
                    </div>
                </div>
                
                <!-- G. DOCUMENTARY REQUIREMENTS -->
<div class="section-header mt-4">
    G. DOCUMENTARY REQUIREMENTS
    <span style="font-weight: normal; font-style: italic; font-size: 0.9em;">
        (to be filled-up by NCSC personnel only)
    </span>
</div>

<!-- Applicant Type Selection -->
<div class="bg-light p-3 border mb-4 rounded">
    <div class="mb-2 fw-bold text-dark">Select Applicant Type:</div>
    <div class="d-flex gap-4">
        <div class="form-check">
            <input type="radio" name="applicant_type" value="local" checked onclick="toggleApplicantType()" class="form-check-input" id="applicant_local">
            <label class="form-check-label" for="applicant_local">Local Applicant</label>
        </div>
        <div class="form-check">
            <input type="radio" name="applicant_type" value="abroad" onclick="toggleApplicantType()" class="form-check-input" id="applicant_abroad">
            <label class="form-check-label" for="applicant_abroad">Applicant Living Abroad</label>
        </div>
    </div>
</div>

<!-- Local Applicants Requirements -->
<div id="localRequirements" class="mb-4">
    <div class="bg-light p-2 mb-3 border rounded">
        <h4 class="m-0 text-dark fw-bold" style="font-size: 14px;">Local Applicants - Documentary Requirements</h4>
    </div>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-pink align-middle" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Requirements</th>
                    <th class="text-center" style="width: 120px;">Complied</th>
                    <th style="width: 200px;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>a. Duly accomplished application form "Annex A"</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_annex_a" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_annex_a" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="local_annex_a_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>b. Primary Documents (any one):</strong><br>
                        <div class="ms-3 mt-2 text-secondary">
                            • Certificate of Live Birth (PSA)<br>
                            • Philippine ID / PhilSys / National ID
                        </div>
                        <div class="fst-italic small text-secondary mt-2 p-2 bg-light border rounded">
                            <strong>Note:</strong> If primary documents are unavailable, submit any two secondary documents as per Item VI of Implementing Guidelines.
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_primary_docs" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_primary_docs" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="local_primary_docs_remarks" placeholder="Specify secondary documents if primary unavailable..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>c. Recent 5.08 cm x 5.08 cm (2" x 2") ID picture</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_id_picture" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_id_picture" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="local_id_picture_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>d. Full body picture printed on A4 bond/photo paper</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_full_body" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_full_body" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="local_full_body_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>e. Endorsed list from Local Chief Executive</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_endorsed_list" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="local_endorsed_list" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="local_endorsed_list_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Applicants Living Abroad Requirements -->
<div id="abroadRequirements" class="mb-4" style="display: none;">
    <div class="bg-light p-2 mb-3 border rounded">
        <h4 class="m-0 text-dark fw-bold" style="font-size: 14px;">Applicants Living Abroad - Documentary Requirements</h4>
    </div>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-pink align-middle" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Requirements</th>
                    <th class="text-center" style="width: 120px;">Complied</th>
                    <th style="width: 200px;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>a. Duly accomplished application form "Annex A"</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer" >
                                <input type="radio" name="abroad_annex_a" value="yes" class="form-check-input" >
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_annex_a" value="no" class="form-check-input" >
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="abroad_annex_a_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>b. Primary Documents (any one):</strong><br>
                        <div class="ms-3 mt-2 text-secondary">
                            • Valid Philippine Passport<br>
                            • Retention/Re-acquisition Certificate or DFA Attestation
                        </div>
                        <div class="fst-italic small text-secondary mt-2 p-2 bg-light border rounded">
                            <strong>Note:</strong> If primary documents are unavailable, submit any two secondary documents as per Item VI of Implementing Guidelines.
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_primary_docs" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_primary_docs" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="abroad_primary_docs_remarks" placeholder="Specify secondary documents if primary unavailable..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>c. Recent 5.08 cm x 5.08 cm (2" x 2") ID picture</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_id_picture" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_id_picture" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="abroad_id_picture_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>d. Full body picture printed on A4 bond/photo paper</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_full_body" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_full_body" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="abroad_full_body_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>e. Endorsed list from PE/Consulate/DFA/DMW/CFO</strong>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_endorsed_list" value="yes" class="form-check-input">
                                <span>Yes</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="radio" name="abroad_endorsed_list" value="no" class="form-check-input">
                                <span>No</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <textarea name="abroad_endorsed_list_remarks" placeholder="Enter remarks..." class="form-control form-control-sm"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

                <!-- H. VALIDATION ASSESSMENT REPORT -->
                <div class="section-header mt-4">H. VALIDATION ASSESSMENT REPORT <span class="fw-normal fst-italic" style="font-size: 0.9em;">(To be filled-up by the NCSC personnel only)</span></div>
                
                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">H.1 FINDINGS/CONCERNS/RECOMMENDATION</label>
                            <textarea name="findings_concerns" class="form-control form-control-sm" rows="4"></textarea>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">H.2 INITIAL ASSESSMENT</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input type="radio" name="initial_assessment" value="eligible" class="form-check-input" id="assessment_eligible">
                                    <label class="form-check-label" for="assessment_eligible">Eligible</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="initial_assessment" value="ineligible" class="form-check-input" id="assessment_ineligible">
                                    <label class="form-check-label" for="assessment_ineligible">Ineligible</label>
                                </div>
                            </div>
                        </div>
                    </div>
                            </div>
 
                            <!-- Submit Button -->
                            <div class="text-center mt-3">
                                 <button type="submit" class="btn btn-primary">SAVE APPLICATION</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /*G style*/
        .table-pink th, .table-pink td {
             vertical-align: top !important;
            border: 2px solid #ffb7ce !important;
            font-size: 14px;
        }
        .table-pink thead tr {
            background: #fff0fa;
        }
        .table-pink th {
            color: #e31575;
            font-weight: bold;
        }
        .form-control, .form-control-sm, .form-select, .form-select-sm, textarea.form-control {
            border: 2px solid #ffb7ce;
            border-radius: 8px;
            box-shadow: none;
            font-size: 14px;
        }
        .form-check-input {
            accent-color: #e31575;
            border-radius: 4px;
            border: 2px solid #e31575;
            box-shadow: none;
            transform: scale(1.2);
            margin-right: 6px;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .bg-light {
            background: #f5faff !important;
        }
      


        /* Main layout structure */
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
            padding: 24px;
            margin: 0;
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

        /* Custom styles for section headers and specific elements */
        .section-header {
            background: #e31575;
            color: #fff;
            padding: 8px 15px;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 15px;
            border-radius: 4px;
        }

        .btn-add-child {
            width: 100%;
            padding: 20px 14px;
            border: 2px solid #ffb7ce;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fff;
            color: #e31575;
            font-weight: 600;
            box-shadow: inset 0 2px 4px rgba(227, 21, 117, 0.1), inset 0 1px 2px rgba(227, 21, 117, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-add-child:hover, .btn-add-child:focus {
            background-color: #fff0fa;
            border-color: #e31575;
            color: #c01060;
            box-shadow: inset 0 3px 6px rgba(227, 21, 117, 0.15), 0 0 0 3px rgba(227, 21, 117, 0.12);
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
        
        .form-section-bg {
            background: #f5faff;
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

        /* Searchable Dropdown Styles */
        .searchable-dropdown {
            position: relative;
        }

        .searchable-dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0;
            margin: 0;
        }

        .searchable-dropdown .dropdown-item {
            display: block;
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }

        .searchable-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .searchable-dropdown .dropdown-item:last-child {
            border-bottom: none;
        }

        .searchable-dropdown .dropdown-item.selected {
            background-color: #e31575;
            color: white;
        }

        .searchable-dropdown .dropdown-item .senior-name {
            font-weight: 600;
            color: #333;
        }

        .searchable-dropdown .dropdown-item .senior-details {
            font-size: 0.85em;
            color: #666;
            margin-top: 2px;
        }

        .searchable-dropdown .dropdown-item.selected .senior-name,
        .searchable-dropdown .dropdown-item.selected .senior-details {
            color: white;
        }

        .no-results {
            padding: 12px;
            text-align: center;
            color: #666;
            font-style: italic;
        }

        /* Deceased Senior Styling */
        .deceased-senior {
            background-color: #f8f9fa !important;
            opacity: 0.7;
        }

        .deceased-senior:hover {
            background-color: #e9ecef !important;
        }

        .deceased-badge {
            color: #dc3545;
            font-weight: bold;
            font-size: 0.8em;
            margin-left: 8px;
        }

        /* Ineligible Senior Styling */
        .ineligible-senior {
            background-color: #f8f9fa !important;
            opacity: 0.6;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        .ineligible-senior:hover {
            background-color: #f8f9fa !important;
        }

        .ineligible-badge {
            color: #ffc107;
            font-weight: bold;
            font-size: 0.8em;
            margin-left: 8px;
        }
    </style>
    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allSeniors = [];
        let selectedSenior = null;

        // Auto-calculate age from date of birth
        function calculateAge() {
            const dateOfBirthInput = document.querySelector('input[name="date_of_birth"]');
            const ageInput = document.querySelector('input[name="age"]');
            
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
                
                // Milestone age selection is now manual
            }
        }

        // Load all seniors on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllSeniors();
            setupSearchableDropdown();
            
            // Add age calculation for date of birth
            const dateOfBirthInput = document.querySelector('input[name="date_of_birth"]');
            if (dateOfBirthInput) {
                dateOfBirthInput.addEventListener('change', calculateAge);
                dateOfBirthInput.addEventListener('input', calculateAge);
                // Calculate age on page load if date is already filled
                if (dateOfBirthInput.value) {
                    calculateAge();
                }
            }
            
            // Add form submission debugging
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...');
                    
                    // Check if senior_id is selected
                    const seniorId = document.getElementById('selected-senior-id').value;
                    console.log('Senior ID selected:', seniorId);
                    
                    if (!seniorId) {
                        e.preventDefault();
                        // Show custom error modal instead of browser alert
                        document.getElementById('errorMessage').innerText = 'Please select a senior citizen first.';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        return false;
                    }
                    
                    // Debug: Log all form data before submission
                    console.log('=== FORM SUBMISSION DEBUG ===');
                    console.log('Senior ID:', seniorId);
                    
                    // Check if milestone age is selected
                    const milestoneAge = document.querySelector('input[name="milestone_age"]:checked');
                    console.log('Milestone age selected:', milestoneAge ? milestoneAge.value : 'None');
                    
                    if (!milestoneAge) {
                        e.preventDefault();
                        // Show custom error modal instead of browser alert
                        document.getElementById('errorMessage').innerText = 'Please select a milestone age.';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        return false;
                    }
                    
                    // Check if certification is checked
                    const certification = document.querySelector('input[name="certification[]"]:checked');
                    console.log('Certification checked:', certification ? 'Yes' : 'No');
                    
                    if (!certification) {
                        e.preventDefault();
                        // Show custom error modal instead of browser alert
                        document.getElementById('errorMessage').innerText = 'Please check the certification checkbox to proceed.';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        return false;
                    }
                    
                    // Log all form data
                    const formData = new FormData(form);
                    console.log('Form data being submitted:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key + ': ' + value);
                    }
                    
                    console.log('Form submission proceeding...');
                });
            } else {
                console.error('Form not found!');
            }
            
            // Add event listeners to milestone age radio buttons
            document.querySelectorAll('.milestone-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Refresh search results if dropdown is currently visible
                    const searchInput = document.getElementById('senior-search');
                    const dropdown = document.getElementById('senior-dropdown');
                    
                    if (searchInput.value.length >= 1 && dropdown.style.display === 'block') {
                        const query = searchInput.value.toLowerCase().trim();
                        const filteredSeniors = allSeniors.filter(senior => {
                            const fullName = `${senior.first_name} ${senior.last_name} ${senior.middle_name || ''}`.toLowerCase();
                            const oscaId = (senior.osca_id || '').toLowerCase();
                            const barangay = (senior.barangay || '').toLowerCase();
                            
                            return fullName.includes(query) || 
                                   oscaId.includes(query) || 
                                   barangay.includes(query);
                        });
                        
                        displaySearchResults(filteredSeniors);
                    }
                });
            });
        });
        });

        function loadAllSeniors() {
            // Try to load from cache first
            const cachedSeniors = window.CacheManager?.getCachedSearchResults('all_seniors');
            if (cachedSeniors) {
                console.log('Loading seniors from cache');
                allSeniors = cachedSeniors;
                return;
            }

            // Load only basic senior data for the dropdown (benefits-specific data is now in benefits_applications table)
            const seniorsData = {!! json_encode(\App\Models\Senior::orderBy('last_name')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'middle_name', 'name_extension', 'osca_id', 'barangay', 'sex', 'date_of_birth', 'birth_place', 'marital_status', 'contact_number', 'email', 'status'])) !!};
            
            // Format dates for HTML date input, add status indicator, and calculate age
            allSeniors = seniorsData.map(senior => {
                let age = null;
                if (senior.date_of_birth) {
                    const birthDate = new Date(senior.date_of_birth);
                    const today = new Date();
                    age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    
                    // Adjust age if birthday hasn't occurred this year
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                }
                
                return {
                    ...senior,
                    date_of_birth: senior.date_of_birth ? senior.date_of_birth.split('T')[0] : null,
                    age: age,
                    is_deceased: senior.status === 'deceased'
                };
            });
            
            // Debug: Log the first senior's data
            console.log('Loaded seniors:', allSeniors.length);
            if (allSeniors.length > 0) {
                console.log('First senior data:', allSeniors[0]);
            }

            // Cache the seniors data
            if (window.CacheManager) {
                window.CacheManager.cacheSearchResults('all_seniors', allSeniors);
            }
        }

        function setupSearchableDropdown() {
            const searchInput = document.getElementById('senior-search');
            const dropdown = document.getElementById('senior-dropdown');
            const selectedSeniorId = document.getElementById('selected-senior-id');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                if (query.length < 1) {
                    dropdown.style.display = 'none';
                    return;
                }

                // Filter seniors based on search query
                const filteredSeniors = allSeniors.filter(senior => {
                    const fullName = `${senior.last_name}, ${senior.first_name} ${senior.middle_name || ''}`.toLowerCase();
                    const oscaId = senior.osca_id ? senior.osca_id.toLowerCase() : '';
                    return fullName.includes(query) || oscaId.includes(query);
                });

                displaySearchResults(filteredSeniors);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.searchable-dropdown')) {
                    dropdown.style.display = 'none';
                }
            });

            // Show dropdown when focusing on search input
            searchInput.addEventListener('focus', function() {
                if (this.value.length >= 1) {
                    dropdown.style.display = 'block';
                }
            });
        }

        function checkEligibility(senior) {
            // No longer check age eligibility - all seniors can apply for any milestone age
            console.log('Eligibility check removed - all seniors can apply for any milestone age');
            return { eligible: true, reason: '' };
        }

        function displaySearchResults(seniors) {
            const dropdown = document.getElementById('senior-dropdown');
            
            if (seniors.length === 0) {
                dropdown.innerHTML = '<div class="no-results">No seniors found matching your search.</div>';
            } else {
                dropdown.innerHTML = seniors.map(senior => {
                    const eligibility = checkEligibility(senior);
                    const isDeceased = senior.is_deceased;
                    const isIneligible = !eligibility.eligible && !isDeceased;
                    
                    console.log(`Senior: ${senior.first_name} ${senior.last_name}, Age: ${senior.age}, Eligible: ${eligibility.eligible}, IsIneligible: ${isIneligible}`);
                    
                    let cssClasses = 'dropdown-item';
                    if (isDeceased) cssClasses += ' deceased-senior';
                    if (isIneligible) cssClasses += ' ineligible-senior';
                    
                    let badges = '';
                    if (isDeceased) badges += '<span class="deceased-badge">(DECEASED)</span>';
                    if (isIneligible) badges += '<span class="ineligible-badge">(INELIGIBLE)</span>';
                    
                    return `
                        <div class="${cssClasses}" data-senior-id="${senior.id}" data-name="${senior.first_name} ${senior.last_name}" data-age="${senior.age || ''}" data-gender="${senior.sex || ''}" data-address="${senior.barangay || ''}" data-birth-date="${senior.date_of_birth || ''}" data-eligible="${eligibility.eligible}" data-reason="${eligibility.reason}">
                            <div class="senior-name">
                                ${senior.last_name}, ${senior.first_name} ${senior.middle_name || ''}
                                ${badges}
                            </div>
                            <div class="senior-details">
                                OSCA ID: ${senior.osca_id || 'N/A'} | Barangay: ${senior.barangay || 'N/A'} | Age: ${senior.age || 'N/A'}
                                ${isIneligible ? `<br><small class="text-warning">${eligibility.reason}</small>` : ''}
                            </div>
                        </div>
                    `;
                }).join('');

                // Add click event listeners to dropdown items (only for eligible seniors)
                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    // Only add click listener if the senior is not ineligible
                    if (!item.classList.contains('ineligible-senior')) {
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
            const isDeceased = element.classList.contains('deceased-senior');
            const isIneligible = element.classList.contains('ineligible-senior');
            const eligibilityReason = element.getAttribute('data-reason');
            
            // Eligibility check removed - all seniors can apply for any milestone age
            
            // Check if senior is deceased
            if (isDeceased) {
                if (!confirm('This senior is marked as DECEASED. Are you sure you want to proceed with the benefits application?')) {
                    return; // User cancelled
                }
            }
            
            // Update the search input to show selected senior
            document.getElementById('senior-search').value = seniorName;
            document.getElementById('selected-senior-id').value = seniorId;
            
            // Hide dropdown
            document.getElementById('senior-dropdown').style.display = 'none';
            
            // Load senior data
            loadSeniorData(seniorId);
        }

        function loadSeniorData(seniorId) {
            console.log('Loading senior data for ID:', seniorId);
            
            if (!seniorId) {
                // Clear all fields if no senior selected
                clearFormFields();
                return;
            }

            // Find the senior in our data
            const senior = allSeniors.find(s => s.id == seniorId);
            console.log('Found senior:', senior);
            
            if (senior) {
                // First populate basic senior data
                populateBasicSeniorData(senior);
                
                // Then fetch and populate benefits application data if it exists
                fetchBenefitsApplicationData(seniorId);
                console.log('Senior data loaded successfully.');
            } else {
                console.log('Senior not found in allSeniors array');
            }
        }

        function populateBasicSeniorData(senior) {
            // Populate basic information fields from senior data
            document.querySelector('input[name="last_name"]').value = senior.last_name || '';
            document.querySelector('input[name="first_name"]').value = senior.first_name || '';
            document.querySelector('input[name="middle_name"]').value = senior.middle_name || '';
            document.querySelector('input[name="name_extension"]').value = senior.name_extension || '';
            
            // Set date of birth and calculate age
            if (senior.date_of_birth) {
                const dateField = document.querySelector('input[name="date_of_birth"]');
                dateField.value = senior.date_of_birth;
                calculateAge();
                
                // Milestone age selection is now manual
            }
            
            // Set sex
            const sexInputs = document.querySelectorAll('input[name="sex"]');
            sexInputs.forEach(input => {
                if (input.value === senior.sex) {
                    input.checked = true;
                }
            });
            
            // Set civil status
            const civilStatusSelect = document.querySelector('select[name="civil_status"]');
            if (civilStatusSelect && senior.marital_status) {
                civilStatusSelect.value = senior.marital_status;
            }
            
            // Set contact information
            document.querySelector('input[name="contact_number"]').value = senior.contact_number || '';
            document.querySelector('input[name="email"]').value = senior.email || '';
            
            // Set barangay for residential address
            document.querySelector('select[name="res_barangay"]').value = senior.barangay || '';
            document.querySelector('select[name="perm_barangay"]').value = senior.barangay || '';
            
            // Set OSCA ID
            document.querySelector('input[name="osca_id"]').value = senior.osca_id || '';
        }

        function fetchBenefitsApplicationData(seniorId) {
            // Fetch benefits application data via AJAX
            fetch(`/api/seniors/${seniorId}/benefits-application`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.benefitsApplication) {
                        populateBenefitsApplicationData(data.benefitsApplication);
                    }
                })
                .catch(error => {
                    console.log('No benefits application found for this senior:', error);
                });
        }

        function populateBenefitsApplicationData(benefitsApp) {
            // Populate benefits-specific fields from benefits application data
            
            // Set civil status
            const civilStatusSelect = document.querySelector('select[name="civil_status"]');
            if (civilStatusSelect && benefitsApp.civil_status) {
                civilStatusSelect.value = benefitsApp.civil_status;
            }
            
            // Set citizenship
            const citizenshipInputs = document.querySelectorAll('input[name="citizenship"]');
            citizenshipInputs.forEach(input => {
                if (input.value === benefitsApp.citizenship) {
                    input.checked = true;
                }
            });
            
            // Set dual citizenship details
            const dualCitizenshipInput = document.querySelector('input[name="dual_citizenship_details"]');
            if (dualCitizenshipInput && benefitsApp.dual_citizenship_details) {
                dualCitizenshipInput.value = benefitsApp.dual_citizenship_details;
            }
            
            // Set spouse information
            const spouseNameInput = document.querySelector('input[name="spouse_name"]');
            if (spouseNameInput && benefitsApp.spouse_name) {
                spouseNameInput.value = benefitsApp.spouse_name;
            }
            
            const spouseCitizenshipInput = document.querySelector('input[name="spouse_citizenship"]');
            if (spouseCitizenshipInput && benefitsApp.spouse_citizenship) {
                spouseCitizenshipInput.value = benefitsApp.spouse_citizenship;
            }
            
            // Set children
            if (benefitsApp.children && Array.isArray(benefitsApp.children)) {
                const childrenInputs = document.querySelectorAll('input[name="children[]"]');
                benefitsApp.children.forEach((child, index) => {
                    if (childrenInputs[index]) {
                        childrenInputs[index].value = child || '';
                    }
                });
            }
            
            // Set authorized representatives
            if (benefitsApp.authorized_reps && Array.isArray(benefitsApp.authorized_reps)) {
                benefitsApp.authorized_reps.forEach((rep, index) => {
                    const nameInput = document.querySelector(`input[name="authorized_reps[${index}][name]"]`);
                    const relationshipInput = document.querySelector(`input[name="authorized_reps[${index}][relationship]"]`);
                    
                    if (nameInput && rep.name) {
                        nameInput.value = rep.name;
                    }
                    if (relationshipInput && rep.relationship) {
                        relationshipInput.value = rep.relationship;
                    }
                });
            }
            
            // Set beneficiaries
            const primaryBeneficiaryInput = document.querySelector('input[name="primary_beneficiary"]');
            if (primaryBeneficiaryInput && benefitsApp.primary_beneficiary) {
                primaryBeneficiaryInput.value = benefitsApp.primary_beneficiary;
            }
            
            const contingentBeneficiaryInput = document.querySelector('input[name="contingent_beneficiary"]');
            if (contingentBeneficiaryInput && benefitsApp.contingent_beneficiary) {
                contingentBeneficiaryInput.value = benefitsApp.contingent_beneficiary;
            }
            
            // Set utilization checkboxes
            if (benefitsApp.utilization && Array.isArray(benefitsApp.utilization)) {
                benefitsApp.utilization.forEach(util => {
                    const checkbox = document.querySelector(`input[name="utilization[]"][value="${util}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
            
            // Set utilization others
            const utilizationOthersInput = document.querySelector('input[name="utilization_others"]');
            if (utilizationOthersInput && benefitsApp.utilization_others) {
                utilizationOthersInput.value = benefitsApp.utilization_others;
                // Show the input if it has a value
                if (benefitsApp.utilization_others) {
                    utilizationOthersInput.style.display = 'block';
                }
            }
            
            // Set validation assessment fields (Section H)
            const findingsConcernsInput = document.querySelector('textarea[name="findings_concerns"]');
            if (findingsConcernsInput && benefitsApp.findings_concerns) {
                findingsConcernsInput.value = benefitsApp.findings_concerns;
            }
            
            // Set initial assessment radio buttons
            if (benefitsApp.initial_assessment) {
                const assessmentInputs = document.querySelectorAll('input[name="initial_assessment"]');
                assessmentInputs.forEach(input => {
                    if (input.value === benefitsApp.initial_assessment) {
                        input.checked = true;
                    }
                });
            }
        }

        function autoSelectMilestoneAge() {
            // No longer auto-select milestone age - let users choose manually
            // This function is kept for compatibility but does nothing
            console.log('Milestone age selection is now manual - no auto-selection');
        }

        function clearFormFields() {
            // Clear all form fields
            document.querySelector('input[name="last_name"]').value = '';
            document.querySelector('input[name="first_name"]').value = '';
            document.querySelector('input[name="middle_name"]').value = '';
            document.querySelector('input[name="date_of_birth"]').value = '';
            document.querySelector('input[name="age"]').value = '';
            document.querySelector('input[name="osca_id"]').value = '';
            
            // Clear sex selection
            const sexInputs = document.querySelectorAll('input[name="sex"]');
            sexInputs.forEach(input => input.checked = false);
            
            // Clear milestone age selections
            const milestoneInputs = document.querySelectorAll('.milestone-radio');
            milestoneInputs.forEach(input => input.checked = false);
            
            // Clear barangay selections
            document.querySelector('select[name="res_barangay"]').value = '';
            document.querySelector('select[name="perm_barangay"]').value = '';
        }
    </script>
  </x-header>
</x-sidebar>
