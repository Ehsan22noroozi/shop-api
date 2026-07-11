<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_product_belongs_to_category(): void
    {
        $category = Category::factory()->create();

        $product = new Product();
        $product->category_id = $category->id;
        $product->title = 'Galaxy A55';
        $product->slug = 'galaxy-a55';
        $product->description = 'Samsung phone';
        $product->price = 1000000;
        $product->stock = 10;
        $product->status = 'active';
        $product->save();

        $this->assertTrue($product->category->is($category));
    }


    public function test_category_has_many_products(): void
    {
        $category = new Category();
        $category->name = 'Mobile';
        $category->slug = 'mobile';
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->category_id = $category->id;
        $product->title = 'Galaxy A55';
        $product->slug = 'galaxy-a55';
        $product->description = 'Samsung phone';
        $product->price = 1000000;
        $product->stock = 10;
        $product->status = 'active';
        $product->save();

        $this->assertTrue($category->products->contains($product));
    }

    public function test_category_belongs_to_parent_category(): void
    {
        $parent = new Category();
        $parent->name = 'Mobile';
        $parent->slug = 'mobile';
        $parent->is_active = true;
        $parent->save();

        $child = new Category();
        $child->name = 'Samsung';
        $child->slug = 'samsung';
        $child->parent_id = $parent->id;
        $child->is_active = true;
        $child->save();

        $this->assertTrue($child->parent->is($parent));
    }

    public function test_category_has_many_children(): void
    {
        $parent = new Category();
        $parent->name = 'Mobile';
        $parent->slug = 'mobile';
        $parent->is_active = true;
        $parent->save();

        $child = new Category();
        $child->name = 'Samsung';
        $child->slug = 'samsung';
        $child->parent_id = $parent->id;
        $child->is_active = true;
        $child->save();

        $this->assertTrue($parent->children->contains($child));
    }

}
