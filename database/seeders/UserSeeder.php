<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            $user = \App\Models\User::create([
                'name' => 'Awj Admin',
                'email' => 'admin@awj-p.com',
                'phone' => '01111111111',
                'password' => Hash::make('admin_123123')
            ]);
            $user->assignRole('admin');

        }
    }
}
