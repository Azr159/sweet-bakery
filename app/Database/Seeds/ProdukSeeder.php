<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        // kategori_id: 1=Roti, 2=Kue, 3=Pastry, 4=Donat, 5=Minuman
        $data = [
            ['nama_produk' => 'Roti Tawar Premium', 'kategori_id' => 1, 'harga' => 18000, 'stok' => 40, 'deskripsi' => 'Roti tawar lembut dengan tekstur halus, cocok untuk sarapan keluarga.', 'gambar_produk' => 'roti-tawar.svg'],
            ['nama_produk' => 'Croissant Butter',   'kategori_id' => 3, 'harga' => 22000, 'stok' => 30, 'deskripsi' => 'Pastry berlapis dengan butter premium, renyah di luar lembut di dalam.', 'gambar_produk' => 'croissant.svg'],
            ['nama_produk' => 'Donat Coklat',       'kategori_id' => 4, 'harga' => 12000, 'stok' => 60, 'deskripsi' => 'Donat empuk dengan topping coklat leleh yang manis.', 'gambar_produk' => 'donat-coklat.svg'],
            ['nama_produk' => 'Baguette Klasik',    'kategori_id' => 1, 'harga' => 25000, 'stok' => 25, 'deskripsi' => 'Roti Prancis klasik dengan kulit renyah, cocok untuk sandwich.', 'gambar_produk' => 'baguette.svg'],
            ['nama_produk' => 'Red Velvet Cake',    'kategori_id' => 2, 'harga' => 45000, 'stok' => 15, 'deskripsi' => 'Kue red velvet lembut dengan cream cheese frosting.', 'gambar_produk' => 'red-velvet.svg'],
            ['nama_produk' => 'Cheese Cake',        'kategori_id' => 2, 'harga' => 48000, 'stok' => 12, 'deskripsi' => 'Cheese cake creamy dengan base biskuit yang gurih.', 'gambar_produk' => 'cheese-cake.svg'],
            ['nama_produk' => 'Cinnamon Roll',      'kategori_id' => 3, 'harga' => 20000, 'stok' => 28, 'deskripsi' => 'Roti gulung kayu manis dengan glaze manis di atasnya.', 'gambar_produk' => 'cinnamon-roll.svg'],
            ['nama_produk' => 'Brownies Fudgy',     'kategori_id' => 2, 'harga' => 30000, 'stok' => 22, 'deskripsi' => 'Brownies coklat pekat yang fudgy dan kaya rasa.', 'gambar_produk' => 'brownies.svg'],
            ['nama_produk' => 'Melon Pan',          'kategori_id' => 1, 'harga' => 17000, 'stok' => 35, 'deskripsi' => 'Roti manis khas Jepang dengan lapisan kulit cookie renyah.', 'gambar_produk' => 'melon-pan.svg'],
            ['nama_produk' => 'Bagel Original',     'kategori_id' => 1, 'harga' => 16000, 'stok' => 30, 'deskripsi' => 'Bagel kenyal dengan tekstur padat, nikmat dengan olesan apa pun.', 'gambar_produk' => 'bagel.svg'],
            // ---------- MINUMAN ----------
            ['nama_produk' => 'Kopi Latte',         'kategori_id' => 5, 'harga' => 22000, 'stok' => 50, 'deskripsi' => 'Espresso lembut dipadu susu steam, cocok menemani roti favorit Anda.', 'gambar_produk' => 'kopi-latte.svg'],
            ['nama_produk' => 'Cappuccino',         'kategori_id' => 5, 'harga' => 24000, 'stok' => 45, 'deskripsi' => 'Kopi dengan busa susu tebal dan aroma yang kaya.', 'gambar_produk' => 'cappuccino.svg'],
            ['nama_produk' => 'Teh Lemon',          'kategori_id' => 5, 'harga' => 15000, 'stok' => 60, 'deskripsi' => 'Teh segar dengan perasan lemon asli, menyegarkan.', 'gambar_produk' => 'teh-lemon.svg'],
            ['nama_produk' => 'Coklat Panas',       'kategori_id' => 5, 'harga' => 20000, 'stok' => 40, 'deskripsi' => 'Coklat premium yang creamy dan menghangatkan.', 'gambar_produk' => 'coklat-panas.svg'],
            ['nama_produk' => 'Jus Jeruk',          'kategori_id' => 5, 'harga' => 18000, 'stok' => 35, 'deskripsi' => 'Jus jeruk peras segar tanpa tambahan gula.', 'gambar_produk' => 'jus-jeruk.svg'],
        ];
        foreach ($data as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        $this->db->table('produk')->insertBatch($data);
    }
}
