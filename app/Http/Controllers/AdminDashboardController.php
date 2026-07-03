<?php
// app/Http/Controllers/AdminDashboardController.php

namespace App\Http\Controllers;
use App\Models\HouseholdData;
use App\Models\Notification;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Properties;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentRejected;
use App\Mail\DocumentApproved;
// app/Http/Controllers/AdminDashboardController.php

class AdminDashboardController extends Controller
{

  
    public function __construct()
    {
        $this->middleware('admin.auth');
    }
    public function index(Request $request)
    {
        // Get distinct codes and unitNos for filtering
        $codes = Properties::distinct('Code')->pluck('Code');
        $unitNos = HouseholdData::distinct('UnitNo')->pluck('UnitNo');
    
        // Pass unit numbers and property names based on code to the view
        $unitNumbers = $this->getUnitNumbersByCode();
        $propertyNames = Properties::pluck('Property', 'Code');
    
        // Apply filters
        $query = HouseholdData::query();
        if ($request->has('code') && $request->input('code') != '') {
            $query->where('Code', $request->input('code'));
        }
        if ($request->has('unitNo') && $request->input('unitNo') != '') {
            $query->where('UnitNo', $request->input('unitNo'));
        }
    
        // Paginate the results
        $users = $query->paginate(8);

        // Calculate Metrics
        $metrics = [
            'total_properties'   => Properties::count(),
            'total_users'        => User::count(),
            'total_households'   => HouseholdData::count(),
            'total_documents'    => Document::count(),
            'pending_documents'  => Document::where('status', 'pending')->count(),
            'approved_documents' => Document::where('status', 'verified')->count(),
            'rejected_documents' => Document::where('status', 'rejected')->count(),
        ];
    
        return view('admin-dashboard.index', compact('users', 'codes', 'unitNos', 'unitNumbers', 'propertyNames', 'metrics'));
    }
    

    // Function to get unit numbers based on code
    private function getUnitNumbersByCode()
    {
        $unitNumbersByCode = HouseholdData::select('Code', 'UnitNo')->distinct()->get()->groupBy('Code');
        $unitNumbers = [];

        foreach ($unitNumbersByCode as $code => $units) {
            $unitNumbers[$code] = $units->pluck('UnitNo')->unique()->toArray();
        }

        return $unitNumbers;
    }
    public function showUserDocuments($family_member_id)
{
    try {
        // Retrieve the HouseholdData record for the given family_member_id
        $householdData = HouseholdData::findOrFail($family_member_id);
        
        // Access the user associated with the HouseholdData record
        $user = $householdData->user;

        // Retrieve the documents for the user based on family_member_id
        $documents = Document::where('family_member_id', $householdData->id)->get();
        
        // Retrieve property names
        $propertyNames = Properties::pluck('Property', 'Code');
        
        // Return the view with user and documents data
        return view('admin-dashboard.show_user_documents', compact('user', 'documents', 'propertyNames', 'householdData'));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // HouseholdData record not found
        return response()->view('errors.404', [], 404);
    } catch (\Exception $e) {
        // Other unexpected errors
        return response()->view('errors.500', [], 500);
    }
}

    

    public function approveDocument($documentId)
    {
        $document = Document::findOrFail($documentId);
        
        // Update document status to 'approved'
        $document->update(['status' => 'verified']);
    
        // Get the user associated with the document
        $user = $document->user;
        
        // Get the family member associated with the document
        $familyMember = $document->familyMember;
    
        // Create a new notification for document approval
        $notification = new Notification([
            'message' => $document->document_number,
            'status' => 'approved', 
            'user_id' => $user->id,
            'family_member_id' => $familyMember->id,
            'role' =>'Admin',
        ]);
    
        // Save the notification
        $notification->save();
    
        // Send email to the user
        Mail::to($user->email)->send(new DocumentApproved($document));
    
        return redirect()->back()->with('success', 'Document approved successfully.');
    }
    public function rejectDocument(Request $request, $documentId)
    {
        $request->validate([
            'comments' => 'required|string', // Validate comments field
        ]);
    
        $document = Document::findOrFail($documentId);
    
        // Update document status to 'rejected'
        $document->update([
            'status' => 'rejected',
            'comments' => $request->input('comments'), // Add comments to the document
        ]);
    
        // Get the user associated with the document
        $user = $document->user;
    
        // Get the family member associated with the document
        $familyMember = $document->familyMember;
    
        // Create a new notification for document rejection
        $notification = new Notification([
            'message' => $document->document_number, 
            'status' => 'rejected', 
            'user_id' => $user->id,
            'family_member_id' => $familyMember->id,
            'role' =>'Admin',

        ]);
    
        // Save the notification
        $notification->save();
    
        // Send email to the user
        Mail::to($user->email)->send(new DocumentRejected($document));
    
        return redirect()->back()->with('success', 'Document rejected successfully.');
    }
    

    
}