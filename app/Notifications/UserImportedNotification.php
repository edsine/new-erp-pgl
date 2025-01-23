<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserImportedNotification extends Notification
{
    use Queueable;

    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Hello ' . $this->users['first_name'] . ' ' . $this->users['last_name'] . ',')
            ->line('Please find below your user details!')
            ->line('Email: ' . $this->users['email'])
            ->line('Password: ' . "Testingdata1")
            ->line('You can use this details to login and access your dashboard.')
            ->action('Login', url('/login'))
            ->line('Thank you.')
            ->line('E-NIWA');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
