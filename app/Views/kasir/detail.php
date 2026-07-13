<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="flex flex-wrap items-center gap-3 mb-6">
    <a href="<?= base_url('kasir/pesanan') ?>" class="text-brownlite hover:underline">← Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Detail Pesanan #<?= $pesanan['id'] ?></h1>
    <a href="<?= base_url('kasir/struk/' . $pesanan['id']) ?>" target="_blank"
       class="ml-auto px-4 py-2 bg-browndark text-white rounded-full text-sm hover:bg-brownlite transition">🧾 Cetak Struk</a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Kartu antrian -->
        <div class="bg-gradient-to-br from-browndark to-[#6f4930] text-cream rounded-2xl shadow p-5 flex items-center gap-5">
            <div class="text-center">
                <p class="text-cream/70 text-xs">Antrian</p>
                <div class="font-serif text-4xl font-bold"><?= $pesanan['nomor_antrian'] ?? '-' ?></div>
            </div>
            <div class="border-l border-cream/25 pl-5 space-y-1 text-sm">
                <div><?= ($pesanan['tipe_pesanan'] ?? 'Antar') === 'Dine In' ? '🍽️ Makan di Tempat' : '🛵 Diantar' ?></div>
                <?php if (! empty($pesanan['nomor_meja'])): ?>
                    <div>🪑 Meja <strong><?= esc($pesanan['nomor_meja']) ?></strong></div>
                <?php endif; ?>
                <div class="text-cream/70 text-xs"><?= date('d M Y, H:i', strtotime($pesanan['created_at'])) ?></div>
            </div>
        </div>

        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-5 sm:p-6">
            <h2 class="font-semibold mb-4">Item Pesanan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-gray-500 border-b border-brownlite/20"><tr>
                        <th class="pb-2">Produk</th><th class="pb-2">Harga</th><th class="pb-2">Qty</th><th class="pb-2 text-right">Subtotal</th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($detail as $d): ?>
                        <tr class="border-b border-brownlite/10">
                            <td class="py-3">
                                <?= esc($d['nama_produk']) ?>
                                <?php if (! empty($d['varian'])): ?>
                                    <span class="ml-1 px-2 py-0.5 rounded-full text-[11px] <?= $d['varian'] === 'Hot' ? 'bg-orange-100 text-orange-800' : 'bg-sky-100 text-sky-800' ?>">
                                        <?= $d['varian'] === 'Hot' ? '🔥 Hot' : '🧊 Iced' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 whitespace-nowrap"><?= rupiah($d['harga']) ?></td>
                            <td class="py-3"><?= $d['jumlah'] ?></td>
                            <td class="py-3 text-right whitespace-nowrap"><?= rupiah($d['subtotal']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t border-brownlite/30">
                <span>Total</span><span class="text-browndark dark:text-brownlite"><?= rupiah($pesanan['total_harga']) ?></span>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-5 sm:p-6">
            <h2 class="font-semibold mb-4">Informasi</h2>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Pelanggan:</span> <?= esc($pesanan['nama_user'] ?? '-') ?></p>
                <?php if (! empty($pesanan['nomor_telepon'])): ?>
                    <p><span class="text-gray-500">Telepon:</span>
                        <a href="tel:<?= esc($pesanan['nomor_telepon']) ?>" class="text-brownlite font-medium hover:underline">📞 <?= esc($pesanan['nomor_telepon']) ?></a>
                    </p>
                <?php endif; ?>
                <?php if (! empty($pesanan['alamat'])): ?>
                    <p><span class="text-gray-500">Alamat:</span><br><?= esc($pesanan['alamat']) ?></p>
                <?php endif; ?>
                <?php if (! empty($pesanan['catatan'])): ?>
                    <p><span class="text-gray-500">Catatan:</span><br><?= esc($pesanan['catatan']) ?></p>
                <?php endif; ?>
            </div>

            <form action="<?= base_url('kasir/status/' . $pesanan['id']) ?>" method="post" class="mt-5">
                <?= csrf_field() ?>
                <label class="block text-sm font-medium mb-1">Ubah Status</label>
                <div class="flex gap-2">
                    <select name="status" class="flex-1 px-3 py-2 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
                        <?php foreach (['Menunggu','Diproses','Dikirim','Selesai'] as $s): ?>
                            <option value="<?= $s ?>" <?= $pesanan['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="px-4 py-2 bg-browndark text-white rounded-lg hover:bg-brownlite transition">Update</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-5 sm:p-6">
            <h2 class="font-semibold mb-3">Pembayaran</h2>
            <div class="text-sm space-y-2">
                <div class="flex justify-between"><span class="text-gray-500">Metode</span><span class="font-medium"><?= esc($pesanan['metode_bayar'] ?? '-') ?></span></div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2 py-0.5 rounded-full text-xs <?= ($pesanan['status_bayar'] ?? '') === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= esc($pesanan['status_bayar'] ?? 'Belum Dibayar') ?>
                    </span>
                </div>
            </div>
            <form action="<?= base_url('kasir/bayar/' . $pesanan['id']) ?>" method="post" class="mt-4">
                <?= csrf_field() ?>
                <?php $sudah = ($pesanan['status_bayar'] ?? '') === 'Sudah Dibayar'; ?>
                <input type="hidden" name="status_bayar" value="<?= $sudah ? 'Belum Dibayar' : 'Sudah Dibayar' ?>">
                <button class="w-full py-2 rounded-lg text-white transition <?= $sudah ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' ?>">
                    <?= $sudah ? 'Batalkan (Belum Dibayar)' : '✓ Terima Pembayaran' ?>
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
