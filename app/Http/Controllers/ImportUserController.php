<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Middleware\AdminAuthenticate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ImportUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }
    
    
    public function importForm()
    {
        return view('import-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // Specify custom date format (if needed)
        $dateFormat = 'Y-m-d'; // Change this format according to your needs

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        // Save and release session write locks to prevent blocking concurrent page loads
        session()->save();
        if (session_id()) {
            session_write_close();
        }

        Excel::import(new UsersImport($dateFormat), $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully!');
    }

    protected static $passwordHashes = [];

    public function importRow(Request $request)
    {
        $rowData = $request->input('row');
        if (!$rowData) {
            return response()->json(['success' => false, 'error' => 'No row data provided.'], 400);
        }

        // Normalize keys to lowercase alphanumeric (same as Maatwebsite Excel)
        $row = [];
        foreach ($rowData as $key => $value) {
            $normalizedKey = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $key));
            $row[$normalizedKey] = $value;
        }

        // Check if user already exists
        $email = $row['email'] ?? null;
        $userId = $row['userid'] ?? null;

        if (empty($email) && empty($userId)) {
            return response()->json(['success' => false, 'error' => 'Missing Email and User ID.'], 422);
        }

        if ($email && User::where('email', $email)->exists()) {
            return response()->json(['success' => false, 'error' => "Email '$email' already exists."], 422);
        }

        if ($userId && User::where('UserId', $userId)->exists()) {
            return response()->json(['success' => false, 'error' => "User ID '$userId' already exists."], 422);
        }

        // Parse dates
        $dateFormat = 'Y-m-d';
        $certificationDate = $this->parseDate($row['certificationdate'] ?? null, $dateFormat);
        $recertificationDate = $this->parseDate($row['recertificationdate'] ?? null, $dateFormat);

        // Password hash
        $passwordKey = $row['password'] ?? 'password';
        if (!isset(self::$passwordHashes[$passwordKey])) {
            self::$passwordHashes[$passwordKey] = Hash::make($passwordKey);
        }
        $passwordHash = self::$passwordHashes[$passwordKey];

        try {
            $user = User::create([
                'name'                => $row['firstname'] ?? null,
                'email'               => $email,
                'password'            => $passwordHash,
                'UserId'              => $userId,
                'UnitNo'              => $row['unitno'] ?? null,
                'FirstName'           => $row['firstname'] ?? null,
                'LastName'            => $row['lastname'] ?? null,
                'Age'                 => $row['age'] ?? null,
                'FamilySize'          => $row['familysize'] ?? null,
                'CertificationDate'   => $certificationDate,
                'RecertificationDate' => $recertificationDate,
                'ChangePwd'           => isset($row['changepwd']) ? (bool)$row['changepwd'] : false,
                'ContactDetails'      => isset($row['contactdetails']) ? (bool)$row['contactdetails'] : false,
                'PhoneNumber'         => $row['phonenumber'] ?? null,
                'Code'                => $row['code'] ?? null,
            ]);

            return response()->json(['success' => true, 'message' => "User '{$user->FirstName} {$user->LastName}' imported successfully."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function parseDate($date, $dateFormat)
    {
        if (empty($date)) {
            return null;
        }

        if (is_numeric($date)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format($dateFormat);
            } catch (\Exception $e) {
                // fallback
            }
        }

        try {
            return \Carbon\Carbon::parse($date)->format($dateFormat);
        } catch (\Exception $e) {
            return null;
        }
    }
}