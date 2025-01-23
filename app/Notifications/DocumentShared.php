<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class DocumentShared extends Notification
{
    use Queueable;

    protected $documentTitle;
    protected $sharedBy;
    protected $documentId;
    public $lockCode;

    public function __construct($lockCode, $documentTitle, $sharedBy, $documentId)
    {
        $this->documentTitle = $documentTitle;
        $this->sharedBy = $sharedBy;
        $this->documentId = $documentId;
        $this->lockCode = $lockCode;
    }

    public function via($notifiable)
    {
        return ['mail']; // Specify that we want to send an email
    }

    public function toMail($notifiable)
{
    return (new MailMessage)
        ->markdown('emails.document_shared', [
            'documentTitle' => $this->documentTitle,
            'sharedBy' => $this->sharedBy,
            'documentId' => $this->documentId,
            'lockCode' => $this->lockCode,
        ]);
}
}
