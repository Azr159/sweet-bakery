<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex items-center justify-between gap-3">
        <a href="<?= base_url('user/riwayat') ?>" class="text-brownlite hover:underline">← Kembali ke riwayat</a>
        <a href="<?= base_url('user/struk/' . $pesanan['id']) ?>" target="_blank"
           class="px-4 py-2 bg-browndark text-white rounded-full text-sm hover:bg-brownlite transition">
            🧾 Lihat Struk
        </a>
    </div>

    <!-- ===== KARTU NOMOR ANTRIAN ===== -->
    <div class="bg-gradient-to-br from-browndark to-[#6f4930] text-cream rounded-2xl shadow-xl p-6 mt-4 text-center animate-fadeIn">
        <p class="text-cream/80 text-sm">Nomor Antrian Anda</p>
        <div class="font-serif text-6xl font-bold my-2"><?= $pesanan['nomor_antrian'] ?? '-' ?></div>
        <div class="flex flex-wrap items-center justify-center gap-2 mt-3 text-sm">
            <span class="px-3 py-1 rounded-full bg-cream/20">
                <?= ($pesanan['tipe_pesanan'] ?? 'Antar') === 'Dine In' ? '🍽️ Makan di Tempat' : '🛵 Diantar' ?>
            </span>
            <?php if (! empty($pesanan['nomor_meja'])): ?>
                <span class="px-3 py-1 rounded-full bg-cream/20">🪑 Meja <?= esc($pesanan['nomor_meja']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-5 sm:p-6 mt-5">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
                <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Pesanan #<?= $pesanan['id'] ?></h1>
                <p class="text-sm text-gray-500"><?= date('d M Y, H:i', strtotime($pesanan['created_at'])) ?></p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs <?= badge_status($pesanan['status']) ?>"><?= $pesanan['status'] ?></span>
        </div>

        <div class="divide-y divide-brownlite/20">
            <?php foreach ($detail as $d): ?>
            <div class="flex justify-between py-3 gap-3">
                <div>
                    <span class="font-medium"><?= esc($d['nama_produk']) ?></span>
                    <?php if (! empty($d['varian'])): ?>
                        <span class="ml-1 px-2 py-0.5 rounded-full text-[11px] <?= $d['varian'] === 'Hot' ? 'bg-orange-100 text-orange-800' : 'bg-sky-100 text-sky-800' ?>">
                            <?= $d['varian'] === 'Hot' ? '🔥 Hot' : '🧊 Iced' ?>
                        </span>
                    <?php endif; ?>
                    <span class="text-gray-500 text-sm"> × <?= $d['jumlah'] ?></span>
                </div>
                <span class="whitespace-nowrap"><?= rupiah($d['subtotal']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t border-brownlite/30">
            <span>Total</span><span class="text-browndark dark:text-brownlite"><?= rupiah($pesanan['total_harga']) ?></span>
        </div>

        <!-- ===== INFO PEMBAYARAN ===== -->
        <div class="mt-5 p-4 bg-cream dark:bg-stone-700 rounded-xl text-sm space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-500">Metode Bayar</span>
                <span class="font-medium"><?= esc($pesanan['metode_bayar'] ?? 'Bayar di Tempat') ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status Bayar</span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= ($pesanan['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= esc($pesanan['status_bayar'] ?? 'Belum Dibayar') ?>
                </span>
            </div>
            <?php if (! empty($pesanan['alamat'])): ?>
                <div><span class="text-gray-500">Alamat:</span> <?= esc($pesanan['alamat']) ?></div>
            <?php endif; ?>
            <?php if (! empty($pesanan['nomor_telepon'])): ?>
                <div><span class="text-gray-500">Telepon:</span> 📞 <?= esc($pesanan['nomor_telepon']) ?></div>
            <?php endif; ?>
            <?php if (! empty($pesanan['catatan'])): ?>
                <div><span class="text-gray-500">Catatan:</span> <?= esc($pesanan['catatan']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Info bayar di kasir (Dine In) -->
        <?php if (($pesanan['metode_bayar'] ?? '') === 'Bayar di Kasir' && ($pesanan['status_bayar'] ?? '') !== 'Sudah Dibayar'): ?>
        <div class="mt-5 flex items-start gap-3 bg-amber-50 dark:bg-stone-700 border border-amber-200 dark:border-stone-600 rounded-xl p-4">
            <span class="text-2xl">🧾</span>
            <div class="text-sm">
                <div class="font-medium">Silakan bayar di kasir</div>
                <p class="text-gray-500 text-xs mt-1">Tunjukkan nomor antrian <strong><?= $pesanan['nomor_antrian'] ?? '-' ?></strong> kepada petugas kasir.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Info: Dine In + QRIS = diantar ke meja -->
        <?php if (($pesanan['tipe_pesanan'] ?? '') === 'Dine In' && ($pesanan['metode_bayar'] ?? '') === 'QRIS' && ($pesanan['status_bayar'] ?? '') === 'Sudah Dibayar'): ?>
        <div class="mt-5 flex items-start gap-3 bg-green-50 dark:bg-stone-700 border border-green-200 dark:border-stone-600 rounded-xl p-4">
            <span class="text-2xl">🍽️</span>
            <div class="text-sm">
                <div class="font-medium">Pembayaran diterima</div>
                <p class="text-gray-500 text-xs mt-1">Pesanan Anda akan diantar langsung ke <strong>meja <?= esc($pesanan['nomor_meja']) ?></strong>. Silakan tunggu di tempat.</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- QR muncul jika metode QRIS -->
        <?php if (($pesanan['metode_bayar'] ?? '') === 'QRIS' && ($pesanan['status_bayar'] ?? '') !== 'Sudah Dibayar'): ?>
        <div class="mt-5 flex flex-col items-center bg-cream dark:bg-stone-700 rounded-xl p-5">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">QR Pembayaran</p>
            <!-- QR ini dapat diganti dengan QRIS asli milik toko Anda -->
            <img src="<?= base_url('assets/qris.svg') ?>" alt="QRIS" class="w-40 h-40 bg-white p-2 rounded-xl shadow">
        </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
