<?php

namespace Tests\Feature\MultiTenancy;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_are_automatically_scoped_to_the_authenticated_users_shop(): void
    {
        $shopA = Shop::query()->create([
            'name' => 'Shop A',
            'business_type' => 'retail',
            'status' => 'active',
        ]);

        $shopB = Shop::query()->create([
            'name' => 'Shop B',
            'business_type' => 'retail',
            'status' => 'active',
        ]);

        $user = User::factory()->create([
            'shop_id' => $shopA->id,
        ]);

        Product::query()->create([
            'shop_id' => $shopA->id,
            'name' => 'Visible Product',
            'sku' => 'SKU-A',
            'purchase_price' => 100,
            'sale_price' => 150,
            'stock_quantity' => 10,
        ]);

        Product::query()->create([
            'shop_id' => $shopB->id,
            'name' => 'Hidden Product',
            'sku' => 'SKU-B',
            'purchase_price' => 120,
            'sale_price' => 180,
            'stock_quantity' => 12,
        ]);

        $this->actingAs($user);

        $products = Product::query()->pluck('name');

        $this->assertSame(['Visible Product'], $products->all());
    }

    public function test_shop_id_is_automatically_assigned_when_creating_a_multitenant_model(): void
    {
        $shop = Shop::query()->create([
            'name' => 'Main Shop',
            'business_type' => 'retail',
            'status' => 'active',
        ]);

        $user = User::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $this->actingAs($user);

        $product = Product::query()->create([
            'name' => 'Auto Assigned Product',
            'sku' => 'AUTO-001',
            'purchase_price' => 90,
            'sale_price' => 140,
            'stock_quantity' => 8,
        ]);

        $this->assertSame($shop->id, $product->shop_id);
    }
}
