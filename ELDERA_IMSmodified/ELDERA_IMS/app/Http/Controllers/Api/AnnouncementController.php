<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $announcements,
            'message' => 'Announcements retrieved successfully'
        ]);
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'what' => 'required|string',
            'when' => 'required|string',
            'where' => 'required|string',
            'category' => 'nullable|string|in:GENERAL,HEALTH,PENSION',
            'department' => 'nullable|string',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'what' => $request->what,
            'when' => $request->when,
            'where' => $request->where,
            'category' => $request->category ?? 'GENERAL',
            'department' => $request->department,
            'hasListen' => true,
            'postedDate' => Carbon::now()->format('M d, Y'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $announcement,
            'message' => 'Announcement created successfully'
        ], 201);
    }

    /**
     * Display the specified announcement.
     */
    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $announcement,
            'message' => 'Announcement retrieved successfully'
        ]);
    }
}