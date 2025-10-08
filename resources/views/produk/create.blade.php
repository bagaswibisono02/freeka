@extends('layouts.sidebar')

@section('head')
<!-- Trix CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.3.0/trix.min.css" integrity="sha512-k5B2jQ6nLqzS9RgXf1kMV0Pj0xPGzO6XQZGLV5WZjzS0rBco9EoT06a+3ZFG2dFFhfCqV4Zxv5KJk0sCHe/wRQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white shadow-md rounded-xl p-6 max-w-4xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold text-blue-600">Tambah Produk Baru</h2>

        <!-- Preview Media -->
        <div id="filePreviewContainer" class="mb-6">
            <div id="filePreview" class="flex gap-3 overflow-x-auto py-2 px-1"></div>
            <div id="tombol" class="hidden justify-center mt-3">
                <button id="scrollLeft" type="button" class="px-3 py-1 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition" onclick="scrollPreview(-200)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button id="scrollRight" type="button" class="ml-2 px-3 py-1 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition" onclick="scrollPreview(200)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <form action="/produk" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Nama Produk -->
            <div>
                <label class="block font-medium mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('nama') border-red-500 @enderror" placeholder="Masukkan nama produk..." value="{{ old('nama') }}" required>
                @error('nama') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Foto / Video Produk -->
            <div>
                <label class="block font-medium mb-1">Foto / Video Produk <span class="text-red-500">*</span></label>
                <input type="file" id="fileInput" name="media[]" multiple accept="image/*,video/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('media') border-red-500 @enderror" onchange="previewFiles()" required>
                @error('media') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('category_id') border-red-500 @enderror" required>
                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Pilih kategori</option>
                    @foreach ($kategoryes as $k)
                        <option value="{{ $k->id }}" {{ old('category_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Harga Produk -->
            <div>
                <label class="block font-medium mb-1">Harga Produk <span class="text-red-500">*</span></label>
                <input type="number" name="hargajual" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('hargajual') border-red-500 @enderror" placeholder="Masukkan harga jual..." value="{{ old('hargajual') }}" required>
                @error('hargajual') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Link Supplier -->
            <div>
                <label class="block font-medium mb-2">Link Supplier & Harga Asli</label>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-left" id="myTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border-b">Link Supplier</th>
                                <th class="px-4 py-2 border-b">Harga Asli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $links = old('link', ['']);
                                $hargaAslis = old('hargaAsli', ['']);
                            @endphp
                            @foreach ($links as $i => $link)
                                <tr>
                                    <td class="px-4 py-2 border-b">
                                        <input type="url" name="link[]" class="w-full border border-gray-300 rounded px-2 py-1 @error('link.' . $i) border-red-500 @enderror" value="{{ $link }}" placeholder="Link Supplier">
                                        @error('link.' . $i) <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        <input type="number" name="hargaAsli[]" class="w-full border border-gray-300 rounded px-2 py-1 @error('hargaAsli.' . $i) border-red-500 @enderror" value="{{ $hargaAslis[$i] ?? '' }}" placeholder="Harga Asli">
                                        @error('hargaAsli.' . $i) <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition float-right text-sm" onclick="tambahLink()">+ Tambah Link</button>
            </div>

            <!-- Varian Produk -->
            <div>
                <label class="block font-medium mb-2">Varian Produk</label>
                <div id="list-varian" class="flex flex-wrap gap-2 mb-3">
                    @if (old('input_varian'))
                        @foreach (old('input_varian') as $v)
                            <div class="flex items-center gap-1 bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                                <input type="hidden" name="input_varian[]" value="{{ $v }}">
                                <span>{{ $v }}</span>
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">❌</button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="flex gap-2">
                    <input type="text" id="input-varian" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none" placeholder="Nama Varian">
                    <button type="button" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition" onclick="masukanVarian()">Tambah</button>
                </div>
            </div>

            <!-- Daerah Gratis Ongkir -->
            <div>
                <label class="block font-medium mb-2">Daerah Gratis Ongkir</label>
                <div id="provinsi-container" class="grid grid-cols-2 md:grid-cols-3 gap-2"></div>
            </div>

            <!-- Deskripsi Produk -->
            <div>
                <label for="keterangan" class="block font-medium mb-2">Deskripsi Produk <span class="text-red-500">*</span></label>
                <input id="keterangan" type="hidden" name="keterangan" value="{{ old('keterangan') }}">
                <trix-editor input="keterangan" class="w-full border border-gray-300 rounded-lg p-3 min-h-[200px] bg-white"></trix-editor>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="text-right">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">💾 Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<!-- Trix JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.3.0/trix.umd.min.js" integrity="sha512-xjCeq3DcTg01zP7rWzfqj9ZB9Bj+u6O1oa5eH2p5yhQoQ7gn36RVktikZf4xZdyPb+eXW1wXubNwZsTNY3Cj2w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
function scrollPreview(offset) {
    document.getElementById('filePreview').scrollBy({ left: offset, behavior: 'smooth' });
}

function masukanVarian() {
    const input = document.getElementById('input-varian');
    const val = input.value.trim();
    if (!val) return;
    const list = document.getElementById('list-varian');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-1 bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm';
    div.innerHTML = `<input type="hidden" name="input_varian[]" value="${val}"><span>${val}</span><button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">❌</button>`;
    list.appendChild(div);
    input.value = '';
}

function tambahLink() {
    const tbody = document.querySelector('#myTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="px-4 py-2 border-b"><input type="url" name="link[]" class="w-full border border-gray-300 rounded px-2 py-1" placeholder="Link Supplier"></td>
        <td class="px-4 py-2 border-b"><input type="number" name="hargaAsli[]" class="w-full border border-gray-300 rounded px-2 py-1" placeholder="Harga Asli"></td>
    `;
    tbody.appendChild(tr);
}

document.addEventListener('DOMContentLoaded', () => {
    fetch('/wilayah/provinsi.json')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('provinsi-container');
            const selected = @json(old('provinsi', []));
            container.innerHTML = data.map(p => `
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="provinsi[]" value="${p.id}" ${selected.includes(p.id.toString()) ? 'checked' : ''} class="form-checkbox h-5 w-5 text-blue-600">
                    <span>${p.nama}</span>
                </label>
            `).join('');
        })
        .catch(err => console.error('Gagal memuat provinsi:', err));
});
</script>
@endsection
