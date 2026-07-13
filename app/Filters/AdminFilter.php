<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminFilter - hanya boleh diakses oleh user dengan role admin.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan login sebagai admin.');
            return redirect()->to('/admin/login');
        }
        if ($session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman admin.');
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
