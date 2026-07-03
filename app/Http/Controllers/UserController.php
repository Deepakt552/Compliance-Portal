<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminAuthenticate;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin.auth');
    }
    
    public function index()
    {
        $users = User::paginate(8);
        
        return view('users.index', compact('users'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $users = User::where(function ($query) use ($search) {
                        $query->where('FirstName', 'like', "%$search%")
                              ->orWhere('LastName', 'like', "%$search%")
                              ->orWhere('UserId', 'like', "%$search%")
                              ->orWhereRaw("CONCAT(FirstName, ' ', LastName) LIKE '%$search%'")
                              ->orWhere('Code', 'like', "%$search%");

                     })
                     ->paginate(8);
    
        return view('users.index', compact('users'));
    }
    
    
    public function updateVacantStatus(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $user->update([
            'Vacant' => $request->input('vacant')
        ]);
        
        return response()->json(['message' => 'Vacant status updated successfully']);
    }
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'UserId' => 'nullable',
            'UnitNo' => 'nullable',
            'FirstName' => 'nullable',
            'LastName' => 'nullable',
            'Age' => 'nullable|integer',
            'FamilySize' => 'nullable|integer',
            'CertificationDate' => 'nullable|date',
            'RecertificationDate' => 'nullable|date',
            'ChangePwd' => 'nullable',
            'ContactDetails' => 'nullable',
            'PhoneNumber' => 'nullable',
            'Code' => 'nullable',
        ]);

        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'UserId' => 'nullable',
            'UnitNo' => 'nullable',
            'FirstName' => 'nullable',
            'LastName' => 'nullable',
            'Age' => 'nullable|integer',
            'FamilySize' => 'nullable|integer',
            'CertificationDate' => 'nullable|date',
            'RecertificationDate' => 'nullable|date',
            'ChangePwd' => 'nullable',
            'ContactDetails' => 'nullable',
            'PhoneNumber' => 'nullable',
            'Code' => 'nullable',
        ]);
    
        $user->update($request->all());
    
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}