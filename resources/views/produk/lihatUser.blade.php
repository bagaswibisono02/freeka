@extends('layouts.navUser')

@section('body')
    <style>
        .buy-now {
            flex: 1;
            padding: 8px 14px;
            border-radius: 6px;
            background-color: #9c27b0;
            color: #fff;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }

        .buy-now:hover {
            background-color: #7b1fa2;
        }

        .add-to-cart {
            background-color: #6a1b9a;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .add-to-cart:hover {
            background-color: #4a148c;
        }

        .add-to-cart i {
            font-size: 18px;
        }

        .product-card a {
            text-decoration: none;
            color: inherit;
        }

        .product-image img {
            transition: transform 0.3s;
        }

        .product-image:hover img {
            transform: scale(1.05);
        }
    </style>

    <main class="container">
        <!-- Navigation -->
        <nav class="main-nav">
            <div class="container">
                <div class="nav-container">
                    <div class="relative group inline-block">
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            <i class="fas fa-bars"></i>
                            <span>Semua Kategori</span>
                        </button>

                        <!-- Mega Menu -->
                        <div
                            class="absolute left-0 top-full mt-2 w-[400px] bg-purple-600 text-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 z-50 pointer-events-auto">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4">
                                @foreach ($kategories as $kat)
                                    <div>
                                        <a href="{{ url('/?kategory=' . $kat->name) }}"
                                            class="block mb-2 font-semibold hover:underline">
                                            {{ $kat->name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>



                    <div class="nav-links">
                        <a href="/">Home</a>
                        <a href="">Flash Sale</a>
                        <a href="#">Diskon Spesial</a>
                        <a href="#">Produk Terbaru</a>
                        <a href="#">Brand Terkenal</a>
                        <a href="#">Top Up & Tagihan</a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Flash Sale -->
        <section class="flash-sale">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-bolt"></i>
                    <h2>Flash Sale</h2>
                </div>
                <a href="#" class="view-all">Lihat Semua <i class="fas fa-chevron-right"></i></a>
            </div>

            <div class="products-grid">
                @foreach ($produks->take(10) as $produk)
                    <div class="product-card">
                        <a href="{{ route('produk.detail', $produk->slug) }}">
                            <div class="product-image">
                                <img src="{{ $produk->media->first()?->file
                                    ? asset('storage/' . $produk->media->first()->file)
                                    : asset('img/no-image.jpg') }}"
                                    alt="{{ $produk->nama }}">
                                <span class="product-badge">Flash Sale</span>
                            </div>
                        </a>
                        <div class="product-info">
                            <a href="{{ route('produk.detail', $produk->slug) }}">
                                <div class="product-name">{{ $produk->nama }}</div>
                            </a>
                            <div class="product-price">
                                <span class="current-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                <span class="original-price">Rp
                                    {{ number_format($produk->harga * 1.2, 0, ',', '.') }}</span>
                                <span class="discount">20%</span>
                            </div>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-count">(128)</span>
                                <span class="sold">Terjual {{ rand(100, 1000) }}</span>
                            </div>

                            <div class="product-actions"
                                style="display:flex; align-items:center; gap:8px; margin-top:10px;">
                                <a href="{{ route('checkout.beliLangsung', ['produk' => encrypt($produk->id)]) }}"
                                    class="buy-now">
                                    Beli
                                </a>
                                <form action="{{ route('keranjang.tambah', ['produk' => encrypt($produk->id)]) }}"
                                    method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="add-to-cart" title="Tambah ke Keranjang">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured-products">
            <div class="section-header">
                <div class="section-title">
                    <h2>Rekomendasi Untuk Anda</h2>
                </div>
            </div>

            <div class="products-grid">
                @foreach ($produks as $produk)
                    <div class="product-card">
                        <a href="{{ route('produk.detail', $produk->slug) }}">
                            <div class="product-image">
                                <img src="{{ $produk->media->first()?->file
                                    ? asset('storage/' . $produk->media->first()->file)
                                    : asset('img/no-image.jpg') }}"
                                    alt="{{ $produk->nama }}">
                            </div>
                        </a>
                        <div class="product-info">
                            <a href="{{ route('produk.detail', $produk->slug) }}">
                                <div class="product-name">{{ $produk->nama }}</div>
                            </a>
                            <div class="product-price">
                                <span class="current-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="product-actions"
                                style="display:flex; align-items:center; gap:8px; margin-top:10px;">
                                <a href="{{ route('checkout.beliLangsung', ['produk' => encrypt($produk->id)]) }}"
                                    class="buy-now">
                                    Beli
                                </a>
                                <form action="{{ route('keranjang.tambah', ['produk' => $produk->id]) }}" method="POST"
                                    style="margin:0;">
                                    @csrf
                                    <button type="submit" class="add-to-cart" title="Tambah ke Keranjang">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection
