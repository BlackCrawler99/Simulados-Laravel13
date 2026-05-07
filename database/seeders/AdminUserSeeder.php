<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'name' => 'root',
        'email' => 'admin@email.com',
        'phone' => '11999999999',
        'password' => Hash::make('senha123'),
        'is_admin' => true, 
        ]);
    }
}
