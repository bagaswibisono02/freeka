@extends('layouts.navUser')
@section('body')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Panel Pesanan</h1>
                <p class="text-gray-600 mt-2">Kelola semua pesanan Anda di satu tempat</p>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
                <div class="p-1">
                @php
    $filters = [
        '' => ['label' => 'Semua', 'icon' => 'grid-fill', 'color' => 'gray'],
        'tunggubayar' => ['label' => 'Menunggu Bayar', 'icon' => 'clock', 'color' => 'yellow'],
        'sudahbayar' => ['label' => 'Sudah Bayar', 'icon' => 'check2-circle', 'color' => 'green'], // ✅ baru
        'keranjang' => ['label' => 'Keranjang', 'icon' => 'cart', 'color' => 'blue'],
        'diproses' => ['label' => 'Diproses', 'icon' => 'truck', 'color' => 'purple'],
        'batal' => ['label' => 'Dibatalkan', 'icon' => 'x-circle', 'color' => 'red'],
        'selesai' => ['label' => 'Selesai', 'icon' => 'check-circle', 'color' => 'emerald'],
    ];
    $active = Request::get('filter');
@endphp

<div class="flex space-x-1 overflow-x-auto no-scrollbar">
    @foreach ($filters as $key => $item)
        <a href="{{ $key === '' ? '/panel' : '/panel?filter=' . $key }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-w-max flex-1 justify-center
                {{ (request()->get('filter') === null && $key === '') || $active === $key
                    ? 'bg-' .
                        $item['color'] .
                        '-100 text-' .
                        $item['color'] .
                        '-700 border-2 border-' .
                        $item['color'] .
                        '-200 shadow-sm'
                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
            <i class="bi bi-{{ $item['icon'] }} text-base"></i>
            <span class="hidden sm:block">{{ $item['label'] }}</span>
        </a>
    @endforeach
</div>

                </div>
            </div>

            <!-- Pesanan List -->
            <div class="space-y-4">
                @forelse ($pesanans as $pesanan)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md">
                        <!-- Header Pesanan -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="bg-purple-100 p-3 rounded-xl">
                                        <i class="bi bi-receipt text-purple-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">#{{ $pesanan->id + 999 }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <!-- Status Badge -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium
                                        {{ match ($pesanan->status) {
                                            'keranjang' => 'bg-blue-100 text-blue-700',
                                            'diproses', 'Berhasil' => 'bg-yellow-100 text-yellow-700',
                                            'batal' => 'bg-red-100 text-red-700',
                                            'selesai' => 'bg-green-100 text-green-700',
                                            'tunggubayar' => 'bg-indigo-100 text-indigo-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        } }}">
                                        <i
                                            class="bi bi-{{ match ($pesanan->status) {
                                                'keranjang' => 'cart',
                                                'diproses' => 'gear',
                                                'batal' => 'x-circle',
                                                'selesai' => 'check-circle',
                                                'tunggubayar' => 'clock',
                                                default => 'circle',
                                            } }}"></i>
                                        {{ ucfirst($pesanan->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content Pesanan -->
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row gap-6">
                                <!-- Gambar Produk -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl overflow-hidden border border-gray-200">
                                        <img src="{{ url('') . '/file?file=' . encrypt($pesanan->produk->media[0]->file) }}"
                                            alt="Produk" class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <!-- Info Produk -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 text-lg leading-tight">
                                        {{ $pesanan->produk->nama }}
                                    </h3>

                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <i class="bi bi-hash"></i>
                                                {{ $pesanan->jumlah }} item
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="bi bi-currency-dollar"></i>
                                                @currency($pesanan->produk->harga) each
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-900">
                                                Total: @currency($pesanan->produk->harga * $pesanan->jumlah)
                                            </p>

                                            <!-- Info Resi -->
                                            @if ($pesanan->resi && $pesanan->resi != 'diterima' && $pesanan->resi != 'direvisi' && $pesanan->resi != 'ditolak')
                                                <div class="flex items-center gap-2 text-sm">
                                                    <i class="bi bi-truck text-purple-600"></i>
                                                    <a href="/lihatresi?pesanan={{ encrypt($pesanan->id) }}"
                                                        class="text-purple-600 hover:text-purple-700 font-medium underline">
                                                        Lacak Resi
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Alert untuk resi ditolak -->
                                        @if ($pesanan->resi == 'ditolak')
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-2">
                                                <div class="flex items-center gap-2 text-red-700">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    <span class="text-sm font-medium">Resi pembayaran tidak valid</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="flex lg:flex-col gap-2 lg:items-end">
                                    @if ($pesanan->status_pembayaran == 'tunggubayar')
                                        @php
                                            $snap = json_decode($pesanan->response_faspay, true);
                                            $tripayUrl =
                                                $snap['data']['checkout_url'] ?? ($snap['data']['pay_url'] ?? '#');
                                        @endphp

                                        <a href="{{ $tripayUrl }}" target="_blank"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-credit-card"></i>
                                            Bayar Sekarang
                                        </a>
                                    @elseif ($pesanan->status_pembayaran == 'paid' )
                                        <a href="/lihatresi?pesanan={{ encrypt($pesanan->id) }}"
                                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-geo-alt"></i>
                                            Lacak Pesanan
                                        </a>
                                    @elseif ($pesanan->status_pembayaran == 'keranjang')
                                        <a href="/checkout?keranjang={{ encrypt($pesanan->id) }}"
                                            class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-arrow-right"></i>
                                            Checkout
                                        </a>
                                    @elseif ($pesanan->status_pembayaran == 'Berhasil')
                                        <a href="/lihat-pesanan?keranjang={{ encrypt($pesanan->id) }}"
                                            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-eye"></i>
                                            Lihat Detail
                                        </a>
                                    @elseif ($pesanan->status_pembayaran == 'selesai')
                                        @if (!$pesanan->review)
                                            <a href="/review?keranjang={{ encrypt($pesanan->id) }}"
                                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="bi bi-star"></i>
                                                Beri Review
                                            </a>
                                        @else
                                            <a href="/review?keranjang={{ encrypt($pesanan->id) }}&review={{ encrypt($pesanan->review->id) }}"
                                                class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="bi bi-pencil"></i>
                                                Edit Review
                                            </a>
                                        @endif
                                    @endif

                                    <!-- Secondary Action -->
                                    <a href="/lihat-pesanan?keranjang={{ encrypt($pesanan->id) }}"
                                        class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200">
                                        <i class="bi bi-info-circle"></i>
                                        Detail
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada pesanan</h3>
                            <p class="text-gray-600 mb-6">Belum ada pesanan yang ditemukan untuk filter ini.</p>
                            <a href="/"
                                class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                                <i class="bi bi-bag"></i>
                                Belanja Sekarang
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination (jika ada) -->
            @if ($pesanans->hasPages())
                <div class="mt-8">
                    {{ $pesanans->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
