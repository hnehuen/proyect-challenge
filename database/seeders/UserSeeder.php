<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Nehuen Hermida',
            'email' => 'hnehuen@gmail.com',
            'password' => Hash::make('asd123asd123')
        ]);
    }
}
