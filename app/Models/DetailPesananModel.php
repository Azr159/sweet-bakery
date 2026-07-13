<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['pesanan_id', 'produk_id', 'nama_produk', 'varian', 'harga', 'jumlah', 'subtotal'];
    protected $useTimestamps    = false;
}
