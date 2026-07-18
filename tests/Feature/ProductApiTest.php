<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_products_list(): void
    {
        $category = Category::factory()->create();

        Product::factory()
            ->for($category)
            ->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
            ]);
    }


    public function test_can_get_single_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()
            ->for($category)
            ->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $product->id);
    }
}
