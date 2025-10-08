@extends('layouts.navUser')
@section('body')
<main class="py-4">
    <section class="trending container">
        <h5 class="mb-4">Pencarian</h5>

        @if ($produks->count() == 0)
            <p class="text-center text-secondary">Produk Tidak Ditemukan, Kami Akan Segera Menambahkan Produk Yang Anda Cari</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                @foreach ($produks as $p)
                   <div
                        class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 group block border border-gray-100 hover:border-pink-400">

                        <!-- Gambar dan link ke detail -->
                        <a href="{{ url('/detail-produk/' . $p->slug) }}">
                            <div
                                class="w-full aspect-square bg-gradient-to-br from-purple-50 to-pink-50 overflow-hidden rounded-t-xl">
                                <img src="{{ $p->media->first()?->file
                                    ? asset('storage/' . $p->media->first()->file)
                                    : asset('img/no-image.jpg') }}"
                                    alt="{{ $p->nama }}"
                                    class="w-full h-48 md:h-64 object-cover object-center transition duration-300 group-hover:scale-105" />
                            </div>
                        </a>

                        <!-- Konten -->
                        <div class="p-4">
                            <a href="{{ url('/detail-produk/' . $p->slug) }}">
                                <h3 class="text-sm font-semibold text-gray-800 group-hover:text-pink-600 truncate">
                                    {{ $p->nama }}
                                </h3>
                            </a>

                            <p class="text-pink-600 font-bold mt-1 text-sm">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </p>

                            <p class="text-sm text-gray-500 mt-1">Terjual: {{ $p->terjual ?? '0' }} produk</p>

                            <!-- Tombol Beli -->
                            <a href="/checkout?produk={{ encrypt($p->id) }}"
                                class="mt-4 block w-full text-center bg-gradient-to-r from-purple-600 to-pink-500 hover:from-pink-600 hover:to-orange-400 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition">
                                Beli Sekarang
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</main>
@endsection
