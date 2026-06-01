<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Verifica tu cuenta en ' . config('app.name'))
            ->greeting('¡Hola!')
            ->line('Gracias por registrarte en ' . config('app.name') . ', tu cuenta ya está lista solo debes confirmarla.')
            ->action('Confirma tu cuenta.', $verificationUrl)
            ->line('Si no creaste esta cuenta, puedes ignorar este mensaje.')
            ->salutation('Saludos, ' . config('app.name'));
    }
}
