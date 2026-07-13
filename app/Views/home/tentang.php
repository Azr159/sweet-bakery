<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="max-w-4xl mx-auto px-6 py-16 text-center animate-slideUp">
    <img src="<?= base_url('assets/logo.svg') ?>" class="w-20 h-20 mx-auto mb-6">
    <h1 class="font-serif text-4xl font-bold text-browndark dark:text-brownlite">Tentang Sweet Bakery</h1>
    <p class="mt-6 text-gray-600 dark:text-gray-300 leading-relaxed">
        Sweet Bakery adalah toko roti premium yang berdedikasi menghadirkan roti dan kue berkualitas tinggi.
        Berdiri sejak 2015, kami memadukan resep klasik dengan sentuhan modern, menggunakan bahan-bahan
        pilihan tanpa pengawet berbahaya. Setiap produk dipanggang segar setiap hari oleh tim chef berpengalaman kami.
    </p>
    <div class="grid sm:grid-cols-3 gap-6 mt-12">
        <div class="bg-white dark:bg-stone-800 p-6 rounded-2xl shadow"><div class="text-3xl font-bold text-brownlite">10+</div><p class="text-sm text-gray-500 mt-1">Tahun Pengalaman</p></div>
        <div class="bg-white dark:bg-stone-800 p-6 rounded-2xl shadow"><div class="text-3xl font-bold text-brownlite">50+</div><p class="text-sm text-gray-500 mt-1">Varian Produk</p></div>
        <div class="bg-white dark:bg-stone-800 p-6 rounded-2xl shadow"><div class="text-3xl font-bold text-brownlite">5000+</div><p class="text-sm text-gray-500 mt-1">Pelanggan Puas</p></div>
    </div>
</section>
<?= $this->endSection() ?>
