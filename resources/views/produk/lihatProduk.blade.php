@extends('layouts.navUser')
@section('body')
    <script>
        $(document).ready(function() {
            $("button#keranjang").click(function() {
                console.log('keranjang')
            });
        });
    </script>

    <div class="container mx-auto mt-8 px-4">
   {{-- Alerts --}}
<div id="alerts-container" class="space-y-3 mb-6 transition-all duration-300">
    {{-- Berhasil --}}
    <div id="berhasil" class="hidden w-full bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-lg relative shadow-md">
        <span id="pesanberhasil" class="block font-medium">Pesanan berhasil dimasukkan ke keranjang.</span>
        <button type="button"
            onclick="this.parentElement.classList.add('hidden')"
            class="absolute top-2 right-2 text-green-600 hover:text-green-800 transition">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    {{-- Ada --}}
    <div id="ada" class="hidden w-full bg-yellow-50 border border-yellow-300 text-yellow-800 px-6 py-4 rounded-lg relative shadow-md">
        <span id="pesanada" class="block font-medium">Pesanan sudah ada dalam keranjang.</span>
        <button type="button"
            onclick="this.parentElement.classList.add('hidden')"
            class="absolute top-2 right-2 text-yellow-600 hover:text-yellow-800 transition">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    {{-- Gagal --}}
    <div id="gagal" class="hidden w-full bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-lg relative shadow-md">
        <span id="pesangagal" class="block font-medium">Silakan login terlebih dahulu.</span>
        <button type="button"
            onclick="this.parentElement.classList.add('hidden')"
            class="absolute top-2 right-2 text-red-600 hover:text-red-800 transition">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>

        <style>
            .hide-scrollbar {
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* IE & Edge */
            }

            .hide-scrollbar::-webkit-scrollbar {
                display: none;
                /* Chrome, Safari, Opera */
            }
        </style>

        <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- Gambar Produk -->
            <div class="md:w-1/2 p-4">
                <div id="filePreviewContainer" class="hide-scrollbar flex space-x-3 overflow-x-auto pb-3">
                    @foreach ($produk->media as $media)
                        <div class="flex-shrink-0 w-64 rounded-xl overflow-hidden border border-gray-200">
                            <img src="{{ env('APP_URL') . '/file?file=' . encrypt($media->file) }}"
                                alt="Gambar Produk {{ $produk->nama }}"
                                class="w-full sm:h-48, md:h-56, lg:h-64 sm:h-64 object-cover object-center" />
                        </div>
                    @endforeach
                </div>
                <!-- Tombol Scroll -->
                <div class="flex justify-center mt-6 gap-4">
                    <button onclick="scrollLeftBtn()"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-white shadow-md hover:shadow-lg hover:scale-105 transition-transform duration-300 focus:outline-none">
                        <i class="ph ph-caret-left text-lg"></i>
                    </button>
                    <button onclick="scrollRightBtn()"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-white shadow-md hover:shadow-lg hover:scale-105 transition-transform duration-300 focus:outline-none">
                        <i class="ph ph-caret-right text-lg"></i>
                    </button>
                </div>

            </div>

            <!-- Detail Produk -->
            <div class="md:w-1/2 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-2 leading-tight">
                        {{ $produk->nama }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-500 mb-4">
                        Kategori:
                        <span class="font-medium text-gray-700">{{ $produk->kategory->name }}</span>
                    </p>

                    <!-- Varian -->
                    @if ($produk->varian->count())
                        <p class="mb-4">
                            <span class="font-semibold">Varian:</span>
                            @foreach ($produk->varian as $varian)
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ $varian->nama }}
                                </span>
                            @endforeach
                        </p>
                    @endif

                    <!-- Harga dan Ongkir -->
                    <div class="mb-4">
                        <h3 class="text-xl sm:text-2xl text-red-600 font-extrabold">@currency($produk->harga)</h3>
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 mt-1">
                            <img src="https://images.tokopedia.net/img/cache/700/VqbcmM/2022/2/22/682b7c8a-6a43-4c9a-a0ef-92d221af7fb9.jpg"
                                alt="Gratis Ongkir" class="w-5 h-5 sm:w-6 sm:h-6" />
                            <span>Gratis Ongkir – Semarang, Bandung, Jakarta</span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center gap-3 mt-5">
                    <!-- Ikon Keranjang -->
                    <button onclick="keranjang('{{ encrypt($produk->id) }}')" id="keranjang"
                        class="p-2 sm:p-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center justify-center w-11 sm:w-12 h-11 sm:h-12">
                        <i class="ph ph-shopping-cart-simple text-lg sm:text-xl"></i>
                    </button>

                    <!-- Tombol Pesan Sekarang -->
                    <a href="/checkout?produk={{ encrypt($produk->id) }}"
                        class="flex-1 max-w-full md:max-w-xs lg:max-w-sm py-2.5 sm:py-3 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-pink-700 hover:to-orange-400 text-white rounded-lg font-semibold text-center text-sm sm:text-base transition">
                        Pesan Sekarang
                    </a>
                </div>


            </div>
        </div>

        <hr class="my-8 border-gray-300">

        {{-- Detail Produk --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h3 class="text-xl font-semibold mb-4">Detail Produk</h3>
            <div class="prose max-w-none" style="text-align: justify;">
                {!! $produk->keterangan !!}
            </div>
        </div>

        {{-- Reviews --}}
        <div class="mb-8">
            <h3 class="text-2xl font-semibold mb-4">Product Reviews</h3>
            @if ($reviews->count() <= 0)
                <p class="text-gray-500">Belum Ada Review</p>
            @endif

            @foreach ($reviews as $review)
                <div class="bg-white shadow rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-4">
                        <img src="https://via.placeholder.com/50" alt="User"
                            class="rounded-full w-12 h-12 object-cover">
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="font-semibold">{{ $review->user->name }}</h4>
                                <time class="text-gray-400 text-sm">{{ $review->created_at->format('d M Y') }}</time>
                            </div>
                            @if ($review->mediaReview)
                                <div class="flex space-x-2 overflow-x-auto mb-2">
                                    @foreach ($review->mediaReview as $m)
                                        <img class="w-24 h-24 object-cover rounded"
                                            src="{{ '/file?file=' . encrypt($m->file) }}" alt="Review Image">
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex space-x-1 text-yellow-400 mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span
                                        class="@if ($review->bintang >= $i) text-yellow-400 @else text-gray-300 @endif text-xl">&#9733;</span>
                                @endfor
                            </div>
                            <p class="text-gray-700">{{ $review->hasil }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-center">
                {{ $reviews->links() }}
            </div>
        </div>

        {{-- Produk Serupa --}}
        <section class="mb-12">
            <h3 class="text-xl font-semibold mb-6">Kategori Sama</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                @foreach ($produkSerupa as $p)
                    <div
                        class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 group block border border-gray-100 hover:border-pink-400">

                        <!-- Gambar dan link ke detail -->
                        <a href="{{ url('/detail-produk/' . $p->slug) }}">
                            <div
                                class="w-full aspect-square bg-gradient-to-br from-purple-50 to-pink-50 overflow-hidden rounded-t-xl">
                                <img src="{{ $p->media->first()?->encrypted_url ?? asset('img/no-image.jpg') }}"
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
            <div class="flex justify-center mt-6">
                <a href="/search?kategory={{ $produk->kategory->name }}"
                    class="border border-blue-600 text-blue-600 px-6 py-2 rounded hover:bg-blue-600 hover:text-white transition">
                    Tampilkan Lebih
                </a>
            </div>
        </section>
    </div>

    <input type="hidden" id="produk" value="{{ $produk->id }}">

    <script src="/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function keranjang(produk) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {

                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText == 'true' || this.responseText === true) {
                        document.getElementById("berhasil").classList.remove('hidden');
                    } else if (this.responseText == 'ada') {
                        document.getElementById("ada").classList.remove('hidden');
                    } else {
                        document.getElementById("gagal").classList.remove('hidden');
                    }
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                     // Otomatis sembunyikan alert setelah 3 detik
            setTimeout(() => {
                document.getElementById("berhasil").classList.add('hidden');
                document.getElementById("ada").classList.add('hidden');
                document.getElementById("gagal").classList.add('hidden');
            }, 3000);
                }
            };
            xmlhttp.open("GET", "/masukan-keranjang?produk=" + produk, true);
            xmlhttp.send();
        }

        function scrollLeftBtn() {
            const container = document.getElementById('filePreviewContainer');
            container.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        }

        function scrollRightBtn() {
            const container = document.getElementById('filePreviewContainer');
            container.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        }
    </script>
@endsection
