<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    /**
     * Display a listing of the email logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = EmailLog::orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('recipient', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15)->appends(['search' => $search]);

        return view('admin.email-logs.index', compact('logs', 'search'));
    }

    /**
     * Display the specified email log details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $log = EmailLog::findOrFail($id);
        return view('admin.email-logs.show', compact('log'));
    }
}
