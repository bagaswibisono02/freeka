<?php

namespace App\Observers;

use App\Models\Produk;

class ProdukObserver
{ public function created(Produk $produk)
    {
        Artisan::call('sitemap:generate');
    }

    public function updated(Produk $produk)
    {
        Artisan::call('sitemap:generate');
    }

    public function deleted(Produk $produk)
    {
        Artisan::call('sitemap:generate');
    }
}
