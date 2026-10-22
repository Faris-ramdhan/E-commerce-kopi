<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Permissions
        $permissions = [
            // Produk & Katalog
            'view products',
            'create products',
            'edit products',
            'delete products',
            // Stok & Gudang
            'view inventory',
            'adjust inventory',
            // Pesanan
            'view orders',
            'process orders',
            'update order status',
            // Konten & Promosi
            'manage posts',
            'manage banners',
            'manage vouchers',
            'manage wholesale leads',
            // Pengguna & Role
            'manage users',
            'view activity log',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Buat Roles dan Assign Permissions
        // Administrator: Full access
        $adminRole = Role::findOrCreate('Administrator', 'web');
        $adminRole->syncPermissions(Permission::all());

        // Roaster: Produk, Roast Level/Profile, Inventory
        $roasterRole = Role::findOrCreate('Roaster', 'web');
        $roasterRole->syncPermissions([
            'view products',
            'create products',
            'edit products',
            'view inventory',
            'adjust inventory',
            'manage posts',
        ]);

        // Staf Gudang/CS: Pesanan, Update Status Resi, Inventory, Wholesale Leads
        $staffRole = Role::findOrCreate('Staf Gudang/CS', 'web');
        $staffRole->syncPermissions([
            'view products',
            'view inventory',
            'adjust inventory',
            'view orders',
            'process orders',
            'update order status',
            'manage wholesale leads',
        ]);

        // 3. Buat default accounts untuk setiap role
        $admin = User::firstOrCreate(
            ['email' => 'admin@kopi.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
                'phone' => '081234567890',
            ]
        );
        $admin->assignRole($adminRole);

        $roaster = User::firstOrCreate(
            ['email' => 'roaster@kopi.test'],
            [
                'name' => 'Head Roaster',
                'password' => Hash::make('password'),
                'is_active' => true,
                'phone' => '081234567891',
            ]
        );
        $roaster->assignRole($roasterRole);

        $staff = User::firstOrCreate(
            ['email' => 'staff@kopi.test'],
            [
                'name' => 'Staf Operasional Gudang',
                'password' => Hash::make('password'),
                'is_active' => true,
                'phone' => '081234567892',
            ]
        );
        $staff->assignRole($staffRole);

        // Buat 1 customer contoh
        User::firstOrCreate(
            ['email' => 'customer@kopi.test'],
            [
                'name' => 'Budi Santoso (Customer)',
                'password' => Hash::make('password'),
                'is_active' => true,
                'phone' => '081234567893',
            ]
        );
    }
}
