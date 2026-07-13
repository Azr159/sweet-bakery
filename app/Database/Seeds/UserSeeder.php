<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now  = date('Y-m-d H:i:s');
        $data = [
            [
                'nama'        => 'Dimas',
                'email'       => 'dimas@mail.com',
                'password'    => password_hash('user123', PASSWORD_DEFAULT),
                'role'        => 'user',
                'foto_profil' => 'default.svg',
                'created_at'  => $now, 'updated_at' => $now,
            ],
            [
                'nama'        => 'Siti Aminah',
                'email'       => 'siti@mail.com',
                'password'    => password_hash('user123', PASSWORD_DEFAULT),
                'role'        => 'user',
                'foto_profil' => 'default.svg',
                'created_at'  => $now, 'updated_at' => $now,
            ],
        ];
        $this->db->table('users')->insertBatch($data);
    }
}
