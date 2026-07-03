<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\HouseholdData;
use App\Models\Notification;
use App\Models\Properties;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckUserDetailsMiddleware;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentUploaded;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkUserDetails');
    }

    public function index()
    {

        $notifications = Notification::all();
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
       
        $familyMembers = HouseholdData::where('userId', $user->UserId)->get();

        // Fetch documents for each family member along with the family member name
        $documentsByFamilyMember = [];
        foreach ($familyMembers as $familyMember) {
            $documents = Document::where('family_member_id', $familyMember->id)->get();
            $documentsByFamilyMember[$familyMember->id] = [
                'family_member_name' => $familyMember->FirstName, // Adjust the property based on your actual structure
                'documents' => $documents,
            ];
        }
        // Get all document names
        $documentNames = Document::$documentNames;

        return view('user-dashboard.index', compact('user', 'familyMembers', 'documentsByFamilyMember', 'documentNames','notifications'));
    }

    public function uploadDocument(Request $request)
    {
        $user = Auth::user();
    
        try {
            Log::debug('Upload Document Request Data:', $request->all());
    
            if (!$request->has('family_member_id') || !$request->has('document_number')) {
                throw new \Exception('The family member id and document number fields are required.');
            }
    
            $request->validate([
                'document' => 'required|file|mimes:pdf,docx|max:102048',
                'family_member_id' => 'required|exists:household_data,id',
                'document_number' => 'required|integer|min:1|max:30', // Adjusted to allow up to 30 documents
            ]);
    
            $familyMember = HouseholdData::find($request->family_member_id);
    
            if (!$familyMember) {
                return redirect()->back()->with('error', 'Invalid family member.');
            }
    
            $uploadedFile = $request->file('document');
            
            // Generate unique file name using timestamp and family member ID
            $fileName = uniqid() . '_' . $familyMember->id . '.' . $uploadedFile->getClientOriginalExtension();
            
            // Store the document with the generated file name
            $filePath = $uploadedFile->storeAs('documents', $fileName);
    
            // Save document details
            $document = new Document([
                'user_id' => $user->id,
                'family_member_id' => $familyMember->id,
                'document_name' => $fileName, // Save the file name instead of original name
                'file_path' => $filePath,
                'status' => 'pending',
                'document_number' => $request->document_number,
            ]);
    
            $document->save();
    
            // Create a notification for the uploaded document
            $code = $familyMember->Code;
    
         // Fetch the property based on the code
$property = Properties::where('Code', $code)->first();

if ($property) {
    // Property found, you can access its attributes
    $propertyName = $property->Property;

    // Determine the role of the user
    // Adjust this according to your user role logic

    // Use the property name and role in the notification message
    $notification = new Notification([
        'message' => 'New Document Uploaded for ' . $propertyName . ' - Unit ' . $familyMember->UnitNo . ' by ' . $familyMember->firstName ,
        'user_id' => $user->id,
        'family_member_id' => $familyMember->id,
        'role' =>'user', // Assign the determined role here
    ]);
} else {
    // Property not found for the given code
    // Handle this case as per your requirement
}

    
            $notification->save();
            Mail::to($user->email)->send(new DocumentUploaded($document));
            return response()->json(['message' => 'Document uploaded successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during document upload'], 500);
        }   
    }
    
    public function documentStatus()
    {
        $user = Auth::user();
        $activeTab = 'pending';

        if (!$user) {
            return redirect()->route('login');
        }

        $familyMembers = HouseholdData::where('userId', $user->UserId)->get();

        // Fetch documents for each family member along with the family member name
        $documentsByFamilyMember = [];
        foreach ($familyMembers as $familyMember) {
            $documents = Document::where('family_member_id', $familyMember->id)->get();
            $documentsByFamilyMember[$familyMember->id] = [
                'family_member_name' => $familyMember->FirstName, // Adjust the property based on your actual structure
                'documents' => $documents,
            ];
        }

        return view('user-dashboard.document-status', compact('user', 'familyMembers', 'documentsByFamilyMember','activeTab'));
    }

    public function reuploadDocument(Request $request)
    {
        $user = Auth::user();
    
        try {
            Log::debug('Reupload Document Request Data:', $request->all());
    
            // Validate the request
            $request->validate([
                'document' => 'required|file|mimes:pdf,docx|max:2048',
                'family_member_id' => 'required|exists:household_data,id',
                'document_number' => 'required|integer|min:1|max:30',
            ]);
    
            // Find the document by ID
            $document = Document::find($request->document_id);
    
            if (!$document) {
                return redirect()->back()->with('error', 'Document not found.');
            }
    
            // Delete the previous document file
            Storage::delete($document->file_path);
    
            // Upload the new document
            $uploadedFile = $request->file('document');
            $filePath = $uploadedFile->store('documents');
    
            // Update document details
            $document->document_name = $uploadedFile->getClientOriginalName();
            $document->file_path = $filePath;
            $document->status = 'pending';
            $document->save();
    
            return redirect()->back()->with('success', 'Document re-uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Reupload Document Error: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'An error occurred during the document re-upload. Please try again.');
        }
    }
    
}