<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::whereNotNull('id')->delete();
        Permission::whereNotNull('id')->delete();

        Permission::create(['name' => 'user.update']);

        $manager = Role::create(['name' => 'manager']); // Not needed but for future use
        $manager->givePermissionTo('user.update');
        
        Role::create(['name' => 'admin']);
    }
}