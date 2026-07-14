<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\HouseholdDataImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HouseholdData;
use App\Models\Properties;
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

    public function index(Request $request)
    {
        // Get distinct codes for filtering
        $codes = Properties::distinct('Code')->pluck('Code');
    
        // Pass unit numbers and property names based on code to the view
        $unitNumbers = $this->getUnitNumbersByCode();
        $propertyNames = Properties::pluck('Property', 'Code');
    
        // Apply filters
        $query = HouseholdData::query();

        // 1. Search Query
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('Code', 'like', "%$search%")
                  ->orWhere('UnitNo', 'like', "%$search%")
                  ->orWhere('userId', 'like', "%$search%")
                  ->orWhere('FirstName', 'like', "%$search%")
                  ->orWhere('LastName', 'like', "%$search%");
            });
        }

        // 2. Dropdown Property Code filter
        if ($request->has('code') && $request->input('code') != '') {
            $query->where('Code', $request->input('code'));
        }

        // 3. Dropdown Unit number filter
        if ($request->has('unitNo') && $request->input('unitNo') != '') {
            $query->where('UnitNo', $request->input('unitNo'));
        }
    
        // Paginate the results
        $householdData = $query->paginate(10);
    
        return view('household.index', compact('householdData', 'codes', 'unitNumbers', 'propertyNames'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    private function getUnitNumbersByCode()
    {
        $unitNumbersByCode = HouseholdData::select('Code', 'UnitNo')->distinct()->get()->groupBy('Code');
        $unitNumbers = [];

        foreach ($unitNumbersByCode as $code => $units) {
            $unitNumbers[$code] = $units->pluck('UnitNo')->unique()->toArray();
        }

        return $unitNumbers;
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

        $exists = HouseholdData::where('firstName', $request->firstName)
            ->where('lastName', $request->lastName)
            ->where('Code', $request->Code)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['error' => 'A family member with this name and property code already exists in the database.']);
        }

        $member = HouseholdData::create($request->all());

        \App\Services\AuditLogger::log('create', 'household_member', $member->id, [
            'name' => $member->firstName . ' ' . $member->lastName,
            'Code' => $member->Code,
            'userId' => $member->userId,
        ]);

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

        $exists = HouseholdData::where('firstName', $request->firstName)
            ->where('lastName', $request->lastName)
            ->where('Code', $request->Code)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['error' => 'A family member with this name and property code already exists in the database.']);
        }
        
        $householdData->update($request->all());

        \App\Services\AuditLogger::log('update', 'household_member', $householdData->id, [
            'name' => $householdData->firstName . ' ' . $householdData->lastName,
            'Code' => $householdData->Code,
            'userId' => $householdData->userId,
        ]);

        return redirect()->route('household.index')->with('success', 'Household data updated successfully.');
    }

    public function destroy($id)
    {
        $member = HouseholdData::findOrFail($id);
        $memberData = [
            'name' => $member->firstName . ' ' . $member->lastName,
            'Code' => $member->Code,
            'userId' => $member->userId,
        ];
        $memberId = $member->id;

        $member->delete();

        \App\Services\AuditLogger::log('delete', 'household_member', $memberId, $memberData);

        return redirect()->route('household.index')->with('success', 'Household data deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $members = HouseholdData::whereIn('id', $ids)->get();
            foreach ($members as $member) {
                $memberData = [
                    'name' => $member->firstName . ' ' . $member->lastName,
                    'Code' => $member->Code,
                    'userId' => $member->userId,
                ];
                $memberId = $member->id;
                $member->delete();
                \App\Services\AuditLogger::log('delete', 'household_member', $memberId, $memberData);
            }
            return redirect()->route('household.index')->with('success', 'Selected household members deleted successfully.');
        }
        return redirect()->route('household.index')->with('error', 'No household members selected.');
    }

    public function importHouseholdRow(Request $request)
    {
        $rowData = $request->input('row');
        if (!$rowData) {
            return response()->json(['success' => false, 'error' => 'No data.'], 400);
        }

        $row = [];
        foreach ($rowData as $key => $value) {
            $normalizedKey = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $key));
            $row[$normalizedKey] = $value;
        }

        $firstName = $row['firstname'] ?? null;
        $lastName = $row['lastname'] ?? null;
        $code = $row['code'] ?? null;

        if (empty($firstName) || empty($lastName) || empty($code)) {
            return response()->json(['success' => false, 'error' => 'Missing Name or Property Code.'], 422);
        }

        // Check duplicate
        if (HouseholdData::where('firstName', $firstName)->where('lastName', $lastName)->where('Code', $code)->exists()) {
            return response()->json(['success' => false, 'error' => "Member '$firstName $lastName' with property code '$code' already exists."], 422);
        }

        // Parse dates
        $certificationDate = $this->parseImportDate($row['certificationdate'] ?? null);
        $recertificationDate = $this->parseImportDate($row['recertificationdate'] ?? null);
        $dob = $this->parseImportDate($row['dob'] ?? null);

        try {
            $member = HouseholdData::create([
                'UnitNo'              => $row['unitno'] ?? null,
                'userId'              => $row['userid'] ?? null,
                'firstName'           => $firstName,
                'lastName'            => $lastName,
                'Age'                 => $row['age'] ?? null,
                'AdultOrMinor'        => $row['adultminor'] ?? null,
                'Relation'            => $row['relation'] ?? null,
                'Student'             => $row['student'] ?? null,
                'FamilySize'          => $row['familysize'] ?? null,
                'CertificationDate'   => $certificationDate,
                'RecertificationDate' => $recertificationDate,
                'dob'                 => $dob,
                'Code'                => $code,
                'gender'              => $row['gender'] ?? null,
            ]);

            \App\Services\AuditLogger::log('import', 'household_member', $member->id, [
                'name' => $member->firstName . ' ' . $member->lastName,
                'Code' => $member->Code,
                'userId' => $member->userId,
            ]);

            return response()->json(['success' => true, 'message' => "Member '{$member->firstName} {$member->lastName}' imported."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function parseImportDate($date)
    {
        if (empty($date)) {
            return null;
        }

        if (is_numeric($date)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            } catch (\Exception $e) {
                // fallback
            }
        }

        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}