<?php

use App\Http\Controllers\AfiliateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\mainController;
use App\Http\Controllers\KategoryController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ManagementPromoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\SitemapController;
use Chatify\Http\Controllers\MessagesController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/admin', [mainController::class, 'login']);
Route::get('/logout', [mainController::class, 'logout']);
Route::post('/logout', [mainController::class, 'logout']);
Route::post('/login', [mainController::class, 'prosesLogin']);
Route::get('/dashboard', [mainController::class, 'dashboard'])->middleware(['auth', 'admin']);
Route::resource('/kategori', kategoryController::class);
Route::resource('/penerima', PenerimaController::class);
Route::resource('/pesanan', PesananController::class);
Route::resource('/keuangan', KeuanganController::class);
Route::resource('/supplier', SupplierController::class);

Route::get('/promo', function () {
    return redirect()->route('maintenance');
});
Route::get('/bantuan', function () {
    return redirect()->route('maintenance');
});
Route::get('/edukasi', function () {
    return redirect()->route('maintenance');
});
Route::get('/kontak', function () {
    return redirect()->route('maintenance');
});

Route::post('/tambah-penerima', [PenerimaController::class, 'tambah']);
Route::get('/thankyou', [PesananController::class, 'responseFastpay']);



//file
Route::get('/file', [mainController::class, 'getfile']); //get file
Route::get('/download-qris', [mainController::class, 'downloadQris']); //dwonload qris
Route::post('/upload-verifikasi-pembayaran', [pesananController::class, 'uploadPembayaran']); //dwonload qris

//user
Route::get('/', [mainController::class, 'index']);
Route::get('/list-kategory', [mainController::class, 'listKategory']);
Route::get('/maintenance', [mainController::class, 'maintenance'])->name('maintenance');
Route::get('/forgot-password', [mainController::class, 'forgotPassword']);
Route::post('/forgot-password', [mainController::class, 'kirimEmail']);
Route::get('/search', [mainController::class, 'search']);
Route::get('/lihat-pesanan', [PesananController::class, 'detailPesanan'])->middleware(['auth']);
Route::get('/logout', [mainController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');
Route::get('/pembayaran', [PesananController::class, 'pembayaran'])->middleware(['auth']);
Route::post('/masukan-keranjang', [mainController::class, 'masukanKeranjang'])->middleware(['auth'])->name('keranjang.tambah');
Route::get('/register', [mainController::class, 'registerUser']);
Route::post('/register', [mainController::class, 'prosesRegisterUser']);
Route::get('/login', [mainController::class, 'loginUser'])->name('login');
Route::get('/panel', [mainController::class, 'panel'])->middleware(['auth']);
Route::get('/checkout', [PesananController::class, 'checkout'])->middleware('auth')->name('checkout.beliLangsung');
Route::Post('/checkout', [PesananController::class, 'createSnapToken'])->middleware(['auth']);
Route::get('/resi', [PesananController::class, 'resi'])->middleware(['auth']);
Route::post('/resi/{id}', [PesananController::class, 'tambahResi'])->middleware(['auth']);
Route::post('/resi/{id}/edit', [PesananController::class, 'editResi'])->middleware(['auth']);
Route::get('/supplier-pesan/{id}', [PesananController::class, 'pesanSupplier'])->middleware(['auth']);
Route::get('/profile', [mainController::class, 'profile'])->middleware(['auth']);
Route::post('/profile', [mainController::class, 'updateProfile'])->middleware(['auth']);
Route::get('/notifikasi', [mainController::class, 'notifikasi'])->middleware(['auth']);
Route::get('/team', [mainController::class, 'team'])->middleware(['auth']);


Route::get('/checkout/success', function () {
    return redirect('/panel?filter=tunggubayar');
});


//pesaan
Route::get('/terima', [PesananController::class, 'terima']);
Route::get('/tolak', [PesananController::class, 'tolak']);
Route::get('/lihatresi', [PesananController::class, 'lihatResi']);
Route::post('/pesanan-diterima/{id}', [PesananController::class, 'diterima']);
Route::get('/review', [ProdukController::class, 'review']);
Route::get('/pesanan/{idpesanan}/{iduser}', [PesananController::class, 'gantiAlamat']);
Route::post('/editpesanan', [PesananController::class, 'editPesanan']);
Route::post('/submit-review', [PesananController::class, 'tambahReview']);
Route::post('/submit-review/{pesanan_id}/edit', [PesananController::class, 'editReview']);

//produk
Route::resource('/produk', ProdukController::class);
Route::get('/detail-produk/{slug}', [ProdukController::class, 'lihatProduk'])->name('produk.detail');
Route::get('/varian/{id}', [ProdukController::class, 'hapusVarian']);
Route::post('/hapus-foto-produk', [ProdukController::class, 'hapusFotoProduk']);
Route::post('/update-terjual/{id}', [ProdukController::class, 'updateTerjual']);

//modul chat
// Route::get('/chat', Chat::class);

// Form permintaan reset password
Route::get('/forgot-password', [PasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Kirim email reset password
Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Form reset password
Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');

// Simpan password baru
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');


Route::get('/chat', [MessagesController::class, 'index'])->middleware('auth');
Route::get('/notfikasi', [mainController::class, 'notifikasi'])->middleware('auth');

Route::get('/api/tripay/channels', function () {
    $url = 'https://tripay.co.id/api-sandbox/merchant/payment-channel'; // sandbox
    $apiKey = config('services.tripay.api_key'); // simpan di .env

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
    ])->get($url);

    if ($response->successful()) {
        return response()->json($response->json()['data']);
    }

    return response()->json(['error' => 'Gagal memuat channel'], 500);
});



//Admin
Route::get('/admin',[mainController::class,'dashboard'])->name('admin.dashboard');
Route::resource('/admin/produk',AdminProductController::class);

//promo
Route::get('/management-promo',[ManagementPromoController::class,'index']);

// / Admin Categories Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    
    // Additional routes
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
        ->name('categories.toggle-status');
    
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])
        ->name('categories.update-order');
    
    Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])
        ->name('categories.bulk-action');
});

