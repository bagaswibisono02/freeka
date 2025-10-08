@extends('layouts.navUser')

@section('body')
    <style>
        .hat-purple {
            background: linear-gradient(to right, #8b5cf6, #7c3aed);
        }

        .hat-purple-text {
            background: linear-gradient(to right, #8b5cf6, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hat-purple-btn {
            background: linear-gradient(to right, #8b5cf6, #7c3aed);
            color: white;
        }

        .hat-purple-btn:hover {
            background: linear-gradient(to right, #a78bfa, #c4b5fd);
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold hat-purple-text">Kategori Produk</h1>
            <p class="text-gray-600">Temukan produk berdasarkan kategori pilihanmu</p>
        </div>

        <div class="grid gap-6 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach ($kategoris as $kategori)
                <a href="/search?kategory={{ urlencode($kategori->name) }}"
                    class="group block bg-purple-50 border border-purple-100 shadow-md rounded-xl overflow-hidden transition transform hover:-translate-y-1 hover:shadow-xl duration-300">

                    <div class="h-32 bg-purple-50 flex items-center justify-center overflow-hidden relative">
                        @php
                            $images = $kategori->produk
                                ->take(2)
                                ->flatMap(function ($p) {
                                    return $p->media->pluck('encrypted_url');
                                })
                                ->take(2);
                        @endphp

                        @if ($images->isNotEmpty())
                            <img src="{{ $images[0] }}" alt="{{ $kategori->name }}"
                                class="h-full w-full object-cover absolute top-0 left-0 transition-opacity duration-500 opacity-100 group-hover:opacity-0">

                            @if (isset($images[1]))
                                <img src="{{ $images[1] }}" alt="{{ $kategori->name }}"
                                    class="h-full w-full object-cover absolute top-0 left-0 transition-opacity duration-500 opacity-0 group-hover:opacity-100">
                            @endif
                        @else
                            <div class="text-purple-300 font-medium">Tidak Ada Gambar</div>
                        @endif

                        <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs px-2 py-0.5 rounded shadow">
                            {{ $kategori->produk->count() ?? '0' }} produk
                        </div>
                    </div>

                    <div class="p-4 text-center  bg-gradient-to-r from-purple-400 to-pink-300 bg-purple-100">
                        <h2
                            class="text-sm font-semibold  text-white group-hover:text-purple-900 transition duration-200">
                            {{ $kategori->name }}
                        </h2>
                    </div>

                </a>
            @endforeach
        </div>


    </div>
@endsection
