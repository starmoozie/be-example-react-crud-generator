<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Menu;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = new Menu;
        $data = [
            [
                'name' => 'Developer',
                'menu' => $menu->get([...$menu->getFillable(), ...['id']])->toArray()
            ]
        ];

        foreach ($data as $value) {
            Role::updateOrCreate(['name' => $value['name']], $value);
        }
    }
}
