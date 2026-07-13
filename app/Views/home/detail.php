<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="max-w-6xl mx-auto px-6 py-10">
    <a href="<?= base_url('produk') ?>" class="text-brownlite hover:underline">← Kembali ke produk</a>

    <div class="grid md:grid-cols-2 gap-10 mt-6 bg-white dark:bg-stone-800 rounded-3xl shadow-xl p-6 md:p-10 animate-fadeIn">
        <div>
            <!-- Gambar ini dapat diganti sesuai kebutuhan -->
            <img src="<?= base_url('uploads/produk/' . $produk['gambar_produk']) ?>" alt="<?= esc($produk['nama_produk']) ?>"
                 class="w-full rounded-2xl shadow-lg hover:scale-105 transition-transform duration-500">
        </div>
        <div class="flex flex-col justify-center">
            <span class="inline-block px-3 py-1 rounded-full bg-brownlite/20 text-browndark dark:text-brownlite text-sm w-fit"><?= esc($produk['nama_kategori'] ?? 'Umum') ?></span>
            <h1 class="font-serif text-3xl font-bold text-browndark dark:text-brownlite mt-3"><?= esc($produk['nama_produk']) ?></h1>
            <div class="text-2xl font-bold text-browndark dark:text-brownlite mt-3"><?= rupiah($produk['harga']) ?></div>
            <p class="text-gray-600 dark:text-gray-300 mt-4 leading-relaxed"><?= esc($produk['deskripsi']) ?></p>
            <p class="mt-3 text-sm">Stok: <span class="font-semibold <?= (int)$produk['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>"><?= (int)$produk['stok'] > 0 ? $produk['stok'] . ' tersedia' : 'Habis' ?></span></p>

            <?php if ((int)$produk['stok'] > 0): ?>
                <?php if (session()->get('isLoggedIn') && session()->get('role') === 'user'): ?>
                <?php // Minuman punya varian Hot / Iced
                      $isMinuman = strtolower($produk['nama_kategori'] ?? '') === 'minuman'; ?>
                <form action="<?= base_url('cart/add/' . $produk['id']) ?>" method="post" class="mt-6 space-y-4">
                    <?= csrf_field() ?>

                    <?php if ($isMinuman): ?>
                    <!-- ===== PILIHAN VARIAN MINUMAN ===== -->
                    <div x-data="{ varian: 'Iced' }">
                        <label class="block text-sm font-medium mb-2">Pilih Varian</label>
                        <div class="flex gap-3">
                            <label :class="varian === 'Hot' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                                   class="flex-1 cursor-pointer border-2 rounded-xl px-4 py-3 flex items-center gap-2 transition">
                                <input type="radio" name="varian" value="Hot" x-model="varian" class="accent-[#8B5E3C]">
                                <span>🔥 Hot</span>
                            </label>
                            <label :class="varian === 'Iced' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                                   class="flex-1 cursor-pointer border-2 rounded-xl px-4 py-3 flex items-center gap-2 transition">
                                <input type="radio" name="varian" value="Iced" x-model="varian" class="accent-[#8B5E3C]">
                                <span>🧊 Iced</span>
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="flex items-center gap-3">
                        <input type="number" name="jumlah" value="1" min="1" max="<?= $produk['stok'] ?>"
                               class="w-20 px-3 py-2.5 border border-brownlite/50 rounded-lg dark:bg-stone-700 outline-none">
                        <button type="submit" class="flex-1 sm:flex-none px-8 py-3 bg-browndark text-white rounded-full font-medium hover:bg-brownlite hover:scale-105 transition-all">
                            🛒 Tambah ke Keranjang
                        </button>
                    </div>
                </form>
                <?php elseif (session()->get('role') === 'admin'): ?>
                    <p class="mt-6 text-sm text-gray-500">Login sebagai admin — silakan gunakan akun user untuk membeli.</p>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="mt-6 inline-block px-8 py-3 bg-browndark text-white rounded-full font-medium hover:bg-brownlite transition w-fit">
                        Login untuk Membeli
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <button disabled class="mt-6 px-8 py-3 bg-gray-300 text-gray-500 rounded-full font-medium cursor-not-allowed w-fit">Stok Habis</button>
            <?php endif; ?>
        </div>
    </div>

    <?php if (! empty($terkait)): ?>
    <div class="mt-14">
        <h2 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite mb-6">Produk Terkait</h2>
        <div class="grid sm:grid-cols-3 gap-6">
            <?php foreach ($terkait as $p): ?>
            <a href="<?= base_url('produk/detail/' . $p['id']) ?>" class="group bg-white dark:bg-stone-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition">
                <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']) ?>" class="w-full h-40 object-cover group-hover:scale-105 transition-transform">
                <div class="p-4">
                    <h3 class="font-serif font-bold text-browndark dark:text-brownlite"><?= esc($p['nama_produk']) ?></h3>
                    <span class="text-sm text-brownlite"><?= rupiah($p['harga']) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<?= $this->endSection() ?>
