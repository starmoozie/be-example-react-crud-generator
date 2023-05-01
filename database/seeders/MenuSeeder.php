<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = new Permission;

        foreach ($permission->get($permission->getFillable())->groupBy('position') as $key => $value) {
            $permissions[] = [
                'position' => $key,
                'access' => $value
            ];
        }

        $data = [
            [
                'name' => 'Supplier',
                'path' => '/suppliers',
                'permission' => $permissions
            ],
            [
                'name' => 'Customer',
                'path' => '/Customers',
                'permission' => $permissions
            ],
            [
                'name' => 'Product Category',
                'path' => '/product-categories',
                'permission' => $permissions
            ],
            [
                'name' => 'Payment Method',
                'path' => '/payment-methods',
                'permission' => $permissions
            ],
            [
                'name' => 'Product',
                'path' => '/Products',
                'permission' => $permissions
            ],
            [
                'name' => 'Sale',
                'path' => '/Sales',
                'permission' => $permissions
            ],
        ];

        foreach ($data as $value) {
            Menu::updateOrCreate(['name' => $value['name'], 'path' => $value['path']], $value);
        }
    }
}
