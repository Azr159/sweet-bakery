<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="font-serif text-3xl font-bold text-browndark dark:text-brownlite mb-2">Halo, <?= esc(session()->get('nama')) ?> 👋</h1>
    <p class="text-gray-500 mb-8">Selamat datang di dashboard Anda.</p>

    <div class="grid sm:grid-cols-3 gap-6 mb-10">
        <a href="<?= base_url('user/riwayat') ?>" class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 hover:shadow-xl transition">
            <div class="text-3xl mb-2">🧾</div>
            <div class="text-2xl font-bold text-browndark dark:text-brownlite"><?= $totalPesanan ?></div>
            <p class="text-sm text-gray-500">Total Pesanan</p>
        </a>
        <a href="<?= base_url('produk') ?>" class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 hover:shadow-xl transition">
            <div class="text-3xl mb-2">🍞</div>
            <div class="text-lg font-bold text-browndark dark:text-brownlite">Belanja</div>
            <p class="text-sm text-gray-500">Lihat produk terbaru</p>
        </a>
        <a href="<?= base_url('user/profil') ?>" class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 hover:shadow-xl transition">
            <div class="text-3xl mb-2">👤</div>
            <div class="text-lg font-bold text-browndark dark:text-brownlite">Profil</div>
            <p class="text-sm text-gray-500">Kelola akun Anda</p>
        </a>
    </div>

    <h2 class="font-serif text-xl font-bold text-browndark dark:text-brownlite mb-4">Pesanan Terakhir</h2>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow overflow-hidden">
        <?php if (empty($terakhir)): ?>
            <p class="p-6 text-gray-400 text-center">Belum ada pesanan.</p>
        <?php else: ?>
        <table class="w-full text-sm">
            <thead class="bg-cream dark:bg-stone-700 text-left"><tr>
                <th class="p-3">ID</th><th class="p-3">Total</th><th class="p-3">Status</th><th class="p-3">Tanggal</th>
            </tr></thead>
            <tbody>
                <?php foreach ($terakhir as $p): ?>
                <tr class="border-t border-brownlite/10 hover:bg-cream/50 dark:hover:bg-stone-700/50">
                    <td class="p-3">#<?= $p['id'] ?></td>
                    <td class="p-3"><?= rupiah($p['total_harga']) ?></td>
                    <td class="p-3"><span class="px-2 py-1 rounded-full text-xs <?= badge_status($p['status']) ?>"><?= $p['status'] ?></span></td>
                    <td class="p-3"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
