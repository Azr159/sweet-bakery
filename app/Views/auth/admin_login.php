<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="min-h-[80vh] flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-md bg-browndark text-cream rounded-3xl shadow-2xl p-8 animate-slideUp">
        <div class="text-center mb-6">
            <img src="<?= base_url('assets/logo.svg') ?>" class="w-16 h-16 mx-auto mb-2 bg-cream rounded-full p-2">
            <h1 class="font-serif text-2xl font-bold">Login Admin</h1>
            <p class="text-sm text-cream/70">Panel Administrasi Sweet Bakery</p>
        </div>
        <form action="<?= base_url('admin/login') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium mb-1">Email Admin</label>
                <input type="email" name="email" value="<?= old('email') ?>" required
                       class="w-full px-4 py-2.5 rounded-lg text-gray-800 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-lg text-gray-800 outline-none">
            </div>
            <button type="submit" class="w-full py-3 bg-cream text-browndark rounded-lg font-bold hover:bg-brownlite hover:text-white transition">Masuk</button>
        </form>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
