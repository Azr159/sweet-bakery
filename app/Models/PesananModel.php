<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'pesanan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id', 'total_harga', 'status', 'alamat', 'nomor_telepon',
        'tipe_pesanan', 'nomor_meja', 'nomor_antrian',
        'metode_bayar', 'status_bayar', 'catatan',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function withUser()
    {
        return $this->select('pesanan.*, users.nama AS nama_user, users.email')
                    ->join('users', 'users.id = pesanan.user_id', 'left');
    }
}
