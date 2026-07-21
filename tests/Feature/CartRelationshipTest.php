<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_belongs_to_user(): void
    {
        $user = User::factory()->create();

        $cart = Cart::factory()
            ->for($user)
            ->create();

        $this->assertTrue($cart->user->is($user));
    }

    public function test_cart_has_many_items(): void
    {
        $cart = Cart::factory()->create();

        CartItem::factory()
            ->count(3)
            ->for($cart)
            ->create();

        $this->assertCount(3, $cart->items);
    }

    public function test_cart_item_belongs_to_product(): void
    {
        $product = Product::factory()->create();

        $cartItem = CartItem::factory()
            ->for($product)
            ->create();

        $this->assertTrue($cartItem->product->is($product));
    }
}
