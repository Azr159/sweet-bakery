# Cuman Buat Tugas (Tidak Akan Di Upload (Hosting)

# 🍞 Sweet Bakery — Website Toko Roti (CodeIgniter 4 + SQLite)

> ### 🚀 Cara cepat menjalankan
> ```bash
> php spark serve
> ```
> Buka **http://localhost:8080** — selesai. Tidak perlu MySQL / import SQL.
>
> ### 🔑 Akun untuk login
> | Peran | Email | Password | Login di |
> |-------|-------|----------|----------|
> | 👑 **Admin** | `azir@sweetbakery.com` | `sweetbakery123` | `/admin/login` |
> | 🧾 **Kasir** | `kasir@sweetbakery.com` | `kasir123` | `/admin/login` |
> | 👤 **User** | `sumanto@ganteng.com` | `sumanto` | `/auth/login` |
>
> 📄 Panduan lengkap + alur uji coba: **[PANDUAN-DOSEN.md](PANDUAN-DOSEN.md)**


Website penjualan roti premium modern dengan CodeIgniter 4, SQLite, TailwindCSS, Alpine.js, dan Chart.js.
Lengkap: autentikasi (session), CRUD, upload gambar, migration & seeder, dashboard admin & user,
splash screen, dark mode, toast, modal konfirmasi, dan pagination.

## ⭐ Kelebihan: TANPA setup database!
Project ini memakai **SQLite** — database berupa SATU FILE yang sudah menyatu di dalam project
(`writable/database/sweet_bakery.db`) dan **sudah terisi data + akun demo**.

Artinya siapa pun yang meng-clone dari GitHub **TIDAK perlu**:
- ❌ Install / menjalankan MySQL
- ❌ Buka phpMyAdmin
- ❌ `CREATE DATABASE`
- ❌ Import file .sql
- ❌ Menjalankan migrate / seed

Cukup **1 perintah** langsung jalan. 🎉

---

## 🚀 Cara Menjalankan

Persyaratan: PHP 8.1+ dengan ekstensi `intl`, `mbstring`, `sqlite3`, `json`, `curl`
(cek dengan `php -m`). Composer TIDAK wajib — folder `system/` sudah disertakan.

```bash
php spark serve
```
Buka browser: **http://localhost:8080** — selesai!

> Pakai XAMPP/Laragon? Taruh folder di `htdocs`/`www`, sesuaikan `app.baseURL` di `.env`,
> lalu akses lewat folder `public/`. Pastikan folder `writable/` bisa ditulis.

---

## 🔑 Akun Demo (sudah ada di database)

| Peran | Email                 | Password       | Login di     |
|-------|-----------------------|----------------|--------------|
| Admin | azir@sweetbakery.com  | sweetbakery123 | /admin/login |
| Kasir | kasir@sweetbakery.com | kasir123       | /admin/login |
| User  | sumanto@ganteng.com   | sumanto        | /auth/login  |

**Peran:**
- **Admin** — akses penuh: produk, kategori, user, pesanan, dan Rekap Data (bisa diunduh).
- **Kasir** — fokus melayani: melihat antrian, menerima pembayaran, mengubah status, cetak struk.
- **User** — pembeli: pesan, checkout, lihat riwayat & struk.

---

## 🗺️ Halaman Penting
- `/`                     Landing page + splash screen
- `/produk`               Katalog (cari, filter, pagination)
- `/produk/detail/{id}`   Detail + tambah keranjang
- `/auth/login` `/auth/register`
- `/admin/login`          Login admin
- `/admin/dashboard`      Dashboard admin (statistik + Chart.js)
- `/user/dashboard`       Dashboard user

---

## 🔄 Reset / Isi Ulang Database (opsional)
Kalau database ingin dikosongkan lalu diisi ulang dari awal:
```bash
php spark migrate:refresh
php spark db:seed DatabaseSeeder
```

## 🐬 Ingin pakai MySQL? (opsional)
Buka `app/Config/Database.php`, ganti bagian `$default` menjadi konfigurasi MySQL
(hostname, username, password, database, `DBDriver => 'MySQLi'`), buat database di MySQL,
lalu jalankan `php spark migrate` dan `php spark db:seed DatabaseSeeder`.
Semua migration & model sudah kompatibel dengan MySQL maupun SQLite.

---

## 🎨 Kustomisasi Cepat
Cari komentar ini di kode:
- "Logo ini dapat diganti dengan logo toko Anda"  -> `public/assets/logo.svg`
- "Gambar ini dapat diganti sesuai kebutuhan"     -> `public/uploads/produk/`
- "Background section ini dapat diganti ..."       -> `app/Views/home/index.php`

Warna tema (di `app/Views/layout/main.php` & `admin.php`):
```js
colors: { cream:'#FFF8F0', brownlite:'#D4A373', browndark:'#8B5E3C' }
```

---

## 📁 Struktur Utama
```
app/
  Controllers/  Home, Auth, Cart, UserPanel, Admin/*
  Models/       User, Kategori, Produk, Pesanan, DetailPesanan
  Views/        layout, home, auth, cart, user, admin
  Filters/      AuthFilter, AdminFilter, UserFilter
  Helpers/      format_helper.php (rupiah, badge_status)
  Config/       Routes.php, Filters.php, Database.php (SQLite)
  Database/
    Migrations/ users, kategori, produk, pesanan, detail_pesanan
    Seeds/      Admin, User, Kategori, Produk, DatabaseSeeder
public/
  uploads/produk/  gambar produk (dummy .svg + hasil upload)
  uploads/profil/  foto profil
  assets/logo.svg  logo bakery
writable/
  database/sweet_bakery.db   <-- DATABASE SQLite (sudah terisi data)
```

---

## 💡 Catatan
- Tailwind, Alpine.js, Chart.js dimuat via CDN -> tanpa `npm install`, langsung jalan
  (butuh koneksi internet saat membuka halaman).
- Gambar dummy berformat `.svg`; upload gambar baru mendukung JPG/PNG/WEBP.
- File `.env` sengaja diikutkan ke repo (SQLite tidak menyimpan password, jadi aman untuk tugas).

Selamat mencoba! 🥐
