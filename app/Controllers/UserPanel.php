<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PesananModel;
use App\Models\DetailPesananModel;

class UserPanel extends BaseController
{
    public function dashboard()
    {
        $pesananModel = new PesananModel();
        $uid = session()->get('id');
        $data = [
            'title'        => 'Dashboard - Sweet Bakery',
            'totalPesanan' => $pesananModel->where('user_id', $uid)->countAllResults(),
            'terakhir'     => $pesananModel->where('user_id', $uid)->orderBy('id', 'DESC')->findAll(3),
        ];
        return view('user/dashboard', $data);
    }

    public function profil()
    {
        $user = (new UserModel())->find(session()->get('id'));
        return view('user/profil', [
            'title' => 'Profil - Sweet Bakery',
            'user'  => $user,
        ]);
    }

    public function updateProfil()
    {
        $userModel = new UserModel();
        $id   = session()->get('id');
        $data = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];

        // Upload foto profil (opsional)
        $foto = $this->request->getFile('foto_profil');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            if (! in_array($foto->getClientMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])) {
                return redirect()->back()->with('error', 'Format gambar harus JPG, PNG, WEBP, atau SVG.');
            }
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/profil', $newName);
            $data['foto_profil'] = $newName;

            // Hapus foto lama (kecuali default)
            $old = session()->get('foto');
            if ($old && $old !== 'default.svg' && is_file(FCPATH . 'uploads/profil/' . $old)) {
                @unlink(FCPATH . 'uploads/profil/' . $old);
            }
            session()->set('foto', $newName);
        }

        $userModel->update($id, $data);
        session()->set(['nama' => $data['nama'], 'email' => $data['email']]);
        return redirect()->to('/user/profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('id'));

        $lama = $this->request->getPost('password_lama');
        $baru = $this->request->getPost('password_baru');
        $konf = $this->request->getPost('password_konf');

        if (! password_verify($lama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }
        if (strlen($baru) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }
        if ($baru !== $konf) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $userModel->update($user['id'], ['password' => password_hash($baru, PASSWORD_DEFAULT)]);
        return redirect()->to('/user/profil')->with('success', 'Password berhasil diubah.');
    }

    public function riwayat()
    {
        $pesananModel = new PesananModel();
        $data = [
            'title'   => 'Riwayat Pembelian - Sweet Bakery',
            'pesanan' => $pesananModel->where('user_id', session()->get('id'))
                            ->orderBy('id', 'DESC')->findAll(),
        ];
        return view('user/riwayat', $data);
    }

    public function detailRiwayat($id)
    {
        $pesananModel = new PesananModel();
        $detailModel  = new DetailPesananModel();

        $pesanan = $pesananModel->where('id', $id)
                    ->where('user_id', session()->get('id'))->first();
        if (! $pesanan) {
            return redirect()->to('/user/riwayat')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('user/detail_riwayat', [
            'title'   => 'Detail Pesanan #' . $id,
            'pesanan' => $pesanan,
            'detail'  => $detailModel->where('pesanan_id', $id)->findAll(),
        ]);
    }

    /** Struk pesanan (bisa dicetak / disimpan PDF) */
    public function struk($id)
    {
        $pesananModel = new PesananModel();
        $detailModel  = new DetailPesananModel();

        // hanya boleh melihat struk miliknya sendiri
        $pesanan = $pesananModel->withUser()
                    ->where('pesanan.id', $id)
                    ->where('pesanan.user_id', session()->get('id'))
                    ->first();
        if (! $pesanan) {
            return redirect()->to('/user/riwayat')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('struk', [
            'title'   => 'Struk Pesanan #' . $id,
            'pesanan' => $pesanan,
            'detail'  => $detailModel->where('pesanan_id', $id)->findAll(),
            'kembali' => base_url('user/riwayat/' . $id),
        ]);
    }
}
