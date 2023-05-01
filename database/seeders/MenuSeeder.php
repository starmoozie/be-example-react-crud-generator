<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Supplier',
                'path' => '/suppliers'
            ],
            [
                'name' => 'Customer',
                'path' => '/Customers'
            ],
            [
                'name' => 'Product Category',
                'path' => '/product-categories'
            ],
            [
                'name' => 'Payment Method',
                'path' => '/payment-methods'
            ],
            [
                'name' => 'Product',
                'path' => '/Products'
            ],
            [
                'name' => 'Sale',
                'path' => '/Sales'
            ],
        ];

        foreach ($data as $value) {
            Menu::updateOrCreate($value, $value);
        }
    }
}
