<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\OptionValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;


    private function createAdminUser(): User
    {
        $permissions = [
            'product.create',
            'product.update',
            'product.delete',
            'product.restore',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }

        $role = Role::create([
            'name' => 'admin',
        ]);

        $role->givePermissionTo($permissions);

        $user = User::factory()->create();

        $user->assignRole('admin');

        return $user;
    }


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
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $product->id);
    }


    public function test_can_create_product(): void
    {
        $user = $this->createAdminUser();

        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
                'title' => 'iPhone 17 Pro',
                'category_id' => $category->id,
                'description' => 'Apple new phone',
                'price' => 70000000,
                'stock' => 5,
                'status' => 'active',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'title' => 'iPhone 17 Pro',
        ]);
    }


    public function test_can_create_product_with_option_values(): void
    {
        $user = $this->createAdminUser();

        $category = Category::factory()->create();

        $optionValues = OptionValue::factory()
            ->count(3)
            ->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
                'title' => 'iPhone 17 Pro',
                'category_id' => $category->id,
                'description' => 'Apple phone',
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
        $user = $this->createAdminUser();

        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/products/{$product->id}", [
                'title' => 'Updated Product',
                'price' => 2000,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Updated Product',
        ]);
    }


    public function test_can_update_product_option_values(): void
    {
        $user = $this->createAdminUser();

        $oldOptions = OptionValue::factory()
            ->count(2)
            ->create();

        $newOptions = OptionValue::factory()
            ->count(2)
            ->create();

        $product = Product::factory()->create();

        $product->optionValues()->attach(
            $oldOptions->pluck('id')
        );

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/products/{$product->id}", [
                'option_values' => $newOptions->pluck('id')->toArray(),
            ]);

        $response->assertStatus(200);

        $product->refresh();

        $this->assertCount(2, $product->optionValues);
    }


    public function test_product_can_be_soft_deleted(): void
    {
        $user = $this->createAdminUser();

        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }


    public function test_product_can_be_restored(): void
    {
        $user = $this->createAdminUser();

        $product = Product::factory()->create();

        $product->delete();

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson("/api/products/{$product->id}/restore");

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    }


    public function test_product_can_add_image(): void
    {
        $user = $this->createAdminUser();

        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/products/{$product->id}/images", [
                'path' => 'products/test.jpg',
                'alt' => 'test image',
                'is_primary' => true,
                'sort_order' => 1,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
        ]);
    }


    public function test_product_image_can_be_deleted(): void
    {
        $user = $this->createAdminUser();

        $product = Product::factory()->create();

        $image = $product->images()->create([
            'path' => 'products/test.jpg',
            'alt' => 'test',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson(
                "/api/products/{$product->id}/images/{$image->id}"
            );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('product_images', [
            'id' => $image->id,
        ]);
    }


    public function test_user_cannot_create_product(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
                'title' => 'Normal User Product',
                'category_id' => $category->id,
                'price' => 1000,
                'stock' => 1,
                'status' => 'active',
            ]);

        $response->assertStatus(403);
    }
}
