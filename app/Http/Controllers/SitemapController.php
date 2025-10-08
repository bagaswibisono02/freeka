<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Carbon;
use App\Models\produk;
use App\Models\kategory;

class SitemapController extends Controller
{
   public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setLastModificationDate(Carbon::now()))
            ->add(Url::create('/produk'))
            ->add(Url::create('/kategoris'));

        // Produk
       foreach (produk::all() as $produk) {
    $sitemap->add(
        Url::create(route('detail.produk', ['slug' => $produk->slug]))
            ->setLastModificationDate($produk->updated_at)
    );
}

        // Kategori
        foreach (kategory::all() as $kategori) {
            $sitemap->add(
                Url::create('/list-kategory' . urlencode($kategori->name))
            );
        }

        return $sitemap->toResponse(request());
    }
}
