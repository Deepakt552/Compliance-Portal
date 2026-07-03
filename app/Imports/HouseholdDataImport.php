<?php

// app/Imports/HouseholdDataImport.php

namespace App\Imports;

use App\Models\HouseholdData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class HouseholdDataImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty(array_filter($row, function ($value) {
            return $value !== null && $value !== '';
        }))) {
            return null;
        }

        // Skip duplicates (firstName, lastName, and Code same as another)
        $exists = HouseholdData::where('firstName', $row['firstname'] ?? null)
            ->where('lastName', $row['lastname'] ?? null)
            ->where('Code', $row['code'] ?? null)
            ->exists();
        if ($exists) {
            return null;
        }

        return new HouseholdData([
            'UnitNo'              => $row['unitno'] ?? null,
            'userId'              => $row['userid'] ?? null,
            'firstName'           => $row['firstname'] ?? null,
            'lastName'            => $row['lastname'] ?? null,
            'Age'                 => $row['age'] ?? null,
            'AdultOrMinor'        => $row['adultminor'] ?? null, // Adjust the column name as needed
            'Relation'            => $row['relation'] ?? null,
            'Student'             => $row['student'] ?? null,
            'FamilySize'          => $row['familysize'] ?? null,
            'CertificationDate'   => $this->parseDate($row['certificationdate'] ?? null),
            'RecertificationDate' => $this->parseDate($row['recertificationdate'] ?? null),
            'dob'                 => $this->parseDate($row['dob'] ?? null),
            'Code'                => $row['code'] ?? null,
            'gender'              => $row['gender'] ?? null,
        ]);
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        // If it's a numeric value, it's likely an Excel date serial number
        if (is_numeric($date)) {
            try {
                return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
            } catch (\Exception $e) {
                // fallback
            }
        }

        // Otherwise try to parse it as a string
        try {
            return \Carbon\Carbon::parse($date);
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