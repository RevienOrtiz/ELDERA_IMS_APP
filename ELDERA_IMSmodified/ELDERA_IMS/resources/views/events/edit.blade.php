<x-sidebar>
    <x-header title="Edit Event" icon="fas fa-calendar-edit">
        @include('message.popup_message')
        <div class="main">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Edit Event: {{ $event->title }}</h2>
                    <a href="{{ route('events.show', $event->id) }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Event Details
                    </a>
                </div>

                <form method="POST" action="{{ route('events.update', $event->id) }}" class="event-form">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-section">
                        <h3 class="section-title">Event Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="title" class="form-label">Event Title *</label>
                                <input type="text" id="title" name="title" class="form-control" 
                                       value="{{ old('title', $event->title) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" 
                                          rows="3">{{ old('description', $event->description) }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_type" class="form-label">Event Type *</label>
                                <select id="event_type" name="event_type" class="form-control" required>
                                    <option value="">Select Event Type</option>
                                    <option value="general" {{ old('event_type', $event->event_type) == 'general' ? 'selected' : '' }}>General Meeting</option>
                                    <option value="pension" {{ old('event_type', $event->event_type) == 'pension' ? 'selected' : '' }}>Pension Distribution</option>
                                    <option value="health" {{ old('event_type', $event->event_type) == 'health' ? 'selected' : '' }}>Health Check-up</option>
                                    <option value="id_claiming" {{ old('event_type', $event->event_type) == 'id_claiming' ? 'selected' : '' }}>ID Claiming</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="status" class="form-label">Event Status *</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="upcoming" {{ old('status', $event->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('status', $event->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Date & Time</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="date" id="event_date" name="event_date" class="form-control" 
                                       value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="start_time" class="form-label">Start Time *</label>
                                <input type="time" id="start_time" name="start_time" class="form-control" 
                                       value="{{ old('start_time', $event->start_time->format('H:i')) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" id="end_time" name="end_time" class="form-control" 
                                       value="{{ old('end_time', $event->end_time ? $event->end_time->format('H:i') : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Location & Contact</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" id="location" name="location" class="form-control" 
                                       value="{{ old('location', $event->location) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="organizer" class="form-label">Organizer</label>
                                <input type="text" id="organizer" name="organizer" class="form-control" 
                                       value="{{ old('organizer', $event->organizer) }}">
                            </div>
                            <div class="form-group">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" id="contact_person" name="contact_person" class="form-control" 
                                       value="{{ old('contact_person', $event->contact_person) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number" class="form-control" 
                                       value="{{ old('contact_number', $event->contact_number) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Participants & Requirements</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="max_participants" class="form-label">Maximum Participants</label>
                                <input type="number" id="max_participants" name="max_participants" class="form-control" 
                                       value="{{ old('max_participants', $event->max_participants) }}" min="1">
                                <small class="form-text">Leave empty for unlimited participants</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Current Participants</label>
                                <input type="text" class="form-control" value="{{ $event->current_participants }}" readonly>
                                <small class="form-text">Read-only field - managed automatically</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Created By</label>
                                <input type="text" class="form-control" value="{{ $event->createdBy->name ?? 'Unknown User' }}" readonly>
                                <small class="form-text">Read-only field</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Created At</label>
                                <input type="text" class="form-control" value="{{ $event->created_at->format('M d, Y g:i A') }}" readonly>
                                <small class="form-text">Read-only field</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="requirements" class="form-label">Requirements</label>
                                <textarea id="requirements" name="requirements" class="form-control" 
                                          rows="3">{{ old('requirements', $event->requirements) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('events.show', $event->id) }}'">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </form>
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

            .form-container {
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }

            .form-header {
                background: linear-gradient(135deg, #e31575 0%, #c01060 100%);
                color: white;
                padding: 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .form-title {
                margin: 0;
                font-size: 24px;
                font-weight: 700;
            }

            .back-btn {
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

            .back-btn:hover {
                background: white;
                color: #e31575;
                text-decoration: none;
            }

            .event-form {
                padding: 30px;
            }

            .form-section {
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 1px solid #e0e0e0;
            }

            .form-section:last-child {
                border-bottom: none;
            }

            .section-title {
                color: #e31575;
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #f0f0f0;
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 20px;
            }

            .form-row .form-group:only-child {
                grid-column: 1 / -1;
            }

            .form-group {
                display: flex;
                flex-direction: column;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .form-control {
                padding: 12px 16px;
                border: 2px solid #e0e0e0;
                border-radius: 6px;
                font-size: 14px;
                transition: all 0.3s ease;
                background: white;
            }

            .form-control:focus {
                outline: none;
                border-color: #e31575;
                box-shadow: 0 0 0 3px rgba(227, 21, 117, 0.1);
            }

            .form-control[readonly] {
                background-color: #f8f9fa;
                color: #6c757d;
            }

            .form-text {
                font-size: 12px;
                color: #666;
                margin-top: 5px;
            }

            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 6px;
            }

            .alert-danger {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
            }

            .form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 15px;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #e0e0e0;
            }

            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
            }

            .btn-secondary {
                background: #6c757d;
                color: white;
            }

            .btn-secondary:hover {
                background: #545b62;
            }

            .btn-primary {
                background: #e31575;
                color: white;
            }

            .btn-primary:hover {
                background: #c41e3a;
            }

            @media (max-width: 768px) {
                .main {
                    margin-left: 0;
                    padding: 10px;
                }
                
                .form-header {
                    flex-direction: column;
                    gap: 20px;
                    text-align: center;
                }
                
                .form-row {
                    grid-template-columns: 1fr;
                }
                
                .form-actions {
                    flex-direction: column;
                }
            }
        </style>
    </x-header>
</x-sidebar>
