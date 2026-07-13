<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<?php $isEdit = $user !== null; ?>
<div class="flex items-center gap-3 mb-6">
    <a href="<?= base_url('admin/user') ?>" class="text-brownlite hover:underline">← Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite"><?= $isEdit ? 'Edit' : 'Tambah' ?> User</h1>
</div>

<div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 max-w-xl">
    <form action="<?= $isEdit ? base_url('admin/user/update/' . $user['id']) : base_url('admin/user/store') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="nama" value="<?= old('nama', $isEdit ? $user['nama'] : '') ?>" required
                   class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="<?= old('email', $isEdit ? $user['email'] : '') ?>" required
                   class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '' ?></label>
            <input type="password" name="password" <?= $isEdit ? '' : 'required' ?> minlength="6"
                   class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
                <option value="user"  <?= ($isEdit && $user['role'] === 'user')  ? 'selected' : '' ?>>User (pembeli)</option>
                <option value="kasir" <?= ($isEdit && $user['role'] === 'kasir') ? 'selected' : '' ?>>Kasir (kelola pesanan)</option>
                <option value="admin" <?= ($isEdit && $user['role'] === 'admin') ? 'selected' : '' ?>>Admin (akses penuh)</option>
            </select>
        </div>
        <button class="px-6 py-2.5 bg-browndark text-white rounded-full hover:bg-brownlite transition"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?></button>
    </form>
</div>
<?= $this->endSection() ?>
