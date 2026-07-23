<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'product.view',
            'product.create',
            'product.update',
            'product.delete',
            'product.restore',

            'category.manage',

            'cart.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
        ]);

        Role::firstOrCreate([
            'name' => 'user',
        ]);

        $admin->givePermissionTo($permissions);
    }
}
