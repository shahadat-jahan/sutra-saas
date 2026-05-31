<?php

namespace App\Listeners;

use App\Events\ShopCreatedEvent;
use App\Models\User;
use App\Models\Role;
use App\Notifications\PlatformAccessNotification;
use Illuminate\Support\ServiceProvider;

class ShopCreatedListener
{
    public function handle(ShopCreatedEvent $event): void
    {
        $shop = $event->shop;
        $data = $event->data;
        $ownerPassword = $event->ownerPassword;

        // Create shop owner user
        $owner = User::create([
            'shop_id' => $shop->id,
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' =>
                Hash::make((string) $ownerPassword),
            'status' => 1,
        ]);

        // Set permissions for shop
        app(ServiceProvider::class)->setPermissionsTeamId($shop->id);

        // Create shop-owner role
        $role = Role::firstOrCreate([
            'name' => 'shop-owner',
            'team_id' => $shop->id,
            'guard_name' => 'web'
        ]);

        // Assign role to owner
        $owner->assignRole($role);

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