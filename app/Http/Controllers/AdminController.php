<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    public function index()
    {
        $admins = Admin::paginate(8);
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        $newAdmin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'receive_upload_notifications' => $request->has('receive_upload_notifications'),
        ]);

        \App\Services\AuditLogger::log('create', 'admin', $newAdmin->id, [
            'name' => $newAdmin->name,
            'email' => $newAdmin->email,
            'receive_upload_notifications' => $newAdmin->receive_upload_notifications,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'receive_upload_notifications' => $request->has('receive_upload_notifications'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        \App\Services\AuditLogger::log('update', 'admin', $admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'receive_upload_notifications' => $admin->receive_upload_notifications,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        // Prevent deleting the currently authenticated admin
        if (Auth::guard('admin')->id() === $admin->id) {
            return redirect()->route('admins.index')->with('error', 'You cannot delete your own admin account.');
        }

        $adminData = [
            'name' => $admin->name,
            'email' => $admin->email,
        ];
        $adminId = $admin->id;

        $admin->delete();

        \App\Services\AuditLogger::log('delete', 'admin', $adminId, $adminData);

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $admins = Admin::where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->paginate(8);

        return view('admins.index', compact('admins'));
    }
}
