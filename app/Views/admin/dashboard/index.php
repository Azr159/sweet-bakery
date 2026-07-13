<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-browndark dark:text-brownlite">Halo, <?= esc(session()->get('nama')) ?> 👋</h1>
    <p class="text-gray-500 text-sm mt-1">Berikut ringkasan toko hari ini.</p>
</div>

<!-- Kartu statistik -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    <?php
    $cards = [
        ['🍞', 'Jumlah Produk',    $jumlahProduk,              'from-amber-400 to-amber-500'],
        ['👥', 'Jumlah User',      $jumlahUser,                'from-sky-400 to-sky-500'],
        ['🧾', 'Jumlah Pesanan',   $jumlahPesanan,             'from-violet-400 to-violet-500'],
        ['💰', 'Total Pendapatan', rupiah($totalPendapatan),   'from-emerald-400 to-emerald-500'],
    ];
    foreach ($cards as [$icon, $label, $value, $grad]): ?>
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm hover:shadow-lg transition p-4 sm:p-5">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br <?= $grad ?> flex items-center justify-center text-xl shadow mb-3">
            <?= $icon ?>
        </div>
        <div class="text-lg sm:text-2xl font-bold text-browndark dark:text-brownlite break-words"><?= $value ?></div>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5"><?= $label ?></p>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Grafik -->
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-semibold mb-4">Pesanan per Status</h2>
        <canvas id="chartPesanan" height="180"></canvas>
    </div>

    <!-- Pesanan terbaru -->
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">Pesanan Terbaru</h2>
            <a href="<?= base_url('admin/pesanan') ?>" class="text-xs text-brownlite hover:underline">Lihat semua →</a>
        </div>
        <?php if (empty($pesananTerbaru)): ?>
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">📭</div>
                <p class="text-sm">Belum ada pesanan.</p>
            </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($pesananTerbaru as $p): ?>
            <a href="<?= base_url('admin/pesanan/detail/' . $p['id']) ?>"
               class="flex items-center justify-between gap-2 text-sm border-b border-brownlite/10 pb-2.5 hover:bg-cream/60 dark:hover:bg-stone-700/50 rounded-lg px-2 -mx-2 transition">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-cream dark:bg-stone-700 flex items-center justify-center font-bold text-browndark dark:text-brownlite text-xs shrink-0">
                        #<?= $p['id'] ?>
                    </div>
                    <div class="min-w-0">
                        <div class="font-medium truncate"><?= esc($p['nama_user'] ?? 'User') ?></div>
                        <div class="text-xs text-gray-500"><?= rupiah($p['total_harga']) ?></div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap <?= badge_status($p['status']) ?>"><?= $p['status'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('chartPesanan');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($chartData)) ?>,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: <?= json_encode(array_values($chartData)) ?>,
                backgroundColor: ['#FCD34D', '#60A5FA', '#C084FC', '#4ADE80'],
                borderRadius: 10,
                maxBarThickness: 60,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(139,94,60,.1)' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
<?= $this->endSection() ?>
