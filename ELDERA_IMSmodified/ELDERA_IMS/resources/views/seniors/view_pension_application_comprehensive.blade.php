<x-sidebar>
    <x-header title="SOCIAL PENSION APPLICATION DETAILS" icon="fas fa-hand-holding-usd">
      <div class="main">
          <div class="form">
              <div class="form-content">
                  <div class="form-section">
                     
                      <!-- Header with logos and title -->
                      <div class="d-flex justify-content-between align-items-center mb-3">
                          <img src="{{ asset('images/DSWD_LOGO.png') }}" alt="DSWD Logo" style="max-height: 80px;">
                            <img src="{{ asset('images/Bagong_Pilipinas.png') }}" alt="Bagong Pilipinas Logo" style="max-height: 80px;">
                          <div class="text-center flex-grow-1">
                              <div style="color: #000; font-size: 18px; font-weight: bold;">OFFICE OF THE SENIOR CITIZENS AFFAIRS</div>
                          </div>
                        
                          <div class="button-group">
                              <a href="{{ route('seniors') }}" class="action-btn back-btn">
                                  <i class="fas fa-arrow-left"></i> Back to Seniors
                              </a>
                              <button onclick="confirmEdit('{{ $application->senior->id }}', '{{ $application->senior->first_name }} {{ $application->senior->last_name }}')" class="action-btn edit-btn">
                                  <i class="fas fa-edit"></i> Edit
                              </button>
                              <button onclick="generatePDF()" class="action-btn pdf-btn">
                                  <i class="fas fa-file-pdf"></i> Generate PDF
                              </button>
                          </div>
                      </div>
  
                      <!-- Pink section header -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          SOCIAL PENSION APPLICATION - SOCIAL PENSION FOR INDIGENT SENIOR CITIZENS
                      </div>
  
                      <!-- Profile Photo Section in Header -->
                      <div class="row mb-4" style="background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
                          <div class="col-md-2">
                              <div class="profile-photo-section" style="text-align: left;">
                                  @if($application->senior->photo_path)
                                      <img src="{{ asset('storage/' . $application->senior->photo_path) }}" alt="Profile Photo" class="profile-photo" style="margin-left: 0;">
                                  @else
                                      <div class="profile-photo-placeholder" style="width: 120px; height: 120px; background-color: #e9ecef; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                          <i class="fas fa-user" style="font-size: 48px; color: #6c757d;"></i>
                                      </div>
                                  @endif
                              </div>
                          </div>
                          <div class="col-md-10">
                              <div class="profile-info-section" style="padding-left: px;">
                                  <h4 class="senior-name mb-1" style="text-align: left;">{{ $application->senior->last_name }}, {{ $application->senior->first_name }}</h4>
                                  <p class="senior-id mb-2" style="text-align: left;">{{ $application->senior->osca_id }}</p>
                               
                                      <span class="status-badge badge-active">{{ $application->status ?? 'Active' }}</span>
                                      @if($application->senior->has_pension)
                                      <span class="status-badge badge-pension">Pension ✓</span>
                                      @endif
                               
                              </div>
                          </div>
                      </div>

                      <!-- I. PERSONAL INFORMATION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          I. PERSONAL INFORMATION
                      </div>

                      <div class="row">
                          <!-- Left Column: Basic Information -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">BASIC INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">OSCA ID Number:</span>
                                          <span class="info-value">{{ $application->senior->osca_id ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">NCSC RRN:</span>
                                          <span class="info-value">{{ $application->pensionApplication->rrn ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Last Name:</span>
                                          <span class="info-value">{{ $application->senior->last_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">First Name:</span>
                                          <span class="info-value">{{ $application->senior->first_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Middle Name:</span>
                                          <span class="info-value">{{ $application->senior->middle_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Extension:</span>
                                          <span class="info-value">{{ $application->senior->name_extension ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Sex:</span>
                                          <span class="info-value">{{ $application->senior->sex ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Date of Birth:</span>
                                          <span class="info-value">{{ $application->senior->date_of_birth ? date('M d, Y', strtotime($application->senior->date_of_birth)) : 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Age:</span>
                                          <span class="info-value">{{ \Carbon\Carbon::parse($application->senior->date_of_birth)->age ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Place of Birth:</span>
                                          <span class="info-value">{{ $application->senior->birth_place ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Right Column: Address Information -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">PERMANENT ADDRESS</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">House No./Zone/Purok/Sitio:</span>
                                          <span class="info-value">{{ $application->senior->residence ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Street:</span>
                                          <span class="info-value">{{ $application->senior->street ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Barangay:</span>
                                          <span class="info-value">{{ ucfirst($application->senior->barangay ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">City/Municipality:</span>
                                          <span class="info-value">{{ ucfirst($application->senior->city ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Province:</span>
                                          <span class="info-value">{{ ucfirst($application->senior->province ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Zip Code:</span>
                                          <span class="info-value">{{ $application->senior->zip_code ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>

                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">CONTACT INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Contact Number:</span>
                                          <span class="info-value">{{ $application->senior->contact_number ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Email Address:</span>
                                          <span class="info-value">{{ $application->senior->email ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- II. ECONOMIC STATUS -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin: 30px 0 20px 0;">
                          II. ECONOMIC STATUS
                      </div>

                      <div class="row">
                          <!-- Left Column: Living Arrangement -->
                          <div class="col-md-4">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">LIVING ARRANGEMENT</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Arrangement:</span>
                                          <span class="info-value">
                                              @if($application->pensionApplication->living_arrangement && is_array($application->pensionApplication->living_arrangement))
                                                  {{ implode(', ', array_map(function($arr) { return ucfirst(str_replace('_', ' ', $arr)); }, array_filter($application->pensionApplication->living_arrangement))) }}
                                              @else
                                                  N/A
                                              @endif
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Middle Column: Receiving Pension -->
                          <div class="col-md-4">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">RECEIVING PENSION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Has Pension:</span>
                                          <span class="info-value">{{ $application->pensionApplication->has_pension ? 'Yes' : 'No' }}</span>
                                      </div>
                                      @if($application->pensionApplication->has_pension)
                                      <div class="info-row">
                                          <span class="info-label">Pension Amount:</span>
                                          <span class="info-value">{{ $application->pensionApplication->pension_amount ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Pension Source:</span>
                                          <span class="info-value">{{ $application->pensionApplication->pension_source ?? 'N/A' }}</span>
                                      </div>
                                      @endif
                                  </div>
                              </div>
                          </div>

                          <!-- Right Column: Permanent Income -->
                          <div class="col-md-4">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">PERMANENT INCOME</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Has Income:</span>
                                          <span class="info-value">{{ $application->pensionApplication->permanent_income ?? 'N/A' }}</span>
                                      </div>
                                      @if($application->pensionApplication->permanent_income == 'Yes')
                                      <div class="info-row">
                                          <span class="info-label">Income Amount:</span>
                                          <span class="info-value">{{ $application->pensionApplication->income_amount ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Income Source:</span>
                                          <span class="info-value">{{ $application->pensionApplication->income_source ?? 'N/A' }}</span>
                                      </div>
                                      @endif
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- III. HEALTH CONDITION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          III. HEALTH CONDITION
                      </div>

                      <div class="row">
                          <!-- Left Column: Health Information -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">HEALTH INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Existing Illness:</span>
                                          <span class="info-value">{{ $application->pensionApplication->existing_illness ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Monthly Income:</span>
                                          <span class="info-value">{{ $application->pensionApplication->monthly_income ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Right Column: Additional Health Info -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">ADDITIONAL INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Civil Status:</span>
                                          <span class="info-value">{{ $application->pensionApplication->civil_status ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Gender:</span>
                                          <span class="info-value">{{ ucfirst($application->pensionApplication->gender ?? $application->senior->sex ?? 'N/A') }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* View Senior Profile Styles */
        .form-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        
        .senior-name {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin: 10px 0 5px 0;
        }
        
        .senior-id {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .info-section {
            background: transparent;
            border: none;
            padding: 15px 0;
            border-radius: 0;
            box-shadow: none;
        }
        
        .info-section-clean {
            padding: 15px 0;
            background: transparent;
            border: none;
            box-shadow: none;
        }
        
        .info-section h6,
        .section-title {
            color: #e31575;
            font-weight: bold;
            margin-bottom: 12px;
            border-bottom: 2px solid #e31575;
            padding-bottom: 5px;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .info-content {
            font-size: 14px;
        }
        
        .info-row {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
            flex-shrink: 0;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .info-value {
            color: #666;
            text-align: right;
            word-break: break-word;
            flex: 1;
            font-size: 14px;
        }

        .profile-photo-section {
            text-align: center;
            padding: 15px 0;
        }

        .profile-card {
            background: transparent;
            border: none;
            padding: 0;
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            margin-left: 8px;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
        }

        .back-btn:hover {
            background-color: #5a6268;
            color: white;
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .edit-btn:hover {
            background-color: #0056b3;
            color: white;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
            color: white;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending { background-color: #ffc107; color: #000; }
        .status-approved { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        .status-under_review { background-color: #17a2b8; color: #fff; }

        .certification-box {
            background-color: #f8f9fa;
            border: 2px solid #ffb7ce;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .profile-photo {
                width: 100px;
                height: 100px;
            }
            
            .senior-name {
                font-size: 16px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .info-value {
                text-align: left;
                margin-top: 2px;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        function confirmEdit(seniorId, seniorName) {
            if (confirm(`Are you sure you want to edit the profile of ${seniorName}?`)) {
                window.location.href = `/Edit_senior/${seniorId}`;
            }
        }

        function generatePDF() {
            // Hide action buttons
            const buttons = document.querySelectorAll('.button-group, .action-btn');
            buttons.forEach(btn => btn.style.display = 'none');
            
            // Hide sidebar
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) sidebar.style.display = 'none';
            
            // Adjust main content
            const main = document.querySelector('.main');
            if (main) {
                main.style.marginLeft = '0';
                main.style.width = '100%';
            }
            
            // Wait a moment for layout to adjust
            setTimeout(() => {
                // Use browser's print to PDF
                window.print();
                
                // Restore layout after printing
                setTimeout(() => {
                    buttons.forEach(btn => btn.style.display = '');
                    if (sidebar) sidebar.style.display = '';
                    if (main) {
                        main.style.marginLeft = '';
                        main.style.width = '';
                    }
                }, 1000);
            }, 500);
        }
    </script>

    
    <style>
        .pdf-btn {
            background: #dc3545 !important;
            color: white !important;
        }
        .pdf-btn:hover {
            background: #c82333 !important;
        }
        
        @media print {
            /* Hide sidebar and navigation */
            .sidebar, .sidebar * {
                display: none !important;
            }
            /* Hide header section */
            .header, .header *, [class*="header"] {
                display: none !important;
            }
            /* Hide action buttons */
            .button-group, .action-btn, .btn, button {
                display: none !important;
            }
            /* Reset main layout for print */
            .main {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }
            /* Ensure form content is visible */
            .form {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 10px !important;
                width: 100% !important;
                max-width: none !important;
            }
            /* Ensure all content is visible */
            .form-content, .form-section {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            /* Fix text visibility */
            body, .form, .form-content, .form-section {
                color: #000 !important;
                background: #fff !important;
            }
            /* Ensure images are visible */
            img {
                max-width: 100% !important;
                height: auto !important;
            }
            
            /* Professional PDF layout matching the image */
            .form-section {
                page-break-inside: avoid;
                break-inside: avoid;
                margin-bottom: 15px !important;
                padding: 10px !important;
            }
            
            /* Clean two-column layout */
            .row {
                margin-bottom: 12px !important;
                display: flex !important;
                flex-wrap: wrap !important;
            }
            
            .col-md-6 {
                width: 50% !important;
                padding: 8px !important;
                box-sizing: border-box !important;
            }
            
            .col-md-4 {
                width: 33.333% !important;
                padding: 8px !important;
                box-sizing: border-box !important;
            }
            
            .col-md-3 {
                width: 25% !important;
                padding: 8px !important;
                box-sizing: border-box !important;
            }
            
            /* Section headers with pink styling */
            .section-title {
                font-size: 16px !important;
                font-weight: bold !important;
                color: #e31575 !important;
                margin-bottom: 10px !important;
                padding: 8px 12px !important;
                background-color: #f8f9fa !important;
                border-bottom: 2px solid #e31575 !important;
                text-transform: uppercase !important;
            }
            
            /* Information display styling */
            .info-content {
                font-size: 13px !important;
                line-height: 1.5 !important;
                margin-bottom: 8px !important;
                display: block !important;
                clear: both !important;
            }
            
            .info-label {
                font-size: 13px !important;
                font-weight: bold !important;
                color: #333 !important;
                display: inline-block !important;
                width: 40% !important;
                vertical-align: top !important;
            }
            
            .info-value {
                font-size: 13px !important;
                color: #666 !important;
                display: inline-block !important;
                width: 55% !important;
                vertical-align: top !important;
                margin-left: 5% !important;
            }
            
            /* Banner sections */
            .banner-section {
                background-color: #e31575 !important;
                color: white !important;
                padding: 12px !important;
                margin: 15px 0 !important;
                text-align: center !important;
                font-weight: bold !important;
                font-size: 16px !important;
                text-transform: uppercase !important;
            }
            
            /* Balanced margins and padding */
            .mb-3, .mb-4, .mb-5 {
                margin-bottom: 12px !important;
            }
            
            .mt-3, .mt-4, .mt-5 {
                margin-top: 12px !important;
            }
            
            .p-3, .p-4, .p-5 {
                padding: 12px !important;
            }
            
            /* Profile section styling */
            .profile-photo-section {
                width: 100px !important;
                height: 100px !important;
                margin: 0 auto !important;
            }
            
            .profile-photo, .profile-photo-placeholder {
                width: 100px !important;
                height: 100px !important;
                border-radius: 8px !important;
            }
            
            /* Clean spacing */
            .d-flex {
                margin-bottom: 8px !important;
            }
            
            /* Table styling */
            table {
                font-size: 12px !important;
                width: 100% !important;
                border-collapse: collapse !important;
            }
            
            table td, table th {
                padding: 6px 8px !important;
                border: 1px solid #ddd !important;
            }
            
            table th {
                background-color: #f8f9fa !important;
                font-weight: bold !important;
            }
        }
    </style>
  </x-header>
</x-sidebar>
