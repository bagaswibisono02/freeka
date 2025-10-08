@extends('layouts.navUser')
@section('body')
    @if (session('error'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form
        action="/checkout?@if (Request::get('keranjang')) keranjang={{ Request::get('keranjang') }}@else{{ 'produk=' . encrypt($produk->id) }} @endif"
        method="post" class="mt-6">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-lg shadow-md border border-purple-100 max-w-6xl mx-auto">
                <!-- Header -->
                <div class="bg-purple-600 p-6 text-white rounded-t-lg">
                    <h4 class="text-2xl font-bold text-center">Checkout Produk</h4>
                </div>

                <div class="p-6">
                    <!-- Alamat Section -->
                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <div>
                                <h5 class="text-lg font-semibold text-gray-800">Alamat Pengiriman</h5>
                                <span id="alamatfix" class="text-purple-700 font-medium">Belum dipilih</span>
                            </div>
                            <button type="button" id="ubah"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-edit mr-2"></i>Pilih Alamat
                            </button>
                        </div>

                        <div id="hidden-penerima"></div>
                        @error('penerima')
                            <p class="text-red-500 text-sm mt-2">Pilih Alamat Pengiriman</p>
                        @enderror

                        <div id="fix-alamat" class="mt-4"></div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mt-4">
                            <p class="text-yellow-700 text-sm">
                                Pastikan alamat Anda terdaftar di daerah gratis ongkir
                            </p>
                        </div>
                    </div>

                    <!-- Produk Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Gambar Produk -->
                        <div class="space-y-4">
                            <!-- Gambar Utama -->
                            <div class="w-full aspect-square bg-purple-100 rounded-lg overflow-hidden">
                                <img id="mainImage"
                                    src="{{ env('APP_URL') . '/file?file=' . encrypt($produk->media[0]->file) }}"
                                    alt="Gambar Utama" class="w-full h-full object-cover">
                            </div>

                            <!-- Thumbnail -->
                            <div class="flex space-x-2 overflow-x-auto">
                                @foreach ($produk->media as $media)
                                    <img class="thumbnail w-16 h-16 object-cover rounded border border-purple-300 cursor-pointer"
                                        src="{{ env('APP_URL') . '/file?file=' . encrypt($media->file) }}"
                                        data-src="{{ env('APP_URL') . '/file?file=' . encrypt($media->file) }}"
                                        alt="Thumbnail">
                                @endforeach
                            </div>
                        </div>

                        <!-- Detail Produk -->
                        <div class="space-y-4">
                            <!-- Nama & Kategori -->
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">{{ $produk->nama }}</h1>
                                <p class="text-gray-500 mt-1">
                                    Kategori: <span class="text-purple-600">{{ $produk->kategory->name }}</span>
                                </p>
                            </div>

                            <!-- Harga -->
                            <div class="text-3xl font-bold text-purple-700">@currency($produk->harga)</div>

                            <!-- Gratis Ongkir -->
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-purple-700 font-medium">
                                    Gratis Ongkir ke:
                                    @foreach ($produk->provinsi as $item)
                                        {{ $item->nama }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            </div>

                            <!-- Varian -->
                            @if ($produk->varian->count() > 0)
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">Pilih Varian:</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($produk->varian as $varian)
                                            <label
                                                class="border border-purple-400 px-4 py-2 rounded bg-purple-50 text-purple-700 cursor-pointer hover:bg-purple-100 transition">
                                                <input type="checkbox" class="hidden varian-checkbox"
                                                    value="{{ $varian->id }}" data-harga="{{ $produk->harga }}">
                                                {{ $varian->nama }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @error('input-varian')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            <!-- Total & Jumlah -->
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <p class="text-gray-600">Total Harga</p>
                                        <div id="hargatotal" class="text-xl font-bold text-purple-700">@currency($produk->harga)
                                        </div>
                                        <span id="harga_awal" class="hidden">{{ $produk->harga }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button" onclick="minus()"
                                            class="w-8 h-8 rounded-full bg-white border border-purple-400 text-purple-700">−</button>
                                        <span id="total" class="text-lg font-medium">1</span>
                                        <button type="button" onclick="add()"
                                            class="w-8 h-8 rounded-full bg-white border border-purple-400 text-purple-700">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 bg-white border border-purple-200 rounded-2xl p-5 shadow-sm">
                                <label for="metode"
                                    class="block font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-wallet text-purple-600"></i>
                                    Pilih Metode Pembayaran
                                </label>

                                <!-- Custom wrapper -->
                                <div class="relative">
                                    <div id="custom-select"
                                        class="border border-purple-300 rounded-lg p-3 flex justify-between items-center cursor-pointer hover:border-purple-500 transition">
                                        <div>
                                            <p id="selected-method" class="font-medium text-gray-800">Pilih Metode
                                                Pembayaran</p>
                                            <p id="selected-fee" class="text-sm text-gray-500">–</p>
                                        </div>
                                        <i class="fas fa-chevron-down text-purple-500"></i>
                                    </div>

                                    <!-- Dropdown content -->
                                    <div id="custom-options"
                                        class="absolute z-10 w-full bg-white border border-purple-200 rounded-xl mt-2 shadow-xl hidden max-h-72 overflow-y-auto">
                                        <div class="text-center text-gray-400 py-3" id="loading-text">Memuat metode
                                            pembayaran...</div>
                                    </div>

                                    <!-- Hidden real select (untuk dikirim ke backend) -->
                                    <select id="metode" name="metode" class="hidden" required></select>
                                </div>

                                <div id="fee-info" class="mt-5 hidden bg-purple-50 rounded-xl p-4">
                                    <div class="flex justify-between text-sm text-gray-700">
                                        <span>Subtotal:</span>
                                        <span id="subtotal-text" class="font-semibold text-gray-800">Rp0</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-700 mt-1">
                                        <span>Biaya Admin:</span>
                                        <span id="fee-text" class="font-semibold text-gray-800">Rp0</span>
                                    </div>
                                    <div
                                        class="flex justify-between text-base font-semibold text-purple-700 border-t border-purple-200 mt-2 pt-2">
                                        <span>Total Bayar:</span>
                                        <span id="total-bayar-text">Rp0</span>
                                    </div>
                                </div>
                            </div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const hargaAwal = parseFloat(document.getElementById('harga_awal').innerText.replace(/[^0-9]/g, '') || 0);
    const customSelect = document.getElementById('custom-select');
    const customOptions = document.getElementById('custom-options');
    const metodeSelect = document.getElementById('metode');
    const selectedMethod = document.getElementById('selected-method');
    const selectedFee = document.getElementById('selected-fee');
    const feeInfo = document.getElementById('fee-info');
    const subtotalText = document.getElementById('subtotal-text');
    const feeText = document.getElementById('fee-text');
    const totalText = document.getElementById('total-bayar-text');
    const loadingText = document.getElementById('loading-text');

    subtotalText.textContent = hargaAwal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

    try {
        const res = await fetch('/api/tripay/channels');
        const channels = await res.json();

        channels.forEach(c => {
            c.total_biaya = c.total_fee.flat + (c.total_fee.percent * hargaAwal / 100);
        });

        channels.sort((a, b) => a.total_biaya - b.total_biaya);

        customOptions.innerHTML = '';
        metodeSelect.innerHTML = '<option value="">Pilih Metode Pembayaran</option>';

        channels.forEach(ch => {
            const fee = Math.round(ch.total_biaya);
            const option = document.createElement('option');
            option.value = ch.code;
            option.textContent = ch.name;
            metodeSelect.appendChild(option);

            const card = document.createElement('div');
            card.className = "flex items-center gap-3 p-3 border-b border-gray-100 hover:bg-purple-50 cursor-pointer transition";
            card.innerHTML = `
                <img src="${ch.icon_url || `https://tripay.co.id/images/bank/${ch.code.toLowerCase()}.png`}"
                     class="w-10 h-10 object-contain" alt="${ch.name}">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">${ch.name}</p>
                    <p class="text-xs text-gray-500">${ch.group}</p>
                    <p class="text-xs text-purple-600 font-medium mt-1">Fee: Rp${fee.toLocaleString('id-ID')}</p>
                </div>
            `;

            card.addEventListener('click', () => {
                // Update select value
                metodeSelect.value = ch.code;

                // Tampilkan data terpilih
                selectedMethod.textContent = ch.name;
                selectedFee.textContent = `Fee: Rp${fee.toLocaleString('id-ID')}`;

                // Hitung total bayar
                const totalBayar = hargaAwal + fee;
                feeText.textContent = fee.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                totalText.textContent = totalBayar.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                feeInfo.classList.remove('hidden');

                // Tutup dropdown
                customOptions.classList.add('hidden');

                const totalInput = document.getElementById('total_bayar_input');
                if (totalInput) totalInput.value = totalBayar;
            });

            customOptions.appendChild(card);
        });
    } catch (err) {
        console.error('Gagal memuat metode pembayaran Tripay:', err);
        loadingText.textContent = 'Gagal memuat data. Silakan refresh halaman.';
    }

    // Toggle dropdown
    customSelect.addEventListener('click', () => {
        customOptions.classList.toggle('hidden');
    });

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', e => {
        if (!customSelect.contains(e.target) && !customOptions.contains(e.target)) {
            customOptions.classList.add('hidden');
        }
    });
});
</script>





                            @csrf
                            <input type="hidden" name="produk" value="{{ encrypt($produk->id) }}">
                            <input type="hidden" name="jumlahBeli" id="input_jumlah_beli" value="1">
                            <input type="hidden" id="input_varian" name="varian" value="Original">

                            <!-- Catatan -->
                            <div>
                                <label class="block font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                                <textarea name="catatan" rows="3" placeholder="Tulis catatan untuk penjual"
                                    class="w-full px-3 py-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-400"></textarea>
                            </div>

                            <!-- Tombol -->
                            <button type="submit"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-semibold transition">
                                🛒 Pesan Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal -->
    <div class="fixed inset-0 z-50 hidden" id="modalOverlay">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-purple-600 p-4 text-white rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Pilih Alamat Pengiriman</h3>
                        <button type="button" id="close-modal" class="text-white text-xl">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Tabs -->
                    <div class="flex border-b border-purple-200 mb-4">
                        <button id="tab-pilih"
                            class="px-4 py-2 font-semibold text-purple-600 border-b-2 border-purple-600">
                            Pilih Alamat
                        </button>
                        <button id="tab-baru" class="px-4 py-2 font-semibold text-gray-500">
                            Alamat Baru
                        </button>
                    </div>

                    <!-- Tab Pilih Alamat -->
                    <div id="pilih-alamat" class="space-y-4">
                        <div>
                            <label for="alamat-terdaftar" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Tersimpan
                            </label>
                            <select id="alamat-terdaftar" name="penerima"
                                class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Pilih salah satu alamat</option>
                                @foreach (Auth::user()->penerima as $p)
                                    <option value="{{ encrypt($p->id) }}" data-penerima="{{ $p->penerima }}"
                                        data-contact="{{ $p->contact }}" data-alamat="{{ $p->alamat }}">
                                        {{ $p->penerima }} | {{ $p->contact }} | {{ \Str::limit($p->alamat, 50) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Detail Alamat -->
                        <div id="detail-alamat" class="hidden bg-purple-50 p-4 rounded border border-purple-200">
                            <h4 class="font-semibold text-gray-800 mb-2">Alamat Terpilih</h4>
                            <div class="text-gray-700 space-y-1">
                                <p><strong>Nama:</strong> <span id="detail-nama"></span></p>
                                <p><strong>Kontak:</strong> <span id="detail-contact"></span></p>
                                <p><strong>Alamat:</strong> <span id="detail-fullalamat"></span></p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button id="gunakan" disabled
                                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Gunakan Alamat Ini
                            </button>
                        </div>
                    </div>

                    <!-- Tab Alamat Baru -->
                    <div id="form-alamat-baru" class="hidden space-y-4">
                        <form id="alamatForm" action="/tambah-penerima" method="post" class="space-y-4">
                            <script>
                                document.addEventListener("DOMContentLoaded", async () => {
                                    const provinsiSelect = document.getElementById("provinsi");
                                    const kabupatenSelect = document.getElementById("kabupaten");
                                    const kecamatanSelect = document.getElementById("kecamatan");
                                    const kelurahanSelect = document.getElementById("kelurahan");
                                    const rtRwInput = document.getElementById("rt_rw");
                                    const jalanInput = document.getElementById("jalan");

                                    // 🔹 Load data provinsi saat halaman dimuat
                                    async function loadProvinces() {
                                        const res = await fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json");
                                        const provinces = await res.json();

                                        provinces.forEach(prov => {
                                            const opt = document.createElement("option");
                                            opt.value = prov.id;
                                            opt.textContent = prov.name;
                                            provinsiSelect.appendChild(opt);
                                        });
                                    }

                                    // 🔹 Load kabupaten sesuai provinsi
                                    async function loadRegencies(provinceId) {
                                        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
                                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                                        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

                                        if (!provinceId) return;

                                        const res = await fetch(
                                            `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`);
                                        const regencies = await res.json();

                                        regencies.forEach(kab => {
                                            const opt = document.createElement("option");
                                            opt.value = kab.id;
                                            opt.textContent = kab.name;
                                            kabupatenSelect.appendChild(opt);
                                        });

                                        kabupatenSelect.disabled = false;
                                    }

                                    // 🔹 Load kecamatan
                                    async function loadDistricts(regencyId) {
                                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                                        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

                                        if (!regencyId) return;

                                        const res = await fetch(
                                            `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`);
                                        const districts = await res.json();

                                        districts.forEach(kec => {
                                            const opt = document.createElement("option");
                                            opt.value = kec.id;
                                            opt.textContent = kec.name;
                                            kecamatanSelect.appendChild(opt);
                                        });

                                        kecamatanSelect.disabled = false;
                                    }

                                    // 🔹 Load kelurahan
                                    async function loadVillages(districtId) {
                                        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                                        if (!districtId) return;

                                        const res = await fetch(
                                            `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`);
                                        const villages = await res.json();

                                        villages.forEach(kel => {
                                            const opt = document.createElement("option");
                                            opt.value = kel.id;
                                            opt.textContent = kel.name;
                                            kelurahanSelect.appendChild(opt);
                                        });

                                        kelurahanSelect.disabled = false;
                                        rtRwInput.disabled = false;
                                        jalanInput.disabled = false;
                                    }

                                    // 🔹 Event listener antar dropdown
                                    provinsiSelect.addEventListener("change", e => {
                                        kabupatenSelect.disabled = true;
                                        kecamatanSelect.disabled = true;
                                        kelurahanSelect.disabled = true;
                                        rtRwInput.disabled = true;
                                        jalanInput.disabled = true;
                                        loadRegencies(e.target.value);
                                    });

                                    kabupatenSelect.addEventListener("change", e => {
                                        kecamatanSelect.disabled = true;
                                        kelurahanSelect.disabled = true;
                                        rtRwInput.disabled = true;
                                        jalanInput.disabled = true;
                                        loadDistricts(e.target.value);
                                    });

                                    kecamatanSelect.addEventListener("change", e => {
                                        kelurahanSelect.disabled = true;
                                        rtRwInput.disabled = true;
                                        jalanInput.disabled = true;
                                        loadVillages(e.target.value);
                                    });

                                    // 🔹 Jalankan pertama kali
                                    loadProvinces();
                                });
                            </script>

                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="penerima" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Penerima
                                    </label>
                                    <input type="text" id="penerima" name="penerima" placeholder="Nama lengkap"
                                        class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required />
                                </div>
                                <div>
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Telepon
                                    </label>
                                    <input type="text" id="contact" name="contact" placeholder="081234567890"
                                        class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required />
                                </div>
                            </div>

                            <div>
                                <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Provinsi
                                </label>
                                <select id="provinsi" name="provinsi"
                                    class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="kabupaten" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kabupaten/Kota
                                    </label>
                                    <select id="kabupaten" name="kabupaten"
                                        class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required disabled>
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kecamatan
                                    </label>
                                    <select id="kecamatan" name="kecamatan"
                                        class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="kelurahan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kelurahan/Desa
                                    </label>
                                    <select id="kelurahan" name="kelurahan"
                                        class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required disabled>
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="rt_rw" class="block text-sm font-medium text-gray-700 mb-1">
                                        RT/RW
                                    </label>
                                    <input type="text" id="rt_rw" name="rt_rw" placeholder="01/05" disabled
                                        class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required />
                                </div>
                            </div>

                            <div>
                                <label for="jalan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jalan & Nomor Rumah
                                </label>
                                <input type="text" id="jalan" name="jalan" placeholder="Jl. Melati No. 10"
                                    disabled
                                    class="w-full p-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    required />
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded transition">
                                    Simpan Alamat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div x-data="{ open: true }" x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-red-600">Terjadi Kesalahan</h3>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>
                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <div class="mt-4 text-right">
                    <button @click="open = false"
                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Thumbnail functionality
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.addEventListener('click', function() {
                const mainImage = document.getElementById('mainImage');
                if (mainImage) {
                    mainImage.src = this.dataset.src;
                }
            });
        });

        // Modal functionality
        const ubahBtn = document.getElementById('ubah');
        const modalOverlay = document.getElementById('modalOverlay');
        const closeModal = document.getElementById('close-modal');

        ubahBtn.addEventListener('click', () => {
            modalOverlay.classList.remove('hidden');
        });

        closeModal.addEventListener('click', () => {
            modalOverlay.classList.add('hidden');
        });

        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.add('hidden');
            }
        });

        // Tab functionality
        const tabPilih = document.getElementById('tab-pilih');
        const tabBaru = document.getElementById('tab-baru');
        const pilihAlamat = document.getElementById('pilih-alamat');
        const formAlamatBaru = document.getElementById('form-alamat-baru');

        tabPilih.addEventListener('click', () => {
            tabPilih.classList.add('text-purple-600', 'border-purple-600');
            tabBaru.classList.remove('text-purple-600', 'border-purple-600');
            tabBaru.classList.add('text-gray-500');
            pilihAlamat.classList.remove('hidden');
            formAlamatBaru.classList.add('hidden');
        });

        tabBaru.addEventListener('click', () => {
            tabBaru.classList.add('text-purple-600', 'border-purple-600');
            tabPilih.classList.remove('text-purple-600', 'border-purple-600');
            tabPilih.classList.add('text-gray-500');
            formAlamatBaru.classList.remove('hidden');
            pilihAlamat.classList.add('hidden');
        });

        // Alamat selection functionality
        document.getElementById('gunakan').addEventListener('click', () => {
            modalOverlay.classList.add('hidden');

            const detailAlamat = document.getElementById('detail-alamat');
            const fixAlamat = document.getElementById('fix-alamat');

            fixAlamat.innerHTML = detailAlamat.innerHTML;
            detailAlamat.classList.add('hidden');

            const selectAlamat = document.getElementById('alamat-terdaftar');
            const selectedValue = selectAlamat.value;

            const container = document.getElementById('hidden-penerima');
            container.innerHTML = '';

            if (selectedValue) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'penerimaPesanan';
                hiddenInput.value = selectedValue;
                container.appendChild(hiddenInput);
            }
        });

        document.getElementById('alamat-terdaftar').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const nama = selected.getAttribute('data-penerima');
            const contact = selected.getAttribute('data-contact');
            const alamat = selected.getAttribute('data-alamat');

            if (nama && contact && alamat) {
                document.getElementById('detail-nama').textContent = nama;
                document.getElementById('detail-contact').textContent = contact;
                document.getElementById('detail-fullalamat').textContent = alamat;
                document.getElementById('detail-alamat').classList.remove('hidden');
                document.getElementById('gunakan').disabled = false;
            } else {
                document.getElementById('detail-alamat').classList.add('hidden');
                document.getElementById('gunakan').disabled = true;
            }
        });

        // Quantity functionality
        let jumlah = 1;
        const hargaSatuan = parseInt(document.getElementById('harga_awal').innerText);
        const jumlahSpan = document.getElementById('total');
        const totalHargaEl = document.getElementById('hargatotal');
        const jumlahInput = document.getElementById('input_jumlah_beli');

        function updateHarga() {
            jumlahSpan.innerText = jumlah;
            jumlahInput.value = jumlah;
            const total = hargaSatuan * jumlah;
            totalHargaEl.innerText = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(total);
        }

        function add() {
            jumlah++;
            updateHarga();
        }

        function minus() {
            if (jumlah > 1) {
                jumlah--;
                updateHarga();
            }
        }

        updateHarga();

        @if (session('berhasil'))
            document.addEventListener("DOMContentLoaded", function() {
                const ubahBtn = document.getElementById('ubah');
                if (ubahBtn) {
                    ubahBtn.click();
                }
            });
        @endif
    </script>


@endsection
