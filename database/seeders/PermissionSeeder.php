<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Create',
                'key' => 'create',
                'position' => 0,
                'method' => 'post',
                'type' => null,
            ],
            [
                'name' => 'Export',
                'key' => 'export',
                'position' => 1,
                'method' => null,
                'type' => null,
            ],
            [
                'name' => 'Import',
                'key' => 'import',
                'position' => 1,
                'method' => null,
                'type' => null,
            ],
            [
                'name' => 'Detail',
                'key' => 'detail',
                'position' => 2,
                'method' => null,
                'type' => 'Default',
            ],
            [
                'name' => 'Edit',
                'key' => 'edit',
                'position' => 2,
                'method' => 'put',
                'type' => 'Form',
            ],
            [
                'name' => 'Delete',
                'key' => 'delete',
                'position' => 2,
                'method' => 'delete',
                'type' => 'Confirm',
            ],
        ];

        Permission::upsert($data, 'name');
    }
}
