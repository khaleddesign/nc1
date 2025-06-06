<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()
                            ->with('chantier')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->marquerLue();
        
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('lu', false)
            ->update(['lu' => true, 'lu_at' => now()]);
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}