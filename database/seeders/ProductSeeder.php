<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\OptionValue;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobile = Category::where('name', 'Mobile')->first();

        $product = Product::firstOrCreate([
            'slug' => 'iphone-16-pro',
        ], [
            'category_id' => $mobile->id,
            'title' => 'iPhone 16 Pro',
            'description' => 'Apple iPhone 16 Pro',
            'price' => 1200,
            'stock' => 10,
            'status' => 'active',
        ]);

        $productOptions = [
            'Brand' => 'Apple',
            'Storage' => '256GB',
            'Color' => 'Black',
        ];

        $optionValueIds = [];

        foreach ($productOptions as $option => $value) {
            $optionValue = $this->findOptionValue($option, $value);

            $optionValueIds[] = $optionValue->id;
        }

        $product->optionValues()->sync($optionValueIds);
    }

    private function findOptionValue($option, $value)
    {
        return OptionValue::whereHas('option', function ($query) use ($option) {
            $query->where('name', $option);
        })
        ->where('value', $value)
        ->first();
    }
}
