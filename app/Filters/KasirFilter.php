<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * KasirFilter - halaman kasir.
 * Boleh diakses oleh role "kasir" dan juga "admin" (admin bisa memantau).
 */
class KasirFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan login sebagai kasir.');
            return redirect()->to('/admin/login');
        }
        if (! in_array($session->get('role'), ['kasir', 'admin'], true)) {
            $session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman kasir.');
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
