<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // ---------------- LOGIN USER ----------------
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('auth/login', ['title' => 'Login - Sweet Bakery']);
    }

    public function attemptLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $this->setUserSession($user);

        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
        }
        if ($user['role'] === 'kasir') {
            return redirect()->to('/kasir/dashboard')->with('success', 'Selamat datang, Kasir!');
        }
        return redirect()->to('/produk')->with('success', 'Login berhasil. Selamat berbelanja!');
    }

    // ---------------- REGISTER ----------------
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('auth/register', ['title' => 'Daftar - Sweet Bakery']);
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'passconf' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $userModel->insert([
            'nama'        => $this->request->getPost('nama'),
            'email'       => $this->request->getPost('email'),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'        => 'user',
            'foto_profil' => 'default.svg',
        ]);

        return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ---------------- LOGIN ADMIN ----------------
    public function adminLogin()
    {
        if (session()->get('isLoggedIn')) {
            if (session()->get('role') === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            if (session()->get('role') === 'kasir') {
                return redirect()->to('/kasir/dashboard');
            }
        }
        return view('auth/admin_login', ['title' => 'Login Staff - Sweet Bakery']);
    }

    public function attemptAdminLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        // Halaman ini untuk STAFF: admin maupun kasir
        $user = $userModel->where('email', $email)->whereIn('role', ['admin', 'kasir'])->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Kredensial staff tidak valid.');
        }

        $this->setUserSession($user);

        if ($user['role'] === 'kasir') {
            return redirect()->to('/kasir/dashboard')->with('success', 'Selamat datang, Kasir!');
        }
        return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang kembali, Admin!');
    }

    // ---------------- LOGOUT ----------------
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah logout.');
    }

    private function setUserSession(array $user): void
    {
        session()->set([
            'id'         => $user['id'],
            'nama'       => $user['nama'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'foto'       => $user['foto_profil'] ?? 'default.svg',
            'isLoggedIn' => true,
        ]);
    }
}
