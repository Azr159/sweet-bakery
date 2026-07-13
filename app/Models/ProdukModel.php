<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_produk', 'kategori_id', 'harga', 'stok', 'deskripsi', 'gambar_produk'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil produk beserta nama kategorinya (JOIN).
     */
    public function withKategori()
    {
        return $this->select('produk.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id = produk.kategori_id', 'left');
    }

    /**
     * Pencarian + pagination untuk admin & katalog.
     */
    public function search(?string $keyword = null)
    {
        $builder = $this->withKategori();
        if ($keyword) {
            $builder = $builder->groupStart()
                ->like('produk.nama_produk', $keyword)
                ->orLike('kategori.nama_kategori', $keyword)
                ->groupEnd();
        }
        return $builder;
    }
}
