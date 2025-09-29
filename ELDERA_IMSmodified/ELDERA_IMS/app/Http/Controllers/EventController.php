<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Senior;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(): View
    {
        $events = Event::with(['createdBy'])
            ->orderBy('event_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('events', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_type' => 'required|string|in:general,pension,health,id_claiming',
                'event_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after:start_time',
                'location' => 'required|string|max:255',
                'organizer' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'max_participants' => 'nullable|integer|min:1',
                'requirements' => 'nullable|string',
            ]);

            $validatedData['status'] = 'upcoming';
            $validatedData['current_participants'] = 0;
            $validatedData['created_by'] = Auth::id();

            Event::create($validatedData);

            return redirect()->route('events')
                ->with('success', 'Event created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while creating the event. Please try again.');
        }
    }

    /**
     * Display the specified event.
     */
    public function show(string $id): View
    {
        $event = Event::with(['createdBy', 'participants'])
            ->findOrFail($id);

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(string $id): View
    {
        $event = Event::findOrFail($id);

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $event = Event::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_type' => 'required|string|in:general,pension,health,id_claiming',
                'event_date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after:start_time',
                'location' => 'required|string|max:255',
                'organizer' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'max_participants' => 'nullable|integer|min:1',
                'requirements' => 'nullable|string',
                'status' => 'required|string|in:upcoming,ongoing,completed,cancelled',
            ]);

            $event->update($validatedData);

            return redirect()->route('events')
                ->with('success', 'Event updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating the event. Please try again.');
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();

            return redirect()->route('events')
                ->with('success', 'Event deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the event. Please try again.');
        }
    }

    /**
     * Show participants for an event.
     */
    public function participants(string $id): View
    {
        $event = Event::with(['participants', 'createdBy'])->findOrFail($id);
        
        // Get all seniors for potential registration
        $allSeniors = Senior::select('id', 'first_name', 'last_name', 'osca_id')->get();
        
        return view('events.participants', compact('event', 'allSeniors'));
    }

    /**
     * Update participant attendance.
     */
    public function updateAttendance(Request $request, string $eventId, string $seniorId): JsonResponse
    {
        try {
            $event = Event::findOrFail($eventId);
            $senior = Senior::findOrFail($seniorId);
            
            $attended = $request->boolean('attended');
            
            // Update the attendance in the pivot table
            $event->participants()->updateExistingPivot($seniorId, [
                'attended' => $attended,
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'attended' => $attended
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating attendance'
            ], 500);
        }
    }

    /**
     * Register a senior for an event.
     */
    public function registerParticipant(Request $request, string $eventId): RedirectResponse
    {
        try {
            $event = Event::findOrFail($eventId);
            $seniorId = $request->input('senior_id');
            
            if (!$seniorId) {
                return redirect()->back()->with('error', 'Please select a senior to register.');
            }
            
            $senior = Senior::findOrFail($seniorId);
            
            // Check if already registered
            if ($event->participants()->where('senior_id', $seniorId)->exists()) {
                return redirect()->back()->with('error', 'This senior is already registered for this event.');
            }
            
            // Register the senior
            $event->participants()->attach($seniorId, [
                'registered_at' => now(),
                'attended' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Update current participants count
            $event->increment('current_participants');
            
            return redirect()->back()->with('success', 'Senior registered successfully for the event.');
            
        } catch (\Exception $e) {
            Log::error('Error registering participant: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while registering the participant.');
        }
    }

    /**
     * Remove a participant from an event.
     */
    public function removeParticipant(string $eventId, string $seniorId): RedirectResponse
    {
        try {
            $event = Event::findOrFail($eventId);
            
            $detached = $event->participants()->detach($seniorId);
            
            if ($detached) {
                $event->decrement('current_participants');
                return redirect()->back()->with('success', 'Participant removed successfully.');
            }
            
            return redirect()->back()->with('error', 'Participant not found.');
            
        } catch (\Exception $e) {
            Log::error('Error removing participant: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while removing the participant.');
        }
    }

    /**
     * Get events for calendar display.
     */
    public function getCalendarEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $events = Event::whereBetween('event_date', [$start, $end])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->event_date->format('Y-m-d'),
                    'end' => $event->event_date->format('Y-m-d'),
                    'time' => $event->start_time->format('H:i'),
                    'location' => $event->location,
                    'type' => $event->event_type,
                    'status' => $event->status,
                    'color' => $this->getEventColor($event->event_type),
                ];
            });

        return response()->json($events);
    }

    /**
     * Get event color based on type.
     */
    private function getEventColor(string $type): string
    {
        return match($type) {
            'general' => '#007bff',
            'pension' => '#28a745',
            'health' => '#dc3545',
            'id_claiming' => '#ffc107',
            default => '#6c757d'
        };
    }
}
