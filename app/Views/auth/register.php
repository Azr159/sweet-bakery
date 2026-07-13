<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="min-h-[80vh] flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-md bg-white dark:bg-stone-800 rounded-3xl shadow-2xl p-8 animate-slideUp">
        <div class="text-center mb-6">
            <img src="<?= base_url('assets/logo.svg') ?>" class="w-16 h-16 mx-auto mb-2">
            <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500">Bergabung dengan Sweet Bakery</p>
        </div>
        <form action="<?= base_url('auth/register') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= old('nama') ?>" required
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" required
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="passconf" required minlength="6"
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
            </div>
            <button type="submit" class="w-full py-3 bg-browndark text-white rounded-lg font-medium hover:bg-brownlite transition">Daftar</button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-5">
            Sudah punya akun? <a href="<?= base_url('auth/login') ?>" class="text-brownlite font-medium hover:underline">Login</a>
        </p>
    </div>
</section>
<?= $this->endSection() ?>
