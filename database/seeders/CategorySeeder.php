<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobile = Category::firstOrCreate([
            'name' => 'Mobile',
            'slug' => 'mobile',
            'is_active' => true,
        ]);

        $laptop = Category::firstOrCreate([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
        ]);

        // $accessory = Category::firstOrCreate([
        //     'name' => 'Accessory',
        //     'slug' => 'accessory',
        //     'is_active' => true,
        // ]);
    }
}
