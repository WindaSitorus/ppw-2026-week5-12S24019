<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['Sistem Informasi', 'Informatika', 'Teknik Elektro', 'Bioproses'];
        foreach ($kategoris as $kat) {
            Kategori::create(['nama' => $kat]);
        }
        
        Buku::factory(50)->create();
    }
}