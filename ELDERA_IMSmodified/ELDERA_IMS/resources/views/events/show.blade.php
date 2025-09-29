<x-sidebar>
    <x-header title="Event Details" icon="fas fa-calendar-check">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('message.popup_message')
        <div class="main">
            <div class="event-details-container">
                <!-- Event Header -->
                <div class="event-header">
                    <div class="header-left">
                        <h1 class="event-title">{{ $event->title }}</h1>
                        <div class="event-meta">
                            <span class="event-type {{ $event->event_type }}">
                                <i class="fas fa-tag"></i>
                                {{ $event->event_type_text }}
                            </span>
                            <span class="event-status {{ $event->status }}">
                                <i class="fas fa-circle"></i>
                                {{ $event->status_text }}
                            </span>
                        </div>
                    </div>
                    <div class="header-right">
                        <a href="{{ route('events') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i> Back to Events
                        </a>
                        @if($event->status === 'upcoming')
                            <a href="{{ route('events.edit', $event->id) }}" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit Event
                            </a>
                        @endif
                        <a href="{{ route('events.participants', $event->id) }}" class="participants-btn">
                            <i class="fas fa-users"></i> Manage Participants
                        </a>
                    </div>
                </div>

                <!-- Event Content -->
                <div class="event-content">
                    <div class="content-left">
                        <!-- Event Information -->
                        <div class="info-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Event Information
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Date & Time</label>
                                    <div class="info-value">
                                        <i class="fas fa-calendar"></i>
                                        {{ $event->formatted_date_time }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Location</label>
                                    <div class="info-value">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $event->location }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Organizer</label>
                                    <div class="info-value">
                                        <i class="fas fa-user-tie"></i>
                                        {{ $event->organizer }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Contact Person</label>
                                    <div class="info-value">
                                        <i class="fas fa-phone"></i>
                                        {{ $event->contact_person }} - {{ $event->contact_number }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($event->description)
                        <div class="info-section">
                            <h3 class="section-title">
                                <i class="fas fa-align-left"></i>
                                Description
                            </h3>
                            <div class="description-content">
                                {{ $event->description }}
                            </div>
                        </div>
                        @endif

                        <!-- Requirements -->
                        @if($event->requirements)
                        <div class="info-section">
                            <h3 class="section-title">
                                <i class="fas fa-clipboard-list"></i>
                                Requirements
                            </h3>
                            <div class="requirements-content">
                                {{ $event->requirements }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="content-right">
                        <!-- Participants -->
                        <div class="participants-section">
                            <h3 class="section-title">
                                <i class="fas fa-users"></i>
                                Participants
                            </h3>
                            <div class="participants-stats">
                                <div class="stat-item">
                                    <span class="stat-number">{{ $event->current_participants }}</span>
                                    <span class="stat-label">Registered</span>
                                </div>
                                @if($event->max_participants)
                                <div class="stat-item">
                                    <span class="stat-number">{{ $event->max_participants }}</span>
                                    <span class="stat-label">Max Capacity</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">{{ $event->available_slots }}</span>
                                    <span class="stat-label">Available</span>
                                </div>
                                @else
                                <div class="stat-item">
                                    <span class="stat-number">∞</span>
                                    <span class="stat-label">Unlimited</span>
                                </div>
                                @endif
                            </div>

                            @if($event->participants->count() > 0)
                            <div class="participants-list">
                                <h4>Registered Participants</h4>
                                <div class="participants-grid">
                                    @foreach($event->participants as $participant)
                                    <div class="participant-item">
                                        <div class="participant-info">
                                            <strong>{{ $participant->first_name }} {{ $participant->last_name }}</strong>
                                            <small>{{ $participant->osca_id }}</small>
                                        </div>
                                        <div class="participant-status">
                                            @if($participant->pivot->attended)
                                                <span class="status-attended">
                                                    <i class="fas fa-check-circle"></i> Attended
                                                </span>
                                            @else
                                                <span class="status-registered">
                                                    <i class="fas fa-clock"></i> Registered
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="no-participants">
                                <i class="fas fa-users-slash"></i>
                                <p>No participants registered yet</p>
                            </div>
                            @endif
                        </div>

                        <!-- Event Actions -->
                        <div class="actions-section">
                            <h3 class="section-title">
                                <i class="fas fa-cogs"></i>
                                Event Actions
                            </h3>
                            <div class="action-buttons">
                                @if($event->status === 'upcoming')
                                    <button class="action-btn ongoing-btn" onclick="updateEventStatus('{{ $event->id }}', 'ongoing')">
                                        <i class="fas fa-play"></i>
                                        Mark as Ongoing
                                    </button>
                                @endif
                                
                                @if($event->status === 'ongoing')
                                    <button class="action-btn complete-btn" onclick="updateEventStatus('{{ $event->id }}', 'completed')">
                                        <i class="fas fa-check"></i>
                                        Mark as Completed
                                    </button>
                                @endif
                                
                                @if(in_array($event->status, ['upcoming', 'ongoing']))
                                    <button class="action-btn cancel-btn" onclick="updateEventStatus('{{ $event->id }}', 'cancelled')">
                                        <i class="fas fa-times"></i>
                                        Cancel Event
                                    </button>
                                @endif
                                
                                <button class="action-btn delete-btn" onclick="deleteEvent('{{ $event->id }}')">
                                    <i class="fas fa-trash"></i>
                                    Delete Event
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Confirmation Modal for Events -->
        <div class="modal fade" id="eventConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                            <i class="bi bi-exclamation-lg text-white fs-2"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-warning" id="eventConfirmTitle">Are you sure?</h4>
                    <p id="eventConfirmMessage">Do you really want to proceed?</p>
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning w-50" id="eventConfirmBtn" onclick="executeConfirmedAction()">Yes, Proceed</button>
                    </div>
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

            .event-details-container {
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }

            .event-header {
                background: linear-gradient(135deg, #e31575 0%, #c01060 100%);
                color: white;
                padding: 30px;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }

            .event-title {
                margin: 0 0 15px 0;
                font-size: 28px;
                font-weight: 700;
            }

            .event-meta {
                display: flex;
                gap: 20px;
                align-items: center;
            }

            .event-type, .event-status {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 600;
            }

            .event-type.general { background: rgba(0, 123, 255, 0.2); }
            .event-type.pension { background: rgba(40, 167, 69, 0.2); }
            .event-type.health { background: rgba(220, 53, 69, 0.2); }
            .event-type.id_claiming { background: rgba(255, 193, 7, 0.2); }

            .event-status.upcoming { background: rgba(255, 193, 7, 0.2); }
            .event-status.ongoing { background: rgba(40, 167, 69, 0.2); }
            .event-status.completed { background: rgba(108, 117, 125, 0.2); }
            .event-status.cancelled { background: rgba(220, 53, 69, 0.2); }

            .header-right {
                display: flex;
                gap: 15px;
                align-items: center;
            }

            .back-btn, .edit-btn {
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border: 2px solid white;
                border-radius: 6px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 600;
            }

            .back-btn:hover, .edit-btn:hover, .participants-btn:hover {
                background: white;
                color: #e31575;
                text-decoration: none;
            }

            .participants-btn {
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border: 2px solid white;
                border-radius: 6px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 600;
                background: rgba(40, 167, 69, 0.2);
            }

            .event-content {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 30px;
                padding: 30px;
            }

            .info-section {
                margin-bottom: 30px;
            }

            .section-title {
                color: #e31575;
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #f0f0f0;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .info-item {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                border-left: 4px solid #e31575;
            }

            .info-item label {
                display: block;
                font-weight: 600;
                color: #666;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .info-value {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 16px;
                color: #333;
            }

            .description-content, .requirements-content {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                border-left: 4px solid #e31575;
                line-height: 1.6;
            }

            .participants-section {
                background: #f8f9fa;
                padding: 25px;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .participants-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }

            .stat-item {
                text-align: center;
                background: white;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .stat-number {
                display: block;
                font-size: 24px;
                font-weight: 700;
                color: #e31575;
            }

            .stat-label {
                font-size: 12px;
                color: #666;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .participants-list h4 {
                margin-bottom: 15px;
                color: #333;
            }

            .participants-grid {
                max-height: 300px;
                overflow-y: auto;
            }

            .participant-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px;
                background: white;
                border-radius: 6px;
                margin-bottom: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .participant-info strong {
                display: block;
                color: #333;
            }

            .participant-info small {
                color: #666;
                font-size: 12px;
            }

            .status-attended {
                color: #28a745;
                font-size: 12px;
                font-weight: 600;
            }

            .status-registered {
                color: #ffc107;
                font-size: 12px;
                font-weight: 600;
            }

            .no-participants {
                text-align: center;
                padding: 40px 20px;
                color: #666;
            }

            .no-participants i {
                font-size: 48px;
                margin-bottom: 15px;
                opacity: 0.5;
            }

            .actions-section {
                background: #f8f9fa;
                padding: 25px;
                border-radius: 8px;
            }

            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .action-btn {
                padding: 12px 20px;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                justify-content: center;
            }

            .ongoing-btn {
                background: #28a745;
                color: white;
            }

            .ongoing-btn:hover {
                background: #218838;
            }

            .complete-btn {
                background: #17a2b8;
                color: white;
            }

            .complete-btn:hover {
                background: #138496;
            }

            .cancel-btn {
                background: #ffc107;
                color: #333;
            }

            .cancel-btn:hover {
                background: #e0a800;
            }

            .delete-btn {
                background: #dc3545;
                color: white;
            }

            .delete-btn:hover {
                background: #c82333;
            }


            @media (max-width: 768px) {
                .main {
                    margin-left: 0;
                    padding: 10px;
                }
                
                .event-content {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                
                .event-header {
                    flex-direction: column;
                    gap: 20px;
                }
                
                .header-right {
                    width: 100%;
                    justify-content: flex-start;
                }
                
                .info-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <script>
            let currentAction = null;
            let currentEventId = null;

            function updateEventStatus(eventId, status) {
                console.log('updateEventStatus called with:', eventId, status);
                currentAction = 'updateStatus';
                currentEventId = eventId;
                
                const statusMessages = {
                    'ongoing': 'mark this event as ongoing?',
                    'completed': 'mark this event as completed?',
                    'cancelled': 'cancel this event?'
                };
                
                document.getElementById('eventConfirmTitle').textContent = 'Confirm Status Change';
                document.getElementById('eventConfirmMessage').textContent = `Are you sure you want to ${statusMessages[status]}`;
                document.getElementById('eventConfirmBtn').textContent = 'Confirm';
                
                // Set button color based on status
                if (status === 'ongoing') {
                    document.getElementById('eventConfirmBtn').className = 'btn btn-success w-50';
                } else if (status === 'cancelled') {
                    document.getElementById('eventConfirmBtn').className = 'btn btn-warning w-50';
                } else {
                    document.getElementById('eventConfirmBtn').className = 'btn btn-primary w-50';
                }
                
                // Store the status for the confirmation
                document.getElementById('eventConfirmBtn').dataset.status = status;
                
                const modal = new bootstrap.Modal(document.getElementById('eventConfirmModal'));
                modal.show();
            }

            function deleteEvent(eventId) {
                console.log('deleteEvent called with:', eventId);
                currentAction = 'delete';
                currentEventId = eventId;
                
                document.getElementById('eventConfirmTitle').textContent = 'Confirm Deletion';
                document.getElementById('eventConfirmMessage').textContent = 'Are you sure you want to delete this event? This action cannot be undone.';
                document.getElementById('eventConfirmBtn').textContent = 'Delete';
                document.getElementById('eventConfirmBtn').className = 'btn btn-danger w-50';
                
                const modal = new bootstrap.Modal(document.getElementById('eventConfirmModal'));
                modal.show();
            }

            function executeConfirmedAction() {
                if (currentAction === 'updateStatus') {
                    const status = document.getElementById('eventConfirmBtn').dataset.status;
                    fetch(`/Events/${currentEventId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status,
                            _method: 'PUT'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            showErrorModal('Error updating event status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorModal('Error updating event status');
                    });
                } else if (currentAction === 'delete') {
                    fetch(`/Events/${currentEventId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        redirect: 'follow'
                    })
                    .then(response => {
                        // For DELETE requests that return redirects, we consider it successful
                        // The server will redirect to /Events with success message
                        // Force cache refresh by adding timestamp
                        window.location.href = '/Events?t=' + Date.now();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorModal('Error deleting event');
                    });
                }
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('eventConfirmModal'));
                if (modal) {
                    modal.hide();
                }
            }

            // Function to show error modal using the existing popup system
            function showErrorModal(message) {
                document.getElementById('errorMessage').innerText = message;
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }

        </script>
    </x-header>
</x-sidebar>






