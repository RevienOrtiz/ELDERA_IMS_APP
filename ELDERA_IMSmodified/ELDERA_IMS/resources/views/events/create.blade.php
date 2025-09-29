<x-sidebar>
    <x-header title="Add New Event" icon="fas fa-calendar-plus">
        @include('message.popup_message')
        <div class="main">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Create New Event</h2>
                    <a href="{{ route('events') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Events
                    </a>
                </div>

                <form method="POST" action="{{ route('events.store') }}" class="event-form">
                    @csrf

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
                                       value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" 
                                          rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_type" class="form-label">Event Type *</label>
                                <select id="event_type" name="event_type" class="form-control" required>
                                    <option value="">Select Event Type</option>
                                    <option value="general" {{ old('event_type') == 'general' ? 'selected' : '' }}>General Meeting</option>
                                    <option value="pension" {{ old('event_type') == 'pension' ? 'selected' : '' }}>Pension Distribution</option>
                                    <option value="health" {{ old('event_type') == 'health' ? 'selected' : '' }}>Health Check-up</option>
                                    <option value="id_claiming" {{ old('event_type') == 'id_claiming' ? 'selected' : '' }}>ID Claiming</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="date" id="event_date" name="event_date" class="form-control" 
                                       value="{{ old('event_date') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="start_time" class="form-label">Start Time *</label>
                                <input type="time" id="start_time" name="start_time" class="form-control" 
                                       value="{{ old('start_time') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" id="end_time" name="end_time" class="form-control" 
                                       value="{{ old('end_time') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" id="location" name="location" class="form-control" 
                                       value="{{ old('location') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Contact Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="organizer" class="form-label">Organizer *</label>
                                <input type="text" id="organizer" name="organizer" class="form-control" 
                                       value="{{ old('organizer') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_person" class="form-label">Contact Person *</label>
                                <input type="text" id="contact_person" name="contact_person" class="form-control" 
                                       value="{{ old('contact_person') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number *</label>
                                <input type="text" id="contact_number" name="contact_number" class="form-control" 
                                       value="{{ old('contact_number') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Event Details</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="max_participants" class="form-label">Maximum Participants</label>
                                <input type="number" id="max_participants" name="max_participants" class="form-control" 
                                       value="{{ old('max_participants') }}" min="1">
                                <small class="form-text">Leave empty for unlimited participants</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="requirements" class="form-label">Requirements</label>
                                <textarea id="requirements" name="requirements" class="form-control" 
                                          rows="3">{{ old('requirements') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('events') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Event
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
                padding: 20px 30px;
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
                padding: 8px 16px;
                border: 2px solid white;
                border-radius: 6px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
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
                display: flex;
                gap: 20px;
                margin-bottom: 20px;
            }

            .form-group {
                flex: 1;
            }

            .form-label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #333;
            }

            .form-control {
                width: 100%;
                padding: 12px;
                border: 2px solid #ddd;
                border-radius: 6px;
                font-size: 14px;
                transition: border-color 0.3s ease;
            }

            .form-control:focus {
                outline: none;
                border-color: #e31575;
                box-shadow: 0 0 0 3px rgba(227, 21, 117, 0.1);
            }

            .form-text {
                font-size: 12px;
                color: #666;
                margin-top: 5px;
            }

            .form-actions {
                display: flex;
                gap: 15px;
                justify-content: flex-end;
                padding-top: 20px;
                border-top: 1px solid #eee;
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
                background: #5a6268;
                color: white;
                text-decoration: none;
            }

            .btn-primary {
                background: #e31575;
                color: white;
            }

            .btn-primary:hover {
                background: #c01060;
                color: white;
            }

            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 6px;
            }

            .alert-danger {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            @media (max-width: 768px) {
                .main {
                    margin-left: 0;
                    padding: 10px;
                }
                
                .form-row {
                    flex-direction: column;
                    gap: 10px;
                }
                
                .form-actions {
                    flex-direction: column;
                }
            }
        </style>
    </x-header>
</x-sidebar>
