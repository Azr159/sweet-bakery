<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="min-h-[80vh] flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-md bg-white dark:bg-stone-800 rounded-3xl shadow-2xl p-8 animate-slideUp">
        <div class="text-center mb-6">
            <img src="<?= base_url('assets/logo.svg') ?>" class="w-16 h-16 mx-auto mb-2">
            <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Masuk Akun</h1>
            <p class="text-sm text-gray-500">Selamat datang kembali di Sweet Bakery</p>
        </div>
        <form action="<?= base_url('auth/login') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" required
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
            </div>
            <div x-data="{ show: false }">
                <label class="block text-sm font-medium mb-1">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                           class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
                    <button type="button" @click="show = !show" class="absolute right-3 top-2.5 text-gray-400" x-text="show ? '🙈' : '👁️'"></button>
                </div>
            </div>
            <button type="submit" class="w-full py-3 bg-browndark text-white rounded-lg font-medium hover:bg-brownlite transition">Login</button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-5">
            Belum punya akun? <a href="<?= base_url('auth/register') ?>" class="text-brownlite font-medium hover:underline">Daftar</a>
        </p>
        <div class="mt-4 p-3 bg-cream dark:bg-stone-700 rounded-lg text-xs text-center">
            <strong class="block text-center">Buat Akun Terlebih Dahulu Untuk Berbelanja</strong>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
