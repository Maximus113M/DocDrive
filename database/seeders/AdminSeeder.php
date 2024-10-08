<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'document' => '1052456123',
            'phone' => '3156262456',
            'email' => 'admin@example.com',
            'password' => Hash::make('hola1234'),
            'role_id' => 1,
        ]);
        $admin->assignRole("admin");
    }
}
