@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-sidebar>
    <x-header title="BENEFITS APPLICATION DETAILS" icon="fas fa-gift">
      <div class="main">
          <div class="form">
              <div class="form-content">
                  <div class="form-section">
                     
                      <!-- Header with logos and title -->
                      <div class="d-flex justify-content-between align-items-center mb-3">
                          <img src="{{ asset('images/OSCA.png') }}" alt="OSCA Logo" style="max-height: 60px;">
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
                          BENEFITS APPLICATION - OCTOGENARIAN, NONAGENARIAN, AND CENTENARIAN BENEFIT PROGRAM
                      </div>
  
                      <!-- Profile Photo Section in Header -->
                      <div class="row mb-4" style="background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
                          <div class="col-md-2">
                              <div class="profile-photo-section" style="text-align: left;">
                                  @if($application->senior->photo_path)
                                      <img src="{{ asset('storage/' . $application->senior->photo_path) }}" alt="Profile Photo" class="profile-photo" style="margin-left: 0;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                      <div class="profile-photo-placeholder" style="width: 120px; height: 120px; background-color: #e9ecef; border: 2px dashed #ccc; display: none; align-items: center; justify-content: center; border-radius: 8px;">
                                          <i class="fas fa-user" style="font-size: 48px; color: #6c757d;"></i>
                                      </div>
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

                      <!-- MILESTONE AGE SELECTION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          APPLICANT FOR MILESTONE AGE
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">MILESTONE AGE INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Selected Milestone Age:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->milestone_age ?? 'N/A' }} years old</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Current Age:</span>
                                          <span class="info-value">{{ \Carbon\Carbon::parse($application->senior->date_of_birth)->age ?? 'N/A' }} years old</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Eligibility Status:</span>
                                          <span class="info-value">
                                              @php
                                                  $currentAge = \Carbon\Carbon::parse($application->senior->date_of_birth)->age;
                                                  $milestoneAge = $application->benefitsApplication->milestone_age ?? 0;
                                                  if ($currentAge >= $milestoneAge) {
                                                      echo 'Eligible';
                                                  } else {
                                                      echo 'Future Eligible (applying for future milestone)';
                                                  }
                                              @endphp
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- A. PERSONAL INFORMATION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          A. PERSONAL INFORMATION
                      </div>

                      <div class="row">
                          <!-- Left Column: Basic Information -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">BASIC INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">NCSC RRN:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->rrn ?? $application->senior->other_govt_id ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">OSCA ID Number:</span>
                                          <span class="info-value">{{ $application->senior->osca_id ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Last Name:</span>
                                          <span class="info-value">{{ $application->senior->last_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Given Name:</span>
                                          <span class="info-value">{{ $application->senior->first_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Middle Name:</span>
                                          <span class="info-value">{{ $application->senior->middle_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Name Extension:</span>
                                          <span class="info-value">{{ $application->senior->name_extension ?? 'N/A' }}</span>
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
                                          <span class="info-label">Sex:</span>
                                          <span class="info-value">{{ $application->senior->sex ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Civil Status:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->civil_status ?? $application->senior->marital_status ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Citizenship:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->citizenship ?? $application->senior->citizenship ?? 'Filipino' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Dual Citizenship Details:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->dual_citizenship_details ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Civil Status Others:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->civil_status_others ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Right Column: Address Information -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">RESIDENTIAL ADDRESS</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">House Number:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->res_house_number ?? $application->senior->residence ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Street:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->res_street ?? $application->senior->street ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Barangay:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->res_barangay ?? $application->senior->barangay ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">City:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->res_city ?? $application->senior->city ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Province:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->res_province ?? $application->senior->province ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Zip Code:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->res_zip ?? $application->senior->zip_code ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>

                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">PERMANENT ADDRESS</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">House Number:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->perm_house_number ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Street:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->perm_street ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Barangay:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->perm_barangay ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">City:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->perm_city ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Province:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->perm_province ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Zip Code:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->perm_zip ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- B. FAMILY INFORMATION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin: 30px 0 20px 0;">
                          B. FAMILY INFORMATION
                      </div>

                      <div class="row">
                          <!-- Left Column: Family Details -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">FAMILY DETAILS</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Name of Spouse:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->spouse_name ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Spouse Citizenship:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->spouse_citizenship ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Children:</span>
                                          <span class="info-value">
                                              @if($application->benefitsApplication->children && is_array($application->benefitsApplication->children))
                                                  {{ implode(', ', array_filter($application->benefitsApplication->children)) }}
                                              @else
                                                  N/A
                                              @endif
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Right Column: Authorized Representatives -->
                          <div class="col-md-6">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">AUTHORIZED REPRESENTATIVES</h6>
                                  <div class="info-content">
                                      @if($application->benefitsApplication->authorized_reps && is_array($application->benefitsApplication->authorized_reps))
                                          @foreach($application->benefitsApplication->authorized_reps as $index => $rep)
                                              @if($rep && isset($rep['name']) && isset($rep['relationship']))
                                                  <div class="info-row">
                                                      <span class="info-label">Rep {{ $index + 1 }}:</span>
                                                      <span class="info-value">{{ $rep['name'] }} - {{ $rep['relationship'] }}</span>
                                                  </div>
                                              @endif
                                          @endforeach
                                      @else
                                          <div class="info-row">
                                              <span class="info-label">Representatives:</span>
                                              <span class="info-value">N/A</span>
                                          </div>
                                      @endif
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- C. CONTACT INFORMATION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          C. CONTACT INFORMATION
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">CONTACT DETAILS</h6>
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

                      <!-- D. DESIGNATED BENEFICIARY -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          D. DESIGNATED BENEFICIARY
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">BENEFICIARY INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Primary Beneficiary:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->primary_beneficiary ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Contingent Beneficiary:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->contingent_beneficiary ?? 'N/A' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- E. UTILIZATION OF CASH GIFTS -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          E. UTILIZATION OF CASH GIFTS
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">UTILIZATION PLANS</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Intended Use:</span>
                                          <span class="info-value">
                                              @if($application->benefitsApplication->utilization && is_array($application->benefitsApplication->utilization))
                                                  {{ implode(', ', array_map(function($util) { return ucfirst(str_replace('_', ' ', $util)); }, array_filter($application->benefitsApplication->utilization))) }}
                                                  @if($application->benefitsApplication->utilization_others)
                                                      , Others: {{ $application->benefitsApplication->utilization_others }}
                                                  @endif
                                              @else
                                                  N/A
                                              @endif
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- F. CERTIFICATION -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          F. CERTIFICATION
                      </div>

                      <div class="certification-box">
                          <div class="row">
                              <div class="col-md-12">
                                  <p class="mb-3">I hereby certify under oath that all the information in this application form are true and correct. I authorize the verification of the information provided in this application form by the Office of the Senior Citizen Affairs in accordance with the R.A. 10173, otherwise known as the "Data Privacy Act of 2012", its Implementing Rules and Regulations, and issuances of the National Privacy Commission. I further warrant that I have provided my personal information voluntarily and I am giving my full consent for the use of these data. I understand that my application shall not processed if any statement herein made is found to be false, or if any document I submitted is found to have been falsified, or if I fail to comply with all the requirements with respect to my application, without prejudice to any administrative, civil, or criminal liability that may be imposed upon me under existing laws of the Republic of the Philippines. Further, I hereby certify that I have not commenced the application/processing for the cash benefits as provided for under R.A. No. 11982 before any government agency.</p>
                              </div>
                          </div>
                      </div>

                      <!-- G. DOCUMENTARY REQUIREMENTS -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          G. DOCUMENTARY REQUIREMENTS
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">APPLICANT TYPE & REQUIREMENTS</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Applicant Type:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->applicant_type ?? 'Local') }} Applicant</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Application Form (Annex A):</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->local_annex_a ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Primary Documents:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->local_primary_docs ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">ID Picture (2" x 2"):</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->local_id_picture ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Proof of Residency:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->local_residency_proof ?? 'N/A') }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Bank Account Details:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->local_bank_account ?? 'N/A') }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- H. VALIDATION ASSESSMENT REPORT -->
                      <div style="background-color: #e31575; color: white; padding: 10px; font-weight: bold; margin-bottom: 20px;">
                          H. VALIDATION ASSESSMENT REPORT
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="info-section-clean mb-4">
                                  <h6 class="section-title">ASSESSMENT INFORMATION</h6>
                                  <div class="info-content">
                                      <div class="info-row">
                                          <span class="info-label">Findings/Concerns/Recommendation:</span>
                                          <span class="info-value">{{ $application->benefitsApplication->findings_concerns ?? 'N/A' }}</span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Initial Assessment:</span>
                                          <span class="info-value">{{ ucfirst($application->benefitsApplication->initial_assessment ?? 'N/A') }}</span>
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
