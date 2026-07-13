<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite mb-5">Kelola Pesanan</h1>

<!-- Filter status -->
<div class="flex flex-wrap gap-2 mb-5">
    <?php $filters = ['' => 'Semua', 'Menunggu' => 'Menunggu', 'Diproses' => 'Diproses', 'Dikirim' => 'Dikirim', 'Selesai' => 'Selesai']; ?>
    <?php foreach ($filters as $val => $label): ?>
        <a href="<?= base_url('admin/pesanan') . ($val ? '?status=' . $val : '') ?>"
           class="px-4 py-1.5 rounded-full text-sm transition <?= ($statusAktif ?? '') == $val ? 'bg-browndark text-white shadow' : 'bg-white dark:bg-stone-800 border border-brownlite/30 hover:bg-brownlite hover:text-white' ?>"><?= $label ?></a>
    <?php endforeach; ?>
</div>

<div x-data="{ open:false, target:'' }">

    <?php if (empty($pesanan)): ?>
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-12 text-center text-gray-400">
            <div class="text-5xl mb-3">📭</div><p>Tidak ada pesanan.</p>
        </div>
    <?php else: ?>

    <!-- ===== TAMPILAN TABEL (desktop) ===== -->
    <div class="hidden md:block bg-white dark:bg-stone-800 rounded-2xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-cream dark:bg-stone-700 text-left"><tr>
                <th class="p-3">Antrian</th><th class="p-3">User</th><th class="p-3">Tipe</th>
                <th class="p-3">Total</th><th class="p-3">Bayar</th><th class="p-3">Status</th><th class="p-3">Aksi</th>
            </tr></thead>
            <tbody>
                <?php foreach ($pesanan as $p): ?>
                <tr class="border-t border-brownlite/10 hover:bg-cream/40 dark:hover:bg-stone-700/40">
                    <td class="p-3">
                        <div class="w-9 h-9 rounded-lg bg-browndark text-white flex items-center justify-center font-bold">
                            <?= $p['nomor_antrian'] ?? '-' ?>
                        </div>
                        <span class="text-[10px] text-gray-400">#<?= $p['id'] ?></span>
                    </td>
                    <td class="p-3 font-medium"><?= esc($p['nama_user'] ?? '-') ?></td>
                    <td class="p-3">
                        <?php if (($p['tipe_pesanan'] ?? 'Antar') === 'Dine In'): ?>
                            <span class="px-2 py-1 rounded-full text-xs bg-brownlite/25 text-browndark">🍽️ Meja <?= esc($p['nomor_meja'] ?: '-') ?></span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs bg-sky-100 text-sky-800">🛵 Antar</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-3 whitespace-nowrap"><?= rupiah($p['total_harga']) ?></td>
                    <td class="p-3">
                        <div class="text-xs"><?= esc($p['metode_bayar'] ?? '-') ?></div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] <?= ($p['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                            <?= esc($p['status_bayar'] ?? 'Belum Dibayar') ?>
                        </span>
                    </td>
                    <td class="p-3">
                        <form action="<?= base_url('admin/pesanan/status/' . $p['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <select name="status" onchange="this.form.submit()"
                                    class="px-2 py-1 rounded-full text-xs border-0 cursor-pointer <?= badge_status($p['status']) ?>">
                                <?php foreach (['Menunggu','Diproses','Dikirim','Selesai'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $p['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="<?= base_url('admin/pesanan/detail/' . $p['id']) ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs hover:bg-blue-600">Detail</a>
                            <button @click="open=true; target='<?= base_url('admin/pesanan/delete/' . $p['id']) ?>'"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">Hapus</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== TAMPILAN KARTU (mobile) ===== -->
    <div class="md:hidden space-y-3">
        <?php foreach ($pesanan as $p): ?>
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-browndark text-white flex items-center justify-center font-bold">
                        <?= $p['nomor_antrian'] ?? '-' ?>
                    </div>
                    <div>
                        <div class="font-medium"><?= esc($p['nama_user'] ?? '-') ?></div>
                        <div class="text-xs text-gray-500">#<?= $p['id'] ?> • <?= date('d M Y', strtotime($p['created_at'])) ?></div>
                    </div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs <?= badge_status($p['status']) ?>"><?= $p['status'] ?></span>
            </div>

            <div class="flex flex-wrap gap-2 mt-3 text-xs">
                <?php if (($p['tipe_pesanan'] ?? 'Antar') === 'Dine In'): ?>
                    <span class="px-2 py-1 rounded-full bg-brownlite/25 text-browndark">🍽️ Meja <?= esc($p['nomor_meja'] ?: '-') ?></span>
                <?php else: ?>
                    <span class="px-2 py-1 rounded-full bg-sky-100 text-sky-800">🛵 Antar</span>
                <?php endif; ?>
                <span class="px-2 py-1 rounded-full <?= ($p['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= esc($p['metode_bayar'] ?? '-') ?>
                </span>
                <span class="px-2 py-1 rounded-full bg-cream dark:bg-stone-700 font-semibold"><?= rupiah($p['total_harga']) ?></span>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <form action="<?= base_url('admin/pesanan/status/' . $p['id']) ?>" method="post" class="flex-1">
                    <?= csrf_field() ?>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-2 py-1.5 rounded-lg text-xs border border-brownlite/30 dark:bg-stone-700">
                        <?php foreach (['Menunggu','Diproses','Dikirim','Selesai'] as $s): ?>
                            <option value="<?= $s ?>" <?= $p['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a href="<?= base_url('admin/pesanan/detail/' . $p['id']) ?>" class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs">Detail</a>
                <button @click="open=true; target='<?= base_url('admin/pesanan/delete/' . $p['id']) ?>'"
                        class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Modal hapus -->
    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.outside="open=false" class="bg-white dark:bg-stone-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm">
            <div class="text-4xl text-center mb-3">🗑️</div>
            <h3 class="font-bold text-center text-lg mb-4">Hapus Pesanan ini?</h3>
            <div class="flex gap-3">
                <button @click="open=false" class="flex-1 py-2 border border-gray-300 dark:border-stone-600 rounded-lg">Batal</button>
                <a :href="target" class="flex-1 py-2 bg-red-600 text-white rounded-lg text-center hover:bg-red-700">Hapus</a>
            </div>
        </div>
    </div>
</div>

<div class="mt-6"><?= $pager->only(['status'])->links('pesanan', 'tailwind_full') ?></div>
<?= $this->endSection() ?>
