<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\ShopCreatedEvent;
use App\Events\ShopUpdatedEvent;
use App\Events\ShopDeletedEvent;
use App\Notifications\PlatformAccessNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class ShopEventsTests extends TestCase
{
    use RefreshDatabase;

    public function testShopCreatedEventIsDispatched()
    {
        $requestData = [
            'shop_name' => 'Test Shop',
            'business_type' => 'Multi-Store',
            'owner_name' => 'John Doe',
            'owner_email' => 'john@example.com',
        ];

        $response = $this->post(route('admin.shop.store'), $requestData);

        // Check event dispatch
        $this->assertEventDispatched(ShopCreatedEvent::class);

        // Verify database changes
        $this->assertDatabaseHas('shops', ['name' => 'Test Shop']);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        // Verify notification was sent
        Notification::assertSentTo(
            function ($notifiable, $notification) use ($requestData) {
                return $notifiable->email === $requestData['owner_email'] &&
                       $notification instanceof PlatformAccessNotification;
            }
        );
    }

    public function testShopUpdatedEventIsDispatched()
    {
        $shop = \App\Models\Shop::factory()->create(['name' => 'Original Shop']);

        $response = $this->put(route('admin.shop.update', $shop->id), [
            'name' => 'Updated Shop',
        ]);

        $this->assertEventDispatched(ShopUpdatedEvent::class);

        // Verify shop was updated
        $this->assertDatabaseHas('shops', ['name' => 'Updated Shop']);
    }

    public function testShopDeletedEventIsDispatched()
    {
        $shop = \App\Models\Shop::factory()->create();

        $response = $this->delete(route('admin.shop.destroy', $shop->id));

        $this->assertEventDispatched(ShopDeletedEvent::class);

        // Verify cleanup
        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
        $this->assertDatabaseMissing('users', ['shop_id' => $shop->id]);
    }
}