<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'superadmin@ez-pump.com')->exists()) {
            User::create([
                'name' => 'superadmin',
                'email' => 'superadmin@ez-pump.com',
                'password' => Hash::make('KHSHUIS@SIDs!$'),
                'role' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
