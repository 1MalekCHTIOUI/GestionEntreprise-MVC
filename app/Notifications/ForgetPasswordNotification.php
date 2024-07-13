<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url('/password/reset?token=' . $this->token . '&email=' . $notifiable->email);

        return (new MailMessage)
                    ->subject('Reset Your Password')
                    ->line('You requested a password reset.')
                    ->action('Reset Password', $resetUrl)
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
