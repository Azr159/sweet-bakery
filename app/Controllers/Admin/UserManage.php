<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserManage extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('admin/user/index', [
            'title' => 'Kelola User - Admin',
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/user/form', ['title' => 'Tambah User - Admin', 'user' => null]);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,user,kasir]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }
        $this->userModel->insert([
            'nama'        => $this->request->getPost('nama'),
            'email'       => $this->request->getPost('email'),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'        => $this->request->getPost('role'),
            'foto_profil' => 'default.svg',
        ]);
        return redirect()->to('/admin/user')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan.');
        }
        return view('admin/user/form', ['title' => 'Edit User - Admin', 'user' => $user]);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'nama'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'  => 'required|in_list[admin,user,kasir]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];
        
        $pass = $this->request->getPost('password');
        if (! empty($pass)) {
            $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/admin/user')->with('success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        if ((int) $id === (int) session()->get('id')) {
            return redirect()->to('/admin/user')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        $this->userModel->delete($id);
        return redirect()->to('/admin/user')->with('success', 'User berhasil dihapus.');
    }
}
