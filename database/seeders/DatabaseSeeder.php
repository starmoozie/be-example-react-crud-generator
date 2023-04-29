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

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Starmoozie',
        //     'email' => 'starmoozie@gmail.com',
        //     'password' => \Hash::make('password')
        // ]);
        // User::factory(50)->create();
        // Supplier::factory(50)->create();
        // Customer::factory(50)->create();
        // PaymentMethod::factory(50)->create();
        // ProductCategory::factory(50000)->create();
        Product::factory(5000)->create();
    }
}
