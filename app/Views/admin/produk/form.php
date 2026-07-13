<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<?php $isEdit = $produk !== null; ?>
<div class="flex items-center gap-3 mb-6">
    <a href="<?= base_url('admin/produk') ?>" class="text-brownlite hover:underline">← Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-browndark dark:text-brownlite"><?= $isEdit ? 'Edit' : 'Tambah' ?> Produk</h1>
</div>

<div class="bg-white dark:bg-stone-800 rounded-2xl shadow p-6 max-w-2xl"
     x-data="{ preview: '<?= $isEdit ? base_url('uploads/produk/' . $produk['gambar_produk']) : '' ?>' }">
    <form action="<?= $isEdit ? base_url('admin/produk/update/' . $produk['id']) : base_url('admin/produk/store') ?>"
          method="post" enctype="multipart/form-data" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block text-sm font-medium mb-1">Nama Produk</label>
            <input type="text" name="nama_produk" value="<?= old('nama_produk', $isEdit ? $produk['nama_produk'] : '') ?>" required
                   class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori_id" class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
                    <option value="">- Pilih Kategori -</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($isEdit && $produk['kategori_id'] == $k['id']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="<?= old('harga', $isEdit ? $produk['harga'] : '') ?>" required min="0"
                       class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Stok</label>
            <input type="number" name="stok" value="<?= old('stok', $isEdit ? $produk['stok'] : '0') ?>" required min="0"
                   class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-brownlite/40 outline-none dark:bg-stone-700"><?= old('deskripsi', $isEdit ? $produk['deskripsi'] : '') ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Gambar Produk <?= $isEdit ? '(kosongkan jika tidak diganti)' : '' ?></label>
            <input type="file" name="gambar" accept="image/*"
                   @change="preview = URL.createObjectURL($event.target.files[0])"
                   class="w-full text-sm">
            <!-- Preview gambar sebelum upload -->
            <template x-if="preview">
                <img :src="preview" class="mt-3 w-32 h-32 object-cover rounded-lg border border-brownlite/30">
            </template>
        </div>
        <button class="px-6 py-2.5 bg-browndark text-white rounded-full hover:bg-brownlite transition"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Produk' ?></button>
    </form>
</div>
<?= $this->endSection() ?>
