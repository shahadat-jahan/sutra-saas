<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Modules\Shared\Domain\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $appName,
        private readonly Shop $shop,
        private readonly array $changes
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
            ->subject("Your {$this->appName} shop has been updated")
            ->greeting("Hello {$notifiable->name},")
            ->line("The settings for your shop '{$this->shop->name}' have been updated by the administrator.");

        if (! empty($this->changes)) {
            $mail->line('The following updates were made:');
            foreach ($this->changes as $key => $value) {
                $formattedKey = ucwords(str_replace('_', ' ', $key));

                if (is_array($value)) {
                    $value = implode(', ', $value);
                } elseif (is_bool($value)) {
                    $value = $value ? 'Yes' : 'No';
                }

                $mail->line("- **{$formattedKey}**: {$value}");
            }
        }

        $scheme = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http';
        $tenantUrl = sprintf('%s://%s.%s/dashboard', $scheme, $this->shop->slug, (string) config('app.domain', 'localhost'));

        $mail->action('Go to Dashboard', $tenantUrl);

        return $mail->line('If you have any questions, please contact our support team.');
    }
}
