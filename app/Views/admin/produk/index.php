<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Kelola Produk</h1>
    <a href="<?= base_url('admin/produk/create') ?>" class="px-5 py-2.5 bg-browndark text-white rounded-full hover:bg-brownlite transition shadow">+ Tambah Produk</a>
</div>

<form method="get" class="mb-5">
    <div class="relative max-w-xs">
        <input type="text" name="cari" value="<?= esc($keyword ?? '') ?>" placeholder="Cari produk / kategori..."
               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-800">
        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
    </div>
</form>

<div x-data="{ open: false, target: '', name: '' }">
    <?php if (empty($produk)): ?>
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-12 text-center text-gray-400">
            <div class="text-5xl mb-3">🥖</div><p>Tidak ada produk.</p>
        </div>
    <?php else: ?>

    <!-- ===== TABEL (desktop) ===== -->
    <div class="hidden md:block bg-white dark:bg-stone-800 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-cream dark:bg-stone-700 text-left"><tr>
                <th class="p-3">Gambar</th><th class="p-3">Nama</th><th class="p-3">Kategori</th>
                <th class="p-3">Harga</th><th class="p-3">Stok</th><th class="p-3">Aksi</th>
            </tr></thead>
            <tbody>
                <?php foreach ($produk as $p): ?>
                <tr class="border-t border-brownlite/10 hover:bg-cream/40 dark:hover:bg-stone-700/40 transition">
                    <td class="p-3"><img src="<?= base_url('uploads/produk/' . $p['gambar_produk']) ?>" class="w-14 h-14 object-cover rounded-xl shadow-sm"></td>
                    <td class="p-3 font-medium"><?= esc($p['nama_produk']) ?></td>
                    <td class="p-3"><span class="px-2 py-1 rounded-full text-xs bg-brownlite/20 text-browndark"><?= esc($p['nama_kategori'] ?? '-') ?></span></td>
                    <td class="p-3 whitespace-nowrap"><?= rupiah($p['harga']) ?></td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs <?= (int)$p['stok'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $p['stok'] ?>
                        </span>
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="<?= base_url('admin/produk/edit/' . $p['id']) ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs hover:bg-blue-600">Edit</a>
                            <button @click="open=true; target='<?= base_url('admin/produk/delete/' . $p['id']) ?>'; name='<?= esc($p['nama_produk'], 'js') ?>'"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">Hapus</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== KARTU (mobile) ===== -->
    <div class="md:hidden space-y-3">
        <?php foreach ($produk as $p): ?>
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-3 flex gap-3">
            <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']) ?>" class="w-20 h-20 object-cover rounded-xl shrink-0">
            <div class="flex-1 min-w-0">
                <div class="font-medium truncate"><?= esc($p['nama_produk']) ?></div>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-brownlite/20 text-browndark"><?= esc($p['nama_kategori'] ?? '-') ?></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] <?= (int)$p['stok'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">Stok <?= $p['stok'] ?></span>
                </div>
                <div class="font-bold text-browndark dark:text-brownlite text-sm mt-1"><?= rupiah($p['harga']) ?></div>
                <div class="flex gap-2 mt-2">
                    <a href="<?= base_url('admin/produk/edit/' . $p['id']) ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs">Edit</a>
                    <button @click="open=true; target='<?= base_url('admin/produk/delete/' . $p['id']) ?>'; name='<?= esc($p['nama_produk'], 'js') ?>'"
                            class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Modal konfirmasi hapus -->
    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.outside="open=false" class="bg-white dark:bg-stone-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm">
            <div class="text-4xl text-center mb-3">🗑️</div>
            <h3 class="font-bold text-center text-lg mb-1">Hapus Produk?</h3>
            <p class="text-center text-gray-500 text-sm mb-5">Yakin ingin menghapus "<span x-text="name"></span>"?</p>
            <div class="flex gap-3">
                <button @click="open=false" class="flex-1 py-2 border border-gray-300 dark:border-stone-600 rounded-lg">Batal</button>
                <a :href="target" class="flex-1 py-2 bg-red-600 text-white rounded-lg text-center hover:bg-red-700">Hapus</a>
            </div>
        </div>
    </div>
</div>

<div class="mt-6"><?= $pager->only(['cari'])->links('produk', 'tailwind_full') ?></div>
<?= $this->endSection() ?>
