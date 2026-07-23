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

    public function test_can_update_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()
            ->for($category)
            ->create([
                'title' => 'Old Product',
                'price' => 1000,
            ]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'title' => 'Updated Product',
            'price' => 2000,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Product');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Updated Product',
            'price' => 2000,
        ]);
    }

    public function test_can_update_product_option_values(): void
    {
        $category = Category::factory()->create();

        $oldOptions = OptionValue::factory()
            ->count(2)
            ->create();

        $newOptions = OptionValue::factory()
            ->count(2)
            ->create();

        $product = Product::factory()
            ->for($category)
            ->create();

        // ارتباط اولیه
        $product->optionValues()->attach($oldOptions->pluck('id'));

        $response = $this->putJson("/api/products/{$product->id}", [
            'option_values' => $newOptions->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);

        $product->refresh();

        $this->assertCount(2, $product->optionValues);

        $this->assertTrue(
            $product->optionValues
                ->pluck('id')
                ->contains($newOptions[0]->id)
        );

        $this->assertFalse(
            $product->optionValues
                ->pluck('id')
                ->contains($oldOptions[0]->id)
        );
    }

    public function test_product_can_be_soft_deleted(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    public function test_product_can_be_restored(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $response = $this->patchJson("/api/products/{$product->id}/restore");

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    }

    public function test_product_can_have_image(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson("/api/products/{$product->id}/images", [
            'path' => 'products/test.jpg',
            'alt' => 'test image',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'path' => 'products/test.jpg',
        ]);
    }

    public function test_product_can_add_image(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson("/api/products/{$product->id}/images", [
            'path' => 'products/test.jpg',
            'alt' => 'test image',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'path' => 'products/test.jpg',
        ]);
    }

    public function test_product_image_can_be_deleted(): void
    {
        $product = Product::factory()->create();

        $image = $product->images()->create([
            'path' => 'products/test.jpg',
            'alt' => 'test',
        ]);

        $response = $this->deleteJson(
            "/api/products/{$product->id}/images/{$image->id}"
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('product_images', [
            'id' => $image->id,
        ]);
    }
}
