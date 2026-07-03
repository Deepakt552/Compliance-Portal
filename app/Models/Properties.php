<?php
// app/Models/Properties.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{
    use HasFactory;

    protected $table = 'properties'; // Assuming your table name is 'properties'

    protected $fillable = [
        'Code',
        'Property',
        'Address',
        'City',
        'Zip',
        'State',
        'units',
    ];

    // You can define relationships or additional methods here if needed
}