@extends('layouts.sidebar')

@section('content')
    <div class=" container" style="padding: 20px">
<!-- Header Action -->
<div class="flex flex-wrap justify-between items-center gap-3">
    <!-- Tombol Tambah Produk -->
    <a href="/produk/create"
        class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-5 py-2.5 rounded-xl shadow transition flex items-center">
        <i class="fas fa-plus mr-2"></i> Tambah Produk
    </a>

    <!-- Dropdown Kategori -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-medium shadow transition flex items-center">
            <i class="fas fa-tags mr-2"></i> Kategori
            <i class="fas fa-chevron-down ml-2 text-sm"></i>
        </button>

        <div x-show="open" @click.away="open = false" x-transition
            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-20">
            <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
                class="w-full text-left px-4 py-2 hover:bg-purple-50 text-gray-700 flex items-center gap-2">
                <i class="fas fa-plus-circle text-purple-600"></i> Tambah Kategori
            </button>
            <button data-modal-target="modalLihat" data-modal-toggle="modalLihat"
                class="w-full text-left px-4 py-2 hover:bg-purple-50 text-gray-700 flex items-center gap-2">
                <i class="fas fa-eye text-purple-600"></i> Lihat Kategori
            </button>
        </div>
    </div>
</div>

<div id="modalTambah" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md">
        <form action="/kategori" method="POST">
            @csrf
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-lg text-gray-800">Tambah Kategori</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="nama" required
                        class="mt-1 w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button" data-modal-hide="modalTambah"
                    class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Tambah</button>
            </div>
        </form>
    </div>
</div>


<div id="modalLihat" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-lg text-gray-800">Daftar Kategori</h3>
            <button data-modal-hide="modalLihat" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 max-h-80 overflow-y-auto space-y-2">
            @forelse ($kategory as $kt)
                <div class="flex justify-between items-center border rounded-lg px-3 py-2 hover:bg-gray-50 transition">
                    <span class="font-medium text-gray-700">{{ $kt->name }}</span>
                    <div class="flex items-center gap-2">
                        <button onclick="buka({{ $kt->id }})" class="text-yellow-500 hover:text-yellow-600">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="/kategori/{{ encrypt($kt->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button onclick="return confirm('Hapus kategori ini?')"
                                class="text-red-500 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada kategori.</p>
            @endforelse
        </div>
        <div class="px-6 py-4 border-t text-right">
            <button data-modal-hide="modalLihat"
                class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">Tutup</button>
        </div>
    </div>
</div>

        <!-- Grid Produk -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
            @foreach ($produks as $p)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition p-3 flex flex-col">
                    <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden">
                        @if ($p->media->first()?->file)
                            <img src="{{ $p->media->first()->encrypted_url }}" class="w-full h-full object-cover"
                                alt="Produk">
                        @else
                            <img src="{{ env('APP_URL') . '/file?file=' . encrypt('/qris.png') }}"
                                class="w-full h-full object-cover" alt="Default">
                        @endif
                    </div>

                    <div class="flex-1 mt-3">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1 truncate">
                            <a href="/produk/{{ $p->slug }}" class="hover:text-purple-600">{{ $p->nama }}</a>
                        </h3>
                        <p class="text-gray-500 text-xs mb-2">Harga: @currency($p->harga)</p>
                    </div>

                    <div class="flex justify-between items-center mt-auto">
                        <small class="text-gray-400 text-xs">Terjual {{ $p->terjual }}</small>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="text-gray-500 hover:text-purple-600 p-1 rounded transition">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 bottom-8 w-40 bg-white rounded-xl border border-gray-200 shadow-md overflow-hidden z-20">
                                <form action="/update-terjual/{{ encrypt($p->id) }}" method="post"
                                    class="px-3 py-2 flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="terjual" class="w-14 border rounded text-xs px-1 py-0.5"
                                        placeholder="Qty">
                                    <button class="text-green-600 text-xs font-medium">OK</button>
                                </form>
                                <a href="/produk/{{ encrypt($p->id) }}/edit"
                                    class="block px-4 py-2 text-sm hover:bg-purple-50 text-gray-700">
                                    <i class="fas fa-edit mr-2 text-purple-600"></i> Edit
                                </a>
                                <form action="/produk/{{ encrypt($p->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus produk ini?')"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-purple-50 text-red-600 flex items-center">
                                        <i class="fas fa-trash mr-2"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($produks->hasPages())
            <div class="mt-8 flex justify-center">
                <nav role="navigation" aria-label="Pagination" class="flex items-center space-x-2">
                    {{-- Tombol Prev --}}
                    @if ($produks->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed text-sm">
                            <i class="fas fa-chevron-left mr-1"></i> Prev
                        </span>
                    @else
                        <a href="{{ $produks->previousPageUrl() }}"
                            class="px-3 py-1.5 rounded-lg bg-purple-600 text-white hover:bg-purple-700 text-sm transition">
                            <i class="fas fa-chevron-left mr-1"></i> Prev
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($produks->getUrlRange(1, $produks->lastPage()) as $page => $url)
                        @if ($page == $produks->currentPage())
                            <span
                                class="px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 font-semibold text-sm">{{ $page }}</span>
                        @elseif ($page == 1 || $page == $produks->lastPage() || abs($page - $produks->currentPage()) <= 2)
                            <a href="{{ $url }}"
                                class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-purple-50 text-gray-700 text-sm transition">
                                {{ $page }}
                            </a>
                        @elseif ($page == 2 && $produks->currentPage() > 4)
                            <span class="px-2 text-gray-400">...</span>
                        @elseif ($page == $produks->lastPage() - 1 && $produks->currentPage() < $produks->lastPage() - 3)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($produks->hasMorePages())
                        <a href="{{ $produks->nextPageUrl() }}"
                            class="px-3 py-1.5 rounded-lg bg-purple-600 text-white hover:bg-purple-700 text-sm transition">
                            Next <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed text-sm">
                            Next <i class="fas fa-chevron-right ml-1"></i>
                        </span>
                    @endif
                </nav>
            </div>
        @endif

    </div>



    <script>
        function buka(id) {
            alert("Mode edit kategori " + id + " bisa ditambahkan inline form.");
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
