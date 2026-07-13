<?php

/**
 * Helper kustom Sweet Bakery
 */

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi Rupiah. Contoh: 18000 => "Rp 18.000"
     */
    function rupiah($angka): string
    {
        return 'Rp ' . number_format((float) $angka, 0, ',', '.');
    }
}

if (! function_exists('badge_status')) {
    /**
     * Kembalikan kelas Tailwind untuk badge status pesanan.
     */
    function badge_status(string $status): string
    {
        return match ($status) {
            'Menunggu' => 'bg-yellow-100 text-yellow-800',
            'Diproses' => 'bg-blue-100 text-blue-800',
            'Dikirim'  => 'bg-purple-100 text-purple-800',
            'Selesai'  => 'bg-green-100 text-green-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    }
}
