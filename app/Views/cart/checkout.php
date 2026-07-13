<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="max-w-4xl mx-auto px-4 sm:px-6 py-10"
         x-data="{ tipe: 'Dine In', bayarDine: 'Bayar di Kasir', bayarAntar: 'Bayar di Tempat' }">
    <h1 class="font-serif text-3xl font-bold text-browndark dark:text-brownlite mb-8">Checkout</h1>

    <form action="<?= base_url('cart/checkout') ?>" method="post" class="grid lg:grid-cols-3 gap-6">
        <?= csrf_field() ?>

        <!-- ============ KIRI: PILIHAN PESANAN ============ -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Tipe Pesanan -->
            <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-5 sm:p-6">
                <h3 class="font-semibold mb-4">1. Mau pesan bagaimana?</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    <label :class="tipe === 'Dine In' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                           class="cursor-pointer border-2 rounded-xl p-4 flex items-start gap-3 transition">
                        <input type="radio" name="tipe_pesanan" value="Dine In" x-model="tipe" class="mt-1 accent-[#8B5E3C]">
                        <div>
                            <div class="font-medium">🍽️ Makan di Tempat</div>
                            <p class="text-xs text-gray-500 mt-1">Pesan, duduk di meja Anda, lalu bayar di kasir.</p>
                        </div>
                    </label>
                    <label :class="tipe === 'Antar' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                           class="cursor-pointer border-2 rounded-xl p-4 flex items-start gap-3 transition">
                        <input type="radio" name="tipe_pesanan" value="Antar" x-model="tipe" class="mt-1 accent-[#8B5E3C]">
                        <div>
                            <div class="font-medium">🛵 Diantar</div>
                            <p class="text-xs text-gray-500 mt-1">Pesanan dikirim ke alamat Anda.</p>
                        </div>
                    </label>
                </div>

                <!-- Nomor meja (Dine In) -->
                <div x-show="tipe === 'Dine In'" x-cloak x-transition class="mt-4">
                    <label class="block text-sm font-medium mb-1">Nomor Meja <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_meja" placeholder="Contoh: 12" value="<?= old('nomor_meja') ?>"
                           class="w-full sm:w-48 px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
                    <p class="text-xs text-gray-500 mt-1">Nomor antrian diberikan otomatis setelah pesan.</p>
                </div>

                <!-- Alamat + Telepon (Antar) -->
                <div x-show="tipe === 'Antar'" x-cloak x-transition class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Alamat Pengiriman <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap..."
                                  class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700"><?= old('alamat') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="nomor_telepon" placeholder="Contoh: 0812-3456-7890" value="<?= old('nomor_telepon') ?>"
                               class="w-full sm:w-64 px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700">
                        <p class="text-xs text-gray-500 mt-1">📞 Kurir akan menelepon jika alamat sulit ditemukan di peta.</p>
                    </div>
                </div>
            </div>

            <!-- 2. Pembayaran -->
            <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-5 sm:p-6">
                <h3 class="font-semibold mb-4">2. Mau bayar bagaimana?</h3>

                <!-- ===== DINE IN: Bayar di Kasir ATAU QRIS di meja ===== -->
                <div x-show="tipe === 'Dine In'" x-cloak x-transition>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <label :class="bayarDine === 'Bayar di Kasir' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                               class="cursor-pointer border-2 rounded-xl p-4 flex items-start gap-3 transition">
                            <input type="radio" name="metode_bayar" value="Bayar di Kasir" x-model="bayarDine" class="mt-1 accent-[#8B5E3C]">
                            <div>
                                <div class="font-medium">🧾 Bayar di Kasir</div>
                                <p class="text-xs text-gray-500 mt-1">Bayar di kasir sambil menunjukkan nomor antrian.</p>
                            </div>
                        </label>
                        <label :class="bayarDine === 'QRIS' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                               class="cursor-pointer border-2 rounded-xl p-4 flex items-start gap-3 transition">
                            <input type="radio" name="metode_bayar" value="QRIS" x-model="bayarDine" class="mt-1 accent-[#8B5E3C]">
                            <div>
                                <div class="font-medium">📱 Bayar QRIS di Meja</div>
                                <p class="text-xs text-gray-500 mt-1">Scan & bayar dari meja, pesanan diantar ke meja Anda.</p>
                            </div>
                        </label>
                    </div>

                    <div x-show="bayarDine === 'QRIS'" x-cloak x-transition
                         class="mt-5 flex flex-col items-center bg-cream dark:bg-stone-700 rounded-xl p-5">
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Scan QR untuk membayar</p>
                        <p class="text-xs text-gray-500 mb-3">🍽️ Pesanan akan diantar langsung ke meja Anda</p>
                        <!-- QR ini hanya contoh, dapat diganti dengan QRIS asli milik toko Anda -->
                        <img src="<?= base_url('assets/qris.svg') ?>" alt="QRIS Sweet Bakery" class="w-44 h-44 bg-white p-2 rounded-xl shadow">
                        <p class="text-xs text-gray-500 mt-3">Total: <span class="font-bold text-browndark dark:text-brownlite"><?= rupiah($total) ?></span></p>
                    </div>
                </div>

                <!-- ===== ANTAR: QRIS atau COD ===== -->
                <div x-show="tipe === 'Antar'" x-cloak x-transition>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <label :class="bayarAntar === 'Bayar di Tempat' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                               class="cursor-pointer border-2 rounded-xl p-4 flex items-start gap-3 transition">
                            <input type="radio" name="metode_bayar" value="Bayar di Tempat" x-model="bayarAntar" class="mt-1 accent-[#8B5E3C]">
                            <div>
                                <div class="font-medium">💵 Bayar di Tempat</div>
                                <p class="text-xs text-gray-500 mt-1">Tunai saat pesanan tiba (COD).</p>
                            </div>
                        </label>
                        <label :class="bayarAntar === 'QRIS' ? 'border-browndark bg-cream dark:bg-stone-700 ring-2 ring-brownlite' : 'border-brownlite/30'"
                               class="cursor-pointer border-2 rounded-xl p-4 flex items-start gap-3 transition">
                            <input type="radio" name="metode_bayar" value="QRIS" x-model="bayarAntar" class="mt-1 accent-[#8B5E3C]">
                            <div>
                                <div class="font-medium">📱 Bayar via QRIS</div>
                                <p class="text-xs text-gray-500 mt-1">Scan QR, bayar dari mana saja.</p>
                            </div>
                        </label>
                    </div>

                    <div x-show="bayarAntar === 'QRIS'" x-cloak x-transition
                         class="mt-5 flex flex-col items-center bg-cream dark:bg-stone-700 rounded-xl p-5">
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Scan QR di bawah untuk membayar</p>
                        <!-- QR ini hanya contoh, dapat diganti dengan QRIS asli milik toko Anda -->
                        <img src="<?= base_url('assets/qris.svg') ?>" alt="QRIS Sweet Bakery" class="w-44 h-44 bg-white p-2 rounded-xl shadow">
                        <p class="text-xs text-gray-500 mt-3">Total: <span class="font-bold text-browndark dark:text-brownlite"><?= rupiah($total) ?></span></p>
                    </div>
                </div>
            </div>

            <!-- 3. Catatan -->
            <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-5 sm:p-6">
                <h3 class="font-semibold mb-3">3. Catatan (opsional)</h3>
                <textarea name="catatan" rows="2" placeholder="Contoh: jangan terlalu manis, tanpa kacang..."
                          class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 focus:ring-2 focus:ring-brownlite outline-none dark:bg-stone-700"><?= old('catatan') ?></textarea>
            </div>
        </div>

        <!-- ============ KANAN: RINGKASAN ============ -->
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-5 sm:p-6 h-fit lg:sticky lg:top-24">
            <h3 class="font-serif text-lg font-bold text-browndark dark:text-brownlite mb-4">Ringkasan Pesanan</h3>
            <div class="divide-y divide-brownlite/20 max-h-64 overflow-y-auto">
                <?php foreach ($cart as $item): ?>
                <div class="flex justify-between py-2 text-sm gap-2">
                    <span class="flex-1">
                        <?= esc($item['nama']) ?>
                        <?php if (! empty($item['varian'])): ?>
                            <span class="text-[11px] <?= $item['varian'] === 'Hot' ? 'text-orange-600' : 'text-sky-600' ?>">
                                (<?= $item['varian'] === 'Hot' ? '🔥 Hot' : '🧊 Iced' ?>)
                            </span>
                        <?php endif; ?>
                        <span class="text-gray-400">× <?= $item['jumlah'] ?></span>
                    </span>
                    <span class="whitespace-nowrap"><?= rupiah($item['harga'] * $item['jumlah']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t border-brownlite/30">
                <span>Total</span><span class="text-browndark dark:text-brownlite"><?= rupiah($total) ?></span>
            </div>

            <div class="mt-4 text-xs text-gray-500 space-y-1">
                <p>Tipe: <span class="font-medium text-browndark dark:text-brownlite" x-text="tipe"></span></p>
                <p>Bayar: <span class="font-medium text-browndark dark:text-brownlite"
                                x-text="tipe === 'Dine In' ? bayarDine : bayarAntar"></span></p>
                <p x-show="tipe === 'Dine In' && bayarDine === 'QRIS'" x-cloak class="text-green-600">🍽️ Diantar ke meja Anda</p>
            </div>

            <button type="submit" class="w-full mt-5 py-3 bg-browndark text-white rounded-full font-medium hover:bg-brownlite transition">
                Konfirmasi Pesanan
            </button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>
