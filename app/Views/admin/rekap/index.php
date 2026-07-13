<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Rekap Penjualan</h1>
        <p class="text-sm text-gray-500 mt-1">
            Periode <strong><?= date('d M Y', strtotime($dari)) ?></strong> s/d <strong><?= date('d M Y', strtotime($sampai)) ?></strong>
        </p>
    </div>
    <!-- Tombol unduh (mengikuti rentang tanggal yang dipilih) -->
    <a href="<?= base_url('admin/rekap/download?dari=' . $dari . '&sampai=' . $sampai) ?>"
       class="px-5 py-2.5 bg-green-600 text-white rounded-full hover:bg-green-700 transition shadow flex items-center gap-2">
        📥 Download Rekap (Excel/CSV)
    </a>
</div>

<!-- ===== FILTER TANGGAL ===== -->
<form method="get" class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-4 sm:p-5 mb-6">
    <div class="grid sm:grid-cols-4 gap-3 items-end">
        <div>
            <label class="block text-sm font-medium mb-1">Dari Tanggal</label>
            <input type="date" name="dari" value="<?= esc($dari) ?>"
                   class="w-full px-3 py-2 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" value="<?= esc($sampai) ?>"
                   class="w-full px-3 py-2 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div class="flex gap-2">
            <button class="px-5 py-2 bg-browndark text-white rounded-lg hover:bg-brownlite transition">Tampilkan</button>
            <a href="<?= base_url('admin/rekap') ?>" class="px-4 py-2 border border-brownlite/40 rounded-lg hover:bg-cream dark:hover:bg-stone-700 transition">Reset</a>
        </div>
        <!-- Pintasan cepat -->
        <div class="flex flex-wrap gap-2 text-xs">
            <a href="<?= base_url('admin/rekap?dari=' . date('Y-m-d') . '&sampai=' . date('Y-m-d')) ?>"
               class="px-3 py-1.5 rounded-full bg-cream dark:bg-stone-700 hover:bg-brownlite hover:text-white transition">Hari Ini</a>
            <a href="<?= base_url('admin/rekap?dari=' . date('Y-m-d', strtotime('-6 days')) . '&sampai=' . date('Y-m-d')) ?>"
               class="px-3 py-1.5 rounded-full bg-cream dark:bg-stone-700 hover:bg-brownlite hover:text-white transition">7 Hari</a>
            <a href="<?= base_url('admin/rekap?dari=' . date('Y-m-01') . '&sampai=' . date('Y-m-t')) ?>"
               class="px-3 py-1.5 rounded-full bg-cream dark:bg-stone-700 hover:bg-brownlite hover:text-white transition">Bulan Ini</a>
        </div>
    </div>
</form>

<!-- ===== RINGKASAN ===== -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php
    $cards = [
        ['🧾', 'Jumlah Pesanan', $totalPesanan,          'from-violet-400 to-violet-500'],
        ['💰', 'Total Omzet',    rupiah($totalOmzet),    'from-emerald-400 to-emerald-500'],
        ['✅', 'Sudah Dibayar',  rupiah($totalLunas),    'from-sky-400 to-sky-500'],
        ['⏳', 'Belum Dibayar',  rupiah($totalOmzet - $totalLunas), 'from-amber-400 to-amber-500'],
    ];
    foreach ($cards as [$icon, $label, $value, $grad]): ?>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-4 sm:p-5">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br <?= $grad ?> flex items-center justify-center text-xl shadow mb-3"><?= $icon ?></div>
        <div class="text-base sm:text-xl font-bold text-browndark dark:text-brownlite break-words"><?= $value ?></div>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5"><?= $label ?></p>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- ===== REKAP PER TANGGAL ===== -->
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-semibold mb-4">Penjualan per Tanggal</h2>
        <?php if (empty($perTanggal)): ?>
            <p class="text-gray-400 text-sm text-center py-6">Belum ada data.</p>
        <?php else: ?>
        <div class="space-y-2 max-h-96 overflow-y-auto">
            <?php foreach ($perTanggal as $tgl => $d): ?>
            <div class="flex items-center justify-between text-sm border-b border-brownlite/10 pb-2">
                <div>
                    <div class="font-medium"><?= date('d M Y', strtotime($tgl)) ?></div>
                    <div class="text-xs text-gray-500"><?= $d['jumlah'] ?> pesanan</div>
                </div>
                <span class="font-semibold text-browndark dark:text-brownlite whitespace-nowrap"><?= rupiah($d['omzet']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="mt-4 pt-4 border-t border-brownlite/20 flex justify-between text-sm">
            <span>🍽️ Dine In: <strong><?= $jmlDineIn ?></strong></span>
            <span>🛵 Antar: <strong><?= $jmlAntar ?></strong></span>
        </div>
    </div>

    <!-- ===== TABEL RINCIAN ===== -->
    <div class="lg:col-span-2 bg-white dark:bg-stone-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 pb-3">
            <h2 class="font-semibold">Rincian Pesanan</h2>
        </div>
        <?php if (empty($pesanan)): ?>
            <p class="text-gray-400 text-sm text-center py-12">Tidak ada pesanan pada rentang tanggal ini.</p>
        <?php else: ?>
        <div class="overflow-x-auto max-h-[520px]">
            <table class="w-full text-sm">
                <thead class="bg-cream dark:bg-stone-700 text-left sticky top-0"><tr>
                    <th class="p-3">Tanggal</th><th class="p-3">Pesanan</th><th class="p-3">Pelanggan</th>
                    <th class="p-3">Tipe</th><th class="p-3">Total</th><th class="p-3">Bayar</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($pesanan as $p): ?>
                    <tr class="border-t border-brownlite/10 hover:bg-cream/40 dark:hover:bg-stone-700/40">
                        <td class="p-3 whitespace-nowrap">
                            <div><?= date('d/m/Y', strtotime($p['created_at'])) ?></div>
                            <div class="text-xs text-gray-400"><?= date('H:i', strtotime($p['created_at'])) ?></div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium">#<?= $p['id'] ?></div>
                            <div class="text-xs text-gray-400">Antrian <?= $p['nomor_antrian'] ?? '-' ?></div>
                        </td>
                        <td class="p-3"><?= esc($p['nama_user'] ?? '-') ?></td>
                        <td class="p-3">
                            <?= ($p['tipe_pesanan'] ?? '') === 'Dine In'
                                ? '🍽️ Meja ' . esc($p['nomor_meja'] ?: '-')
                                : '🛵 Antar' ?>
                        </td>
                        <td class="p-3 whitespace-nowrap font-semibold"><?= rupiah($p['total_harga']) ?></td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] <?= ($p['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' ?>">
                                <?= esc($p['status_bayar'] ?? '-') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
