<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends BaseVerifyEmail
{
    use Queueable;

    /**
     * Gera a URL de verificação assinada.
     */
    protected function verificationUrl(mixed $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Constrói o e-mail com o template Blade customizado.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Confirme seu e-mail – ' . config('app.name'))
            ->view('emails.verify-email', [
                'verificationUrl' => $verificationUrl,
                'userName'        => $notifiable->name,
                'isSupplier'      => $notifiable->isSupplier(),
            ]);
    }
}
