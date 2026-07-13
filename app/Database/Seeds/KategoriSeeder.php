<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $now  = date('Y-m-d H:i:s');
        $data = [
            ['nama_kategori' => 'Roti',    'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kue',     'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pastry',  'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Donat',   'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Minuman', 'created_at' => $now, 'updated_at' => $now],
        ];
        $this->db->table('kategori')->insertBatch($data);
    }
}
