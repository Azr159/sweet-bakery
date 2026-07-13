<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder utama - jalankan: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('KategoriSeeder');
        $this->call('AdminSeeder');
        $this->call('UserSeeder');
        $this->call('ProdukSeeder');
    }
}
