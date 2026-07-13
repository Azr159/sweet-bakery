<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\DetailPesananModel;

class Rekap extends BaseController
{
    protected PesananModel $pesananModel;
    protected DetailPesananModel $detailModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->detailModel  = new DetailPesananModel();
    }

    private function getRange(): array
    {
        $dari   = $this->request->getGet('dari')   ?: date('Y-m-d', strtotime('-29 days'));
        $sampai = $this->request->getGet('sampai') ?: date('Y-m-d');
        return [$dari, $sampai];
    }

    private function queryPesanan(string $dari, string $sampai)
    {
        return $this->pesananModel->withUser()
            ->where('DATE(pesanan.created_at) >=', $dari)
            ->where('DATE(pesanan.created_at) <=', $sampai);
    }

    public function index()
    {
        [$dari, $sampai] = $this->getRange();

        $pesanan = $this->queryPesanan($dari, $sampai)
                        ->orderBy('pesanan.created_at', 'DESC')->findAll();

        
        $totalPesanan = count($pesanan);
        $totalOmzet   = 0;   
        $totalLunas   = 0;   
        $jmlDineIn    = 0;
        $jmlAntar     = 0;
        $perTanggal   = [];  

        foreach ($pesanan as $p) {
            $totalOmzet += (int) $p['total_harga'];
            if (($p['status_bayar'] ?? '') === 'Sudah Dibayar') {
                $totalLunas += (int) $p['total_harga'];
            }
            if (($p['tipe_pesanan'] ?? '') === 'Dine In') {
                $jmlDineIn++;
            } else {
                $jmlAntar++;
            }

            $tgl = date('Y-m-d', strtotime($p['created_at']));
            if (! isset($perTanggal[$tgl])) {
                $perTanggal[$tgl] = ['jumlah' => 0, 'omzet' => 0];
            }
            $perTanggal[$tgl]['jumlah']++;
            $perTanggal[$tgl]['omzet'] += (int) $p['total_harga'];
        }
        krsort($perTanggal); 

        return view('admin/rekap/index', [
            'title'        => 'Rekap Penjualan - Admin',
            'dari'         => $dari,
            'sampai'       => $sampai,
            'pesanan'      => $pesanan,
            'totalPesanan' => $totalPesanan,
            'totalOmzet'   => $totalOmzet,
            'totalLunas'   => $totalLunas,
            'jmlDineIn'    => $jmlDineIn,
            'jmlAntar'     => $jmlAntar,
            'perTanggal'   => $perTanggal,
        ]);
    }

    public function download()
    {
        [$dari, $sampai] = $this->getRange();
        $pesanan = $this->queryPesanan($dari, $sampai)
                        ->orderBy('pesanan.created_at', 'ASC')->findAll();

        $namaFile = 'rekap-penjualan_' . $dari . '_sampai_' . $sampai . '.csv';

        $isi = "\xEF\xBB\xBF";

        $isi .= "REKAP PENJUALAN SWEET BAKERY\n";
        $isi .= "Periode;" . date('d/m/Y', strtotime($dari)) . " s/d " . date('d/m/Y', strtotime($sampai)) . "\n";
        $isi .= "Diunduh;" . date('d/m/Y H:i') . "\n\n";

        
        $isi .= "No;Tanggal;Jam;No. Pesanan;Antrian;Pelanggan;Tipe;Meja;Telepon;Item;Total (Rp);Metode Bayar;Status Bayar;Status Pesanan\n";

        $no = 1;
        $totalOmzet = 0;
        $totalLunas = 0;

        foreach ($pesanan as $p) {
            $items = $this->detailModel->where('pesanan_id', $p['id'])->findAll();
            $daftar = [];
            foreach ($items as $d) {
                $nama = $d['nama_produk'];
                if (! empty($d['varian'])) {
                    $nama .= ' (' . $d['varian'] . ')';
                }
                $daftar[] = $nama . ' x' . $d['jumlah'];
            }
            $itemStr = implode(', ', $daftar);

            $totalOmzet += (int) $p['total_harga'];
            if (($p['status_bayar'] ?? '') === 'Sudah Dibayar') {
                $totalLunas += (int) $p['total_harga'];
            }

            $baris = [
                $no++,
                date('d/m/Y', strtotime($p['created_at'])),
                date('H:i', strtotime($p['created_at'])),
                '#' . $p['id'],
                $p['nomor_antrian'] ?? '-',
                $p['nama_user'] ?? '-',
                $p['tipe_pesanan'] ?? '-',
                $p['nomor_meja'] ?: '-',
                $p['nomor_telepon'] ?: '-',
                $itemStr,
                number_format((int) $p['total_harga'], 0, ',', '.'),
                $p['metode_bayar'] ?? '-',
                $p['status_bayar'] ?? '-',
                $p['status'] ?? '-',
            ];
            
            $baris = array_map(static fn ($v) => str_replace(';', ',', (string) $v), $baris);
            $isi  .= implode(';', $baris) . "\n";
        }

        
        $isi .= "\n";
        $isi .= "RINGKASAN\n";
        $isi .= "Jumlah Pesanan;" . count($pesanan) . "\n";
        $isi .= "Total Omzet (Rp);" . number_format($totalOmzet, 0, ',', '.') . "\n";
        $isi .= "Sudah Dibayar (Rp);" . number_format($totalLunas, 0, ',', '.') . "\n";
        $isi .= "Belum Dibayar (Rp);" . number_format($totalOmzet - $totalLunas, 0, ',', '.') . "\n";

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $namaFile . '"')
            ->setBody($isi);
    }
}
