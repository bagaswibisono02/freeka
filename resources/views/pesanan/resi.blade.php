@extends('layouts.sidebar')
@section('content')
    <style>
        body {
            background-color: #f9fafb;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .form-control {
            border-radius: 12px;
        }

        .btn {
            border-radius: 10px;
        }

        .table td {
            vertical-align: middle;
        }

        .img-preview {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-action {
            margin-top: 0.5rem;
        }

        .d-none {
            display: none !important;
        }
    </style>

    <div class="container my-5">
        <!-- CARD: Detail Pemesanan -->
        <div class="card card-custom mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Pemesanan Produk</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Gambar Produk -->
                    <div class="col-md-4 text-center">
                        <img src="{{ env('APP_URL') . '/file?file=' . encrypt($pesanan->produk->media[0]->file) }}"
                            alt="Produk" class="img-fluid img-preview">
                    </div>

                    <!-- Informasi Produk -->
                    <div class="col-md-8">
                        <h5><strong>{{ $pesanan->produk->nama }}</strong></h5>
                        <p><strong>Harga Satuan:</strong> @currency($pesanan->produk->harga)</p>
                        <p><strong>Jumlah:</strong> {{ $pesanan->jumlah }}</p>
                        <p><strong>Total Harga:</strong> @currency($pesanan->jumlah * $pesanan->produk->harga)</p>
                        <p><strong>Alamat Pengiriman:</strong><br>{{ $pesanan->alamatPenerima->alamat }}</p>
                        <p><strong>Penerima:</strong> {{ $pesanan->alamatPenerima->penerima }}</p>

                        <div class="mt-3">
                            <span class="badge bg-warning text-dark">Status: {{ $pesanan->status }}</span>
                            <span class="badge bg-secondary">Resi: {{ $pesanan->resi ?? '-' }}</span>
                        </div>

                        <div class="mt-4">
                            <a href="{{ $pesanan->link_beli }}" class="btn btn-info" target="_blank">Cek Supplier</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN: Perjalanan Paket + Bukti Pembayaran -->
        <div class="row">
            <!-- Perjalanan Paket -->
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Perjalanan Paket</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm align-middle">
                            @foreach ($pesanan->pengiriman as $p)
                                <tr id="{{ $p->id }}">
                                    <td>{{ $p->status }}</td>
                                    <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>
                                    @if ($pesanan->status != 'selesai')
                                        <td>
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="tampilUpdateResi('{{ $p->id }}')">Update</button>
                                        </td>
                                    @endif
                                </tr>

                                @foreach ($resi as $r)
                                    <tr>


                                        <td>{{ $r['desc'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($r['date'])->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endforeach
                                @if ($pesanan->status != 'selesai')
                                    <tr id="form-{{ $p->id }}" class="d-none">
                                        <form action="/resi/{{ encrypt($p->id) }}/edit" method="POST">
                                            @csrf
                                            <td colspan="2">
                                                <input class="form-control" type="text" name="status"
                                                    value="{{ $p->status }}">
                                            </td>
                                            <td><button type="submit" class="btn btn-danger btn-sm">Simpan</button></td>
                                        </form>
                                    </tr>
                                @endif
                            @endforeach

                            @if ($pesanan->status != 'selesai')
                                <form action="/resi/{{ encrypt($pesanan->id) }}" method="POST">
                                    @csrf
                                    <tr>
                                        <td colspan="2">
                                            <input class="form-control" type="text" name="status"
                                                placeholder="Status Baru">
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm">Tambah</button>
                                        </td>
                                    </tr>
                                </form>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran + Konfirmasi -->
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body text-center">
                        <img class="img-preview mb-3"
                            src="{{ env('APP_URL') . '/file?file=' . encrypt($pesanan->konfirmasiPembayaran->bukti_transaksi) }}"
                            alt="Bukti Transaksi">

                        @if ($pesanan->status != 'selesai')
                            <form action="/pesanan-diterima/{{ encrypt($pesanan->id) }}" method="POST">
                                @csrf
                                <button onclick="return confirm('Yakin Pesanan Selesai?')" class="btn btn-warning">
                                    Tandai Selesai
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success mt-3" role="alert">
                                Pesanan telah selesai!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tampilUpdateResi(id) {
            const form = document.getElementById('form-' + id);
            form.classList.toggle('d-none');
        }
    </script>
@endsection
