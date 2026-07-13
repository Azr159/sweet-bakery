<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<section class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="font-serif text-3xl font-bold text-browndark dark:text-brownlite mb-8">🛒 Keranjang Belanja</h1>

    <?php if (empty($cart)): ?>
        <div class="text-center py-20 bg-white dark:bg-stone-800 rounded-2xl shadow">
            <div class="text-6xl mb-4">🥐</div>
            <p class="text-gray-500">Keranjang Anda masih kosong.</p>
            <a href="<?= base_url('produk') ?>" class="inline-block mt-4 px-6 py-2.5 bg-browndark text-white rounded-full hover:bg-brownlite transition">Mulai Belanja</a>
        </div>
    <?php else: ?>
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <?php foreach ($cart as $key => $item): ?>
            <div class="flex items-center gap-3 sm:gap-4 bg-white dark:bg-stone-800 rounded-2xl shadow p-3 sm:p-4">
                <img src="<?= base_url('uploads/produk/' . $item['gambar']) ?>" class="w-20 h-20 object-cover rounded-xl shrink-0">
                <div class="flex-1 min-w-0">
                    <h3 class="font-serif font-bold text-browndark dark:text-brownlite truncate"><?= esc($item['nama']) ?></h3>

                    <?php if (! empty($item['varian'])): ?>
                        <!-- Label varian minuman (Hot / Iced) -->
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[11px] <?= $item['varian'] === 'Hot' ? 'bg-orange-100 text-orange-800' : 'bg-sky-100 text-sky-800' ?>">
                            <?= $item['varian'] === 'Hot' ? '🔥 Hot' : '🧊 Iced' ?>
                        </span>
                    <?php endif; ?>

                    <p class="text-sm text-gray-500 mt-1"><?= rupiah($item['harga']) ?></p>
                    <form action="<?= base_url('cart/update/' . $key) ?>" method="post" class="flex items-center gap-2 mt-2">
                        <?= csrf_field() ?>
                        <input type="number" name="jumlah" value="<?= $item['jumlah'] ?>" min="0"
                               class="w-16 px-2 py-1 border border-brownlite/40 rounded dark:bg-stone-700 outline-none">
                        <button class="text-sm text-brownlite hover:underline">Update</button>
                    </form>
                </div>
                <div class="text-right shrink-0">
                    <div class="font-bold text-browndark dark:text-brownlite text-sm sm:text-base"><?= rupiah($item['harga'] * $item['jumlah']) ?></div>
                    <a href="<?= base_url('cart/remove/' . $key) ?>" class="text-red-500 text-sm hover:underline">Hapus</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 h-fit lg:sticky lg:top-24">
            <h3 class="font-serif text-lg font-bold text-browndark dark:text-brownlite mb-4">Ringkasan</h3>
            <div class="flex justify-between mb-2 text-sm"><span>Subtotal</span><span><?= rupiah($total) ?></span></div>
            <div class="flex justify-between mb-4 text-sm"><span>Ongkir</span><span class="text-green-600">Gratis</span></div>
            <hr class="border-brownlite/30 mb-4">
            <div class="flex justify-between font-bold text-lg mb-6"><span>Total</span><span class="text-browndark dark:text-brownlite"><?= rupiah($total) ?></span></div>
            <a href="<?= base_url('cart/checkout') ?>" class="block text-center w-full py-3 bg-browndark text-white rounded-full font-medium hover:bg-brownlite transition">Checkout</a>
        </div>
    </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
