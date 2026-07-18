<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\OptionValue;
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

    public function test_can_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'title' => 'iPhone 17 Pro',
            'category_id' => $category->id,
            'description' => 'Apple new phone',
            'price' => 70000000,
            'stock' => 5,
            'status' => 'active',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'iPhone 17 Pro');

        $this->assertDatabaseHas('products', [
            'title' => 'iPhone 17 Pro',
            'category_id' => $category->id,
        ]);
    }

    public function test_can_create_product_with_option_values(): void
    {
        $category = Category::factory()->create();

        $optionValues = OptionValue::factory()
            ->count(3)
            ->create();

        $response = $this->postJson('/api/products', [
            'title' => 'iPhone 17 Pro',
            'category_id' => $category->id,
            'description' => 'Apple new phone',
            'price' => 70000000,
            'stock' => 5,
            'status' => 'active',
            'option_values' => $optionValues->pluck('id')->toArray(),
        ]);

        $response->assertStatus(201);

        $product = Product::where('title', 'iPhone 17 Pro')->first();

        $this->assertCount(3, $product->optionValues);
    }
}
