<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_user' => 1,
                'nama' => 'Admin',
                'email' => 'admin@gmail.com',
                'no_hp' => '081234567890',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'nama' => 'Petugas',
                'email' => 'petugas@gmail.com',
                'no_hp' => '081234567891',
                'role' => 'petugas',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'nama' => 'Pimpinan',
                'email' => 'pimpinan@gmail.com',
                'no_hp' => '0812345678912',
                'role' => 'pimpinan',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'nama' => 'Pengunjung',
                'email' => 'pengunjung@gmail.com',
                'no_hp' => '0812345678913',
                'role' => 'pengunjung',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
