<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => getenv('DEFAULT_ADMIN_USER_FIRST_NAME'),
            'last_name' => getenv('DEFAULT_ADMIN_USER_LAST_NAME'),
            'email' => getenv('DEFAULT_ADMIN_EMAIL'),
            'password' => Hash::make(getenv('DEFAULT_ADMIN_PASSWORD')),
            'email_verified_at' => now(),
            'role' => 'super'
        ]);
    }
}
