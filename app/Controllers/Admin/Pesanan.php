<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\DetailPesananModel;

class Pesanan extends BaseController
{
    protected PesananModel $pesananModel;
    protected DetailPesananModel $detailModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->detailModel  = new DetailPesananModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $builder = $this->pesananModel->withUser();
        if ($status) {
            $builder = $builder->where('pesanan.status', $status);
        }
        return view('admin/pesanan/index', [
            'title'      => 'Kelola Pesanan - Admin',
            'pesanan'    => $builder->orderBy('pesanan.id', 'DESC')->paginate(8, 'pesanan'),
            'pager'      => $this->pesananModel->pager,
            'statusAktif'=> $status,
        ]);
    }

    public function detail($id)
    {
        $pesanan = $this->pesananModel->withUser()->where('pesanan.id', $id)->first();
        if (! $pesanan) {
            return redirect()->to('/admin/pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }
        return view('admin/pesanan/detail', [
            'title'   => 'Detail Pesanan #' . $id,
            'pesanan' => $pesanan,
            'detail'  => $this->detailModel->where('pesanan_id', $id)->findAll(),
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $valid  = ['Menunggu', 'Diproses', 'Dikirim', 'Selesai'];
        if (! in_array($status, $valid, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }
        $this->pesananModel->update($id, ['status' => $status]);
        return redirect()->back()->with('success', 'Status pesanan diperbarui menjadi ' . $status . '.');
    }

    /** Struk pesanan (bisa dicetak untuk pelanggan) */
    public function struk($id)
    {
        $pesanan = $this->pesananModel->withUser()->where('pesanan.id', $id)->first();
        if (! $pesanan) {
            return redirect()->to('/admin/pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }
        return view('struk', [
            'title'   => 'Struk Pesanan #' . $id,
            'pesanan' => $pesanan,
            'detail'  => $this->detailModel->where('pesanan_id', $id)->findAll(),
            'kembali' => base_url('admin/pesanan/detail/' . $id),
        ]);
    }

    /** Tandai pesanan sudah dibayar / belum dibayar */
    public function updateBayar($id)
    {
        $status = $this->request->getPost('status_bayar');
        if (! in_array($status, ['Sudah Dibayar', 'Belum Dibayar'], true)) {
            return redirect()->back()->with('error', 'Status pembayaran tidak valid.');
        }
        $this->pesananModel->update($id, ['status_bayar' => $status]);
        return redirect()->back()->with('success', 'Pembayaran ditandai: ' . $status . '.');
    }

    public function delete($id)
    {
        $this->pesananModel->delete($id); // detail terhapus otomatis via FK CASCADE
        return redirect()->to('/admin/pesanan')->with('success', 'Pesanan berhasil dihapus.');
    }
}
