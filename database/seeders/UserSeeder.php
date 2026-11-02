<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Thủ thư Admin',
            'email' => 'admin@library.test',
            'password' => Hash::make('12345678'),
            'tel' => '0900000000',
            'role' => 'admin',
        ]);

        $readers = [
            ['Lilian',  'lilian@library.test'],
            ['Lili',    'lili@library.test'],
            ['Liliana', 'liliana@library.test'],
            ['Lily',    'lily@library.test'],
        ];

        foreach ($readers as $i => [$name, $email]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'tel' => '09000001' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'role' => 'user',
            ]);
        }

        // 1 tài khoản bị khóa để test chức năng khóa/mở
        User::create([
            'name' => 'User Bị Khóa',
            'email' => 'locked@library.test',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'is_active' => false,
        ]);
    }
}