<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPesanan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pesanan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'produk_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama_produk'=> ['type' => 'VARCHAR', 'constraint' => 150],
            'harga'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah'     => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'subtotal'   => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pesanan_id', 'pesanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('detail_pesanan');
    }

    public function down()
    {
        $this->forge->dropTable('detail_pesanan');
    }
}
