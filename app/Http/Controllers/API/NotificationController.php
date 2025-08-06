<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotficationResource;
use App\Models\notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $notifications = $user->notifications()->latest()->paginate(10);
        return NotficationResource::collection($notifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:policy_approved,claim_update,payment_reminder,kyc_status,system_alert',
            'related_entity_type' => 'nullable|in:policy,claim,payment,user',
            'related_entity_id' => 'nullable|integer',
            'action_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $notification = $user->notifications()->create($request->all());

        return new NotficationResource($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(notifications $notification)
    {
        return new NotficationResource($notification);
    }

    /**
     * Mark as read
     */
    public function markAsRead(notifications $notification)
    {
        $notification->update(['is_read' => true]);
        return new NotficationResource($notification);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead(User $user)
    {
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(notifications $notification)
    {
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}