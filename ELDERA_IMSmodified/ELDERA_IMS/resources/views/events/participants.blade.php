<x-sidebar>
    <x-header title="Manage Participants" icon="fas fa-users">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('message.popup_message')
        
        <div class="main">
            <!-- Event Banner -->
            <div class="event-banner">
                <div class="event-banner-content">
                    <h1 class="event-title">{{ $event->title }}</h1>
                    <div class="event-details">
                        <div class="event-detail-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $event->event_date->format('F d, Y') }}</span>
                        </div>
                        <div class="event-detail-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $event->start_time->format('g:i A') }}</span>
                        </div>
                        <div class="event-detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants Management Section -->
            <div class="participants-section">
                <div class="section-header">
                    <div class="header-left">
                        <a href="{{ route('events.show', $event->id) }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i> Back to Event Details
                        </a>
                        <h2 class="section-title">Participants Management</h2>
                    </div>
                    <div class="section-actions">
                        <button class="btn btn-primary" onclick="openAddParticipantModal()">
                            <i class="fas fa-plus"></i> Add Participant
                        </button>
                    </div>
                </div>

                <!-- Participants Table -->
                <div class="table-container">
                    <table class="participants-table">
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>OSCA ID NO.</th>
                                <th>FULL NAME</th>
                                <th>AGE</th>
                                <th>GENDER</th>
                                <th>BARANGAY</th>
                                <th>ATTENDANCE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($event->participants as $index => $participant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $participant->osca_id ?? 'N/A' }}</td>
                                    <td>{{ $participant->first_name }} {{ $participant->last_name }}</td>
                                    <td>{{ $participant->age ?? 'N/A' }}</td>
                                    <td>{{ $participant->sex ?? 'N/A' }}</td>
                                    <td>{{ $participant->barangay ?? 'N/A' }}</td>
                                    <td>
                                        <div class="attendance-radio">
                                            <label class="radio-option">
                                                <input type="radio" 
                                                       name="attendance_{{ $participant->id }}" 
                                                       value="yes"
                                                       class="attendance-radio-btn" 
                                                       data-event-id="{{ $event->id }}" 
                                                       data-senior-id="{{ $participant->id }}"
                                                       {{ $participant->pivot->attended ? 'checked' : '' }}>
                                                <span class="radio-label yes">Yes</span>
                                            </label>
                                            <label class="radio-option">
                                                <input type="radio" 
                                                       name="attendance_{{ $participant->id }}" 
                                                       value="no"
                                                       class="attendance-radio-btn" 
                                                       data-event-id="{{ $event->id }}" 
                                                       data-senior-id="{{ $participant->id }}"
                                                       {{ !$participant->pivot->attended ? 'checked' : '' }}>
                                                <span class="radio-label no">No</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="removeParticipant({{ $event->id }}, {{ $participant->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No participants registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Statistics -->
                <div class="participants-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total Registered:</span>
                        <span class="stat-value">{{ $event->current_participants }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Attended:</span>
                        <span class="stat-value">{{ $event->participants()->wherePivot('attended', true)->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Attendance Rate:</span>
                        <span class="stat-value">{{ $event->current_participants > 0 ? round(($event->participants()->wherePivot('attended', true)->count() / $event->current_participants) * 100, 1) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Participant Modal -->
        <div id="addParticipantModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add New Participant</h3>
                    <span class="close" onclick="closeAddParticipantModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="addParticipantForm" method="POST" action="{{ route('events.register', $event->id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="senior_id">Select Senior Citizen</label>
                            <select id="senior_id" name="senior_id" class="form-control" required>
                                <option value="">Choose a senior citizen...</option>
                                @foreach($allSeniors as $senior)
                                    @if(!$event->participants->contains('id', $senior->id))
                                        <option value="{{ $senior->id }}">
                                            {{ $senior->first_name }} {{ $senior->last_name }} 
                                            ({{ $senior->osca_id ?? 'No OSCA ID' }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-secondary" onclick="closeAddParticipantModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Participant</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .main {
                margin-left: 250px;
                margin-top: 60px;
                min-height: calc(100vh - 60px);
                padding: 20px;
                background: #f8f9fa;
            }

            /* Event Banner */
            .event-banner {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
                padding: 40px;
                border-radius: 12px;
                margin-bottom: 30px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }

            .event-title {
                font-size: 28px;
                font-weight: 700;
                margin: 0 0 20px 0;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }

            .event-details {
                display: flex;
                gap: 30px;
                flex-wrap: wrap;
            }

            .event-detail-item {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 16px;
                font-weight: 500;
            }

            .event-detail-item i {
                font-size: 18px;
                opacity: 0.9;
            }

            /* Participants Section */
            .participants-section {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }

            .section-header {
                background: #f8f9fa;
                padding: 20px 30px;
                border-bottom: 1px solid #e0e0e0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .header-left {
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .back-btn {
                color: #e31575;
                text-decoration: none;
                padding: 8px 16px;
                border: 2px solid #e31575;
                border-radius: 6px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 600;
                font-size: 14px;
            }

            .back-btn:hover {
                background: #e31575;
                color: white;
                text-decoration: none;
            }

            .section-title {
                margin: 0;
                color: #333;
                font-size: 20px;
                font-weight: 600;
            }

            .section-actions {
                display: flex;
                gap: 10px;
            }

            /* Table Styles */
            .table-container {
                overflow-x: auto;
            }

            .participants-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
            }

            .participants-table th {
                background: #e31575;
                color: white;
                padding: 15px 12px;
                text-align: left;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
                letter-spacing: 0.5px;
            }

            .participants-table th:nth-child(7) {
                text-align: center;
                width: 120px;
            }

            .participants-table td {
                padding: 15px 12px;
                border-bottom: 1px solid #e0e0e0;
                vertical-align: middle;
                text-align: center;
            }

            .participants-table td:not(:nth-child(7)) {
                text-align: left;
            }

            /* Specific styling for attendance column */
            .participants-table td:nth-child(7) {
                text-align: center;
                vertical-align: middle;
                padding: 8px 4px;
                width: 120px;
            }

            .participants-table tbody tr:hover {
                background-color: #f8f9fa;
            }

            .participants-table tbody tr:nth-child(even) {
                background-color: #fafafa;
            }

            /* Attendance Radio Buttons */
            .attendance-radio {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 10px;
                height: 40px;
            }

            .radio-option {
                display: flex;
                align-items: center;
                cursor: pointer;
                padding: 4px 8px;
                border-radius: 4px;
                transition: all 0.3s ease;
                border: 1px solid transparent;
                height: 28px;
            }

            .radio-option:hover {
                background-color: #f8f9fa;
            }

            .radio-option input[type="radio"] {
                margin: 0 4px 0 0;
                width: 12px;
                height: 12px;
                cursor: pointer;
            }

            .radio-label {
                font-weight: 500;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.3s ease;
                white-space: nowrap;
            }

            .radio-label.yes {
                color: #28a745;
            }

            .radio-label.no {
                color: #dc3545;
            }

            .radio-option input[type="radio"]:checked + .radio-label.yes {
                color: #28a745;
                font-weight: 700;
            }

            .radio-option input[type="radio"]:checked + .radio-label.no {
                color: #dc3545;
                font-weight: 700;
            }

            .radio-option:has(input[type="radio"]:checked) {
                background-color: rgba(227, 21, 117, 0.1);
                border-color: #e31575;
            }

            /* Statistics */
            .participants-stats {
                background: #f8f9fa;
                padding: 20px 30px;
                border-top: 1px solid #e0e0e0;
                display: flex;
                gap: 40px;
                flex-wrap: wrap;
            }

            .stat-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
            }

            .stat-label {
                font-size: 12px;
                color: #666;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .stat-value {
                font-size: 24px;
                font-weight: 700;
                color: #e31575;
            }

            /* Buttons */
            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
            }

            .btn-primary {
                background: #e31575;
                color: white;
            }

            .btn-primary:hover {
                background: #c41e3a;
            }

            .btn-danger {
                background: #dc3545;
                color: white;
                padding: 8px 12px;
            }

            .btn-danger:hover {
                background: #c82333;
            }

            .btn-secondary {
                background: #6c757d;
                color: white;
            }

            .btn-secondary:hover {
                background: #545b62;
            }

            /* Modal Styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
            }

            .modal-content {
                background-color: white;
                margin: 5% auto;
                padding: 0;
                border-radius: 12px;
                width: 90%;
                max-width: 500px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            }

            .modal-header {
                background: #e31575;
                color: white;
                padding: 20px 30px;
                border-radius: 12px 12px 0 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-header h3 {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
            }

            .close {
                color: white;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
                line-height: 1;
            }

            .close:hover {
                opacity: 0.7;
            }

            .modal-body {
                padding: 30px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #333;
            }

            .form-control {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e0e0e0;
                border-radius: 6px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                outline: none;
                border-color: #e31575;
                box-shadow: 0 0 0 3px rgba(227, 21, 117, 0.1);
            }

            .modal-actions {
                display: flex;
                justify-content: flex-end;
                gap: 15px;
                margin-top: 20px;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .main {
                    margin-left: 0;
                    padding: 10px;
                }
                
                .event-details {
                    flex-direction: column;
                    gap: 15px;
                }
                
                .section-header {
                    flex-direction: column;
                    gap: 15px;
                    text-align: center;
                }
                
                .header-left {
                    flex-direction: column;
                    gap: 15px;
                    align-items: center;
                }
                
                .participants-stats {
                    justify-content: center;
                }
                
                .participants-table {
                    font-size: 12px;
                }
                
                .participants-table th,
                .participants-table td {
                    padding: 10px 8px;
                }
                
                .attendance-radio {
                    gap: 10px;
                }
            }
        </style>

        <script>
            // Attendance radio button functionality
            document.addEventListener('DOMContentLoaded', function() {
                const attendanceRadios = document.querySelectorAll('.attendance-radio-btn');
                
                attendanceRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const eventId = this.dataset.eventId;
                        const seniorId = this.dataset.seniorId;
                        const attended = this.value === 'yes';
                        
                        // Show loading state
                        this.disabled = true;
                        
                        fetch(`/Events/${eventId}/participants/${seniorId}/attendance`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                attended: attended
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update statistics
                                updateStatistics();
                            } else {
                                // Revert radio state
                                const otherRadio = document.querySelector(`input[name="attendance_${seniorId}"][value="${attended ? 'no' : 'yes'}"]`);
                                if (otherRadio) {
                                    otherRadio.checked = true;
                                }
                                alert('Error updating attendance: ' + data.message);
                            }
                        })
                        .catch(error => {
                            // Revert radio state
                            const otherRadio = document.querySelector(`input[name="attendance_${seniorId}"][value="${attended ? 'no' : 'yes'}"]`);
                            if (otherRadio) {
                                otherRadio.checked = true;
                            }
                            alert('Error updating attendance. Please try again.');
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            this.disabled = false;
                        });
                    });
                });
            });

            // Update statistics
            function updateStatistics() {
                // Reload the page to update statistics
                // In a real application, you might want to update via AJAX
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }

            // Remove participant
            function removeParticipant(eventId, seniorId) {
                if (confirm('Are you sure you want to remove this participant?')) {
                    fetch(`/Events/${eventId}/participants/${seniorId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            alert('Error removing participant. Please try again.');
                        }
                    })
                    .catch(error => {
                        alert('Error removing participant. Please try again.');
                        console.error('Error:', error);
                    });
                }
            }

            // Modal functions
            function openAddParticipantModal() {
                document.getElementById('addParticipantModal').style.display = 'block';
            }

            function closeAddParticipantModal() {
                document.getElementById('addParticipantModal').style.display = 'none';
                document.getElementById('addParticipantForm').reset();
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('addParticipantModal');
                if (event.target === modal) {
                    closeAddParticipantModal();
                }
            }
        </script>
    </x-header>
</x-sidebar>
