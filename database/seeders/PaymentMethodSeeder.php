<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [[
            'name' => 'Shoope Pay'
        ], [
            'name' => 'Bank Transfer'
        ]];

        foreach ($data as $value) {
            PaymentMethod::updateOrCreate($value, $value);
        }
    }
}
