<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menambah opsi pemesanan pada tabel pesanan:
 * - tipe_pesanan  : "Dine In" (makan di tempat) atau "Antar" (diantar)
 * - nomor_meja    : diisi jika Dine In
 * - nomor_antrian : nomor antrian otomatis
 * - metode_bayar  : "QRIS" atau "Bayar di Tempat"
 * - status_bayar  : "Belum Dibayar" atau "Sudah Dibayar"
 */
class AddOrderOptionsToPesanan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pesanan', [
            'tipe_pesanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Antar',
                'after'      => 'status',
            ],
            'nomor_meja' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'tipe_pesanan',
            ],
            'nomor_antrian' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'nomor_meja',
            ],
            'metode_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'Bayar di Tempat',
                'after'      => 'nomor_antrian',
            ],
            'status_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Belum Dibayar',
                'after'      => 'metode_bayar',
            ],
            'catatan' => [
                'type'  => 'TEXT',
                'null'  => true,
                'after' => 'status_bayar',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pesanan', [
            'tipe_pesanan',
            'nomor_meja',
            'nomor_antrian',
            'metode_bayar',
            'status_bayar',
            'catatan',
        ]);
    }
}
