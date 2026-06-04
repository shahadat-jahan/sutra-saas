<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $appName,
        private readonly string $shopName,
        private readonly string $ownerName
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
        return (new MailMessage)
            ->subject("Your {$this->appName} shop has been deleted")
            ->greeting("Hello {$this->ownerName},")
            ->line("We are writing to inform you that your shop '{$this->shopName}' has been permanently deleted from {$this->appName} by an administrator.")
            ->line('All associated data, settings, and user accounts have been removed.')
            ->line('If you believe this was a mistake or have any questions, please contact our support team immediately.');
    }
}
