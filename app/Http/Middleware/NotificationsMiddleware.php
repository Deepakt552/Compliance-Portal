<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Notification;

class NotificationsMiddleware
{
    public function handle($request, Closure $next)
    {
        // Retrieve notifications for the current authenticated user with role 'admin'
        $userNotifications = Notification::where('user_id', Auth::id())
            ->where('role', 'admin')
            ->get();
        
        // Count the notifications
        $notificationCount = $userNotifications->count();

        // Share the user's notifications and the count with all views
        view()->share('notifications', $userNotifications);
        view()->share('notificationCount', $notificationCount);


        //for admin notifications
        $userNotifications = Notification::where('role', 'user')->get();
        
        // Count the user notifications
        $userNotificationCount = $userNotifications->count();

        // Share the user notifications and the count with all views
        view()->share('userNotifications', $userNotifications);
        view()->share('userNotificationCount', $userNotificationCount);

        return $next($request);
    }
}