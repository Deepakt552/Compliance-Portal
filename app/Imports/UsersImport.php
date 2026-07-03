<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $dateFormat;
    protected static $passwordHashes = [];

    public function __construct($dateFormat = 'Y-m-d')
    {
        $this->dateFormat = $dateFormat;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty(array_filter($row, function ($value) {
            return $value !== null && $value !== '';
        }))) {
            return null;
        }

        $passwordKey = $row['password'] ?? 'password';
        if (!isset(self::$passwordHashes[$passwordKey])) {
            self::$passwordHashes[$passwordKey] = Hash::make($passwordKey);
        }
        $passwordHash = self::$passwordHashes[$passwordKey];

        return new User([
            'name'                => $row['firstname'] ?? null,
            'email'               => $row['email'] ?? null,
            'password'            => $passwordHash,
            'UserId'              => $row['userid'] ?? null,
            'UnitNo'              => $row['unitno'] ?? null,
            'FirstName'           => $row['firstname'] ?? null,
            'LastName'            => $row['lastname'] ?? null,
            'Age'                 => $row['age'] ?? null,
            'FamilySize'          => $row['familysize'] ?? null,
            'CertificationDate'   => $this->parseDate($row['certificationdate'] ?? null),
            'RecertificationDate' => $this->parseDate($row['recertificationdate'] ?? null),
            'ChangePwd'           => $row['changepwd'] ?? false,
            'ContactDetails'      => $row['contactdetails'] ?? false,
            'PhoneNumber'         => $row['phonenumber'] ?? null,
            'Code'                => $row['code'] ?? null,
        ]);
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        if (is_numeric($date)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format($this->dateFormat);
            } catch (\Exception $e) {
                // fallback
            }
        }

        try {
            return \Carbon\Carbon::parse($date)->format($this->dateFormat);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}