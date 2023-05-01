<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [[
            'id' => \Str::uuid()->toString(),
            'name' => 'Starmoozie',
            'email' => 'starmoozie@gmail.com',
            'password' => \Hash::make('password'),
            'role_id' => Role::first()?->id
        ]];

        User::upsert($data, 'email');
    }
}
