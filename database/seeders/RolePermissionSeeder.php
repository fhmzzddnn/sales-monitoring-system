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
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        $roleStaff = Role::create(['name' => 'Staff']);
        $roleStaff->givePermissionTo(['user-list', 'item-list', 'item-create', 'item-edit']);

        // Create Default Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
        $admin->assignRole($roleAdmin);

        // Create Default Staff User
        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@staff.com',
            'password' => bcrypt('staff'),
        ]);
        $staff->assignRole($roleStaff);

        // Seed some Categories
        Category::create(['name' => 'Electronics', 'prefix' => 'EL']);
        Category::create(['name' => 'Fashion', 'prefix' => 'FS']);
    }
}
