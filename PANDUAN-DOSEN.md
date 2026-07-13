# 📋 Panduan Singkat — Sweet Bakery

> Website Toko Roti · CodeIgniter 4 · Dibuat oleh: **Azir**

---

## ▶️ Cara Menjalankan (1 perintah)

Buka terminal di dalam folder project, lalu ketik:

```bash
php spark serve
```

Buka browser: **http://localhost:8080**

**Tidak perlu** membuat database, tidak perlu XAMPP/MySQL, tidak perlu import file `.sql`.
Database sudah menyatu di dalam project (SQLite) dan sudah terisi data.

> Syarat: PHP 8.1+ dengan ekstensi `sqlite3`, `intl`, `mbstring` aktif.
> Cek dengan: `php -m`

---

## 🔑 AKUN UNTUK LOGIN

### 👑 Admin — akses penuh
| | |
|---|---|
| **Email** | `azir@sweetbakery.com` |
| **Password** | `sweetbakery123` |
| **Login di** | http://localhost:8080/admin/login |

Bisa: kelola produk, kategori, user, pesanan, dan **Rekap Data** (unduh CSV).

### 🧾 Kasir — melayani pesanan
| | |
|---|---|
| **Email** | `kasir@sweetbakery.com` |
| **Password** | `kasir123` |
| **Login di** | http://localhost:8080/admin/login |

Bisa: lihat antrian masuk, terima pembayaran, ubah status pesanan, cetak struk.
Tidak bisa mengubah produk/user (khusus admin).

### 👤 User / Pembeli
| | |
|---|---|
| **Email** | `dimas@mail.com` |
| **Password** | `user123` |
| **Login di** | http://localhost:8080/auth/login |

Bisa: pesan produk, checkout, lihat riwayat & struk.
Pengunjung juga bisa **mendaftar akun sendiri** lewat menu Daftar.

> Catatan: petunjuk akun demo ini juga ditampilkan langsung di halaman login,
> jadi tidak perlu menghafal.

---

## 🧪 Alur Uji Coba yang Disarankan

1. **Sebagai User** (`dimas@mail.com`)
   - Buka menu **Produk** → pilih minuman (mis. Kopi Latte) → pilih varian **Hot / Iced**
   - Tambah ke keranjang → **Checkout**
   - Pilih **Makan di Tempat** → isi nomor meja → pilih **Bayar di Kasir** atau **QRIS**
     *(atau pilih **Diantar** → isi alamat + nomor telepon)*
   - Setelah pesan, muncul **nomor antrian** → klik **Lihat Struk**

2. **Sebagai Kasir** (`kasir@sweetbakery.com`)
   - Pesanan tadi langsung muncul di **Dashboard Kasir**
   - Klik **✓ Terima Pembayaran** → ubah status jadi **Selesai** → **Cetak Struk**

3. **Sebagai Admin** (`azir@sweetbakery.com`)
   - Lihat **Dashboard** (statistik + grafik)
   - Coba **CRUD Produk** (tambah/edit/hapus + upload gambar)
   - Buka **Rekap Data** → pilih rentang tanggal → klik **Download Rekap (CSV)**

---

## ✨ Fitur Utama

- **3 role**: Admin, Kasir, User (dengan hak akses berbeda)
- **CRUD lengkap**: Produk, Kategori, User, Pesanan
- **Migration & Seeder**
- **Session & Authentication** + Filter (Auth/Admin/User/Kasir)
- **Upload gambar** + preview sebelum upload
- **Pemesanan**: Makan di Tempat (nomor meja + antrian) / Diantar (alamat + telepon)
- **Pembayaran**: Bayar di Kasir, QRIS, atau COD
- **Struk** siap cetak / simpan PDF
- **Varian produk**: minuman Hot / Iced
- **Rekap penjualan** dengan filter tanggal + unduh CSV
- **UI**: TailwindCSS, dark mode (tersimpan), responsive HP/tablet/laptop,
  splash screen, toast, modal konfirmasi, pagination, Chart.js

---

## 🔄 Reset Database (opsional)

Kalau ingin mengembalikan data ke kondisi awal:

```bash
php spark migrate:refresh
php spark db:seed DatabaseSeeder
```
