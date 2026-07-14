<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        $color = Option::firstOrCreate([
            'name' => 'Color',
            'slug' => 'color',
            'is_filterable' => true,
            'is_active' => true,
        ]);

        $storage = Option::firstOrCreate([
            'name' => 'Storage',
            'slug' => 'storage',
            'is_filterable' => true,
            'is_active' => true,
        ]);

        $brand = Option::firstOrCreate([
            'name' => 'Brand',
            'slug' => 'brand',
            'is_filterable' => true,
            'is_active' => true,
        ]);

        // $color->optionValues()->createMany([
        //     [
        //         'value' => 'Black',
        //         'slug' => 'black',
        //         'sort_order' => 1,
        //         'is_active' => true,
        //     ],
        //     [
        //         'value' => 'White',
        //         'slug' => 'white',
        //         'sort_order' => 2,
        //         'is_active' => true,
        //     ],
        // ]);

        $colorValues = [
            [
                'value' => 'Black',
                'slug' => 'black',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'value' => 'White',
                'slug' => 'white',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($colorValues as $value) {
            $color->optionValues()->firstOrCreate($value);
        }

        $storageValues = [
            [
                'value' => '128GB',
                'slug' => '128gb',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'value' => '256GB',
                'slug' => '256gb',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($storageValues as $value) {
            $storage->optionValues()->firstOrCreate($value);
        }

        $brandValues = [
            [
                'value' => 'Apple',
                'slug' => 'apple',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'value' => 'Samsung',
                'slug' => 'samsung',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($brandValues as $value) {
            $brand->optionValues()->firstOrCreate($value);
        }
    }
}
