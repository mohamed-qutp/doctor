<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Dr Salah',
            'email' => 'DrSalah@gmail.com',
            'phone' => '1123456789',
            'code' =>   '+20',
            'password' => Hash::make('12345678'),
            'user_type' =>'admin'
        ]);
        $user->assignRole('super admin');
    }
}