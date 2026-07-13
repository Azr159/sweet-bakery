<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite mb-6">Kelola Kategori</h1>

<div class="grid md:grid-cols-3 gap-6">
    <!-- Form tambah -->
    <div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 h-fit">
        <h2 class="font-semibold mb-4">Tambah Kategori</h2>
        <form action="<?= base_url('admin/kategori/store') ?>" method="post" class="space-y-3">
            <?= csrf_field() ?>
            <input type="text" name="nama_kategori" placeholder="Nama kategori" required
                   class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            <button class="w-full py-2.5 bg-browndark text-white rounded-lg hover:bg-brownlite transition">Tambah</button>
        </form>
    </div>

    <!-- Daftar -->
    <div class="md:col-span-2 bg-white dark:bg-stone-800 rounded-2xl shadow overflow-hidden"
         x-data="{ open:false, target:'', name:'', editId:null, editName:'' }">
        <table class="w-full text-sm">
            <thead class="bg-cream dark:bg-stone-700 text-left"><tr>
                <th class="p-3">#</th><th class="p-3">Nama Kategori</th><th class="p-3">Aksi</th>
            </tr></thead>
            <tbody>
                <?php if (empty($kategori)): ?>
                    <tr><td colspan="3" class="p-6 text-center text-gray-400">Belum ada kategori.</td></tr>
                <?php else: foreach ($kategori as $i => $k): ?>
                <tr class="border-t border-brownlite/10">
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3">
                        <span x-show="editId !== <?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></span>
                        <form x-show="editId === <?= $k['id'] ?>" action="<?= base_url('admin/kategori/update/' . $k['id']) ?>" method="post" class="flex gap-2" style="display:none">
                            <?= csrf_field() ?>
                            <input type="text" name="nama_kategori" x-model="editName" class="px-2 py-1 border rounded dark:bg-stone-700 dark:text-white">
                            <button class="px-3 py-1 bg-green-500 text-white rounded text-xs">Simpan</button>
                        </form>
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <button @click="editId = (editId === <?= $k['id'] ?> ? null : <?= $k['id'] ?>); editName='<?= esc($k['nama_kategori'], 'js') ?>'"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs hover:bg-blue-600">Edit</button>
                            <button @click="open=true; target='<?= base_url('admin/kategori/delete/' . $k['id']) ?>'; name='<?= esc($k['nama_kategori'], 'js') ?>'"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">Hapus</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>

        <!-- Modal hapus -->
        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none">
            <div @click.outside="open=false" class="bg-white dark:bg-stone-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">
                <div class="text-4xl text-center mb-3">🗑️</div>
                <h3 class="font-bold text-center text-lg mb-1">Hapus Kategori?</h3>
                <p class="text-center text-gray-500 text-sm mb-5">"<span x-text="name"></span>" akan dihapus.</p>
                <div class="flex gap-3">
                    <button @click="open=false" class="flex-1 py-2 border border-gray-300 rounded-lg">Batal</button>
                    <a :href="target" class="flex-1 py-2 bg-red-600 text-white rounded-lg text-center hover:bg-red-700">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
