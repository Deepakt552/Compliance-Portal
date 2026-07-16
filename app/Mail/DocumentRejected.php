<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $statusUrl;

    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->statusUrl = route('document.status');
    }

    public function build()
    {
        return $this->subject('Document Rejected - Triumph Residential')
                    ->view('emails.document_rejected');
    }
}