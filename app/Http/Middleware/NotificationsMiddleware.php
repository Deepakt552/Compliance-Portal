<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Notification;

class NotificationsMiddleware
{
    public function handle($request, Closure $next)
    {
        // Retrieve notifications for the current authenticated user with role 'admin' (meaning notifications sent by admin)
        $userNotifications = Notification::where('user_id', Auth::id())
            ->where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Count the unread notifications
        $notificationCount = $userNotifications->where('read', false)->count();

        // Share the user's notifications and the count with all views
        view()->share('notifications', $userNotifications);
        view()->share('notificationCount', $notificationCount);


        //for admin notifications (meaning notifications sent by user)
        $adminNotifications = Notification::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Count the unread admin notifications
        $userNotificationCount = $adminNotifications->where('read', false)->count();

        // Share the user notifications and the count with all views
        view()->share('userNotifications', $adminNotifications);
        view()->share('userNotificationCount', $userNotificationCount);

        return $next($request);
    }
}