<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@rental.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);
        $user->assignRole('admin');

        User::factory()->count(25)->create();
    }
}