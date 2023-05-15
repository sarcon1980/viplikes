<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        //Упрощенная схема, по сути даем права на вход в админку, по мере требований будем расщирять

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::where('name','admin')->first();

        $users = User::where('name', '!=', 'admin')->get();

        $userPermission = Permission::create(['name' => 'user']);
        $adminPermission = Permission::create(['name' => 'admin']);

        $adminRole = Role::create(['name' => 'Admins']);
        $userRole = Role::create(['name' => 'Users']);

        $adminRole->syncPermissions([
            $userPermission,
            $adminPermission,
        ]);

        $userRole->syncPermissions([
            $userPermission,
        ]);

        $admin->syncRoles([$adminRole]);

        foreach ($users as $user) {
            $user->syncRoles($userRole);
        }
    }
}
