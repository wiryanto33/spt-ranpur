<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // MenuItem::factory()->count(10)->create();
        $items = [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'permission_name' => 'dashboard',
                'menu_group_id' => 1,
            ],
            [
                'name' => 'User List',
                'route' => 'user-management/user',
                'permission_name' => 'user.index',
                'menu_group_id' => 2,
            ],
            [
                'name' => 'Role List',
                'route' => 'role-and-permission/role',
                'permission_name' => 'role.index',
                'menu_group_id' => 3,
            ],
            [
                'name' => 'Permission List',
                'route' => 'role-and-permission/permission',
                'permission_name' => 'permission.index',
                'menu_group_id' => 3,
            ],
            [
                'name' => 'Permission To Role',
                'route' => 'role-and-permission/assign',
                'permission_name' => 'assign.index',
                'menu_group_id' => 3,
            ],
            [
                'name' => 'User To Role',
                'route' => 'role-and-permission/assign-user',
                'permission_name' => 'assign.user.index',
                'menu_group_id' => 3,
            ],
            [
                'name' => 'Menu Group',
                'route' => 'menu-management/menu-group',
                'permission_name' => 'menu-group.index',
                'menu_group_id' => 4,
            ],
            [
                'name' => 'Menu Item',
                'route' => 'menu-management/menu-item',
                'permission_name' => 'menu-item.index',
                'menu_group_id' => 4,
            ],
        ];

        // Append ranpur menu item under "Ranpur Management" group if exists
        $vehicleGroupId = MenuGroup::where('name', 'Ranpur Management')->value('id');
        if ($vehicleGroupId) {
            $items[] = [
                'name' => 'Ranpur',
                'route' => 'ranpur-management/ranpur',
                'permission_name' => 'ranpur.index',
                'menu_group_id' => $vehicleGroupId,
            ];
            $items[] = [
                'name' => 'Laporan Pemeliharaan',
                'route' => 'ranpur-management/laporan-rutin',
                'permission_name' => 'laporan-rutin.index',
                'menu_group_id' => $vehicleGroupId,
            ];
            $items[] = [
                'name' => 'Laporan Kerusakan',
                'route' => 'ranpur-management/laporan-kerusakan',
                'permission_name' => 'laporan-kerusakan.index',
                'menu_group_id' => $vehicleGroupId,
            ];
            $items[] = [
                'name' => 'Laporan pemeriksaan',
                'route' => 'ranpur-management/diagnosis-report',
                'permission_name' => 'diagnosis-report.index',
                'menu_group_id' => $vehicleGroupId,
            ];
            $items[] = [
                'name' => 'Catatan Perbaikan',
                'route' => 'ranpur-management/repair-record',
                'permission_name' => 'repair-record.index',
                'menu_group_id' => $vehicleGroupId,
            ];
        }

        // Append storage menu item under "Storage Management" group if exists
        $storageGroupId = MenuGroup::where('name', 'Storage Management')->value('id');
        if ($storageGroupId) {
            $items[] = [
                'name' => 'Lokasi Penyimpanan',
                'route' => 'ranpur-management/storage-location',
                'permission_name' => 'storage-location.index',
                'menu_group_id' => $storageGroupId,
            ];

            $items[] = [
                'name' => 'Sparepart',
                'route' => 'ranpur-management/sparepart',
                'permission_name' => 'sparepart.index',
                'menu_group_id' => $storageGroupId,
            ];
            $items[] = [
                'name' => 'Stock Movement',
                'route' => 'ranpur-management/stock-movement',
                'permission_name' => 'stock-movement.index',
                'menu_group_id' => $storageGroupId,
            ];
            $items[] = [
                'name' => 'Sparepart Request',
                'route' => 'ranpur-management/sparepart-request',
                'permission_name' => 'sparepart-request.index',
                'menu_group_id' => $storageGroupId,
            ];
        }

        MenuItem::insert($items);
    }
}
