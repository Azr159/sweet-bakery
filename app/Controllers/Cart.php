<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\PesananModel;
use App\Models\DetailPesananModel;

class Cart extends BaseController
{
    /** Ambil keranjang dari session. Key = "{produk_id}-{varian}" */
    private function getCart(): array
    {
        return session()->get('cart') ?? [];
    }

    private function saveCart(array $cart): void
    {
        session()->set('cart', $cart);
    }

    public function index()
    {
        $cart  = $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        return view('cart/index', [
            'title' => 'Keranjang - Sweet Bakery',
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    public function add($id)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->withKategori()->where('produk.id', $id)->first();
        if (! $produk) {
            return redirect()->to('/produk')->with('error', 'Produk tidak ditemukan.');
        }

        // ---- Varian (khusus minuman: Hot / Iced) ----
        $varian = $this->request->getPost('varian');
        $isMinuman = strtolower($produk['nama_kategori'] ?? '') === 'minuman';

        if ($isMinuman) {
            if (! in_array($varian, ['Hot', 'Iced'], true)) {
                return redirect()->back()->with('error', 'Silakan pilih varian Hot atau Iced.');
            }
        } else {
            $varian = null; // produk non-minuman tidak punya varian
        }

        $jumlah = max(1, (int) $this->request->getPost('jumlah'));
        $cart   = $this->getCart();

        // Hot & Iced dianggap item berbeda di keranjang
        $key = $id . '-' . ($varian ?? 'std');

        if (isset($cart[$key])) {
            $cart[$key]['jumlah'] += $jumlah;
        } else {
            $cart[$key] = [
                'key'    => $key,
                'id'     => $produk['id'],
                'nama'   => $produk['nama_produk'],
                'varian' => $varian,
                'harga'  => (int) $produk['harga'],
                'gambar' => $produk['gambar_produk'],
                'jumlah' => $jumlah,
            ];
        }
        $this->saveCart($cart);
        return redirect()->to('/cart')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update($key)
    {
        $cart   = $this->getCart();
        $jumlah = (int) $this->request->getPost('jumlah');
        if (isset($cart[$key])) {
            if ($jumlah <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['jumlah'] = $jumlah;
            }
            $this->saveCart($cart);
        }
        return redirect()->to('/cart')->with('success', 'Keranjang diperbarui.');
    }

    public function remove($key)
    {
        $cart = $this->getCart();
        unset($cart[$key]);
        $this->saveCart($cart);
        return redirect()->to('/cart')->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout()
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            return redirect()->to('/produk')->with('error', 'Keranjang masih kosong.');
        }
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        return view('cart/checkout', [
            'title' => 'Checkout - Sweet Bakery',
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    public function processCheckout()
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            return redirect()->to('/produk')->with('error', 'Keranjang masih kosong.');
        }

        // ---- Ambil pilihan pemesanan ----
        $tipe    = $this->request->getPost('tipe_pesanan');   // "Dine In" / "Antar"
        $bayar   = $this->request->getPost('metode_bayar');   // "QRIS" / "Bayar di Tempat"
        $meja    = $this->request->getPost('nomor_meja');
        $alamat  = $this->request->getPost('alamat');
        $telepon = $this->request->getPost('nomor_telepon');
        $catatan = $this->request->getPost('catatan');

        if (! in_array($tipe, ['Dine In', 'Antar'], true)) {
            return redirect()->back()->withInput()->with('error', 'Silakan pilih tipe pesanan.');
        }

        // ============================================================
        //  ATURAN PEMBAYARAN
        //  - Dine In : "Bayar di Kasir" ATAU "QRIS" (pesanan diantar ke meja)
        //  - Antar   : "QRIS" ATAU "Bayar di Tempat" (COD)
        // ============================================================
        if ($tipe === 'Dine In') {
            if (empty($meja)) {
                return redirect()->back()->withInput()->with('error', 'Nomor meja wajib diisi untuk pesanan di tempat.');
            }
            if (! in_array($bayar, ['Bayar di Kasir', 'QRIS'], true)) {
                return redirect()->back()->withInput()->with('error', 'Silakan pilih metode pembayaran.');
            }
            // Bayar QRIS = lunas & pesanan diantar ke meja.
            // Bayar di Kasir = lunas setelah membayar di kasir.
            $statusBayar = ($bayar === 'QRIS') ? 'Sudah Dibayar' : 'Belum Dibayar';
        } else {
            if (! in_array($bayar, ['QRIS', 'Bayar di Tempat'], true)) {
                return redirect()->back()->withInput()->with('error', 'Silakan pilih metode pembayaran.');
            }
            if (empty($alamat)) {
                return redirect()->back()->withInput()->with('error', 'Alamat pengiriman wajib diisi untuk pesanan antar.');
            }
            if (empty($telepon)) {
                return redirect()->back()->withInput()->with('error', 'Nomor telepon wajib diisi agar kurir dapat menghubungi Anda.');
            }
            $statusBayar = ($bayar === 'QRIS') ? 'Sudah Dibayar' : 'Belum Dibayar';
        }

        $pesananModel = new PesananModel();
        $detailModel  = new DetailPesananModel();
        $produkModel  = new ProdukModel();

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // ---- Nomor antrian otomatis (reset tiap hari) ----
        $terakhir = $pesananModel
            ->where('DATE(created_at)', date('Y-m-d'))
            ->selectMax('nomor_antrian')
            ->first();
        $nomorAntrian = (int) ($terakhir['nomor_antrian'] ?? 0) + 1;

        $pesananId = $pesananModel->insert([
            'user_id'       => session()->get('id'),
            'total_harga'   => $total,
            'status'        => 'Menunggu',
            'tipe_pesanan'  => $tipe,
            'nomor_meja'    => ($tipe === 'Dine In') ? $meja : null,
            'nomor_antrian' => $nomorAntrian,
            'metode_bayar'  => $bayar,
            'status_bayar'  => $statusBayar,
            'alamat'        => ($tipe === 'Antar') ? $alamat : null,
            'nomor_telepon' => $telepon ?: null,
            'catatan'       => $catatan,
        ], true);

        foreach ($cart as $item) {
            $detailModel->insert([
                'pesanan_id'  => $pesananId,
                'produk_id'   => $item['id'],
                'nama_produk' => $item['nama'],
                'varian'      => $item['varian'] ?? null,
                'harga'       => $item['harga'],
                'jumlah'      => $item['jumlah'],
                'subtotal'    => $item['harga'] * $item['jumlah'],
            ]);
            // Kurangi stok
            $produk = $produkModel->find($item['id']);
            if ($produk) {
                $stokBaru = max(0, (int) $produk['stok'] - $item['jumlah']);
                $produkModel->update($item['id'], ['stok' => $stokBaru]);
            }
        }

        session()->remove('cart');

        if ($tipe === 'Dine In') {
            $pesan = ($bayar === 'QRIS')
                ? 'Pembayaran diterima! Nomor antrian: ' . $nomorAntrian . '. Pesanan akan diantar ke meja ' . $meja . '.'
                : 'Pesanan berhasil! Nomor antrian: ' . $nomorAntrian . '. Silakan bayar di kasir.';
        } else {
            $pesan = 'Pesanan berhasil! Nomor antrian: ' . $nomorAntrian . '.';
        }

        return redirect()->to('/user/riwayat/' . $pesananId)->with('success', $pesan);
    }
}
