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
                'position' => 0
            ],
            [
                'name' => 'Export',
                'position' => 1
            ],
            [
                'name' => 'Import',
                'position' => 1
            ],
            [
                'name' => 'Detail',
                'position' => 2
            ],
            [
                'name' => 'Edit',
                'position' => 2
            ],
            [
                'name' => 'Delete',
                'position' => 2
            ],
        ];

        Permission::upsert($data, 'name');
    }
}
