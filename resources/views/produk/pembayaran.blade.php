@extends('layouts.navUser')
@section('body')
<style>
    body {
        background: linear-gradient(to bottom right, #e3f2fd, #ffffff);
        min-height: 100vh;
        font-family: 'Segoe UI', sans-serif;
    }

    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #0d6efd;
        color: white;
        padding: 2rem;
        text-align: center;
        font-weight: bold;
        font-size: 1.5rem;
    }

    .btn-checkout {
        background-color: #ffc107;
        border: none;
        color: #000;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 30px;
        transition: 0.3s ease;
    }

    .btn-checkout:hover {
        background-color: #e0a800;
        color: #fff;
    }

    .section-title {
        font-weight: 600;
        margin-top: 2rem;
    }

    #imagePreview {
        max-height: 300px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    @media (max-width: 576px) {
        .card-header-custom {
            font-size: 1.2rem;
        }

        .btn-checkout {
            width: 100%;
        }
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            {{-- Card Utama --}}
            <div class="card card-custom">
                <div class="card-header-custom">
                    Pembayaran Pesanan
                    <div class="mt-2 btn-checkout">
                        @currency($pesanan->jumlah * $pesanan->produk->harga)
                    </div>
                </div>

                <div class="card-body p-4 bg-white">
                    {{-- Upload Form --}}
                    <h5 class="section-title">Upload Slip Pembayaran</h5>
                    <p class="text-muted small">
                        Pastikan slip pembayaran mencantumkan:
                    </p>
                    <ul class="small text-muted mb-3">
                        <li>Tulisan "Berhasil"</li>
                        <li>Nominal sesuai</li>
                        <li>Nomor pesanan (opsional)</li>
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Lihat Contoh</a></li>
                    </ul>

                    {{-- Preview Gambar --}}
                    <div class="mb-3 text-center">
                        <img id="imagePreview"
                             src="@if($pesanan->resi=='ditolak' && $pesanan->konfirmasiPembayaran){{ env('APP_URL').'/file?file='.encrypt($pesanan->konfirmasiPembayaran->bukti_transaksi) }}@endif"
                             class="img-fluid border rounded shadow-sm"
                             alt="Preview Slip">
                        @if($pesanan->resi == 'ditolak')
                            <div class="text-danger mt-2 small">* Bukti sebelumnya tidak valid. Silakan unggah ulang.</div>
                        @endif
                    </div>

                    {{-- Form --}}
                    <form action="/upload-verifikasi-pembayaran" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" accept="image/*" onchange="previewImage(event)"
                                   name="form-pembayaran" class="form-control">
                            <input type="hidden" name="pesanan" value="{{ encrypt($pesanan->id) }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100"
                                onclick="return confirm('Pastikan Slip Transaksi Valid Sebelum Dikirim')">Kirim Bukti Pembayaran</button>
                    </form>
                </div>
            </div>

            {{-- Accordion Metode Pembayaran --}}
            <div class="accordion mt-4" id="accordionPembayaran">
                {{-- QRIS --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#qrisCollapse">
                            Pembayaran via QRIS
                        </button>
                    </h2>
                    <div id="qrisCollapse" class="accordion-collapse collapse show">
                        <div class="accordion-body text-center">
                            <img src="{{ env('APP_URL') . '/file?file=' . encrypt('qris.png') }}" width="60%" class="rounded shadow-sm mb-3" alt="QRIS">
                            <br>
                            <button class="btn btn-outline-primary mb-3" onclick="download('{{ encrypt('qris.png') }}')">
                                Download QRIS
                            </button>
                            <ol class="small text-muted text-start">
                                <li>Buka aplikasi mBanking</li>
                                <li>Pilih bayar QR</li>
                                <li>Upload QR dari galeri</li>
                                <li>Masukkan nominal sesuai</li>
                                <li>Upload slip pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Transfer Bank --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#bankCollapse">
                            Transfer via Bank
                        </button>
                    </h2>
                    <div id="bankCollapse" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <p><strong>Rekening:</strong> <span id="norek" class="badge bg-primary">5859459400992029</span>
                                <button onclick="salin()" class="btn btn-sm btn-outline-secondary ms-2">Salin</button>
                            </p>
                            <p><strong>Nama Penerima:</strong> Mval</p>
                            <ol class="small text-muted">
                                <li>Masuk ke aplikasi mBanking</li>
                                <li>Pilih transfer ke bank "Neo Commerce"</li>
                                <li>Masukkan no. rekening & nominal</li>
                                <li>Upload slip pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- ATM --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#atmCollapse">
                            Pembayaran via ATM
                        </button>
                    </h2>
                    <div id="atmCollapse" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <p><strong>Rekening:</strong> 5859459400992029</p>
                            <p><strong>Nama Penerima:</strong> Mval</p>
                            <ol class="small text-muted">
                                <li>Masukkan kartu ATM</li>
                                <li>Kode bank: 490 (Neo)</li>
                                <li>Masukkan rekening & nominal</li>
                                <li>Upload slip pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal --}}
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title">Contoh Slip Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ env('APP_URL') . '/file?file=' . encrypt('Anonymous.png') }}" class="w-100 rounded shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    function download(filename) {
        const url = `/download-qris?file=${filename}`;
        fetch(url)
            .then(res => res.blob())
            .then(blob => {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = "QRIS_Pembayaran";
                a.click();
            }).catch(() => alert('Gagal download QRIS.'));
    }

    function salin() {
        const norek = document.getElementById('norek').innerText;
        navigator.clipboard.writeText(norek)
            .then(() => alert('Nomor rekening berhasil disalin.'))
            .catch(() => alert('Gagal menyalin.'));
    }
</script>
@endsection
