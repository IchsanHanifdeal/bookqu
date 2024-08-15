<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukuData = [];

        for ($i = 1; $i <= 10; $i++) {
            $bukuData[] = [
                'cover' => 'cover_' . $i . '.jpg',
                'judul' => 'Judul Buku ' . $i,
                'penerbit' => 'Penerbit ' . Str::random(5),
                'penulis' => 'Penulis ' . Str::random(5),
                'tahun' => rand(1990, 2023),
                'stock' => rand(1, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('buku')->insert($bukuData);
    }
}
