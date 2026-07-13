<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin - Sweet Bakery') ?></title>
    <link rel="icon" href="<?= base_url('assets/logo.svg') ?>" type="image/svg+xml">

    <!-- Terapkan tema tersimpan sebelum render (tidak reset antar halaman) -->
    <script>
        if (localStorage.getItem('sweetbakery-dark') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {
                colors: { cream:'#FFF8F0', brownlite:'#D4A373', browndark:'#8B5E3C' },
                fontFamily: { serif: ['Georgia', 'serif'] },
            } }
        }
        function toggleDark() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('sweetbakery-dark', isDark);
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none !important}</style>
    <?= $this->renderSection('head') ?>
</head>
<body class="bg-cream dark:bg-stone-900 text-gray-800 dark:text-gray-100 min-h-screen"
      x-data="{ sidebar: false }">

<?php $seg = service('uri')->getSegment(2); ?>

<!-- Overlay saat sidebar terbuka di HP -->
<div x-show="sidebar" x-cloak @click="sidebar=false" x-transition.opacity
     class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

<!-- ============ SIDEBAR ============ -->
<aside :class="sidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-browndark to-[#6f4930] text-cream z-40 transform transition-transform duration-300 flex flex-col">
    <div class="p-5 flex items-center gap-2 border-b border-cream/20">
        <!-- Logo ini dapat diganti dengan logo toko Anda -->
        <img src="<?= base_url('assets/logo.svg') ?>" class="w-9 h-9 bg-cream rounded-full p-1" alt="Logo">
        <span class="font-serif font-bold text-lg"><?= session()->get('role') === 'kasir' ? 'Sweet Kasir' : 'Sweet Admin' ?></span>
        <button @click="sidebar=false" class="ml-auto lg:hidden text-cream/70 hover:text-white">✕</button>
    </div>

    <nav class="p-4 space-y-1 text-sm flex-1 overflow-y-auto">
        <?php
        // Menu berbeda untuk ADMIN dan KASIR
        if (session()->get('role') === 'kasir') {
            $menus = [
                ['dashboard', '📊', 'Dashboard', 'kasir/dashboard'],
                ['pesanan',   '🧾', 'Kelola Pesanan', 'kasir/pesanan'],
            ];
        } else {
            $menus = [
                ['dashboard', '📊', 'Dashboard', 'admin/dashboard'],
                ['produk',    '🍞', 'Produk',    'admin/produk'],
                ['kategori',  '🏷️', 'Kategori',  'admin/kategori'],
                ['user',      '👥', 'User',      'admin/user'],
                ['pesanan',   '🧾', 'Pesanan',   'admin/pesanan'],
                ['rekap',     '📥', 'Rekap Data','admin/rekap'],
            ];
        }
        foreach ($menus as [$key, $icon, $label, $url]):
            $active = ($seg === $key);
        ?>
        <a href="<?= base_url($url) ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= $active ? 'bg-cream text-browndark font-semibold shadow' : 'hover:bg-white/10' ?>">
            <span class="text-base"><?= $icon ?></span> <?= $label ?>
        </a>
        <?php endforeach; ?>

        <hr class="border-cream/20 my-3">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition">🏠 Lihat Website</a>
        <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-500/30 text-red-200 transition">🚪 Logout</a>
    </nav>

    <div class="p-4 border-t border-cream/20 text-xs text-cream/60">
        &copy; <?= date('Y') ?> Sweet Bakery
    </div>
</aside>

<!-- ============ KONTEN ============ -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Topbar -->
    <header class="bg-white/90 dark:bg-stone-800/90 backdrop-blur shadow-sm sticky top-0 z-20">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3">
            <button @click="sidebar = !sidebar" class="p-2 rounded-lg hover:bg-cream dark:hover:bg-stone-700 lg:hidden">☰</button>
            <div class="hidden lg:block font-serif font-bold text-browndark dark:text-brownlite"><?= esc($title ?? 'Admin') ?></div>
            <div class="flex items-center gap-3">
                <button onclick="toggleDark()" class="p-2 rounded-full hover:bg-cream dark:hover:bg-stone-700">
                    <span class="dark:hidden">🌙</span><span class="hidden dark:inline">☀️</span>
                </button>
                <div class="flex items-center gap-2">
                    <img src="<?= base_url('uploads/profil/' . (session()->get('foto') ?: 'default.svg')) ?>" class="w-9 h-9 rounded-full object-cover border-2 border-brownlite">
                    <span class="font-medium hidden sm:inline"><?= esc(session()->get('nama')) ?></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Toast -->
    <?php $err = session()->getFlashdata('error'); $suc = session()->getFlashdata('success'); ?>
    <?php if ($err || $suc): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show=false, 4000)" x-show="show" x-cloak x-transition
         class="fixed top-16 right-4 left-4 sm:left-auto z-50 sm:max-w-sm">
        <div class="<?= $suc?'bg-green-600':'bg-red-600' ?> text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
            <span><?= $suc?'✅':'⚠️' ?></span><span class="text-sm"><?= esc($suc ?: $err) ?></span>
            <button @click="show=false" class="ml-auto">✕</button>
        </div>
    </div>
    <?php endif; ?>

    <main class="p-4 sm:p-6 flex-1">
        <?= $this->renderSection('content') ?>
    </main>
</div>

<?= $this->renderSection('scripts') ?>
</body>
</html>
