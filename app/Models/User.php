<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string|null>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'UserId',
        'UnitNo',
        'FirstName',
        'LastName',
        'Age',
        'FamilySize',
        'CertificationDate',
        'RecertificationDate',
        'ChangePwd',
        'ContactDetails',
        'PhoneNumber',
        'Code',
        'Vacant',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string|null>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship with the Properties model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Properties::class, 'Code', 'Code');
    }

    /**
     * Relationship with the HouseHolddata model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function HouseHold()
    {
        return $this->belongsTo(HouseHolddata::class, 'UserId', 'UserId');
    }
}