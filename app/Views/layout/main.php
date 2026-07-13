<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sweet Bakery') ?></title>
    <link rel="icon" href="<?= base_url('assets/logo.svg') ?>" type="image/svg+xml">

    <!-- ============================================================
         DARK MODE: dijalankan SEBELUM halaman digambar agar tema
         tersimpan (tidak berkedip & tidak reset saat pindah halaman)
         ============================================================ -->
    <script>
        if (localStorage.getItem('sweetbakery-dark') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- TailwindCSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi warna tema Sweet Bakery (mudah diganti sesuai kebutuhan)
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cream:     '#FFF8F0',
                        brownlite: '#D4A373', // Coklat muda
                        browndark: '#8B5E3C', // Coklat tua
                    },
                    fontFamily: { serif: ['Georgia', 'serif'] },
                    keyframes: {
                        fadeIn:  { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        slideUp: { '0%': { opacity: 0, transform: 'translateY(30px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                    },
                    animation: {
                        fadeIn:  'fadeIn .8s ease-out',
                        slideUp: 'slideUp .8s ease-out',
                    }
                }
            }
        }

        // Fungsi ganti tema (dipakai tombol 🌙 / ☀️ di navbar)
        function toggleDark() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('sweetbakery-dark', isDark);
        }
    </script>

    <!-- Alpine.js untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html { scroll-behavior: smooth; } /* Smooth Scroll */
        /* Background pattern bakery (dapat diganti sesuai kebutuhan) */
        .bakery-pattern {
            background-color: #FFF8F0;
            background-image: radial-gradient(#D4A373 0.6px, transparent 0.6px);
            background-size: 22px 22px;
        }
        .dark .bakery-pattern { background-color: #1c1917; background-image: radial-gradient(#57432e 0.6px, transparent 0.6px); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bakery-pattern text-gray-800 dark:text-gray-100 dark:bg-stone-900 min-h-screen flex flex-col transition-colors duration-300">

<!-- ================= NAVBAR (sticky + transparan) ================= -->
<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset > 30)"
     :class="scrolled ? 'bg-cream/95 dark:bg-stone-800/95 shadow-md backdrop-blur' : 'bg-cream/80 dark:bg-stone-900/80 backdrop-blur'"
     class="fixed top-0 inset-x-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo toko dapat diganti dengan logo milik sendiri -->
            <a href="<?= base_url('/') ?>" class="flex items-center gap-2 group">
                <img src="<?= base_url('assets/logo.svg') ?>" alt="Logo" class="w-9 h-9 group-hover:rotate-12 transition-transform">
                <span class="font-serif text-lg sm:text-xl font-bold text-browndark dark:text-brownlite">Sweet Bakery</span>
            </a>

            <div class="hidden md:flex items-center gap-6">
                <a href="<?= base_url('/') ?>" class="hover:text-brownlite transition">Beranda</a>
                <a href="<?= base_url('produk') ?>" class="hover:text-brownlite transition">Produk</a>
                <a href="<?= base_url('tentang') ?>" class="hover:text-brownlite transition">Tentang</a>

                <!-- Dark mode toggle (tersimpan di localStorage) -->
                <button onclick="toggleDark()" class="p-2 rounded-full hover:bg-brownlite/20 transition" title="Ganti Tema">
                    <span class="dark:hidden">🌙</span><span class="hidden dark:inline">☀️</span>
                </button>

                <?php if (session()->get('isLoggedIn')): ?>
                    <?php if (session()->get('role') === 'user'): ?>
                        <?php $jml = is_array(session()->get('cart')) ? count(session()->get('cart')) : 0; ?>
                        <a href="<?= base_url('cart') ?>" class="relative hover:text-brownlite transition">
                            🛒 Keranjang
                            <?php if ($jml > 0): ?>
                                <span class="absolute -top-2 -right-3 bg-red-600 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center"><?= $jml ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <div x-data="{ p: false }" class="relative">
                        <button @click="p = !p" class="flex items-center gap-2 hover:text-brownlite transition">
                            <img src="<?= base_url('uploads/profil/' . (session()->get('foto') ?: 'default.svg')) ?>" class="w-8 h-8 rounded-full object-cover border-2 border-brownlite">
                            <span><?= esc(session()->get('nama')) ?></span>
                        </button>
                        <div x-show="p" x-cloak @click.outside="p=false" x-transition class="absolute right-0 mt-2 w-44 bg-white dark:bg-stone-800 rounded-xl shadow-xl py-2">
                            <?php if (session()->get('role') === 'admin'): ?>
                                <a href="<?= base_url('admin/dashboard') ?>" class="block px-4 py-2 hover:bg-cream dark:hover:bg-stone-700">Dashboard Admin</a>
                            <?php else: ?>
                                <a href="<?= base_url('user/dashboard') ?>" class="block px-4 py-2 hover:bg-cream dark:hover:bg-stone-700">Dashboard</a>
                                <a href="<?= base_url('user/profil') ?>" class="block px-4 py-2 hover:bg-cream dark:hover:bg-stone-700">Profil</a>
                                <a href="<?= base_url('user/riwayat') ?>" class="block px-4 py-2 hover:bg-cream dark:hover:bg-stone-700">Riwayat</a>
                            <?php endif; ?>
                            <a href="<?= base_url('auth/logout') ?>" class="block px-4 py-2 text-red-600 hover:bg-cream dark:hover:bg-stone-700">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="hover:text-brownlite transition">Login</a>
                    <a href="<?= base_url('auth/register') ?>" class="px-4 py-2 bg-browndark text-white rounded-full hover:bg-brownlite transition">Daftar</a>
                <?php endif; ?>
            </div>

            <!-- Tombol mobile -->
            <div class="flex items-center gap-1 md:hidden">
                <button onclick="toggleDark()" class="p-2 rounded-full hover:bg-brownlite/20 transition">
                    <span class="dark:hidden">🌙</span><span class="hidden dark:inline">☀️</span>
                </button>
                <?php if (session()->get('isLoggedIn') && session()->get('role') === 'user'): ?>
                    <a href="<?= base_url('cart') ?>" class="p-2 text-xl">🛒</a>
                <?php endif; ?>
                <button @click="open = !open" class="p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div x-show="open" x-cloak x-transition class="md:hidden bg-cream dark:bg-stone-800 px-5 py-4 space-y-3 shadow-lg border-t border-brownlite/20">
        <?php if (session()->get('isLoggedIn')): ?>
            <div class="flex items-center gap-3 pb-3 border-b border-brownlite/20">
                <img src="<?= base_url('uploads/profil/' . (session()->get('foto') ?: 'default.svg')) ?>" class="w-10 h-10 rounded-full object-cover border-2 border-brownlite">
                <div>
                    <div class="font-medium"><?= esc(session()->get('nama')) ?></div>
                    <div class="text-xs text-gray-500"><?= esc(session()->get('role')) ?></div>
                </div>
            </div>
        <?php endif; ?>
        <a href="<?= base_url('/') ?>" class="block py-1.5">Beranda</a>
        <a href="<?= base_url('produk') ?>" class="block py-1.5">Produk</a>
        <a href="<?= base_url('tentang') ?>" class="block py-1.5">Tentang</a>
        <?php if (session()->get('isLoggedIn')): ?>
            <?php if (session()->get('role') === 'admin'): ?>
                <a href="<?= base_url('admin/dashboard') ?>" class="block py-1.5">Dashboard Admin</a>
            <?php else: ?>
                <a href="<?= base_url('cart') ?>" class="block py-1.5">Keranjang</a>
                <a href="<?= base_url('user/dashboard') ?>" class="block py-1.5">Dashboard</a>
                <a href="<?= base_url('user/profil') ?>" class="block py-1.5">Profil</a>
                <a href="<?= base_url('user/riwayat') ?>" class="block py-1.5">Riwayat</a>
            <?php endif; ?>
            <a href="<?= base_url('auth/logout') ?>" class="block py-1.5 text-red-600">Logout</a>
        <?php else: ?>
            <div class="flex gap-2 pt-2">
                <a href="<?= base_url('auth/login') ?>" class="flex-1 text-center py-2 border border-browndark text-browndark dark:text-brownlite dark:border-brownlite rounded-full">Login</a>
                <a href="<?= base_url('auth/register') ?>" class="flex-1 text-center py-2 bg-browndark text-white rounded-full">Daftar</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<!-- ================= TOAST NOTIFICATION ================= -->
<?php $err = session()->getFlashdata('error'); $suc = session()->getFlashdata('success'); ?>
<?php if ($err || $suc): ?>
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-cloak x-transition
     class="fixed top-20 right-4 left-4 sm:left-auto z-50 sm:max-w-sm">
    <div class="<?= $suc ? 'bg-green-600' : 'bg-red-600' ?> text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <span><?= $suc ? '✅' : '⚠️' ?></span>
        <span class="text-sm"><?= esc($suc ?: $err) ?></span>
        <button @click="show=false" class="ml-auto">✕</button>
    </div>
</div>
<?php endif; ?>

<main class="flex-grow pt-16">
    <?= $this->renderSection('content') ?>
</main>

<!-- ================= FOOTER MODERN ================= -->
<footer class="bg-browndark text-cream mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-8">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <!-- Logo ini dapat diganti dengan logo toko Anda -->
                <img src="<?= base_url('assets/logo.svg') ?>" class="w-8 h-8 bg-cream rounded-full p-1" alt="Logo">
                <span class="font-serif text-xl font-bold">Sweet Bakery</span>
            </div>
            <p class="text-cream/80 text-sm">Roti & kue premium dibuat setiap hari dengan bahan pilihan dan penuh cinta. 🍞🥐</p>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Kontak</h4>
            <p class="text-cream/80 text-sm">📍 Jl. Roti Manis No. 1<br>📞 0812-3456-7890<br>✉️ azir@sweetbakery.com</p>
        </div>
    </div>
    <div class="border-t border-cream/20 py-4 text-center text-cream/70 text-sm">
        &copy; <?= date('Y') ?> Sweet Bakery. All rights reserved
    </div>
</footer>

</body>
</html>
