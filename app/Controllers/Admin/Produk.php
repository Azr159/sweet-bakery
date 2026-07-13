<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\KategoriModel;

class Produk extends BaseController
{
    protected ProdukModel $produkModel;
    protected KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->produkModel   = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('cari');
        $data = [
            'title'   => 'Kelola Produk - Admin',
            'produk'  => $this->produkModel->search($keyword)
                            ->orderBy('produk.id', 'DESC')->paginate(6, 'produk'),
            'pager'   => $this->produkModel->pager,
            'keyword' => $keyword,
        ];
        return view('admin/produk/index', $data);
    }

    public function create()
    {
        return view('admin/produk/form', [
            'title'    => 'Tambah Produk - Admin',
            'kategori' => $this->kategoriModel->findAll(),
            'produk'   => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_produk' => 'required|min_length[3]',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'gambar'      => 'permit_empty|max_size[gambar,2048]|is_image[gambar]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $gambar   = $this->handleUpload();
        $this->produkModel->insert([
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'kategori_id'   => $this->request->getPost('kategori_id') ?: null,
            'harga'         => (int) $this->request->getPost('harga'),
            'stok'          => (int) $this->request->getPost('stok'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'gambar_produk' => $gambar ?: 'roti-tawar.svg',
        ]);
        return redirect()->to('/admin/produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = $this->produkModel->find($id);
        if (! $produk) {
            return redirect()->to('/admin/produk')->with('error', 'Produk tidak ditemukan.');
        }
        return view('admin/produk/form', [
            'title'    => 'Edit Produk - Admin',
            'kategori' => $this->kategoriModel->findAll(),
            'produk'   => $produk,
        ]);
    }

    public function update($id)
    {
        $produk = $this->produkModel->find($id);
        if (! $produk) {
            return redirect()->to('/admin/produk')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'nama_produk' => 'required|min_length[3]',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'gambar'      => 'permit_empty|max_size[gambar,2048]|is_image[gambar]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori_id' => $this->request->getPost('kategori_id') ?: null,
            'harga'       => (int) $this->request->getPost('harga'),
            'stok'        => (int) $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ];

        $gambar = $this->handleUpload();
        if ($gambar) {
            $data['gambar_produk'] = $gambar;
            // hapus gambar lama jika bukan gambar dummy seeder
            $this->deleteOldImage($produk['gambar_produk']);
        }

        $this->produkModel->update($id, $data);
        return redirect()->to('/admin/produk')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $produk = $this->produkModel->find($id);
        if ($produk) {
            $this->deleteOldImage($produk['gambar_produk']);
            $this->produkModel->delete($id);
        }
        return redirect()->to('/admin/produk')->with('success', 'Produk berhasil dihapus.');
    }

    /** Proses upload gambar, kembalikan nama file baru atau null. */
    private function handleUpload(): ?string
    {
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/produk', $newName);
            return $newName;
        }
        return null;
    }

    private function deleteOldImage(?string $filename): void
    {
        // Jangan hapus gambar dummy bawaan seeder (berekstensi .svg default)
        $dummy = ['roti-tawar.svg','croissant.svg','donat-coklat.svg','baguette.svg','red-velvet.svg','cheese-cake.svg','cinnamon-roll.svg','brownies.svg','melon-pan.svg','bagel.svg'];
        if ($filename && ! in_array($filename, $dummy) && is_file(FCPATH . 'uploads/produk/' . $filename)) {
            @unlink(FCPATH . 'uploads/produk/' . $filename);
        }
    }
}
