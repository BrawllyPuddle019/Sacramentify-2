<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
{
    $adminRole = Role::create(['name' => 'admin']);
    $userRole = Role::create(['name' => 'usuario']);

    // Definir permisos
    $createPermission = Permission::create(['name' => 'create']);
    $updatePermission = Permission::create(['name' => 'update']);
    $deletePermission = Permission::create(['name' => 'delete']);
    $filterPermission = Permission::create(['name' => 'filter']);
    $exportPdfPermission = Permission::create(['name' => 'export-pdf']);

    // Asignar permisos a los roles
    $adminRole->givePermissionTo([$createPermission, $updatePermission, $deletePermission, $filterPermission, $exportPdfPermission]);
    $userRole->givePermissionTo([$filterPermission, $exportPdfPermission]);

    // Asignar roles a los usuarios existentes
    $users = User::all();
    foreach ($users as $user) {
        if ($user->is_admin) {
            $user->assignRole('admin');
        } else {
            $user->assignRole('usuario');
        }
    }
}
}