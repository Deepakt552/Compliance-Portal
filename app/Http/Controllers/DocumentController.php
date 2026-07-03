<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminAuthenticate;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }
    
    public function index()
    {
        $documents = Document::paginate(10);
        return view('document.index', compact('documents'));
    }
    public function search(Request $request)
    {
        // Get the search query from the request
        $searchQuery = $request->input('search');
    
        // Perform the search query to find documents matching the search query
        $documents = Document::whereHas('user', function ($query) use ($searchQuery) {
            $query->where('name', 'like', "%$searchQuery%");
        })->orWhereHas('familyMember', function ($query) use ($searchQuery) {
            $query->where('firstName', 'like', "%$searchQuery%")
                  ->orWhere('lastName', 'like', "%$searchQuery%");
        })->paginate(10); // Paginate the results with 10 items per page
    
        // Return the view with the search results
        return view('document.index', compact('documents'));
    }
    
    
    public function create()
    {
        return view('document.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'family_member_id' => 'required',
            'document_name' => 'required',
            'file_path' => 'required',
            'status' => 'required',
            'document_number' => 'required',
            'comments' => 'nullable',
        ]);

        Document::create($request->all());

        return redirect()->route('documents.index')->with('success', 'Document created successfully');
    }

    public function edit(Document $document)
    {
        return view('document.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            // 'user_id' => 'required',
            // 'family_member_id' => 'required',
            // 'document_name' => 'required',
            'file_path' => 'required',
            'status' => 'required',
            // 'document_number' => 'required',
            'comments' => 'nullable',
        ]);

        $document->update($request->all());

        return redirect()->route('documents.index')->with('success', 'Document updated successfully');
    }

    public function destroy($id)
    {
        // Find the document by ID
        $document = Document::findOrFail($id);

        // Delete the file from storage
        Storage::delete($document->file_path);

        // Delete the document from the database
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully');
    }
}