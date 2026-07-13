<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="max-w-7xl mx-auto px-6 py-10">
    <div class="text-center mb-8 animate-slideUp">
        <h1 class="font-serif text-4xl font-bold text-browndark dark:text-brownlite">Katalog Produk</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Temukan roti & kue favorit Anda</p>
    </div>

    <!-- Filter & pencarian -->
    <form method="get" action="<?= base_url('produk') ?>" class="flex flex-wrap gap-3 justify-center mb-8">
        <input type="text" name="cari" value="<?= esc($keyword ?? '') ?>" placeholder="Cari produk..."
               class="px-4 py-2 rounded-full border border-brownlite/50 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-800 w-64">
        <select name="kategori" class="px-4 py-2 rounded-full border border-brownlite/50 dark:bg-stone-800 outline-none">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($aktifKat == $k['id']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-6 py-2 bg-browndark text-white rounded-full hover:bg-brownlite transition">Cari</button>
    </form>

    <?php if (empty($produk)): ?>
        <div class="text-center py-20 text-gray-400">
            <div class="text-6xl mb-4">🥖</div>
            <p>Produk tidak ditemukan.</p>
        </div>
    <?php else: ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <?php foreach ($produk as $p): ?>
        <div class="group bg-white dark:bg-stone-800 rounded-xl overflow-hidden shadow-md hover:shadow-2xl hover:scale-[1.03] transition-all duration-300">
            <div class="overflow-hidden relative">
                <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']) ?>" alt="<?= esc($p['nama_produk']) ?>"
                     class="w-full h-36 sm:h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                <?php if ((int)$p['stok'] <= 0): ?>
                    <span class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full">Habis</span>
                <?php endif; ?>
            </div>
            <div class="p-3 sm:p-4">
                <span class="text-xs text-brownlite font-medium"><?= esc($p['nama_kategori'] ?? 'Umum') ?></span>
                <h3 class="font-serif text-lg font-bold text-browndark dark:text-brownlite mt-1 truncate"><?= esc($p['nama_produk']) ?></h3>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1 line-clamp-2"><?= esc(substr($p['deskripsi'] ?? '', 0, 60)) ?></p>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-browndark dark:text-brownlite font-bold"><?= rupiah($p['harga']) ?></span>
                    <a href="<?= base_url('produk/detail/' . $p['id']) ?>" class="px-3 py-1.5 bg-browndark text-white text-sm rounded-full hover:bg-brownlite transition">Beli</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-10 flex justify-center">
        <?= $pager->only(['cari', 'kategori'])->links('produk', 'tailwind_full') ?>
    </div>
    <?php endif; ?>
</section>

<?= $this->endSection() ?>
