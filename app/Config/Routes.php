<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * =====================================================================
 *  SWEET BAKERY - Daftar Route
 * =====================================================================
 */

// ---------------- PUBLIC / LANDING ----------------
$routes->get('/', 'Home::index');
$routes->get('produk', 'Home::produk');
$routes->get('produk/detail/(:num)', 'Home::detail/$1');
$routes->get('tentang', 'Home::tentang');

// ---------------- AUTH (USER) ----------------
$routes->group('auth', static function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
    $routes->get('logout', 'Auth::logout');
});

// ---------------- AUTH (ADMIN) ----------------
$routes->get('admin/login', 'Auth::adminLogin');
$routes->post('admin/login', 'Auth::attemptAdminLogin');

// ---------------- KERANJANG & CHECKOUT (butuh login user) ----------------
$routes->group('cart', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Cart::index');
    $routes->post('add/(:num)', 'Cart::add/$1');
    $routes->post('update/(:segment)', 'Cart::update/$1');
    $routes->get('remove/(:segment)', 'Cart::remove/$1');
    $routes->get('checkout', 'Cart::checkout');
    $routes->post('checkout', 'Cart::processCheckout');
});

// ---------------- AREA USER ----------------
$routes->group('user', ['filter' => 'user'], static function ($routes) {
    $routes->get('dashboard', 'UserPanel::dashboard');
    $routes->get('profil', 'UserPanel::profil');
    $routes->post('profil/update', 'UserPanel::updateProfil');
    $routes->post('profil/password', 'UserPanel::updatePassword');
    $routes->get('riwayat', 'UserPanel::riwayat');
    $routes->get('riwayat/(:num)', 'UserPanel::detailRiwayat/$1');
    $routes->get('struk/(:num)', 'UserPanel::struk/$1');
});

// ---------------- AREA KASIR ----------------
$routes->group('kasir', ['filter' => 'kasir'], static function ($routes) {
    $routes->get('dashboard', 'Kasir::dashboard');
    $routes->get('pesanan', 'Kasir::pesanan');
    $routes->get('detail/(:num)', 'Kasir::detail/$1');
    $routes->post('status/(:num)', 'Kasir::updateStatus/$1');
    $routes->post('bayar/(:num)', 'Kasir::updateBayar/$1');
    $routes->get('struk/(:num)', 'Kasir::struk/$1');
});

// ---------------- AREA ADMIN ----------------
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Rekap penjualan (+ unduh CSV)
    $routes->get('rekap', 'Admin\Rekap::index');
    $routes->get('rekap/download', 'Admin\Rekap::download');

    // CRUD Produk
    $routes->get('produk', 'Admin\Produk::index');
    $routes->get('produk/create', 'Admin\Produk::create');
    $routes->post('produk/store', 'Admin\Produk::store');
    $routes->get('produk/edit/(:num)', 'Admin\Produk::edit/$1');
    $routes->post('produk/update/(:num)', 'Admin\Produk::update/$1');
    $routes->get('produk/delete/(:num)', 'Admin\Produk::delete/$1');

    // CRUD Kategori
    $routes->get('kategori', 'Admin\Kategori::index');
    $routes->post('kategori/store', 'Admin\Kategori::store');
    $routes->post('kategori/update/(:num)', 'Admin\Kategori::update/$1');
    $routes->get('kategori/delete/(:num)', 'Admin\Kategori::delete/$1');

    // CRUD User
    $routes->get('user', 'Admin\UserManage::index');
    $routes->get('user/create', 'Admin\UserManage::create');
    $routes->post('user/store', 'Admin\UserManage::store');
    $routes->get('user/edit/(:num)', 'Admin\UserManage::edit/$1');
    $routes->post('user/update/(:num)', 'Admin\UserManage::update/$1');
    $routes->get('user/delete/(:num)', 'Admin\UserManage::delete/$1');

    // CRUD Pesanan
    $routes->get('pesanan', 'Admin\Pesanan::index');
    $routes->get('pesanan/detail/(:num)', 'Admin\Pesanan::detail/$1');
    $routes->get('pesanan/struk/(:num)', 'Admin\Pesanan::struk/$1');
    $routes->post('pesanan/status/(:num)', 'Admin\Pesanan::updateStatus/$1');
    $routes->post('pesanan/bayar/(:num)', 'Admin\Pesanan::updateBayar/$1');
    $routes->get('pesanan/delete/(:num)', 'Admin\Pesanan::delete/$1');
});
