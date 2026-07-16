<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $propertyName;
    public $unitNo;
    public $reviewUrl;

    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->propertyName = 'N/A';
        $this->unitNo = 'N/A';

        if ($document->familyMember) {
            $this->unitNo = $document->familyMember->UnitNo ?? 'N/A';
            
            $property = \App\Models\Properties::where('Code', $document->familyMember->Code)->first();
            if ($property) {
                $this->propertyName = $property->Property;
            }
        }

        $this->reviewUrl = route('admin.showUserDocuments', ['family_member_id' => $document->family_member_id]);
    }

    public function build()
    {
        return $this->subject('New Document Uploaded - ' . $this->propertyName . ' (Unit ' . $this->unitNo . ')')
                    ->view('emails.document_uploaded');
    }
}