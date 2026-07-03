<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\Authenticate;


class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }
   
    public function index()
    {
        
        $user = auth()->user();

        // Check if ChangPwd is true, redirect to dashboard
        if ($user && $user->ChangePwd) {
            return redirect('/contact-details');
        }

        return view('change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect current password']);
        }

        // Update user password
        $user->update(['password' => Hash::make($request->new_password)]);

        // Set changepwd to true
        $user->update(['ChangePwd' => true]);

        return redirect('/dashboard')->with('success', 'Password changed successfully.');
    }
}