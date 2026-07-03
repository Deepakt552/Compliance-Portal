<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserDetailsMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // If both changepassword and contactdetails are false, redirect to update details
        if (!$user->ChangePwd  && !$user->ContactDetails ) {
            // Redirect to the change password page
            return redirect()->route('change.password');
        }

        // If either changepassword or contactdetails is false, redirect accordingly
        if (!$user->ChangePwd || !$user->ContactDetails ) {
            if (!$user->changepassword) {
                // Redirect to the change password page
                return redirect()->route('contact.details');
            }

        }

        // If both changepassword and contactdetails are true, allow access to the dashboard
        return $next($request);
    }
}