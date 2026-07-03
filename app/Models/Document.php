<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'user_id',
        'family_member_id',
        'document_name',
        'file_path',
        'status',
        'document_number',
        'comments',
        // Add more fields as needed
    ];
 // Define the total number of documents
 public static $totalDocuments = 30;

   // Define the document names
   public static $documentNames = [
    "Most recent Tax Papers",
    "Paystubs of last 3 months (Weekly)",
    "Paystubs of last 3 months (Bi-Weekly)",
    "Paystubs of last 3 months (Semi-Monthly)",
    "Paystubs of last 3 months (Monthly)",
    "Notarized Copy of TR 113",
    "Most current Award Letter for Unemployment Benefits",
    "Most current Award Letter for Veteran's Administration, GI Bill, or National Guard/Military Benefits / Income",
    "Most current Award Letter for Social Security payments",
    "Most current Award Letter for unearned income from family members age 17 or under",
    "Most current Award Letter for Supplemental Security Income (SSI)",
    "Most current Award Letter for disability, EDD paid family leave, EDD disability insurance, or death benefits other than Social Security",
    "Most current Award Letter for Public Assistance Income (excluding CalFresh, SNAP, Food Stamps)",
    "Court Order or Parental Agreement for Child Support payments",
    "Court Order or Copy of Divorce Agreement for Alimony or Spousal Support payments",
    "Annual Statement for periodic payments from trusts, annuities inheritance, retirement funds or pensions, insurance policies, or lottery winnings",
    "Three months of rent receipts & Property Tax papers for income from real or personal property",
    "Financial Aid Award Letter for student financial aid (public/private, excluding loans) for households receiving Section 8 assistance only",
    "ATM Balance Slip for income sources received as a Debit Visa or Security Mastercard",
    "Last six months of Bank statements for checking account(s)",
    "Most current Savings Account Statement (1 month only) for savings account(s)",
    "Printout of Current Balance (1 month only) for available funds held in a payment service account",
    "Revocable trust statements from bank (6 months) for revocable trust(s)",
    "Property Tax Papers (most current year) for real estate ownership",
    "Crypto statements (6 months) for cryptocurrency ownership",
    "Stock, Bond, Treasury bills statements (6 months) for ownership of stocks, bonds, or treasury bills",
    "Copy of Insurance & Surrender Value statement for life insurance policy with a cash/surrender value",
    "Most current CD Statement (1 month) or Most current Money Market statement (1 month) for Certificates of Deposit (CD) or Money Market account(s)",
    "Most current statement of IRA, 401K, Lumpsum pension, or Keogh A/c (1 month) for IRA, lump sum pension, Keogh account, or 401K",
    "Document showing sale of asset for assets disposed of for less than the fair market value in the last 2 years",
    // Add more document names if needed
];
public static function getDocumentNumber($documentName)
{
    $index = array_search($documentName, self::$documentNames);
    if ($index !== false) {
        return $index + 1;
    }
    return null;
}

public static function getDocumentName($documentNumber)
{
    if ($documentNumber > 0 && $documentNumber <= count(self::$documentNames)) {
        $index = $documentNumber - 1;
        return self::$documentNames[$index];
    }
    return null;
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function familyMember()
{
    return $this->belongsTo(HouseholdData::class, 'family_member_id', 'id');
}

public function property()
{
    return $this->belongsTo(Properties::class, 'Code', 'Code');
}
}