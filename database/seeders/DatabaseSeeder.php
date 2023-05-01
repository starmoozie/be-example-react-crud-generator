<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
        Supplier::factory(100)->create();
        Customer::factory(100)->create();
        PaymentMethod::factory(100)->create();
        ProductCategory::factory(100)->create();
        Product::factory(25)->create();

        $this->call([
            PermissionSeeder::class,
            MenuSeeder::class,
            RoleSeeder::class,
            UserSeeder::class
        ]);
    }
}
