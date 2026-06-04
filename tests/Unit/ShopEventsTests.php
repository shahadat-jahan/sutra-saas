<?php

namespace Tests\Unit;

use App\Enums\ActiveStatus;
use App\Enums\BusinessType;
use App\Events\ShopCreatedEvent;
use App\Events\ShopDeletedEvent;
use App\Events\ShopUpdatedEvent;
use App\Modules\Shared\Domain\Models\Shop;
use App\Modules\Shared\Domain\Models\User;
use App\Notifications\PlatformAccessNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ShopEventsTests extends TestCase
{
    use RefreshDatabase;

    public function test_shop_created_event_is_dispatched()
    {
        Event::fake();
        Notification::fake();

        $requestData = [
            'shop_name' => 'Test Shop',
            'business_type' => '1', // Represents BusinessType::RETAIL
            'owner_name' => 'John Doe',
            'owner_email' => 'john@example.com',
        ];

        $response = $this->post(route('admin.shop.store'), $requestData);

        // Check event dispatch
        Event::assertDispatched(ShopCreatedEvent::class);

        // Verify database changes
        $this->assertDatabaseHas('shops', ['name' => 'Test Shop']);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        // Verify notification was sent
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        Notification::assertSentTo($user, PlatformAccessNotification::class);
    }

    public function test_shop_updated_event_is_dispatched()
    {
        Event::fake();

        $shop = Shop::query()->create([
            'name' => 'Original Shop',
            'business_type' => BusinessType::RETAIL,
            'status' => ActiveStatus::ACTIVE,
        ]);

        $response = $this->put(route('admin.shop.update', $shop->id), [
            'name' => 'Updated Shop',
            'business_type' => BusinessType::RETAIL->value,
            'status' => ActiveStatus::ACTIVE->value,
        ]);

        Event::assertDispatched(ShopUpdatedEvent::class);

        // Verify shop was updated
        $this->assertDatabaseHas('shops', ['name' => 'Updated Shop']);
    }

    public function test_shop_deleted_event_is_dispatched()
    {
        Event::fake();

        $shop = Shop::query()->create([
            'name' => 'Test Shop to Delete',
            'business_type' => BusinessType::RETAIL,
            'status' => ActiveStatus::ACTIVE,
        ]);

        $response = $this->delete(route('admin.shop.destroy', $shop->id));

        Event::assertDispatched(ShopDeletedEvent::class);

        // Verify cleanup
        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
    }
}
