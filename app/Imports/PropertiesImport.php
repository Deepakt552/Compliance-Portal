<?php

// app/Imports/PropertiesImport.php

namespace App\Imports;

use App\Models\Properties;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PropertiesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty(array_filter($row, function ($value) {
            return $value !== null && $value !== '';
        }))) {
            return null;
        }

        return new Properties([
            'Code'     => $row['code'] ?? null,
            'Property' => $row['property'] ?? null,
            'Address'  => $row['address'] ?? null,
            'City'     => $row['city'] ?? null,
            'Zip'      => $row['zip'] ?? null,
            'State'    => $row['state'] ?? null,
            'units'    => $row['units'] ?? null,
        ]);
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