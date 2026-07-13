<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;

class Home extends BaseController
{
    public function index()
    {
        $produkModel = new ProdukModel();
        $data = [
            'title'    => 'Sweet Bakery - Roti & Kue Premium',
            'featured' => $produkModel->withKategori()->orderBy('id', 'DESC')->findAll(6),
        ];
        return view('home/index', $data);
    }

    public function produk()
    {
        $produkModel   = new ProdukModel();
        $kategoriModel = new KategoriModel();
        $keyword       = $this->request->getGet('cari');
        $katId         = $this->request->getGet('kategori');

        $builder = $produkModel->search($keyword);
        if ($katId) {
            $builder = $builder->where('produk.kategori_id', $katId);
        }

        $data = [
            'title'      => 'Katalog Produk - Sweet Bakery',
            'produk'     => $builder->orderBy('produk.id', 'DESC')->paginate(8, 'produk'),
            'pager'      => $produkModel->pager,
            'kategori'   => $kategoriModel->findAll(),
            'keyword'    => $keyword,
            'aktifKat'   => $katId,
        ];
        return view('home/produk', $data);
    }

    public function detail($id)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->withKategori()->where('produk.id', $id)->first();
        if (! $produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }
        $data = [
            'title'   => $produk['nama_produk'] . ' - Sweet Bakery',
            'produk'  => $produk,
            'terkait' => $produkModel->withKategori()
                            ->where('produk.kategori_id', $produk['kategori_id'])
                            ->where('produk.id !=', $id)->findAll(3),
        ];
        return view('home/detail', $data);
    }

    public function tentang()
    {
        return view('home/tentang', ['title' => 'Tentang Kami - Sweet Bakery']);
    }
}
