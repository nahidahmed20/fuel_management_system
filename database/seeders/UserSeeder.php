<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'slug'     => Str::slug('Admin') . '_' . rand(100000, 999999),
            'phone'    => '01711111111',
            'role'     => 'admin',
            'email'    => 'admin@gmail.com',
            'address'  => 'Dhaka',
            'gender'   => 'male',  
            'password' => Hash::make('12345678'),  
            'image' => 'dashboard/assets/images/dummy.jpg',            
            'status'   => '1',
        ]);
    }
}
