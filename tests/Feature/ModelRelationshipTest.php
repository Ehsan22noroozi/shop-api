<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\OptionValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $product = Product::factory()
            ->for($category)
            ->create();

        $this->assertTrue($product->category->is($category));
    }


    public function test_category_has_many_products(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()
            ->for($category)
            ->create();

        $this->assertTrue($category->products->contains($product));
    }

    public function test_category_belongs_to_parent_category(): void
    {
        $parent = Category::factory()->create();

        $child = Category::factory()->create([
            'parent_id' => $parent->id
        ]);

        $this->assertTrue($child->parent->is($parent));
    }

    public function test_category_has_many_children(): void
    {
        $parent = Category::factory()->create();

        $child = Category::factory()->create([
            'parent_id' => $parent->id,
        ]);

        $this->assertTrue($parent->children->contains($child));
    }

    public function test_product_belongs_to_many_option_values(): void
    {
        $product = Product::factory()->create();

        $optionValue = OptionValue::factory()->create();

        $product->optionValues()->attach($optionValue->id);

        $this->assertTrue($product->optionValues->contains($optionValue));
    }

}
