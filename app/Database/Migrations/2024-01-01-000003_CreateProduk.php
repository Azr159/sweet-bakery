<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_produk'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'kategori_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'harga'         => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'stok'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'deskripsi'     => ['type' => 'TEXT', 'null' => true],
            'gambar_produk' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('produk');
    }

    public function down()
    {
        $this->forge->dropTable('produk');
    }
}
