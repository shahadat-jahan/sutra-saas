<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ShopCreatedEvent;
use App\Modules\Shared\Domain\Models\User;
use App\Notifications\PlatformAccessNotification;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShopCreatedListener
{
    public function handle(ShopCreatedEvent $event): void
    {
        $shop = $event->shop;
        $data = $event->data;
        $ownerPassword = $event->ownerPassword;

        // Fetch the shop owner user created in the controller
        /** @var User $owner */
        $owner = User::where('shop_id', $shop->id)
            ->where('email', $data['owner_email'])
            ->firstOrFail();

        // Set Team Context for Spatie Permissions
        app(PermissionRegistrar::class)->setPermissionsTeamId($shop->id);

        // Create shop-owner role
        $role = Role::firstOrCreate([
            'name' => 'shop-owner',
            'team_id' => $shop->id,
            'guard_name' => 'web',
        ]);

        // Assign role to owner
        if (! $owner->hasRole($role)) {
            $owner->assignRole($role);
        }

        // Send platform access notification
        $scheme = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http';
        $tenantUrl = sprintf('%s://%s.%s/dashboard', $scheme, $shop->slug, (string) config('app.domain', 'localhost'));

        $owner->notify(new PlatformAccessNotification(
            appName: (string) config('app.name', 'Sutra'),
            loginUrl: rtrim((string) config('app.url', 'http://localhost'), '/').'/login',
            tenantUrl: $tenantUrl,
            email: $owner->email,
            password: (string) $ownerPassword,
            roleName: 'shop-owner',
        ));
    }
}
