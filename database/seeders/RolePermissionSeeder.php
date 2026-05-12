<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
            'setting-manage',
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

        // Create roles
        $roleAdmin = Role::findOrCreate('Admin');
        $roleSupervisor = Role::findOrCreate('Supervisor');
        $roleStaff = Role::findOrCreate('Staff');

        // Admin: all permissions
        $roleAdmin->syncPermissions(Permission::all());

        // Supervisor: edit all except system settings and master user, cannot see system settings
        $roleSupervisor->syncPermissions([
            'item-list',
            'item-create',
            'item-edit',
            'item-delete',
            'sale-list',
            'sale-create',
            'sale-edit',
            'sale-delete',
            'payment-list',
            'payment-create',
            'payment-edit',
            'payment-delete',
            // Note: intentionally excluded user-* and setting-manage
        ]);

        // Staff: only edit sales and payment, cannot see system settings
        $roleStaff->syncPermissions([
            'sale-list',
            'sale-create',
            'sale-edit',
            'payment-list',
            'payment-create',
            'payment-edit',
            // Note: intentionally excluded item-*, user-*, and setting-manage
        ]);

        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles($roleAdmin);

        // Create Supervisor User
        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@mail.com'],
            [
                'name' => 'Supervisor User',
                'password' => Hash::make('password'),
            ]
        );
        $supervisor->syncRoles($roleSupervisor);

        // Create Staff User
        $staff = User::updateOrCreate(
            ['email' => 'staff@mail.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
            ]
        );
        $staff->syncRoles($roleStaff);
    }
}
