<?php

namespace App\Http\Controllers;
use App\Http\Middleware\Authenticate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ContactDetailsController extends Controller
{

    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }


    public function index()
    {
        $user = auth()->user();

        // Check if ContactDetails is true, redirect to dashboard
        if ($user && $user->ContactDetails) {
            return redirect('/dashboard');
        }

        return view('contact-details');
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'PhoneNumber' => 'required',
            // 'address' => 'required',
        ]);
    
        $user = auth()->user();
    
        try {
            // Update user contact details
            $user->update([
                'email' => $request->email,
                'PhoneNumber' => $request->PhoneNumber,
                // 'address' => $request->address,
            ]);
    
            // Set contact details to true
            $user->update(['ContactDetails' => true]);
    
            return redirect('/dashboard')->with('success', 'Contact details updated successfully.');
        } catch (QueryException $e) {
            // Handle the specific error for duplicate entry
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->withInput()->with('error', 'Email or phone number already exists.');
            }
            
            // For other database errors, redirect with a generic error message
            return redirect()->back()->withInput()->with('error', 'An error occurred. Please try again.');
        }
    }
}