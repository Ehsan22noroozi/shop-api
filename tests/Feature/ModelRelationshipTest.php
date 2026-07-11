<?php

namespace Tests\Feature;

use App\Models\Brand;
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
        $category = new Category();
        $category->name = 'Mobile';
        $category->slug = 'mobile';
        $category->is_active = true;
        $category->save();

        $brand = new Brand();
        $brand->name = 'Samsung';
        $brand->slug = 'samsung';
        $brand->is_active = true;
        $brand->save();

        $product = new Product();
        $product->category_id = $category->id;
        $product->brand_id = $brand->id;
        $product->title = 'Galaxy A55';
        $product->slug = 'galaxy-a55';
        $product->description = 'Samsung phone';
        $product->price = 1000000;
        $product->stock = 10;
        $product->status = 'active';
        $product->save();

        $this->assertTrue($product->category->is($category));
    }

    public function test_product_belongs_to_brand(): void
    {
        $category = new Category();
        $category->name = 'Mobile';
        $category->slug = 'mobile';
        $category->is_active = true;
        $category->save();

        $brand = new Brand();
        $brand->name = 'Samsung';
        $brand->slug = 'samsung';
        $brand->is_active = true;
        $brand->save();

        $product = new Product();
        $product->category_id = $category->id;
        $product->brand_id = $brand->id;
        $product->title = 'Galaxy A55';
        $product->slug = 'galaxy-a55';
        $product->description = 'Samsung phone';
        $product->price = 1000000;
        $product->stock = 10;
        $product->status = 'active';
        $product->save();

        $this->assertTrue($product->brand->is($brand));
    }

    public function test_category_has_many_products(): void
    {
        $category = new Category();
        $category->name = 'Mobile';
        $category->slug = 'mobile';
        $category->is_active = true;
        $category->save();

        $brand = new Brand();
        $brand->name = 'Samsung';
        $brand->slug = 'samsung';
        $brand->is_active = true;
        $brand->save();

        $product = new Product();
        $product->category_id = $category->id;
        $product->brand_id = $brand->id;
        $product->title = 'Galaxy A55';
        $product->slug = 'galaxy-a55';
        $product->description = 'Samsung phone';
        $product->price = 1000000;
        $product->stock = 10;
        $product->status = 'active';
        $product->save();

        $this->assertTrue($category->products->contains($product));
    }
}
