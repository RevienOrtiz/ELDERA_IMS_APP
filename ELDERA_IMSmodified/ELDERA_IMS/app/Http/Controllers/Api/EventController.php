<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $events = \App\Models\Event::with(['createdBy'])
            ->orderBy('event_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $events->items(),
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    /**
     * Display the specified event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $event = \App\Models\Event::with(['createdBy', 'participants'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_type' => $event->event_type,
                'event_type_text' => $event->event_type_text,
                'date' => $event->event_date->format('Y-m-d'),
                'time' => $event->start_time->format('H:i:s'),
                'end_time' => $event->end_time?->format('H:i:s'),
                'location' => $event->location,
                'organizer' => $event->organizer,
                'contact_person' => $event->contact_person,
                'contact_number' => $event->contact_number,
                'status' => $event->status,
                'status_text' => $event->status_text,
                'max_participants' => $event->max_participants,
                'current_participants' => $event->current_participants,
                'available_slots' => $event->available_slots,
                'is_full' => $event->is_full,
                'requirements' => $event->requirements,
                'created_by' => $event->createdBy?->name,
                'created_at' => $event->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}