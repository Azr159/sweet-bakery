<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * UserFilter - hanya boleh diakses oleh user dengan role user.
 */
class UserFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/auth/login');
        }
        if ($session->get('role') !== 'user') {
            // Staff diarahkan ke panel masing-masing
            if ($session->get('role') === 'kasir') {
                return redirect()->to('/kasir/dashboard');
            }
            return redirect()->to('/admin/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
