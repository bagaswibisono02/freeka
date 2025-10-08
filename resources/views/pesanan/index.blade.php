@extends('layouts.sidebar')
@section('content')
{{-- @dd($kurirs) --}}
    <div class="container">
        <form action="" method="GET" class="m-2 container col-11">
            <div class="d-flex justify-content-between">
                <div class="col-">
                    <input type="text" name="query" class="form-control" placeholder="Id Pesanan"
                        value="{{ request('user_name') }}">
                </div>
                <div class="col-">
                    <select name="status" class="form-control">
                        <option value="">Filter</option>
                        <option value="tunggubayar" {{ request('status') == 'pending' ? 'selected' : '' }}>Tunggu Bayar
                        </option>
                        <option value="Berhasil" {{ request('status') == 'completed' ? 'selected' : '' }}>Berhasil</option>
                        <option value="perluresi" {{ request('status') == 'canceled' ? 'selected' : '' }}>Perlu Resi
                        </option>
                        <option value="adaresi" {{ request('status') == 'canceled' ? 'selected' : '' }}>Ada Resi</option>
                        <option value="selesai" {{ request('status') == 'canceled' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ request('status') == 'canceled' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="">
                    <div class="col-">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>

                </div>
                <div class="col-">
                    <button class="btn btn-secondary btn-block" type="reset">Reset</button>
                </div>
            </div>

        </form>
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Resi</th>
                        <th>Nama User</th>
                        <th>Rincian</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanans as $pesanan)
                        <tr>
                            <td>{{ $pesanan->id }}</td>
                            <td>
                                @if ($pesanan->resi == 'ditolak' or $pesanan->resi == 'direvisi')
                                    <!-- Button trigger modal -->
                                    <span type="button" class="d-inline text-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal{{ $pesanan->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-layer-forward" viewBox="0 0 16 16">
                                            <path
                                                d="M8.354.146a.5.5 0 0 0-.708 0l-3 3a.5.5 0 0 0 0 .708l1 1a.5.5 0 0 0 .708 0L7 4.207V12H1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H9V4.207l.646.647a.5.5 0 0 0 .708 0l1-1a.5.5 0 0 0 0-.708z" />
                                            <path
                                                d="M1 7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h4.5a.5.5 0 0 0 0-1H1V8h4.5a.5.5 0 0 0 0-1zm9.5 0a.5.5 0 0 0 0 1H15v2h-4.5a.5.5 0 0 0 0 1H15a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1z" />
                                        </svg>
                                        <span class="text-danger">{{ $pesanan->resi }}</span>
                                    </span>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{ $pesanan->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">

                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Resi</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($pesanan->konfirmasiPembayaran)
                                                        <img width="100%"
                                                            src="{{ env('APP_URL') . '/file?file=' . encrypt($pesanan->konfirmasiPembayaran->bukti_transaksi) }}"
                                                            alt="">
                                                    @endif
                                                    @if ($pesanan->resi == null or $pesanan->resi == 'ditolak' or $pesanan->resi == 'direvisi')
                                                        <a class="btn btn-success"
                                                            href="/terima?pesanan={{ encrypt($pesanan->id) }}">Terima</a>
                                                        <a class="btn btn-danger"
                                                            href="/tolak?pesanan={{ encrypt($pesanan->id) }}">Tolak</a>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                @elseif($pesanan->resi == 'diterima')
                                    <!-- Button trigger modal -->
                                    <span type="button" class="d-inline text-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModa{{ $pesanan->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-layer-forward" viewBox="0 0 16 16">
                                            <path
                                                d="M8.354.146a.5.5 0 0 0-.708 0l-3 3a.5.5 0 0 0 0 .708l1 1a.5.5 0 0 0 .708 0L7 4.207V12H1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H9V4.207l.646.647a.5.5 0 0 0 .708 0l1-1a.5.5 0 0 0 0-.708z" />
                                            <path
                                                d="M1 7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h4.5a.5.5 0 0 0 0-1H1V8h4.5a.5.5 0 0 0 0-1zm9.5 0a.5.5 0 0 0 0 1H15v2h-4.5a.5.5 0 0 0 0 1H15a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1z" />
                                        </svg>
                                    </span>
                                    <span class="text-danger">Menunggu Resi</span>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModa{{ $pesanan->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">

                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Resi</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img width="100%"
                                                        src="{{ env('APP_URL') . '/file?file=' . encrypt($pesanan->konfirmasiPembayaran->bukti_transaksi) }}"
                                                        alt="">

                                                    <form action="/pesanan/{{ encrypt($pesanan->id) }}" method="post">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-container">
                                                            <div class="modal-body">
                                                                <input type="text" class="form-control" required
                                                                    name="resi" value="{{ $pesanan->resi }}" required
                                                                    placeholder="Masukan Resi / Status"><br>
                                                                <select name="jasa_kirim" class="form-control"
                                                                    id="">
                                                                    <option>=Pilih Jasa Kirim=</option>
                                                                    @if (count($kurirs) > 0)
                                                                        @foreach ($kurirs as $k)
                                                                            <option value="{{ $k['code'] }}">{{ $k['description'] }}</option>
                                                                        @endforeach
                                                                    @else
                                                                      <option>List Api Error</option>
                                                                    @endif

                                                                </select>

                                                                <br>
                                                                <input type="text" class="form-control"
                                                                    name="link_beli" value="{{ $pesanan->link_beli }}"
                                                                    placeholder="Link Beli">
                                                                @if ($pesanan->link_beli && $pesanan->resi == 'diterima')
                                                                    <a class="btn btn-danger" target="_Blank"
                                                                        href="{{ $pesanan->link_beli }}">Cek</a>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Masukan
                                                                    Resi</button>
                                                            </div>
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                @elseif($pesanan->resi == null)
                                    <!-- Button trigger modal -->
                                    <span type="button" class="d-inline text-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalTerima-tolak{{ $pesanan->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-layer-forward" viewBox="0 0 16 16">
                                            <path
                                                d="M8.354.146a.5.5 0 0 0-.708 0l-3 3a.5.5 0 0 0 0 .708l1 1a.5.5 0 0 0 .708 0L7 4.207V12H1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H9V4.207l.646.647a.5.5 0 0 0 .708 0l1-1a.5.5 0 0 0 0-.708z" />
                                            <path
                                                d="M1 7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h4.5a.5.5 0 0 0 0-1H1V8h4.5a.5.5 0 0 0 0-1zm9.5 0a.5.5 0 0 0 0 1H15v2h-4.5a.5.5 0 0 0 0 1H15a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1z" />
                                        </svg>
                                        @if ($pesanan->konfirmasiPembayaran)
                                             <span class="text-danger">Tunggu Verifikasi</span>
                                        @else
                                               <span class="text-danger">Tunggu Slip</span>
                                        @endif
                                     
                                    </span>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalTerima-tolak{{ $pesanan->id }}"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Resi</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($pesanan->konfirmasiPembayaran)
                                                        <img width="100%"
                                                            src="{{ env('APP_URL') . '/file?file=' . encrypt($pesanan->konfirmasiPembayaran->bukti_transaksi) }}"
                                                            alt="">
                                                        <a class="btn btn-success"
                                                            href="/terima?pesanan={{ encrypt($pesanan->id) }}">Terima</a>
                                                        <a class="btn btn-danger"
                                                            href="/tolak?pesanan={{ encrypt($pesanan->id) }}">Tolak</a>
                                                    @elseif(!$pesanan->konfirmasiPembayaran)
                                                        <span class="text text-danger">*User Belum Verifikai
                                                            Pembayaran</span>
                                                    @endif




                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                @else
                                    <a href="/resi?pesanan={{ encrypt($pesanan->id) }}">{{ $pesanan->resi }}</a>
                                @endif

                            </td>
                            <td>{{ $pesanan->user->name }}</td>
                            <td><a class="text-success"
                                    href="/produk/{{ encrypt($pesanan->produk->id) }} ">{{ $pesanan->produk->nama }}</a>
                                {{ $pesanan->jumlah . ' x @' . $pesanan->produk->harga }}</td>
                            <td>@currency($pesanan->jumlah * $pesanan->produk->harga)</td>
                            <td>{{ $pesanan->status }}</td>
                            <td>{{ $pesanan->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Tidak ada pesanan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    <!-- Form Filter -->
@endsection
