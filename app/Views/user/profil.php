<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="max-w-4xl mx-auto px-6 py-10 grid md:grid-cols-2 gap-6">
    <!-- Edit Profil -->
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6" x-data="{ preview: '<?= base_url('uploads/profil/' . ($user['foto_profil'] ?: 'default.svg')) ?>' }">
        <h2 class="font-serif text-xl font-bold text-browndark dark:text-brownlite mb-4">Edit Profil</h2>
        <form action="<?= base_url('user/profil/update') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div class="flex flex-col items-center">
                <img :src="preview" class="w-24 h-24 rounded-full object-cover border-4 border-brownlite mb-3">
                <label class="text-sm text-brownlite cursor-pointer hover:underline">
                    Ganti Foto
                    <input type="file" name="foto_profil" accept="image/*" class="hidden"
                           @change="preview = URL.createObjectURL($event.target.files[0])">
                </label>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="nama" value="<?= esc($user['nama']) ?>" required
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="<?= esc($user['email']) ?>" required
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            </div>
            <button class="w-full py-2.5 bg-browndark text-white rounded-lg hover:bg-brownlite transition">Simpan Profil</button>
        </form>
    </div>

    <!-- Ganti Password -->
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6">
        <h2 class="font-serif text-xl font-bold text-browndark dark:text-brownlite mb-4">Ganti Password</h2>
        <form action="<?= base_url('user/profil/password') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium mb-1">Password Lama</label>
                <input type="password" name="password_lama" required class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password Baru</label>
                <input type="password" name="password_baru" required minlength="6" class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_konf" required minlength="6" class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            </div>
            <button class="w-full py-2.5 bg-browndark text-white rounded-lg hover:bg-brownlite transition">Ubah Password</button>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
