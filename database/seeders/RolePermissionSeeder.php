<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'item-list',
            'item-create',
            'item-edit',
            'item-delete',
            'setting-manage', // For Category and Role management
            'sale-list',
            'sale-create',
            'sale-edit',
            'sale-delete',
            'payment-list',
            'payment-create',
            'payment-edit',
            'payment-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign created permissions
        $roleAdmin = Role::findOrCreate('Admin');
        $roleAdmin->givePermissionTo(Permission::all());

        $roleStaff = Role::findOrCreate('Staff');
        $roleStaff->givePermissionTo([
            'user-list', 
            'item-list', 
            'item-create', 
            'item-edit',
            'sale-list',
            'sale-create',
            'sale-edit',
            'payment-list',
            'payment-create',
            'payment-edit',
        ]);

        // Create Default Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin'),
            ]
        );
        $admin->syncRoles($roleAdmin);

        // Create Default Staff User
        $staff = User::updateOrCreate(
            ['email' => 'staff@staff.com'],
            [
                'name' => 'Staff Member',
                'password' => bcrypt('staff'),
            ]
        );
        $staff->syncRoles($roleStaff);

        // Seed some Categories
        Category::firstOrCreate(['prefix' => 'EL'], ['name' => 'Electronics']);
        Category::firstOrCreate(['prefix' => 'FS'], ['name' => 'Fashion']);
    }
}
