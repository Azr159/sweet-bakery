<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-browndark dark:text-brownlite">Halo, <?= esc(session()->get('nama')) ?> 🧾</h1>
    <p class="text-gray-500 text-sm mt-1">Berikut pesanan yang masuk hari ini.</p>
</div>

<!-- Statistik kasir -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    <?php
    $cards = [
        ['🧾', 'Pesanan Hari Ini', $pesananHariIni,               'from-violet-400 to-violet-500'],
        ['⏳', 'Perlu Diproses',   $perluDiproses,                'from-amber-400 to-amber-500'],
        ['💳', 'Belum Dibayar',    $belumBayar,                   'from-rose-400 to-rose-500'],
        ['💰', 'Masuk Hari Ini',   rupiah($pendapatanHariIni),    'from-emerald-400 to-emerald-500'],
    ];
    foreach ($cards as [$icon, $label, $value, $grad]): ?>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm hover:shadow-lg transition p-4 sm:p-5">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br <?= $grad ?> flex items-center justify-center text-xl shadow mb-3"><?= $icon ?></div>
        <div class="text-lg sm:text-2xl font-bold text-browndark dark:text-brownlite break-words"><?= $value ?></div>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5"><?= $label ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- Antrian aktif -->
<div class="flex items-center justify-between mb-4">
    <h2 class="font-serif text-xl font-bold text-browndark dark:text-brownlite">Antrian Aktif</h2>
    <a href="<?= base_url('kasir/pesanan') ?>" class="text-sm text-brownlite hover:underline">Lihat semua pesanan →</a>
</div>

<?php if (empty($antrian)): ?>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-12 text-center text-gray-400">
        <div class="text-5xl mb-3">☕</div>
        <p>Tidak ada antrian. Semua pesanan sudah selesai!</p>
    </div>
<?php else: ?>
<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
    <?php foreach ($antrian as $p): ?>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-4 border-l-4 <?= ($p['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'border-green-500' : 'border-amber-500' ?>">
        <!-- Header kartu -->
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-browndark text-white flex items-center justify-center font-bold text-lg shrink-0">
                    <?= $p['nomor_antrian'] ?? '-' ?>
                </div>
                <div class="min-w-0">
                    <div class="font-medium truncate"><?= esc($p['nama_user'] ?? '-') ?></div>
                    <div class="text-xs text-gray-500">#<?= $p['id'] ?> • <?= date('H:i', strtotime($p['created_at'])) ?></div>
                </div>
            </div>
            <span class="px-2 py-1 rounded-full text-xs whitespace-nowrap <?= badge_status($p['status']) ?>"><?= $p['status'] ?></span>
        </div>

        <!-- Info -->
        <div class="flex flex-wrap gap-1.5 mt-3 text-xs">
            <?php if (($p['tipe_pesanan'] ?? 'Antar') === 'Dine In'): ?>
                <span class="px-2 py-1 rounded-full bg-brownlite/25 text-browndark">🍽️ Meja <?= esc($p['nomor_meja'] ?: '-') ?></span>
            <?php else: ?>
                <span class="px-2 py-1 rounded-full bg-sky-100 text-sky-800">🛵 Antar</span>
            <?php endif; ?>
            <span class="px-2 py-1 rounded-full <?= ($p['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' ?>">
                <?= esc($p['metode_bayar'] ?? '-') ?>
            </span>
            <span class="px-2 py-1 rounded-full bg-cream dark:bg-stone-700 font-semibold"><?= rupiah($p['total_harga']) ?></span>
        </div>

        <!-- Tombol terima pembayaran -->
        <?php if (($p['status_bayar'] ?? '') !== 'Sudah Dibayar'): ?>
        <form action="<?= base_url('kasir/bayar/' . $p['id']) ?>" method="post" class="mt-3">
            <?= csrf_field() ?>
            <input type="hidden" name="status_bayar" value="Sudah Dibayar">
            <button class="w-full py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition">
                ✓ Terima Pembayaran
            </button>
        </form>
        <?php endif; ?>

        <!-- Ubah status -->
        <div class="flex items-center gap-2 mt-2">
            <form action="<?= base_url('kasir/status/' . $p['id']) ?>" method="post" class="flex-1">
                <?= csrf_field() ?>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-2 py-1.5 rounded-lg text-xs border border-brownlite/30 dark:bg-stone-700">
                    <?php foreach (['Menunggu','Diproses','Dikirim','Selesai'] as $s): ?>
                        <option value="<?= $s ?>" <?= $p['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a href="<?= base_url('kasir/detail/' . $p['id']) ?>" class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs">Detail</a>
            <a href="<?= base_url('kasir/struk/' . $p['id']) ?>" target="_blank" class="px-3 py-1.5 bg-browndark text-white rounded-lg text-xs">🧾</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
