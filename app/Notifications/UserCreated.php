<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreated extends Notification
{
    use Queueable;

    private $_input;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($input)
    {
        $this->_input = $input;
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
            ->line('Hello ' . $this->_input['first_name'] . ' ' . $this->_input['last_name'] . ',')
            ->line('Please find below your user details!')
            ->line('Email: ' . $this->_input['email'])
            ->line('Password: ' . $this->_input['plain_password'])
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
