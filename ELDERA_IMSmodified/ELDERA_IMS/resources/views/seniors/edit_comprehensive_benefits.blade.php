<x-sidebar>
  <x-header title="EDIT BENEFITS APPLICATION" icon="fas fa-gift">
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
                            <div class="d-flex gap-2 align-items-center">
                                
                                <img src="{{ asset('images/Bagong_Pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="logo-bagong-pilipinas" style="max-height: 80px;">
                             </div>
                        </div>
                        <!-- Pink line separator -->
                        <div style="height: 5px; background-color: #e31575; width: 100%; margin: 0 0 30px 0;"></div>

                        <div class="mb-3">
                            <div class="fw-bold">PURPOSE: To claim the benefits under Republic Act (R.A.) No. 11982.</div>
                        </div>

                        <form method="POST" action="{{ route('seniors.benefits.update', $application->id) }}" enctype="multipart/form-data" id="editBenefitsForm">
                @csrf
                @method('PUT')
                            <input type="hidden" name="senior_id" value="{{ $senior->id }}">
                            
                            <div class="d-flex justify-content-between align-items-start mb-0">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">INSTRUCTION:</div>
                                    1. Fill out this form completely and correctly.<br>
                                    2. Do not leave blank space. If not applicable, kindly indicate "N/A".<br>


                                <div class="fw-bold mt-4 mb-2">Applicant for milestone age: (Kindly check whichever apply)</div>
                                <div class="d-flex gap-4">
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="80" class="form-check-input me-1" {{ old('milestone_age', $application->benefitsApplication->milestone_age ?? '') == '80' ? 'checked' : '' }}> 80</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="85" class="form-check-input me-1" {{ old('milestone_age', $application->benefitsApplication->milestone_age ?? '') == '85' ? 'checked' : '' }}> 85</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="90" class="form-check-input me-1" {{ old('milestone_age', $application->benefitsApplication->milestone_age ?? '') == '90' ? 'checked' : '' }}> 90</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="95" class="form-check-input me-1" {{ old('milestone_age', $application->benefitsApplication->milestone_age ?? '') == '95' ? 'checked' : '' }}> 95</label>
                                    <label class="form-check-label"><input type="radio" name="milestone_age" value="100" class="form-check-input me-1" {{ old('milestone_age', $application->benefitsApplication->milestone_age ?? '') == '100' ? 'checked' : '' }}> 100</label>
                    </div>

                            <!-- Application Status -->
                            <div class="mt-4 mb-3">
                                <label class="form-label fw-bold">Application Status:</label>
                                <select name="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ old('status', $application->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="received" {{ old('status', $application->status) == 'received' ? 'selected' : '' }}>Received</option>
                                    <option value="approved" {{ old('status', $application->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $application->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    
                                </select>
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
                            <input type="text" name="rrn" class="form-control form-control-sm" placeholder="(RRN optional)" value="{{ old('rrn', $senior->other_govt_id ?? '') }}">
                            </div>
                            <div class="col-md-6">
                            <label class="form-label fw-bold small">OSCA ID NUMBER*</label>
                            <input type="text" name="osca_id" class="form-control form-control-sm" placeholder="OSCA ID Number" value="{{ old('osca_id', $senior->osca_id ?? '') }}">
                            </div>
                        </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A.1 LAST NAME*</label>
                            <input type="text" name="last_name" class="form-control form-control-sm" placeholder="Last Name" value="{{ old('last_name', $senior->last_name ?? '') }}">
                            </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A.2 GIVEN NAME*</label>
                            <input type="text" name="first_name" class="form-control form-control-sm" placeholder="Given Name" value="{{ old('first_name', $senior->first_name ?? '') }}">
                            </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A.3 MIDDLE NAME</label>
                            <input type="text" name="middle_name" class="form-control form-control-sm" placeholder="Middle Name" value="{{ old('middle_name', $senior->middle_name ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">A. NAME EXTENSION</label>
                            <input type="text" name="name_extension" class="form-control form-control-sm" placeholder="Jr." value="{{ old('name_extension', $senior->name_extension ?? '') }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                            <div class="col-md-6">
                            <label class="form-label fw-bold small">A.4 DATE OF BIRTH*</label>
                            <input type="date" name="date_of_birth" class="form-control form-control-sm" value="{{ old('date_of_birth', $senior->date_of_birth ? \Carbon\Carbon::parse($senior->date_of_birth)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                            <label class="form-label fw-bold small">A.5 AGE*</label>
                            <input type="number" name="age" min="0" class="form-control form-control-sm" placeholder="age" value="{{ old('age', $senior->date_of_birth ? \Carbon\Carbon::parse($senior->date_of_birth)->age : '') }}">
                            </div>
                        </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">A.6 RESIDENTIAL ADDRESS/ ADDRESS ABROAD*</label>
                        <div class="row g-2 mb-4">
                            <div class="col-md-4">
                                <input type="text" name="res_house_number" class="form-control form-control-sm" placeholder="House Number" value="{{ old('res_house_number', $senior->residence ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="res_street" class="form-control form-control-sm" placeholder="Street" value="{{ old('res_street', $senior->street ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="res_barangay" class="form-select form-select-sm">
                                    <option value="">Select Barangay</option>
                                    <option value="aliwekwek" {{ old('res_barangay', $senior->barangay ?? '') == 'aliwekwek' ? 'selected' : '' }}>Aliwekwek</option>
                                    <option value="baay" {{ old('res_barangay', $senior->barangay ?? '') == 'baay' ? 'selected' : '' }}>Baay</option>
                                    <option value="balangobong" {{ old('res_barangay', $senior->barangay ?? '') == 'balangobong' ? 'selected' : '' }}>Balangobong</option>
                                    <option value="balococ" {{ old('res_barangay', $senior->barangay ?? '') == 'balococ' ? 'selected' : '' }}>Balococ</option>
                                    <option value="bantayan" {{ old('res_barangay', $senior->barangay ?? '') == 'bantayan' ? 'selected' : '' }}>Bantayan</option>
                                    <option value="basing" {{ old('res_barangay', $senior->barangay ?? '') == 'basing' ? 'selected' : '' }}>Basing</option>
                                    <option value="capandanan" {{ old('res_barangay', $senior->barangay ?? '') == 'capandanan' ? 'selected' : '' }}>Capandanan</option>
                                    <option value="domalandan-center" {{ old('res_barangay', $senior->barangay ?? '') == 'domalandan-center' ? 'selected' : '' }}>Domalandan Center</option>
                                    <option value="domalandan-east" {{ old('res_barangay', $senior->barangay ?? '') == 'domalandan-east' ? 'selected' : '' }}>Domalandan East</option>
                                    <option value="domalandan-west" {{ old('res_barangay', $senior->barangay ?? '') == 'domalandan-west' ? 'selected' : '' }}>Domalandan West</option>
                                    <option value="dorongan" {{ old('res_barangay', $senior->barangay ?? '') == 'dorongan' ? 'selected' : '' }}>Dorongan</option>
                                    <option value="dulag" {{ old('res_barangay', $senior->barangay ?? '') == 'dulag' ? 'selected' : '' }}>Dulag</option>
                                    <option value="estanza" {{ old('res_barangay', $senior->barangay ?? '') == 'estanza' ? 'selected' : '' }}>Estanza</option>
                                    <option value="lasip" {{ old('res_barangay', $senior->barangay ?? '') == 'lasip' ? 'selected' : '' }}>Lasip</option>
                                    <option value="libsong-east" {{ old('res_barangay', $senior->barangay ?? '') == 'libsong-east' ? 'selected' : '' }}>Libsong East</option>
                                    <option value="libsong-west" {{ old('res_barangay', $senior->barangay ?? '') == 'libsong-west' ? 'selected' : '' }}>Libsong West</option>
                                    <option value="malawa" {{ old('res_barangay', $senior->barangay ?? '') == 'malawa' ? 'selected' : '' }}>Malawa</option>
                                    <option value="malimpuec" {{ old('res_barangay', $senior->barangay ?? '') == 'malimpuec' ? 'selected' : '' }}>Malimpuec</option>
                                    <option value="maniboc" {{ old('res_barangay', $senior->barangay ?? '') == 'maniboc' ? 'selected' : '' }}>Maniboc</option>
                                    <option value="matalava" {{ old('res_barangay', $senior->barangay ?? '') == 'matalava' ? 'selected' : '' }}>Matalava</option>
                                    <option value="naguelguel" {{ old('res_barangay', $senior->barangay ?? '') == 'naguelguel' ? 'selected' : '' }}>Naguelguel</option>
                                    <option value="namolan" {{ old('res_barangay', $senior->barangay ?? '') == 'namolan' ? 'selected' : '' }}>Namolan</option>
                                    <option value="pangapisan-north" {{ old('res_barangay', $senior->barangay ?? '') == 'pangapisan-north' ? 'selected' : '' }}>Pangapisan North</option>
                                    <option value="pangapisan-sur" {{ old('res_barangay', $senior->barangay ?? '') == 'pangapisan-sur' ? 'selected' : '' }}>Pangapisan Sur</option>
                                    <option value="poblacion" {{ old('res_barangay', $senior->barangay ?? '') == 'poblacion' ? 'selected' : '' }}>Poblacion</option>
                                    <option value="quibaol" {{ old('res_barangay', $senior->barangay ?? '') == 'quibaol' ? 'selected' : '' }}>Quibaol</option>
                                    <option value="rosario" {{ old('res_barangay', $senior->barangay ?? '') == 'rosario' ? 'selected' : '' }}>Rosario</option>
                                    <option value="sabangan" {{ old('res_barangay', $senior->barangay ?? '') == 'sabangan' ? 'selected' : '' }}>Sabangan</option>
                                    <option value="talogtog" {{ old('res_barangay', $senior->barangay ?? '') == 'talogtog' ? 'selected' : '' }}>Talogtog</option>
                                    <option value="tonton" {{ old('res_barangay', $senior->barangay ?? '') == 'tonton' ? 'selected' : '' }}>Tonton</option>
                                    <option value="tumbar" {{ old('res_barangay', $senior->barangay ?? '') == 'tumbar' ? 'selected' : '' }}>Tumbar</option>
                                    <option value="wawa" {{ old('res_barangay', $senior->barangay ?? '') == 'wawa' ? 'selected' : '' }}>Wawa</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="res_city" class="form-select form-select-sm">
                                    <option value="Lingayen" {{ old('res_city', $senior->city ?? '') == 'Lingayen' ? 'selected' : '' }}>Lingayen</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="res_province" class="form-select form-select-sm">
                                    <option value="Pangasinan" {{ old('res_province', $senior->province ?? '') == 'Pangasinan' ? 'selected' : '' }}>Pangasinan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="res_zip" class="form-control form-control-sm" placeholder="Zip Code" value="{{ old('res_zip', '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">A.7 PERMANENT ADDRESS IN THE PHILIPPINES*</label>
                        <div class="row g-2 mb-4">
                            <div class="col-md-4">
                                <input type="text" name="perm_house_number" class="form-control form-control-sm" placeholder="House Number" value="{{ old('perm_house_number', $metadata['permanent_address']['house_number'] ?? $senior->residence ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="perm_street" class="form-control form-control-sm" placeholder="Street" value="{{ old('perm_street', $metadata['permanent_address']['street'] ?? $senior->street ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="perm_barangay" class="form-select form-select-sm">
                                    <option value="">Select Barangay</option>
                                    <option value="aliwekwek" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'aliwekwek' ? 'selected' : '' }}>Aliwekwek</option>
                                    <option value="baay" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'baay' ? 'selected' : '' }}>Baay</option>
                                    <option value="balangobong" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'balangobong' ? 'selected' : '' }}>Balangobong</option>
                                    <option value="balococ" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'balococ' ? 'selected' : '' }}>Balococ</option>
                                    <option value="bantayan" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'bantayan' ? 'selected' : '' }}>Bantayan</option>
                                    <option value="basing" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'basing' ? 'selected' : '' }}>Basing</option>
                                    <option value="capandanan" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'capandanan' ? 'selected' : '' }}>Capandanan</option>
                                    <option value="domalandan-center" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'domalandan-center' ? 'selected' : '' }}>Domalandan Center</option>
                                    <option value="domalandan-east" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'domalandan-east' ? 'selected' : '' }}>Domalandan East</option>
                                    <option value="domalandan-west" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'domalandan-west' ? 'selected' : '' }}>Domalandan West</option>
                                    <option value="dorongan" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'dorongan' ? 'selected' : '' }}>Dorongan</option>
                                    <option value="dulag" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'dulag' ? 'selected' : '' }}>Dulag</option>
                                    <option value="estanza" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'estanza' ? 'selected' : '' }}>Estanza</option>
                                    <option value="lasip" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'lasip' ? 'selected' : '' }}>Lasip</option>
                                    <option value="libsong-east" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'libsong-east' ? 'selected' : '' }}>Libsong East</option>
                                    <option value="libsong-west" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'libsong-west' ? 'selected' : '' }}>Libsong West</option>
                                    <option value="malawa" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'malawa' ? 'selected' : '' }}>Malawa</option>
                                    <option value="malimpuec" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'malimpuec' ? 'selected' : '' }}>Malimpuec</option>
                                    <option value="maniboc" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'maniboc' ? 'selected' : '' }}>Maniboc</option>
                                    <option value="matalava" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'matalava' ? 'selected' : '' }}>Matalava</option>
                                    <option value="naguelguel" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'naguelguel' ? 'selected' : '' }}>Naguelguel</option>
                                    <option value="namolan" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'namolan' ? 'selected' : '' }}>Namolan</option>
                                    <option value="pangapisan-north" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'pangapisan-north' ? 'selected' : '' }}>Pangapisan North</option>
                                    <option value="pangapisan-sur" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'pangapisan-sur' ? 'selected' : '' }}>Pangapisan Sur</option>
                                    <option value="poblacion" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'poblacion' ? 'selected' : '' }}>Poblacion</option>
                                    <option value="quibaol" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'quibaol' ? 'selected' : '' }}>Quibaol</option>
                                    <option value="rosario" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'rosario' ? 'selected' : '' }}>Rosario</option>
                                    <option value="sabangan" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'sabangan' ? 'selected' : '' }}>Sabangan</option>
                                    <option value="talogtog" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'talogtog' ? 'selected' : '' }}>Talogtog</option>
                                    <option value="tonton" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'tonton' ? 'selected' : '' }}>Tonton</option>
                                    <option value="tumbar" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'tumbar' ? 'selected' : '' }}>Tumbar</option>
                                    <option value="wawa" {{ old('perm_barangay', $metadata['permanent_address']['barangay'] ?? $senior->barangay ?? '') == 'wawa' ? 'selected' : '' }}>Wawa</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="perm_city" class="form-select form-select-sm">
                                    <option value="Lingayen" {{ old('perm_city', $metadata['permanent_address']['city'] ?? $senior->city ?? '') == 'Lingayen' ? 'selected' : '' }}>Lingayen</option>
                                </select>
                                </div>
                            <div class="col-md-4">
                                <select name="perm_province" class="form-select form-select-sm">
                                    <option value="Pangasinan" {{ old('perm_province', $metadata['permanent_address']['province'] ?? $senior->province ?? '') == 'Pangasinan' ? 'selected' : '' }}>Pangasinan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="perm_zip" class="form-control form-control-sm" placeholder="Zip Code" value="{{ old('perm_zip', $metadata['permanent_address']['zip'] ?? '') }}">
                                </div>
                                </div>
                                </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">A.8 SEX</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" value="Male" id="sexMale" {{ old('sex', $senior->sex ?? '') == 'Male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sexMale">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" value="Female" id="sexFemale" {{ old('sex', $senior->sex ?? '') == 'Female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sexFemale">Female</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">A.9 CIVIL STATUS</label>
                            <select name="civil_status" class="form-select form-select-sm">
                                <option value="">Civil Status</option>
                                @php
                                    $currentCivilStatus = old('civil_status') ?: ($application->benefitsApplication?->civil_status ?: $senior->marital_status ?: '');
                                @endphp
                                <option value="Single" {{ $currentCivilStatus == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $currentCivilStatus == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ $currentCivilStatus == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Separated" {{ $currentCivilStatus == 'Separated' ? 'selected' : '' }}>Separated</option>
                                <option value="Others" {{ $currentCivilStatus == 'Others' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Specify if Others</label>
                            <input type="text" name="civil_status_others" class="form-control form-control-sm" placeholder="Specify" value="{{ old('civil_status_others', $application->benefitsApplication?->civil_status_others ?? $metadata['civil_status_others'] ?? '') }}">
                    </div>
                </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">A.10 CITIZENSHIP*</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="citizenship" value="Filipino" id="citizenFilipino" {{ old('citizenship', $metadata['citizenship_details']['citizenship'] ?? 'Filipino') == 'Filipino' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="citizenFilipino">Filipino</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="citizenship" value="Dual" id="citizenDual" {{ old('citizenship', $metadata['citizenship_details']['citizenship'] ?? '') == 'Dual' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="citizenDual">Dual Citizen</label>
                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                            <label class="form-label fw-bold small">If Dual Citizen, kindly indicate the details</label>
                            <input type="text" name="dual_citizenship_details" class="form-control form-control-sm" value="{{ old('dual_citizenship_details', $metadata['citizenship_details']['dual_citizenship_details'] ?? '') }}">
                                            </div>
                                            </div>
                                            </div>

                <!-- B. FAMILY INFORMATION -->
                <div class="section-header">B. FAMILY INFORMATION</div>
                
                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.1 NAME OF SPOUSE</label>
                            <input type="text" name="spouse_name" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('spouse_name', $metadata['spouse_information']['name'] ?? ($senior->spouse_first_name ? $senior->spouse_first_name . ' ' . $senior->spouse_last_name . ' ' . $senior->spouse_middle_name . ' ' . $senior->spouse_extension : '')) }}">
                                            </div>
                                        </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.2 CITIZENSHIP</label>
                            <input type="text" name="spouse_citizenship" class="form-control form-control-sm" value="{{ old('spouse_citizenship', $metadata['spouse_information']['citizenship'] ?? '') }}">
                            </div>
                        </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">B.3 NAME OF CHILDREN</label>
                            <div id="childrenContainer" class="row g-2">
                        <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('children.0', $metadata['children'][0] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('children.1', $metadata['children'][1] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('children.2', $metadata['children'][2] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('children.3', $metadata['children'][3] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="children[]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('children.4', $metadata['children'][4] ?? '') }}">
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
                                    <input type="text" name="authorized_reps[0][name]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('authorized_reps.0.name', $metadata['authorized_representatives'][0]['name'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                    <input type="text" name="authorized_reps[0][relationship]" class="form-control form-control-sm" placeholder="Relationship" value="{{ old('authorized_reps.0.relationship', $metadata['authorized_representatives'][0]['relationship'] ?? '') }}">
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="authorized_reps[1][name]" class="form-control form-control-sm" placeholder="Last Name, Given Name, Middle Name, Ext." value="{{ old('authorized_reps.1.name', $metadata['authorized_representatives'][1]['name'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                    <input type="text" name="authorized_reps[1][relationship]" class="form-control form-control-sm" placeholder="Relationship" value="{{ old('authorized_reps.1.relationship', $metadata['authorized_representatives'][1]['relationship'] ?? '') }}">
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
                            <input type="text" name="contact_number" class="form-control form-control-sm" placeholder="Contact Number" value="{{ old('contact_number', $senior->contact_number ?? '') }}">
                                </div>
                            </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">C.2 EMAIL ADDRESS (IF AVAILABLE)</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="Email Address" value="{{ old('email', $senior->email ?? '') }}">
                                </div>
                            </div>
                                </div>
                
                <!-- D. DESIGNATED BENEFICIARY -->
                <div class="section-header">D. DESIGNATED BENEFICIARY</div>
                
                <div class="mb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">D.1 PRIMARY</label>
                            <input type="text" name="primary_beneficiary" class="form-control form-control-sm" placeholder="Primary" value="{{ old('primary_beneficiary', $metadata['beneficiaries']['primary'] ?? '') }}">
                            </div>
                                </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">D.2 CONTINGENT</label>
                            <input type="text" name="contingent_beneficiary" class="form-control form-control-sm" placeholder="Contingent" value="{{ old('contingent_beneficiary', $metadata['beneficiaries']['contingent'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                <!-- E. UTILIZATION OF CASH GIFTS -->
                <div class="section-header">E. UTILIZATION OF CASH GIFTS</div>
                
                <div class="mb-4">
                    <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="food" id="util_food" class="form-check-input" {{ in_array('food', (array) (old('utilization', $metadata['utilization'] ?? []))) ? 'checked' : '' }}>
                            <label for="util_food" class="form-check-label">FOOD</label>
                                    </div>
                                    <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="medical_checkup" id="util_medical" class="form-check-input" {{ in_array('medical_checkup', (array) (old('utilization', $metadata['utilization'] ?? []))) ? 'checked' : '' }}>
                            <label for="util_medical" class="form-check-label">MEDICAL CHECK-UP</label>
                                    </div>
                                    <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="medicines" id="util_medicines" class="form-check-input" {{ in_array('medicines', (array) (old('utilization', $metadata['utilization'] ?? []))) ? 'checked' : '' }}>
                            <label for="util_medicines" class="form-check-label">MEDICINE/VITAMINS</label>
                                    </div>
                                    <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="livelihood" id="util_livelihood" class="form-check-input" {{ in_array('livelihood', (array) (old('utilization', $metadata['utilization'] ?? []))) ? 'checked' : '' }}>
                            <label for="util_livelihood" class="form-check-label">LIVELIHOOD ENTREPRENEURIAL ACTIVITIES</label>
                                    </div>
                                    <div class="form-check">
                            <input type="checkbox" name="utilization[]" value="others" id="util_others" class="form-check-input" onclick="toggleOthersInput()" {{ in_array('others', (array) (old('utilization', $metadata['utilization'] ?? []))) ? 'checked' : '' }}>
                            <div class="d-flex flex-column w-100">
                                <label for="util_others" class="form-check-label">OTHERS:</label>
                                <div class="d-flex gap-2 mt-1">
                                    <input type="text" name="utilization_others" id="utilization_others_input" class="form-control form-control-sm flex-grow-1" style="display: none;" placeholder="Kindly specify" value="{{ old('utilization_others', $metadata['utilization_others'] ?? '') }}">
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
                            <input type="checkbox" name="certification[]" value="information_correct" id="cert_info_correct" class="form-check-input" {{ in_array('information_correct', (array) (old('certification', $metadata['certification'] ?? []))) ? 'checked' : '' }}>
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
                            <textarea name="findings_concerns" class="form-control form-control-sm" rows="4">{{ old('findings_concerns', $metadata['assessment']['findings_concerns'] ?? '') }}</textarea>
                                </div>
                            </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small">H.2 INITIAL ASSESSMENT</label>
                            <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                    <input type="radio" name="initial_assessment" value="eligible" class="form-check-input" id="assessment_eligible" {{ old('initial_assessment', $metadata['assessment']['initial_assessment'] ?? '') == 'eligible' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="assessment_eligible">Eligible</label>
                                    </div>
                                    <div class="form-check">
                                    <input type="radio" name="initial_assessment" value="ineligible" class="form-check-input" id="assessment_ineligible" {{ old('initial_assessment', $metadata['assessment']['initial_assessment'] ?? '') == 'ineligible' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="assessment_ineligible">Ineligible</label>
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
    </style>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            }
        }
        
        // Add event listener when page loads
        document.addEventListener('DOMContentLoaded', function() {
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
                    
                    // Check if milestone age is selected
                    const milestoneAge = document.querySelector('input[name="milestone_age"]:checked');
                    if (!milestoneAge) {
                        e.preventDefault();
                        // Show custom error modal instead of browser alert
                        document.getElementById('errorMessage').innerText = 'Please select a milestone age (80, 85, 90, 95, or 100).';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        return false;
                    }
                    
                    // Check if status is selected
                    const statusSelect = document.querySelector('select[name="status"]');
                    if (!statusSelect || !statusSelect.value) {
                        e.preventDefault();
                        // Show custom error modal instead of browser alert
                        document.getElementById('errorMessage').innerText = 'Please select an application status.';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        return false;
                    }
                    
                    console.log('Milestone age selected:', milestoneAge.value);
                    console.log('Form submission proceeding...');
                });
            }
            
        });
        
        // Confirmation function for updating benefits application (global scope)
        function confirmUpdate() {
            console.log('confirmUpdate function called');
            
            const firstNameField = document.querySelector('input[name="first_name"]');
            const lastNameField = document.querySelector('input[name="last_name"]');
            
            console.log('First name field:', firstNameField);
            console.log('Last name field:', lastNameField);
            
            const firstName = firstNameField ? firstNameField.value : 'Senior';
            const lastName = lastNameField ? lastNameField.value : 'Citizen';
            const seniorName = firstName + ' ' + lastName;
            
            console.log('Senior name:', seniorName);
            console.log('About to call showConfirmModal');
            
            showConfirmModal(
                'Update Benefits Application',
                `Are you sure you want to update ${seniorName}'s benefits application? This will save all changes made to the form.`,
                '{{ route("seniors.benefits.update", $application->id) }}',
                'PUT'
            );
            
            console.log('showConfirmModal called');
        }
    </script>
  </x-header>
</x-sidebar>
