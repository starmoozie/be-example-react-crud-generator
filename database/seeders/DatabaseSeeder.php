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
        // User::factory(50)->create();
        // Supplier::factory(50)->create();
        // Customer::factory(50)->create();
        // PaymentMethod::factory(50)->create();
        // ProductCategory::factory(50000)->create();
        // Product::factory(5000)->create();

        $this->call([
            PermissionSeeder::class,
            MenuSeeder::class,
            RoleSeeder::class,
            UserSeeder::class
        ]);
    }
}
