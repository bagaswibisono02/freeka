<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freeka - Marketplace Modern</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@1.4.1/dist/phosphor.css">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Flowbite JS (opsional, untuk komponen interaktif seperti modal/dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #8B5FBF;
            --primary-dark: #6B3FA0;
            --primary-light: #B39DDB;
            --secondary: #FF5722;
            --accent: #E91E63;
            --text: #333333;
            --text-light: #666666;
            --bg-light: #F8F9FA;
            --white: #FFFFFF;
            --border: #E0E0E0;
            --success: #4CAF50;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header Styles */
        .top-header {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 8px 0;
            font-size: 0.85rem;
        }

        .top-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-links {
            display: flex;
            gap: 20px;
        }

        .top-links a {
            transition: var(--transition);
        }

        .top-links a:hover {
            color: var(--secondary);
        }

        .main-header {
            background-color: var(--white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-right: 30px;
        }

        .logo img {
            height: 40px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-left: 10px;
        }

        .search-container {
            flex: 1;
            display: flex;
            margin: 0 20px;
        }

        .search-box {
            flex: 1;
            display: flex;
            border: 2px solid var(--primary);
            border-radius: 4px;
            overflow: hidden;
        }

        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: none;
            outline: none;
            font-size: 1rem;
        }

        .search-categories {
            padding: 0 15px;
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            background: var(--bg-light);
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }

        .search-categories span {
            margin-right: 5px;
        }

        .categories-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--white);
            width: 200px;
            box-shadow: var(--shadow);
            border-radius: 4px;
            display: none;
            z-index: 10;
        }

        .search-categories:hover .categories-dropdown {
            display: block;
        }

        .categories-dropdown a {
            display: block;
            padding: 10px 15px;
            transition: var(--transition);
        }

        .categories-dropdown a:hover {
            background: var(--bg-light);
            color: var(--primary);
        }

        .search-button {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background: var(--primary-dark);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
            padding: 5px 10px;
            border-radius: 4px;
        }

        .action-item:hover {
            color: var(--primary);
        }

        .action-item i {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .action-text {
            font-size: 0.85rem;
        }

        .cart-count {
            background: var(--secondary);
            color: var(--white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            position: absolute;
            top: -5px;
            right: 5px;
        }

        /* Navigation */
        .main-nav {
            background: var(--white);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .nav-container {
            display: flex;
            align-items: center;
        }

        .all-categories {
            background: var(--primary);
            color: var(--white);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            width: 250px;
        }

        .all-categories i {
            margin-right: 10px;
        }

        .mega-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: var(--white);
            box-shadow: var(--shadow);
            display: none;
            z-index: 100;
        }

        .all-categories:hover .mega-menu {
            display: block;
        }

        .mega-menu-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            padding: 20px;
            gap: 20px;
        }

        .mega-menu-col h4 {
            margin-bottom: 15px;
            color: var(--primary);
            padding-bottom: 5px;
            border-bottom: 1px solid var(--border);
        }

        .mega-menu-col a {
            display: block;
            padding: 5px 0;
            transition: var(--transition);
        }

        .mega-menu-col a:hover {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            margin-left: 20px;
        }

        .nav-links a {
            padding: 12px 15px;
            transition: var(--transition);
            font-weight: 500;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        /* Banner Slider */
        .banner-slider {
            position: relative;
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .slider-container {
            display: flex;
            transition: transform 0.5s ease;
        }

        .slide {
            min-width: 100%;
            position: relative;
        }

        .slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .slide-content {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            color: var(--white);
            max-width: 500px;
        }

        .slide-content h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .slide-content p {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .slide-button {
            display: inline-block;
            background: var(--secondary);
            color: var(--white);
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: 600;
            transition: var(--transition);
        }

        .slide-button:hover {
            background: #E64A19;
            transform: translateY(-2px);
        }

        .slider-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: var(--transition);
        }

        .dot.active {
            background: var(--white);
            transform: scale(1.2);
        }

        /* Flash Sale */
        .flash-sale {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--shadow);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title h2 {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .section-title i {
            color: var(--accent);
        }

        .countdown {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--accent);
            color: var(--white);
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: 600;
        }

        .countdown-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countdown-value {
            font-size: 1.2rem;
        }

        .countdown-label {
            font-size: 0.7rem;
        }

        .view-all {
            color: var(--primary);
            font-weight: 600;
            transition: var(--transition);
        }

        .view-all:hover {
            color: var(--primary-dark);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .product-card {
            background: var(--white);
            border-radius: 8px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 180px;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--accent);
            color: var(--white);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-size: 0.9rem;
            margin-bottom: 10px;
            height: 40px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .current-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
        }

        .original-price {
            font-size: 0.9rem;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .discount {
            background: var(--success);
            color: var(--white);
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        .stars {
            color: #FFC107;
        }

        .rating-count {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .sold {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-left: auto;
        }

        .add-to-cart {
            width: 100%;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
        }

        .add-to-cart:hover {
            background: var(--primary-dark);
        }

        /* Categories */
        .categories {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--shadow);
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 15px;
        }

        .category-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: var(--transition);
            padding: 15px 10px;
            border-radius: 8px;
        }

        .category-item:hover {
            background: var(--bg-light);
            transform: translateY(-5px);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .category-icon i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .category-name {
            font-size: 0.8rem;
            text-align: center;
            font-weight: 500;
        }

        /* Featured Products */
        .featured-products {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: var(--shadow);
        }

        .tab-header {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: var(--transition);
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }

        .tab.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }

        /* Footer */
        .footer {
            background: var(--primary-dark);
            color: var(--white);
            padding: 40px 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            background: var(--secondary);
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            transition: var(--transition);
            opacity: 0.8;
        }

        .footer-column ul li a:hover {
            opacity: 1;
            padding-left: 5px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .categories-grid {
                grid-template-columns: repeat(5, 1fr);
            }

            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .top-header {
                display: none;
            }

            .header-content {
                flex-wrap: wrap;
            }

            .logo {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .search-container {
                order: 3;
                width: 100%;
                margin: 10px 0 0;
            }

            .nav-container {
                flex-direction: column;
            }

            .all-categories {
                width: 100%;
                justify-content: center;
            }

            .nav-links {
                margin-left: 0;
                flex-wrap: wrap;
                justify-content: center;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .categories-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .categories-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .footer-content {
                grid-template-columns: 1fr;
            }

            .slide-content h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>

    {{-- Modal Notifikasi (Error / Sukses) --}}
@if (session('gagal') || session('berhasil'))
    <div 
        x-data="{ open: true }" 
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 text-center relative">
            
            {{-- Tombol close --}}
            <button 
                @click="open = false" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl font-bold">
                &times;
            </button>

            {{-- Icon --}}
            @if (session('gagal'))
                <div class="text-red-600 text-4xl mb-3">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 class="text-lg font-semibold text-red-600 mb-2">Gagal!</h3>
                <p class="text-gray-700 text-sm">{{ session('gagal') }}</p>
            @endif

            @if (session('berhasil'))
                <div class="text-green-600 text-4xl mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="text-lg font-semibold text-green-600 mb-2">Berhasil!</h3>
                <p class="text-gray-700 text-sm">{{ session('berhasil') }}</p>
            @endif

            {{-- Tombol tutup --}}
            <div class="mt-5">
                <button 
                    @click="open = false"
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endif

    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="top-links">
                <a href="#"><i class="fas fa-question-circle"></i> Pusat Bantuan</a>
                <a href="#"><i class="fas fa-shipping-fast"></i> Lacak Pesanan</a>
                <a href="#"><i class="fas fa-store"></i> Jadi Seller</a>
            </div>
            <div class="top-links">
                <a href="/notifikasi" class="position-relative">
    <i class="fas fa-bell"></i> Notifikasi
    @auth
        @php
            $unreadCount = \App\Models\notifikasi::where('user_id', Auth::id())
                                                ->where('is_read', false)
                                                ->count();
        @endphp
        @if($unreadCount > 0)
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 rounded-circle" 
                  style="font-size:0.75rem; font-weight:bold; color:white;">
                {{ $unreadCount }}
            </span>
        @endif
    @endauth
</a>

                <a href="#"><i class="fas fa-heart"></i> Favorit</a>
                <a href="/panel"><i class="fas fa-user"></i> Akun Saya</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">
                        <div class="logo-text">Freeka</div>
                    </a>
                </div>

                <div class="search-container">
                    <form action="/search" style="display: block" class="search-container" method="get">
                    <div class="search-box">
                        <input type="text" name="parameter" placeholder="Cari produk, merek, dan kategori...">
                        <div class="search-categories">
                            <span>Semua Kategori</span> <i class="fas fa-chevron-down"></i>
                            <div class="categories-dropdown">
                                <a href="#">Elektronik</a>
                                <a href="#">Fashion</a>
                                <a href="#">Rumah Tangga</a>
                                <a href="#">Kesehatan</a>
                                <a href="#">Olahraga</a>
                            </div>
                        </div>
                        <button class="search-button"><i class="fas fa-search"></i></button>
                    </div>
                    </form>
                </div>

                <div class="header-actions flex items-center gap-3 text-sm">

                    @php
                        use Illuminate\Support\Facades\Auth;

                        // Ambil jumlah produk di keranjang jika user login
                        $cartCount = 0;
                        if (Auth::check()) {
                            $cartCount = Auth::user()->keranjang->count(); // atau ->count() tergantung struktur tabel
                        }
                    @endphp
                    {{-- Keranjang --}}
                    <a href="{{ url('/panel?filter=keranjang') }}"
                        class="relative flex items-center gap-1 hover:text-purple-600 transition">
                        <i class="fas fa-shopping-cart text-base"></i>
                        <span class="font-medium">Keranjang</span>

                        @if ($cartCount > 0)
                            <span
                                class="cart-count absolute -top-2 -right-3 bg-purple-600 text-white text-[10px] font-semibold rounded-full px-1.5 py-0.5 leading-none">
                                {{ $cartCount }}
                            </span>
                        
                        @endif
                    </a>

                    {{-- Cek login --}}
             

                    @if (Auth::check())
                        {{-- Sudah login --}}
                        <div class="relative group">
                            <button class="flex items-center gap-2 focus:outline-none hover:text-purple-600 transition">
                                {{-- Inisial --}}
                                <div
                                    class="w-7 h-7 flex items-center justify-center rounded-full bg-purple-600 text-white text-xs font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                {{-- Nama --}}
                                <span
                                    class="font-medium text-gray-800 text-sm">{{ Str::limit(Auth::user()->name, 10) }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </button>

                            {{-- Dropdown --}}
                            <div
                                class="absolute right-0 mt-1 w-40 bg-white rounded-md shadow-md border border-gray-100 hidden group-hover:block z-50">
                                <a href="{{ url('/akun') }}"
                                    class="block px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <i class="fas fa-user-circle mr-1.5"></i> Profil
                                </a>
                                <a href="{{ url('/panel') }}"
                                    class="block px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <i class="fas fa-box mr-1.5"></i> Pesanan
                                </a>
                                  <a href="{{ url('/team') }}"
                                    class="block px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <i class="fas fa-box mr-1.5"></i> Team
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                        <i class="fas fa-sign-out-alt mr-1.5"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Belum login --}}
                        <a href="{{ url('/login') }}" class="flex items-center gap-1 hover:text-purple-600 transition">
                            <i class="fas fa-user text-base"></i>
                            <span class="font-medium">Masuk</span>
                        </a>
                    @endif

                </div>


            </div>
        </div>
    </header>



    @yield('body')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Bantuan & Panduan</h3>
                    <ul>
                        <li><a href="#">Cara Belanja</a></li>
                        <li><a href="#">Pembayaran</a></li>
                        <li><a href="#">Pengiriman</a></li>
                        <li><a href="#">Pengembalian</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Tentang Kami</h3>
                    <ul>
                        <li><a href="#">Tentang PurpleZone</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Jelajahi</h3>
                    <ul>
                        <li><a href="#">Kategori</a></li>
                        <li><a href="#">Brand</a></li>
                        <li><a href="#">Flash Sale</a></li>
                        <li><a href="#">Promo</a></li>
                        <li><a href="#">Gift Card</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Hubungi Kami</h3>
                    <ul>
                        <li><i class="fas fa-phone"></i> 1500-123</li>
                        <li><i class="fas fa-envelope"></i> cs@purplezone.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2023 PurpleZone. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple slider functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    // Remove active class from all dots
                    dots.forEach(d => d.classList.remove('active'));
                    // Add active class to clicked dot
                    this.classList.add('active');
                    // In a real implementation, you would move the slider here
                });
            });

            // Tab functionality
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                });
            });

            // Add to cart animation
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.textContent;
                    this.textContent = 'Ditambahkan!';
                    this.style.background = '#4CAF50';

                    setTimeout(() => {
                        this.textContent = originalText;
                        this.style.background = '';
                    }, 1500);

                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    let count = parseInt(cartCount.textContent);
                    cartCount.textContent = count + 1;

                    // Add animation to cart icon
                    const cartIcon = document.querySelector('.fa-shopping-cart').parentElement;
                    cartIcon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        cartIcon.style.transform = 'scale(1)';
                    }, 300);
                });
            });
        });
    </script>
</body>

</html>
