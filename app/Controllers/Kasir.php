<?php

namespace App\Controllers;

use App\Models\PesananModel;
use App\Models\DetailPesananModel;

/**
 * Panel KASIR - fokus mengelola pesanan yang masuk.
 */
class Kasir extends BaseController
{
    protected PesananModel $pesananModel;
    protected DetailPesananModel $detailModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->detailModel  = new DetailPesananModel();
    }

    /** Dashboard kasir: pesanan hari ini + yang perlu diproses */
    public function dashboard()
    {
        $today = date('Y-m-d');

        $data = [
            'title'          => 'Dashboard Kasir - Sweet Bakery',
            'pesananHariIni' => $this->pesananModel->where('DATE(created_at)', $today)->countAllResults(),
            'belumBayar'     => $this->pesananModel->where('status_bayar', 'Belum Dibayar')->countAllResults(),
            'perluDiproses'  => $this->pesananModel->whereIn('status', ['Menunggu', 'Diproses'])->countAllResults(),
            'pendapatanHariIni' => (int) ($this->pesananModel
                                    ->where('DATE(created_at)', $today)
                                    ->where('status_bayar', 'Sudah Dibayar')
                                    ->selectSum('total_harga')->first()['total_harga'] ?? 0),
            // Pesanan yang masih aktif (belum selesai)
            'antrian'        => $this->pesananModel->withUser()
                                    ->whereIn('pesanan.status', ['Menunggu', 'Diproses', 'Dikirim'])
                                    ->orderBy('pesanan.id', 'ASC')->findAll(),
        ];
        return view('kasir/dashboard', $data);
    }

    /** Daftar semua pesanan (bisa difilter status) */
    public function pesanan()
    {
        $status  = $this->request->getGet('status');
        $builder = $this->pesananModel->withUser();
        if ($status) {
            $builder = $builder->where('pesanan.status', $status);
        }
        return view('kasir/pesanan', [
            'title'       => 'Kelola Pesanan - Kasir',
            'pesanan'     => $builder->orderBy('pesanan.id', 'DESC')->paginate(10, 'pesanan'),
            'pager'       => $this->pesananModel->pager,
            'statusAktif' => $status,
        ]);
    }

    public function detail($id)
    {
        $pesanan = $this->pesananModel->withUser()->where('pesanan.id', $id)->first();
        if (! $pesanan) {
            return redirect()->to('/kasir/pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }
        return view('kasir/detail', [
            'title'   => 'Detail Pesanan #' . $id,
            'pesanan' => $pesanan,
            'detail'  => $this->detailModel->where('pesanan_id', $id)->findAll(),
        ]);
    }

    /** Ubah status pesanan (Menunggu -> Diproses -> Dikirim -> Selesai) */
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        if (! in_array($status, ['Menunggu', 'Diproses', 'Dikirim', 'Selesai'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }
        $this->pesananModel->update($id, ['status' => $status]);
        return redirect()->back()->with('success', 'Status pesanan #' . $id . ' menjadi ' . $status . '.');
    }

    /** Terima pembayaran (menandai lunas) */
    public function updateBayar($id)
    {
        $status = $this->request->getPost('status_bayar');
        if (! in_array($status, ['Sudah Dibayar', 'Belum Dibayar'], true)) {
            return redirect()->back()->with('error', 'Status pembayaran tidak valid.');
        }
        $this->pesananModel->update($id, ['status_bayar' => $status]);
        return redirect()->back()->with('success', 'Pembayaran pesanan #' . $id . ': ' . $status . '.');
    }

    /** Struk pesanan */
    public function struk($id)
    {
        $pesanan = $this->pesananModel->withUser()->where('pesanan.id', $id)->first();
        if (! $pesanan) {
            return redirect()->to('/kasir/pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }
        return view('struk', [
            'title'   => 'Struk Pesanan #' . $id,
            'pesanan' => $pesanan,
            'detail'  => $this->detailModel->where('pesanan_id', $id)->findAll(),
            'kembali' => base_url('kasir/detail/' . $id),
        ]);
    }
}
