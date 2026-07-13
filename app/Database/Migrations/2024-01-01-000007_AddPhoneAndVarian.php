<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * - nomor_telepon : nomor HP pemesan (wajib untuk pesanan Antar,
 *                   agar kurir bisa menelepon jika alamat sulit ditemukan)
 * - varian        : varian item, misal minuman "Hot" / "Iced"
 */
class AddPhoneAndVarian extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pesanan', [
            'nomor_telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
                'null'       => true,
                'after'      => 'alamat',
            ],
        ]);

        $this->forge->addColumn('detail_pesanan', [
            'varian' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'nama_produk',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pesanan', 'nomor_telepon');
        $this->forge->dropColumn('detail_pesanan', 'varian');
    }
}
