<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_get_cart(): void
    {
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'status',
                    'items',
                ],
            ]);

        $this->assertDatabaseHas('carts', [
            'status' => 'active',
        ]);
    }

    public function test_guest_can_add_product_to_cart(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()
            ->for($category)
            ->create([
                'price' => 1200,
            ]);

        $response = $this->postJson('/api/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.product.id', $product->id)
            ->assertJsonPath('data.quantity', 2);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
