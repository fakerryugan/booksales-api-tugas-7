<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        User::create([
            "name"     => "fatkur",
            "email"    => "fatkurirham55@gmail.com",
            "password" => bcrypt("password123"), 
            "role"     => "admin"
        ]);

        User::create([
            "name"     => "customer",
            "email"    => "fatku@gmail.com",
            "password" => bcrypt("faker456"),
            "role"     => "customer"
        ]);
    }
}