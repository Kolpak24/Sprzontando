<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Potwierdź swój adres e-mail')
            ->line('Kliknij poniższy przycisk, aby potwierdzić swój adres e-mail.')
            ->action('Potwierdź adres e-mail', $url)
            ->line('Jeśli nie tworzyłeś konta, zignoruj tę wiadomość.');
    }
}