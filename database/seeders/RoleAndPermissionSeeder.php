<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Define Permissions for Week 1
        $permissions = [
            'view_dashboard',

            'view_products',
            'create_products',
            'edit_products',
            'delete_products',

            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $productManager = Role::firstOrCreate(['name' => 'product_manager']);
        $userManager = Role::firstOrCreate(['name' => 'user_manager']);

        // Assign Permissions to Roles
        $superAdmin->givePermissionTo($permissions);

        $productManager->givePermissionTo([
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories'
        ]);

        $userManager->givePermissionTo([
            'view_users',
            'create_users',
            'edit_users',
            'delete_users'
        ]);
    }
}
