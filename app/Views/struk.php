<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" href="<?= base_url('assets/logo.svg') ?>" type="image/svg+xml">
    <style>
        /* ===== Struk Sweet Bakery ===== */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #EFE6DC;
            padding: 24px 12px;
            display: flex; flex-direction: column; align-items: center;
            color: #2b2b2b;
        }
        .struk {
            background: #fff;
            width: 340px;
            padding: 22px 20px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,.15);
            position: relative;
        }
        /* Gerigi bawah struk */
        .struk::after {
            content: ""; position: absolute; left: 0; right: 0; bottom: -10px; height: 10px;
            background: repeating-linear-gradient(135deg, #fff 0 8px, transparent 8px 16px);
            filter: drop-shadow(0 2px 1px rgba(0,0,0,.06));
        }
        .center { text-align: center; }
        .logo { width: 46px; height: 46px; margin: 0 auto 6px; display: block; }
        h1 { font-size: 18px; letter-spacing: 2px; font-weight: bold; }
        .sub { font-size: 10px; color: #777; margin-top: 3px; line-height: 1.5; }
        .sep { border-top: 1px dashed #bbb; margin: 12px 0; }
        .row { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; gap: 8px; }
        .row .lbl { color: #777; }
        .item { margin-bottom: 8px; }
        .item-nama { font-size: 12px; font-weight: bold; }
        .item-sub { display: flex; justify-content: space-between; font-size: 11px; color: #555; margin-top: 2px; }
        .varian { font-size: 10px; color: #8B5E3C; }
        .total { display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; margin-top: 6px; }
        .antrian {
            border: 2px dashed #8B5E3C; padding: 10px; margin: 12px 0; text-align: center;
        }
        .antrian .no { font-size: 34px; font-weight: bold; line-height: 1; color: #8B5E3C; }
        .antrian .cap { font-size: 10px; color: #777; letter-spacing: 1px; }
        .badge {
            display: inline-block; font-size: 10px; padding: 3px 10px; border: 1px solid #333;
            border-radius: 999px; margin-top: 6px; font-weight: bold;
        }
        .lunas  { border-color: #1a7f37; color: #1a7f37; }
        .belum  { border-color: #b45309; color: #b45309; }
        .footer-note { font-size: 10px; color: #777; text-align: center; margin-top: 14px; line-height: 1.6; }
        .qr { width: 110px; height: 110px; margin: 8px auto 0; display: block; }

        /* Tombol (tidak ikut tercetak) */
        .actions { margin-top: 26px; display: flex; gap: 10px; }
        .btn {
            font-family: system-ui, sans-serif; font-size: 14px; padding: 10px 20px; border-radius: 999px;
            border: none; cursor: pointer; text-decoration: none; display: inline-block;
        }
        .btn-print { background: #8B5E3C; color: #fff; }
        .btn-back  { background: #fff; color: #8B5E3C; border: 1px solid #8B5E3C; }

        /* ===== Saat dicetak: hanya struk yang tampil ===== */
        @media print {
            body { background: #fff; padding: 0; }
            .struk { box-shadow: none; width: 100%; max-width: 300px; }
            .struk::after { display: none; }
            .actions { display: none !important; }
        }
    </style>
</head>
<body>

<div class="struk">
    <div class="center">
        <!-- Logo ini dapat diganti dengan logo toko Anda -->
        <img src="<?= base_url('assets/logo.svg') ?>" class="logo" alt="Logo">
        <h1>SWEET BAKERY</h1>
        <p class="sub">
            Jl. Roti Manis No. 1<br>
            Telp. 0812-3456-7890
        </p>
    </div>

    <div class="sep"></div>

    <div class="row"><span class="lbl">No. Pesanan</span><span>#<?= $pesanan['id'] ?></span></div>
    <div class="row"><span class="lbl">Tanggal</span><span><?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></span></div>
    <div class="row"><span class="lbl">Pelanggan</span><span><?= esc($pesanan['nama_user'] ?? '-') ?></span></div>
    <div class="row">
        <span class="lbl">Tipe</span>
        <span><?= ($pesanan['tipe_pesanan'] ?? 'Antar') === 'Dine In' ? 'Makan di Tempat' : 'Diantar' ?></span>
    </div>
    <?php if (! empty($pesanan['nomor_meja'])): ?>
        <div class="row"><span class="lbl">Meja</span><span>No. <?= esc($pesanan['nomor_meja']) ?></span></div>
    <?php endif; ?>
    <?php if (! empty($pesanan['nomor_telepon'])): ?>
        <div class="row"><span class="lbl">Telepon</span><span><?= esc($pesanan['nomor_telepon']) ?></span></div>
    <?php endif; ?>

    <!-- Nomor antrian -->
    <div class="antrian">
        <div class="cap">NOMOR ANTRIAN</div>
        <div class="no"><?= $pesanan['nomor_antrian'] ?? '-' ?></div>
    </div>

    <div class="sep"></div>

    <!-- Daftar item -->
    <?php foreach ($detail as $d): ?>
    <div class="item">
        <div class="item-nama">
            <?= esc($d['nama_produk']) ?>
            <?php if (! empty($d['varian'])): ?>
                <span class="varian">(<?= esc($d['varian']) ?>)</span>
            <?php endif; ?>
        </div>
        <div class="item-sub">
            <span><?= $d['jumlah'] ?> x <?= rupiah($d['harga']) ?></span>
            <span><?= rupiah($d['subtotal']) ?></span>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="sep"></div>

    <div class="row"><span class="lbl">Subtotal</span><span><?= rupiah($pesanan['total_harga']) ?></span></div>
    <div class="row"><span class="lbl">Ongkir</span><span>Gratis</span></div>
    <div class="total"><span>TOTAL</span><span><?= rupiah($pesanan['total_harga']) ?></span></div>

    <div class="sep"></div>

    <div class="row"><span class="lbl">Metode Bayar</span><span><?= esc($pesanan['metode_bayar'] ?? '-') ?></span></div>
    <?php $lunas = ($pesanan['status_bayar'] ?? '') === 'Sudah Dibayar'; ?>
    <div class="center">
        <span class="badge <?= $lunas ? 'lunas' : 'belum' ?>">
            <?= $lunas ? '✓ LUNAS' : 'BELUM DIBAYAR' ?>
        </span>
    </div>

    <!-- QR ditampilkan jika belum dibayar & metodenya QRIS -->
    <?php if (! $lunas && ($pesanan['metode_bayar'] ?? '') === 'QRIS'): ?>
        <p class="footer-note" style="margin-top:12px">Scan untuk membayar:</p>
        <!-- QR ini dapat diganti dengan QRIS asli milik toko Anda -->
        <img src="<?= base_url('assets/qris.svg') ?>" class="qr" alt="QRIS">
    <?php endif; ?>

    <?php if (! empty($pesanan['catatan'])): ?>
        <div class="sep"></div>
        <div class="row"><span class="lbl">Catatan</span><span><?= esc($pesanan['catatan']) ?></span></div>
    <?php endif; ?>

    <div class="sep"></div>

    <p class="footer-note">
        <?php if (($pesanan['tipe_pesanan'] ?? '') === 'Dine In' && ($pesanan['metode_bayar'] ?? '') === 'QRIS'): ?>
            Pesanan akan diantar ke meja Anda.<br>
        <?php elseif (($pesanan['tipe_pesanan'] ?? '') === 'Dine In'): ?>
            Tunjukkan struk ini di kasir untuk membayar.<br>
        <?php endif; ?>
        Terima kasih telah berbelanja!<br>
        ~ Sweet Bakery ~
    </p>
</div>

<!-- Tombol (tidak ikut tercetak) -->
<div class="actions">
    <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF</button>
    <a href="<?= esc($kembali) ?>" class="btn btn-back">Kembali</a>
</div>

</body>
</html>
