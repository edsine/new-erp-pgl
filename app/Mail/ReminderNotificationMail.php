<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderNotificationMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $reminder;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Reminder $reminder
     * @return void
     */
    public function __construct($user, $reminder)
    {
        $this->user = $user;
        $this->reminder = $reminder;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Reminder Notification')
                    ->view('emails.reminder_notification');  // Assume you have a view at 'resources/views/emails/reminder_notification.blade.php'
    }
}
