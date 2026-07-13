<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // ---- AKUN ADMIN ----
        $this->db->table('users')->insert([
            'nama'        => 'Azir',
            'email'       => 'azir@sweetbakery.com',
            'password'    => password_hash('sweetbakery123', PASSWORD_DEFAULT),
            'role'        => 'admin',
            'foto_profil' => 'default.svg',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // ---- AKUN KASIR ----
        $this->db->table('users')->insert([
            'nama'        => 'Rina',
            'email'       => 'kasir@sweetbakery.com',
            'password'    => password_hash('kasir123', PASSWORD_DEFAULT),
            'role'        => 'kasir',
            'foto_profil' => 'default.svg',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
    }
}
