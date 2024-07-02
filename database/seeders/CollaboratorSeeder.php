<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CollaboratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        for($i= 0; $i < 40; $i++){
            $index=$i+1;
            $admin = User::create([
                'name' => "Colaborador $index",
                'document' => '123456789',
                'phone' => '3156262456',
                'email' => "colaborador$index@gmail.com",
                'password' => Hash::make('1234567890'),
                'role_id' => 3,
            ]);
            $admin->assignRole("collaborator");
        }
    }
}
