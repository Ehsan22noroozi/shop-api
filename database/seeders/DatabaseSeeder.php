<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\OptionSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //INCLUDE BASICSEEDER

        User::factory()->create([
            'name' => 'Test User',
        ]);

        $this->call([
            CategorySeeder::class,
            OptionSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
