<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'dashboard']);
        Permission::create(['name' => 'user.management']);
        Permission::create(['name' => 'role.permission.management']);
        Permission::create(['name' => 'menu.management']);
        //user
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.create']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.destroy']);
        Permission::create(['name' => 'user.import']);
        Permission::create(['name' => 'user.export']);

        //role
        Permission::create(['name' => 'role.index']);
        Permission::create(['name' => 'role.create']);
        Permission::create(['name' => 'role.edit']);
        Permission::create(['name' => 'role.destroy']);
        Permission::create(['name' => 'role.import']);
        Permission::create(['name' => 'role.export']);

        //permission
        Permission::create(['name' => 'permission.index']);
        Permission::create(['name' => 'permission.create']);
        Permission::create(['name' => 'permission.edit']);
        Permission::create(['name' => 'permission.destroy']);
        Permission::create(['name' => 'permission.import']);
        Permission::create(['name' => 'permission.export']);

        //assignpermission
        Permission::create(['name' => 'assign.index']);
        Permission::create(['name' => 'assign.create']);
        Permission::create(['name' => 'assign.edit']);
        Permission::create(['name' => 'assign.destroy']);

        //assingusertorole
        Permission::create(['name' => 'assign.user.index']);
        Permission::create(['name' => 'assign.user.create']);
        Permission::create(['name' => 'assign.user.edit']);

        //menu group
        Permission::create(['name' => 'menu-group.index']);
        Permission::create(['name' => 'menu-group.create']);
        Permission::create(['name' => 'menu-group.edit']);
        Permission::create(['name' => 'menu-group.destroy']);

        //menu item
        Permission::create(['name' => 'menu-item.index']);
        Permission::create(['name' => 'menu-item.create']);
        Permission::create(['name' => 'menu-item.edit']);
        Permission::create(['name' => 'menu-item.destroy']);

        // vehicle
        Permission::create(['name' => 'ranpur.management']);
        Permission::create(['name' => 'ranpur.index']);
        Permission::create(['name' => 'ranpur.create']);
        Permission::create(['name' => 'ranpur.edit']);
        Permission::create(['name' => 'ranpur.destroy']);

        // maintenance report
        Permission::create(['name' => 'laporan-rutin.management']);
        Permission::create(['name' => 'laporan-rutin.index']);
        Permission::create(['name' => 'laporan-rutin.show']);
        Permission::create(['name' => 'laporan-rutin.create']);
        Permission::create(['name' => 'laporan-rutin.edit']);
        Permission::create(['name' => 'laporan-rutin.destroy']);

        // damage report
        Permission::create(['name' => 'laporan-kerusakan.management']);
        Permission::create(['name' => 'laporan-kerusakan.index']);
        Permission::create(['name' => 'laporan-kerusakan.show']);
        Permission::create(['name' => 'laporan-kerusakan.create']);
        Permission::create(['name' => 'laporan-kerusakan.edit']);
        Permission::create(['name' => 'laporan-kerusakan.destroy']);

        // diagnosis report
        Permission::create(['name' => 'diagnosis-report.management']);
        Permission::create(['name' => 'diagnosis-report.index']);
        Permission::create(['name' => 'diagnosis-report.show']);
        Permission::create(['name' => 'diagnosis-report.create']);
        Permission::create(['name' => 'diagnosis-report.edit']);
        Permission::create(['name' => 'diagnosis-report.destroy']);

        // storage location
        Permission::create(['name' => 'storage.management']);
        Permission::create(['name' => 'storage-location.index']);
        Permission::create(['name' => 'storage-location.create']);
        Permission::create(['name' => 'storage-location.edit']);
        Permission::create(['name' => 'storage-location.destroy']);

        // sparepart
        Permission::create(['name' => 'sparepart.management']);
        Permission::create(['name' => 'sparepart.index']);
        Permission::create(['name' => 'sparepart.show']);
        Permission::create(['name' => 'sparepart.create']);
        Permission::create(['name' => 'sparepart.edit']);
        Permission::create(['name' => 'sparepart.destroy']);

        // sparepart request (header)
        Permission::create(['name' => 'sparepart-request.management']);
        Permission::create(['name' => 'sparepart-request.index']);
        Permission::create(['name' => 'sparepart-request.show']);
        Permission::create(['name' => 'sparepart-request.create']);
        Permission::create(['name' => 'sparepart-request.edit']);
        Permission::create(['name' => 'sparepart-request.destroy']);

        // stock movement
        Permission::create(['name' => 'stock-movement.management']);
        Permission::create(['name' => 'stock-movement.index']);
        Permission::create(['name' => 'stock-movement.create']);
        Permission::create(['name' => 'stock-movement.edit']);
        Permission::create(['name' => 'stock-movement.destroy']);

        // repair record
        Permission::create(['name' => 'repair-record.management']);
        Permission::create(['name' => 'repair-record.index']);
        Permission::create(['name' => 'repair-record.show']);
        Permission::create(['name' => 'repair-record.create']);
        Permission::create(['name' => 'repair-record.edit']);
        Permission::create(['name' => 'repair-record.destroy']);

        // create roles
        $roleUser = Role::create(['name' => 'crew']);
        $roleUser->givePermissionTo([
            'dashboard',
            'user.management',
            'user.index',
            'ranpur.management',
            'laporan-rutin.management',
            'laporan-rutin.index',
            'laporan-rutin.show',
            'laporan-rutin.create',
            'laporan-rutin.edit',
            'laporan-kerusakan.management',
            'laporan-kerusakan.index',
            'laporan-kerusakan.show',
            'laporan-kerusakan.create',
            'laporan-kerusakan.edit',
        ]);

        $roleMechanic = Role::create(['name' => 'mechanic']);
        $roleMechanic->givePermissionTo([
            'dashboard',
            'ranpur.management',
            'ranpur.index',
            'laporan-kerusakan.management',
            'laporan-kerusakan.index',
            'laporan-kerusakan.show',
            'laporan-kerusakan.edit',
            'diagnosis-report.management',
            'diagnosis-report.index',
            'diagnosis-report.show',
            'diagnosis-report.create',
            'diagnosis-report.edit',
            'diagnosis-report.destroy',
            'sparepart.index',
            'sparepart.show',
            'storage.management',
            'sparepart.management',
            'sparepart-request.index',
            'sparepart-request.create',
        ]);

        $roleStaff = Role::create(['name' => 'staff']);
        $roleStaff->givePermissionTo([
            'dashboard',
            'ranpur.management',
            'ranpur.index',
            'storage.management',
            'storage-location.index',
            'sparepart.management',
            'sparepart.index',
            'sparepart.show',
            'sparepart.create',
            'sparepart.edit',
            'sparepart.destroy',
            'sparepart-request.management',
            'sparepart-request.index',
            'sparepart-request.show',
            'sparepart-request.edit',
            'stock-movement.management',
            'stock-movement.index',
            'repair-record.management',
            'repair-record.index',
            'repair-record.show',
        ]);

        // create Super Admin
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        //assign user id 1 ke super admin
        $user = User::find(1);
        $user->assignRole('super-admin');
        $user = User::find(2);
        $user->assignRole('crew');
    }
}
