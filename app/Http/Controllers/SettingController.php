<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    /**
     * Display the settings dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $admins = Admin::orderBy('name')->get();
        
        $type = Setting::get('document_notification_type', 'subscribed_admins');
        $adminId = Setting::get('document_notification_admin_id', '');
        $customEmail = Setting::get('document_notification_custom_email', '');

        return view('admin.settings.index', compact('admins', 'type', 'adminId', 'customEmail'));
    }

    /**
     * Update the settings in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'document_notification_type' => 'required|in:subscribed_admins,specific_admin,custom_email',
            'document_notification_admin_id' => 'required_if:document_notification_type,specific_admin|nullable|exists:admins,id',
            'document_notification_custom_email' => 'required_if:document_notification_type,custom_email|nullable|email',
        ], [
            'document_notification_admin_id.required_if' => 'Please select a specific admin.',
            'document_notification_custom_email.required_if' => 'Please enter a valid email address.',
            'document_notification_custom_email.email' => 'Please enter a valid email address.',
        ]);

        Setting::set('document_notification_type', $request->document_notification_type);
        Setting::set('document_notification_admin_id', $request->document_notification_type === 'specific_admin' ? $request->document_notification_admin_id : null);
        Setting::set('document_notification_custom_email', $request->document_notification_type === 'custom_email' ? $request->document_notification_custom_email : null);

        // Optional Audit Logging
        \App\Services\AuditLogger::log('update', 'settings', 0, [
            'document_notification_type' => $request->document_notification_type,
            'document_notification_admin_id' => $request->document_notification_admin_id,
            'document_notification_custom_email' => $request->document_notification_custom_email,
        ]);

        return redirect()->back()->with('success', 'Notification settings updated successfully.');
    }
}
