<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlatformAccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $appName,
        private readonly string $loginUrl,
        private readonly ?string $tenantUrl,
        private readonly string $email,
        private readonly ?string $password,
        private readonly string $roleName,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Your {$this->appName} account details")
            ->greeting("Hello {$notifiable->name},")
            ->line("An account has been created for you on {$this->appName}.")
            ->line("Email: {$this->email}")
            ->line("Role: {$this->roleName}");

        if ($this->password) {
            $mail->line("Temporary password: {$this->password}")
                ->line('Please change your password after your first login.');
        }

        $mail->action('Login', $this->loginUrl);

        if ($this->tenantUrl) {
            $mail->line("Your shop URL: {$this->tenantUrl}");
        }

        return $mail->line('If you did not expect this email, please ignore it.');
    }
}
