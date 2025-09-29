<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Notification::where('user_id', Auth::id())
                ->orWhere('senior_id', Auth::user()->senior_id ?? null);

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('read')) {
                $query->where('is_read', $request->boolean('read'));
            }

            $notifications = $query->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
                'unread_count' => Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(int $id): JsonResponse
    {
        try {
            $notification = Notification::where('id', $id)
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
                          ->orWhere('senior_id', Auth::user()->senior_id ?? null);
                })
                ->firstOrFail();

            $notification->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            Notification::where('user_id', Auth::id())
                ->orWhere('senior_id', Auth::user()->senior_id ?? null)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $seniorId = Auth::user()->senior_id ?? null;

            $stats = [
                'total' => Notification::where('user_id', $userId)
                    ->orWhere('senior_id', $seniorId)
                    ->count(),
                'unread' => Notification::where('user_id', $userId)
                    ->orWhere('senior_id', $seniorId)
                    ->where('is_read', false)
                    ->count(),
                'by_type' => Notification::where('user_id', $userId)
                    ->orWhere('senior_id', $seniorId)
                    ->selectRaw('type, COUNT(*) as count')
                    ->groupBy('type')
                    ->get()
                    ->pluck('count', 'type')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notification statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $notification = Notification::where('id', $id)
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
                          ->orWhere('senior_id', Auth::user()->senior_id ?? null);
                })
                ->firstOrFail();

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }
}
