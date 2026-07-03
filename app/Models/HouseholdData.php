<?php
// app/Models/HouseholdData.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdData extends Model
{
    use HasFactory;

    protected $table = 'household_data'; // Assuming your table name is 'household_data'

    protected $fillable = [
        'UnitNo',
        'userId',
        'firstName',
        'lastName',
        'AdultOrMinor',
        'Relation',
        'Student',
        'Age',
        'FamilySize',
        'CertificationDate',
        'RecertificationDate',
        'Code',
        'dob',
        'gender',
    ];

    protected $casts = [
        'documents' => 'array', // Assuming 'documents' is a JSON field
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'UserId');
    }
   // HouseholdData.php
// HouseholdData.php
public function documents()
{
    return $this->hasMany(Document::class, 'family_member_id', 'id')->withDefault();
}
public function property()
    {
        return $this->belongsTo(Property::class, 'Property');
    }
    

}