<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        return view('admin/kategori/index', [
            'title'    => 'Kelola Kategori - Admin',
            'kategori' => $this->kategoriModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function store()
    {
        if (! $this->validate(['nama_kategori' => 'required|min_length[2]'])) {
            return redirect()->back()->with('error', 'Nama kategori minimal 2 karakter.');
        }
        $this->kategoriModel->insert(['nama_kategori' => $this->request->getPost('nama_kategori')]);
        return redirect()->to('/admin/kategori')->with('success', 'Kategori ditambahkan.');
    }

    public function update($id)
    {
        if (! $this->validate(['nama_kategori' => 'required|min_length[2]'])) {
            return redirect()->back()->with('error', 'Nama kategori minimal 2 karakter.');
        }
        $this->kategoriModel->update($id, ['nama_kategori' => $this->request->getPost('nama_kategori')]);
        return redirect()->to('/admin/kategori')->with('success', 'Kategori diperbarui.');
    }

    public function delete($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to('/admin/kategori')->with('success', 'Kategori dihapus.');
    }
}
