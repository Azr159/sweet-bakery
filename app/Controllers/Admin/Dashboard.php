<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\UserModel;
use App\Models\PesananModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $produkModel  = new ProdukModel();
        $userModel    = new UserModel();
        $pesananModel = new PesananModel();

        // Pendapatan hanya dari pesanan Selesai
        $pendapatan = $pesananModel->where('status', 'Selesai')
                        ->selectSum('total_harga')->first();

        // Data grafik: jumlah pesanan per status
        $statuses = ['Menunggu', 'Diproses', 'Dikirim', 'Selesai'];
        $chartData = [];
        foreach ($statuses as $s) {
            $chartData[$s] = $pesananModel->where('status', $s)->countAllResults();
        }

        $data = [
            'title'          => 'Dashboard Admin - Sweet Bakery',
            'jumlahProduk'   => $produkModel->countAllResults(),
            'jumlahUser'     => $userModel->where('role', 'user')->countAllResults(),
            'jumlahPesanan'  => $pesananModel->countAllResults(),
            'totalPendapatan'=> (int) ($pendapatan['total_harga'] ?? 0),
            'chartData'      => $chartData,
            'pesananTerbaru' => $pesananModel->withUser()->orderBy('pesanan.id', 'DESC')->findAll(5),
        ];
        return view('admin/dashboard/index', $data);
    }
}
