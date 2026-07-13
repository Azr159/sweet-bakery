<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="font-serif text-3xl font-bold text-browndark dark:text-brownlite mb-8">Riwayat Pembelian</h1>
    <?php if (empty($pesanan)): ?>
        <div class="text-center py-20 bg-white dark:bg-stone-800 rounded-2xl shadow">
            <div class="text-6xl mb-4">📦</div>
            <p class="text-gray-500">Belum ada riwayat pembelian.</p>
            <a href="<?= base_url('produk') ?>" class="inline-block mt-4 px-6 py-2.5 bg-browndark text-white rounded-full hover:bg-brownlite transition">Belanja Sekarang</a>
        </div>
    <?php else: ?>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-cream dark:bg-stone-700 text-left"><tr>
                <th class="p-4">Antrian</th><th class="p-4">Total</th><th class="p-4">Status</th><th class="p-4">Tanggal</th><th class="p-4">Aksi</th>
            </tr></thead>
            <tbody>
                <?php foreach ($pesanan as $p): ?>
                <tr class="border-t border-brownlite/10 hover:bg-cream/50 dark:hover:bg-stone-700/50">
                    <td class="p-4"><div class="w-9 h-9 rounded-lg bg-browndark text-white flex items-center justify-center font-bold text-sm"><?= $p['nomor_antrian'] ?? '-' ?></div><span class="text-[10px] text-gray-400">#<?= $p['id'] ?></span></td>
                    <td class="p-4"><?= rupiah($p['total_harga']) ?></td>
                    <td class="p-4"><span class="px-3 py-1 rounded-full text-xs <?= badge_status($p['status']) ?>"><?= $p['status'] ?></span></td>
                    <td class="p-4"><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
                    <td class="p-4">
                        <div class="flex gap-3">
                            <a href="<?= base_url('user/riwayat/' . $p['id']) ?>" class="text-brownlite hover:underline">Detail</a>
                            <a href="<?= base_url('user/struk/' . $p['id']) ?>" target="_blank" class="text-brownlite hover:underline">🧾 Struk</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
