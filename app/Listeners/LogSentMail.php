<?php

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;

class LogSentMail
{
    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event): void
    {
        try {
            $toAddresses = [];
            if ($event->message->getTo()) {
                foreach ($event->message->getTo() as $address) {
                    $toAddresses[] = $address->getAddress();
                }
            }
            $recipient = implode(', ', $toAddresses);

            $subject = $event->message->getSubject() ?? '(No Subject)';
            $body = $event->message->getHtmlBody() ?: $event->message->getTextBody() ?: '';

            EmailLog::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'body' => $body,
                'status' => 'sent',
            ]);
        } catch (\Exception $e) {
            // Silence exceptions to prevent breaking the core application if logging fails
        }
    }
}
