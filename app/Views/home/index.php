<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- ================= SPLASH SCREEN ================= -->
<!-- Splash muncul saat pertama membuka website (2.5 detik) -->
<div x-data="{ splash: true }" x-init="setTimeout(() => splash = false, 2500)"
     x-show="splash" x-transition.opacity.duration.600ms
     class="fixed inset-0 z-[60] flex flex-col items-center justify-center bg-cream dark:bg-stone-900">
    <!-- Logo bakery muncul dari tengah -->
    <img src="<?= base_url('assets/logo.svg') ?>" alt="Logo"
         class="w-24 h-24 animate-bounce mb-4">
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-browndark dark:text-brownlite text-center px-4">
        Selamat Datang di Sweet Bakery 🍞🥐
    </h1>
    <!-- Animasi loading roti -->
    <div class="mt-6 flex gap-2">
        <span class="w-3 h-3 rounded-full bg-brownlite animate-bounce" style="animation-delay:0s"></span>
        <span class="w-3 h-3 rounded-full bg-browndark animate-bounce" style="animation-delay:.15s"></span>
        <span class="w-3 h-3 rounded-full bg-brownlite animate-bounce" style="animation-delay:.3s"></span>
    </div>
</div>

<!-- ================= HERO SECTION ================= -->
<!-- Bagian background ini dapat diganti dengan gambar atau warna sesuai kebutuhan -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-cream via-brownlite/20 to-cream dark:from-stone-900 dark:via-stone-800 dark:to-stone-900"></div>
    <div class="relative max-w-7xl mx-auto px-6 py-16 md:py-28 grid md:grid-cols-2 gap-10 md:gap-10 items-center">
        <div class="animate-slideUp text-center md:text-left order-2 md:order-1">
            <span class="inline-block px-4 py-1 mb-4 rounded-full bg-brownlite/30 text-browndark dark:text-brownlite text-sm font-medium">🥐 Fresh setiap hari</span>
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-browndark dark:text-brownlite leading-tight">
                Selamat Datang di<br>Sweet Bakery
            </h1>
            <p class="mt-5 text-gray-600 dark:text-gray-300 text-base sm:text-lg max-w-md mx-auto md:mx-0">
                Nikmati roti dan kue premium yang dipanggang setiap hari dengan bahan pilihan terbaik. Lezat, lembut, dan penuh cinta.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 justify-center md:justify-start">
                <a href="<?= base_url('produk') ?>" class="px-7 py-3 bg-browndark text-white rounded-full font-medium shadow-lg hover:bg-brownlite hover:scale-105 transition-all text-center">
                    Lihat Produk
                </a>
                <!-- Tombol "Pesan Sekarang" -> memicu splash lalu ke produk -->
                <a href="<?= base_url('produk') ?>" class="px-7 py-3 border-2 border-browndark text-browndark dark:text-brownlite dark:border-brownlite rounded-full font-medium hover:bg-browndark hover:text-white transition-all text-center">
                    Pesan Sekarang
                </a>
            </div>
        </div>
        <div class="animate-fadeIn order-1 md:order-2">
            <!-- Gambar ini dapat diganti sesuai kebutuhan -->
            <div class="relative">
                <div class="absolute -inset-4 bg-brownlite/30 rounded-full blur-3xl"></div>
                <img src="<?= base_url('uploads/produk/croissant.svg') ?>" alt="Bakery"
                     class="relative rounded-3xl shadow-2xl hover:scale-105 transition-transform duration-500 w-full">
            </div>
        </div>
    </div>
</section>

<!-- ================= KEUNGGULAN ================= -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid md:grid-cols-3 gap-6">
        <?php
        $fitur = [
            ['🌾', 'Bahan Premium', 'Dibuat dengan bahan kualitas terbaik.'],
            ['👨‍🍳', 'Dibuat Chef Ahli', 'Diracik oleh baker berpengalaman puluhan tahun.'],
            ['🚚', 'Pengiriman Cepat', 'Sampai ke tangan Anda dalam kondisi fresh.'],
        ];
        foreach ($fitur as $f): ?>
        <div class="bg-white dark:bg-stone-800 rounded-2xl p-8 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all text-center">
            <div class="text-4xl mb-3"><?= $f[0] ?></div>
            <h3 class="font-serif text-xl font-bold text-browndark dark:text-brownlite mb-2"><?= $f[1] ?></h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm"><?= $f[2] ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ================= PRODUK UNGGULAN ================= -->
<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="text-center mb-10">
        <h2 class="font-serif text-3xl font-bold text-browndark dark:text-brownlite">Produk Unggulan</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Pilihan terfavorit dari pelanggan kami</p>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <?php foreach ($featured as $p): ?>
        <div class="group bg-white dark:bg-stone-800 rounded-xl overflow-hidden shadow-md hover:shadow-2xl hover:scale-[1.03] transition-all duration-300">
            <div class="overflow-hidden">
                <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']) ?>" alt="<?= esc($p['nama_produk']) ?>"
                     class="w-full h-36 sm:h-52 object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <div class="p-5">
                <span class="text-xs text-brownlite font-medium"><?= esc($p['nama_kategori'] ?? 'Umum') ?></span>
                <h3 class="font-serif text-lg font-bold text-browndark dark:text-brownlite mt-1"><?= esc($p['nama_produk']) ?></h3>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-browndark dark:text-brownlite font-bold"><?= rupiah($p['harga']) ?></span>
                    <a href="<?= base_url('produk/detail/' . $p['id']) ?>" class="px-4 py-2 bg-browndark text-white text-sm rounded-full hover:bg-brownlite transition">Detail</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-10">
        <a href="<?= base_url('produk') ?>" class="inline-block px-8 py-3 border-2 border-browndark text-browndark dark:text-brownlite dark:border-brownlite rounded-full font-medium hover:bg-browndark hover:text-white transition">
            Lihat Semua Produk →
        </a>
    </div>
</section>

<?= $this->endSection() ?>
