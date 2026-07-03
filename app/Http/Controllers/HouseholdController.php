<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\HouseholdDataImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HouseholdData;
use App\Http\Middleware\AdminAuthenticate;


class HouseholdController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('admin.auth');
    }
    

    public function importForm()
    {
        return view('household.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        // Save and release session write locks to prevent blocking concurrent page loads
        session()->save();
        if (session_id()) {
            session_write_close();
        }

        Excel::import(new HouseholdDataImport, $file);

        return redirect()->route('household.import.form')->with('success', 'Data imported successfully.');
    }
    
    // CRUD operations for HouseholdData

    public function index()
    {
        $householdData = HouseholdData::paginate(10);
        return view('household.index', compact('householdData'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
    
        $householdData = HouseholdData::where('Code', 'like', "%$search%")
            ->orWhere('UnitNo', 'like', "%$search%")
            ->orWhere('userId', 'like', "%$search%")
            ->orWhere('FirstName', 'like', "%$search%")
            ->orWhere('LastName', 'like', "%$search%")
            ->paginate(8);
    
        return view('household.index', compact('householdData'));
    }

    public function create()
    {
        return view('household.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'UnitNo' => 'required',
            'userId' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'AdultOrMinor' => 'required',
            'Relation' => 'required',
            'Student' => 'required',
            'Age' => 'required',
            'FamilySize' => 'required',
            'CertificationDate' => 'required',
            'RecertificationDate' => 'required',
            'Code' => 'required',
        ]);

        HouseholdData::create($request->all());

        return redirect()->route('household.index')->with('success', 'Household data created successfully.');
    }

    public function edit($id)
    {
        // Fetch the household data from the database using the provided $id
        $household = HouseholdData::findOrFail($id);

        // Pass the fetched household data to the view
        return view('household.edit', compact('household'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'UnitNo' => 'required',
            'userId' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'AdultOrMinor' => 'required',
            'Relation' => 'required',
            'Student' => 'required',
            'Age' => 'required',
            'FamilySize' => 'required',
            'CertificationDate' => 'required',
            'RecertificationDate' => 'required',
            'Code' => 'required',
        ]);

        $householdData = HouseholdData::findOrFail($id);
        
        $householdData->update($request->all());

        return redirect()->route('household.index')->with('success', 'Household data updated successfully.');
    }

    public function destroy($id)
    {
        HouseholdData::findOrFail($id)->delete();

        return redirect()->route('household.index')->with('success', 'Household data deleted successfully.');
    }
    
}