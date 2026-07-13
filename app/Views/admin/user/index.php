<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite">Kelola User</h1>
    <a href="<?= base_url('admin/user/create') ?>" class="px-5 py-2.5 bg-browndark text-white rounded-full hover:bg-brownlite transition shadow">+ Tambah User</a>
</div>

<div x-data="{ open:false, target:'', name:'' }">
    <!-- ===== TABEL (desktop) ===== -->
    <div class="hidden md:block bg-white dark:bg-stone-800 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-cream dark:bg-stone-700 text-left"><tr>
                <th class="p-3">Foto</th><th class="p-3">Nama</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3">Aksi</th>
            </tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr class="border-t border-brownlite/10 hover:bg-cream/40 dark:hover:bg-stone-700/40 transition">
                    <td class="p-3"><img src="<?= base_url('uploads/profil/' . ($u['foto_profil'] ?: 'default.svg')) ?>" class="w-10 h-10 rounded-full object-cover border-2 border-brownlite"></td>
                    <td class="p-3 font-medium"><?= esc($u['nama']) ?></td>
                    <td class="p-3 text-gray-500"><?= esc($u['email']) ?></td>
                    <td class="p-3">
                        <?php
                        $badge = match ($u['role']) {
                            'admin' => ['bg-browndark text-white', '👑 admin'],
                            'kasir' => ['bg-emerald-100 text-emerald-800', '🧾 kasir'],
                            default => ['bg-brownlite/25 text-browndark', '👤 user'],
                        };
                        ?>
                        <span class="px-2.5 py-1 rounded-full text-xs <?= $badge[0] ?>"><?= $badge[1] ?></span>
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="<?= base_url('admin/user/edit/' . $u['id']) ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs hover:bg-blue-600">Edit</a>
                            <?php if ((int)$u['id'] !== (int)session()->get('id')): ?>
                            <button @click="open=true; target='<?= base_url('admin/user/delete/' . $u['id']) ?>'; name='<?= esc($u['nama'], 'js') ?>'"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">Hapus</button>
                            <?php else: ?>
                            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-lg text-xs">Akun Anda</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== KARTU (mobile) ===== -->
    <div class="md:hidden space-y-3">
        <?php foreach ($users as $u): ?>
        <div class="bg-white dark:bg-stone-800 rounded-2xl shadow-sm p-4 flex items-center gap-3">
            <img src="<?= base_url('uploads/profil/' . ($u['foto_profil'] ?: 'default.svg')) ?>" class="w-12 h-12 rounded-full object-cover border-2 border-brownlite shrink-0">
            <div class="flex-1 min-w-0">
                <div class="font-medium truncate"><?= esc($u['nama']) ?></div>
                <div class="text-xs text-gray-500 truncate"><?= esc($u['email']) ?></div>
                <?php
                $badge = match ($u['role']) {
                    'admin' => ['bg-browndark text-white', '👑 admin'],
                    'kasir' => ['bg-emerald-100 text-emerald-800', '🧾 kasir'],
                    default => ['bg-brownlite/25 text-browndark', '👤 user'],
                };
                ?>
                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] <?= $badge[0] ?>"><?= $badge[1] ?></span>
            </div>
            <div class="flex flex-col gap-1.5 shrink-0">
                <a href="<?= base_url('admin/user/edit/' . $u['id']) ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs text-center">Edit</a>
                <?php if ((int)$u['id'] !== (int)session()->get('id')): ?>
                <button @click="open=true; target='<?= base_url('admin/user/delete/' . $u['id']) ?>'; name='<?= esc($u['nama'], 'js') ?>'"
                        class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal hapus -->
    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.outside="open=false" class="bg-white dark:bg-stone-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm">
            <div class="text-4xl text-center mb-3">🗑️</div>
            <h3 class="font-bold text-center text-lg mb-1">Hapus User?</h3>
            <p class="text-center text-gray-500 text-sm mb-5">"<span x-text="name"></span>" akan dihapus permanen.</p>
            <div class="flex gap-3">
                <button @click="open=false" class="flex-1 py-2 border border-gray-300 dark:border-stone-600 rounded-lg">Batal</button>
                <a :href="target" class="flex-1 py-2 bg-red-600 text-white rounded-lg text-center hover:bg-red-700">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
