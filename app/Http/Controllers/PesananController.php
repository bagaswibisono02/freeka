<?php

namespace App\Http\Controllers;

use App\Models\alamat_penerima;
use Illuminate\Support\Facades\DB;
use App\Models\kategory;
use App\Models\keuangan;
use App\Models\notifikasi;
use App\Models\konfirmasiPembayaran;
use Illuminate\Support\Facades\Log;
use App\Models\mediaReview;
use App\Models\pengiriman;
use App\Models\pesanan;
use App\Models\produk;
use App\Models\itemPesanan;
use App\Models\provinsi;
use App\Models\reviewProduk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Midtrans\Config;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
class PesananController extends Controller
{
    public function createSnapToken(Request $request)
    {
        $user = Auth::user();

        // 🔹 Validasi Input
        $validator = Validator::make(
            $request->all(),
            [
                'produk' => 'required',
                'jumlahBeli' => 'required|integer|min:1',
                'varian' => 'nullable|string|max:255',
                'catatan' => 'nullable|string|max:255',
                'penerimaPesanan' => 'required',
                'metode' => 'required|string', // metode pembayaran Tripay
            ],
            [
                'produk.required' => 'Produk tidak valid.',
                'jumlahBeli.required' => 'Jumlah pembelian harus diisi.',
                'jumlahBeli.integer' => 'Jumlah pembelian harus berupa angka.',
                'jumlahBeli.min' => 'Minimal pembelian 1 item.',
                'penerimaPesanan.required' => 'Alamat pengiriman belum dipilih.',
                'metode.required' => 'Pilih metode pembayaran.',
            ],
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 🔹 Cek apakah datang dari keranjang
        $fromCart = Str::contains(url()->previous(), 'keranjang=');

        try {
            if ($fromCart) {
                // ==== CASE: dari keranjang ====
                $pesananId = decrypt($request->keranjang);
                $pesanan = Pesanan::findOrFail($pesananId);
                $produk = $pesanan->produk;
                $total = $produk->harga * $request->jumlahBeli;

                $pesanan->update([
                    'alamat_penerima_id' => decrypt($request->penerimaPesanan),
                    'jumlah' => $request->jumlahBeli,
                    'catatan' => $request->catatan,
                    'hargatotal' => $total,
                    'status_pembayaran' => 'tunggubayar',
                ]);

                ItemPesanan::updateOrCreate(
                    ['pesanan_id' => $pesanan->id],
                    [
                        'produk_id' => $produk->id,
                        'jumlah' => $request->jumlahBeli,
                        'total_harga' => $total,
                    ],
                );
            } else {
                // ==== CASE: langsung beli produk ====
                $produk = Produk::findOrFail(decrypt($request->produk));
                $total = $produk->harga * $request->jumlahBeli;

                $pesanan = Pesanan::create([
                    'user_id' => $user->id,
                    'produk_id' => $produk->id,
                    'alamat_penerima_id' => decrypt($request->penerimaPesanan),
                    'jumlah' => $request->jumlahBeli,
                    'catatan' => $request->catatan,
                    'hargatotal' => $total,
                    'status_pembayaran' => 'tunggubayar',
                ]);

                ItemPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $produk->id,
                    'jumlah' => $request->jumlahBeli,
                    'total_harga' => $total,
                ]);
            }

            // 🔹 Generate invoice unik
            $invoice = 'INV-' . time() . '-' . strtoupper(Str::random(6));

            // 🔹 Siapkan payload Tripay
            $merchantCode = config('services.tripay.merchant_code');
            $apiKey = config('services.tripay.api_key');
            $privateKey = config('services.tripay.private_key');
            $callbackUrl = config('services.tripay.callback_url');

            $signature = hash_hmac('sha256', $merchantCode . $invoice . $pesanan->hargatotal, $privateKey);

            $payload = [
                'method' => $request->metode,
                'merchant_ref' => $invoice,
                'amount' => $pesanan->hargatotal,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->contact ?? '08123456789',
                'order_items' => [
                    [
                        'sku' => $produk->id,
                        'name' => $produk->nama,
                        'price' => $produk->harga,
                        'quantity' => $request->jumlahBeli,
                    ],
                ],
                'callback_url' => $callbackUrl,
                'return_url' => url('/checkout/success'),
                'expired_time' => now()->addHours(24)->timestamp,
                'signature' => $signature,
            ];

            // 🔹 Kirim request ke Tripay
            $url = config('services.tripay.mode') === 'live' ? 'https://tripay.co.id/api/transaction/create' : 'https://tripay.co.id/api-sandbox/transaction/create';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post($url, $payload);

            $result = $response->json();

            if (!isset($result['data'])) {
                return back()->with('error', 'Gagal membuat transaksi Tripay: ' . ($result['message'] ?? 'Unknown error.'));
            }

            // 🔹 Simpan response Tripay ke database
            $pesanan->update([
                'response_faspay' => json_encode($result),
                'tripay_reference' => $result['data']['reference'],
            ]);
            // buat notifikasi
            notifikasi::create([
                'user_id' => Auth::id(),
                'title' => 'Order Notification - Pesanan Berhasil Dibuat',
                'message' => 'Pesanan #' . $result['data']['reference'] . ' berhasil dibuat. Silakan cek detail pesanan Anda.',
                'is_read' => false,
            ]);

            // 🔹 Redirect ke halaman pembayaran Tripay
            return redirect($result['data']['checkout_url']);
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function checkout(Request $request)
    {
        if ($request->keranjang) {
            $id_pesanan = decrypt($request->keranjang);
            $keranjang = pesanan::find($id_pesanan);
            $produk = produk::find($keranjang->produk->id);
        } elseif ($request->produk) {
            $id_produk = decrypt($request->produk);
            $produk = produk::find($id_produk);
        } else {
            return back();
        }

        return view('produk.checkout', [
            'produk' => $produk,
            'penerimas' => alamat_penerima::where('user_id', Auth::User()->id)->get(),
        ]);
    }

    public function pembayaran(Request $request)
    {
        $pesanan = decrypt($request->keranjang);

        return view('produk.pembayaran', [
            'pesanan' => pesanan::find($pesanan),
            'kategorys' => kategory::all(),
        ]);
    }

    public function index(Request $request)
    {
        $pesanan = pesanan::FilterId(request()->query('query'))
            ->FilterStatus(request()->query('status'))
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pesanan.index', [
            'pesanans' => $pesanan,
            'kurirs' => $this->getKurir(),
        ]);
    }
    public function getKurir()
    {
        $apiKey = 'ea97564c270da5b3e5eddc8db8f45dfc8165641b7befdb4f81df188612378a9e'; // ganti dengan API key asli
        $response = Http::get('https://api.binderbyte.com/v1/list_courier', [
            'api_key' => $apiKey,
        ]);

        // Cek apakah responsenya sukses
        if ($response->successful()) {
            $data = $response->json();

            // Ambil list courier dari response
            return $data;
        } else {
            return response()->json(['error' => 'Gagal mengambil data kurir'], 500);
        }
    }

    public function update(Request $request, $ids)
    {
        $id = decrypt($ids);
        $pesanan = pesanan::find($id);
        $this->pengiriman(intval($id), 'Diproses Penjual');
        $pesanan->update([
            'resi' => $request->resi,
            'link_beli' => $request->link_beli,
            'jasa_kirim' => $request->jasa_kirim,
        ]);
        return back()->with('berhasil', 'Berhasil Memasukan Resi');
    }

    public function detailPesanan(Request $request)
    {
        $keranjang = decrypt($request->keranjang);

        return view('pesanan.detail', [
            'pesanan' => pesanan::find($keranjang),
            'kategorys' => kategory::all(),
        ]);
    }

    public function pengiriman($pesanan_id, $status)
    {
        $masukan = pengiriman::create([
            'pesanan_id' => $pesanan_id,
            'status' => $status,
        ]);

        if ($masukan) {
            return true;
        } else {
            return back()->with('pesan', 'eror saat memasukan data ');
        }
    }

    public function resi(Request $request)
    {
        $apiKey = 'ea97564c270da5b3e5eddc8db8f45dfc8165641b7befdb4f81df188612378a9e';
        $courier = 'jnt';
        $awb = 'JX5140998027';

        $response = Http::get('https://api.binderbyte.com/v1/track', [
            'api_key' => $apiKey,
            'courier' => $courier,
            'awb' => $awb,
        ]);

        // dd($response->json())

        $id_pesanan = decrypt($request->pesanan);
        return view('pesanan.resi', [
            'pesanan' => pesanan::find($id_pesanan),
            'resi' => $response['data']['history'], // array perjalanan
        ]);
    }

    public function tambahResi(Request $request, $id)
    {
        $pesanan_id = decrypt($id);

        pengiriman::create([
            'pesanan_id' => $pesanan_id,
            'status' => $request->status,
        ]);
        return back();
    }

    public function editResi(Request $request, $id)
    {
        $pengiriman_id = decrypt($id);

        pengiriman::find($pengiriman_id)->update([
            'status' => $request->status,
        ]);
        return back();
    }

    public function pesanSupplier($id)
    {
        $key = decrypt($id);
        return view('pesanan.supplier', [
            'produk' => produk::find($key),
        ]);
    }
    public function uploadPembayaran(Request $request)
    {
        $request->validate([
            'form-pembayaran' => 'required',
        ]);

        $file = $request->file('form-pembayaran');

        //cek revisi atau baru
        $pesanan = pesanan::find(decrypt($request->pesanan));
        if ($pesanan->konfirmasiPembayaran) {
            $filename = $pesanan->konfirmasiPembayaran->bukti_transaksi;
            $path = storage_path('app/public/' . $filename);

            //
            $id_konfirmasiPembayaran = $pesanan->konfirmasiPembayaran->id;

            if (File::exists($path)) {
                File::delete($path);

                konfirmasiPembayaran::find($id_konfirmasiPembayaran)->update([
                    'bukti_transaksi' => $file->store('bukti_pembayaran', 'public'),
                ]);
                $pesanan->update([
                    'status' => 'diproses',
                    'resi' => 'direvisi',
                ]);
                return redirect('/panel?filter=diproses');
            } else {
                konfirmasiPembayaran::find($id_konfirmasiPembayaran)->update([
                    'bukti_transaksi' => $file->store('bukti_pembayaran', 'public'),
                ]);
                $pesanan->update([
                    'status' => 'diproses',
                    'resi' => 'direvisi',
                ]);
                return redirect('/panel?filter=diproses');
            }
        }

        konfirmasiPembayaran::create([
            'pesanan_id' => decrypt($request->pesanan),
            'bukti_transaksi' => $file->store('bukti_pembayaran', 'public'),
        ]);

        $pesanan->update([
            'status' => 'diproses',
        ]);

        return redirect('/panel?filter=diproses');
    }

    public function terima(Request $request)
    {
        $id = decrypt($request->pesanan);
        $pesanan = pesanan::find($id);
        $pesanan->update([
            'resi' => 'diterima',
        ]);

        keuangan::create([
            'tanggal_transaksi' => Carbon::now(),
            'keterangan' => 'Pembayaran Pesanan-' . $id,
            'nominal' => $pesanan->jumlah * $pesanan->produk->harga,
            'jenis_transaksi' => 'masuk',
        ]);
        $produk = produk::find($pesanan->produk->id);
        $produk->update([
            'terjual' => $produk->terjual + 1,
        ]);

        return back();
    }

    public function tolak(Request $request)
    {
        $id = decrypt($request->pesanan);
        pesanan::find($id)->update([
            'resi' => 'ditolak',
        ]);
        return back();
    }

    public function lihatResi(Request $request)
    {
        if (!$request->pesanan) {
            return back();
        }
        $id_pesanan = decrypt($request->pesanan);
        return view('pesanan.lihatResi', [
            'pesanan' => pesanan::find($id_pesanan),
            'kategorys' => kategory::all(),
        ]);
    }

    public function diterima($id)
    {
        $decryptId = decrypt($id);
        $pesanan = pesanan::find($decryptId);

        $pesanan->update([
            'status' => 'selesai',
        ]);

        return redirect('/pesanan')->with('berhasil', 'Pesanan Selesai');
    }

    public function gantiAlamat($idproduk, $iduser, Request $request)
    {
        return view('pesanan.edit', [
            'provinsis' => provinsi::orderBy('nama', 'ASC')->get(),
            'penerimas' => alamat_penerima::where('user_id', Auth::User()->id)
                ->with(['kelurahan.kecamatan.kab_kota.provinsi'])
                ->get(),
            'pesanan' => pesanan::find(decrypt($idproduk)),
            'kategorys' => kategory::all(),
        ]);
    }

    public function editPesanan(Request $request)
    {
        pesanan::find(decrypt($request->keranjang))->update([
            'jumlah' => $request->jumlahBeli,
            'alamat_penerima_id' => decrypt($request->penerima),
            'catatan' => $request->catatan,
            'varian' => $request->varian,
        ]);

        return redirect('/panel');
    }

    public function tambahReview(Request $request)
    {
        $pesanan = pesanan::find(decrypt($request->pesanan));
        $produk = $pesanan->produk;
        // dd($produk);

        $review = reviewProduk::create([
            'produk_id' => $produk->id,
            'pesanan_id' => decrypt($request->pesanan),
            'user_id' => Auth::user()->id,
            'bintang' => $request->rating,
            'hasil' => $request->review,
        ]);

        if ($request->file('fotoReview')) {
            foreach ($request->file('fotoReview') as $media) {
                mediaReview::create([
                    'mediaReview_id' => $review->id,
                    'file' => $media->store('foto-review', 'public'),
                ]);
            }
        }

        return redirect('/panel?filter=selesai');
    }

    public function editReview(Request $request)
    {
        // dd($request);
        $pesanan = pesanan::find(decrypt($request->pesanan));

        $review = $pesanan->review;

        reviewProduk::find($review->id)->update([
            // 'produk_id'=> $produk->id,
            // 'pesanan_id'=> decrypt($request->pesanan),
            // 'user_id'=>Auth::user()->id,
            'bintang' => $request->rating,
            'hasil' => $request->review,
        ]);

        if ($request->file('fotoReview')) {
            $mediaLama = $review->mediaReview;

            foreach ($mediaLama as $med) {
                $filePath = storage_path('app/public/' . $med->file);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                mediaReview::find($med->id)->delete();
            }

            foreach ($request->file('fotoReview') as $media) {
                mediaReview::create([
                    'mediaReview_id' => $review->id,
                    'file' => $media->store('foto-review', 'public'),
                ]);
            }
        }

        return redirect('/panel');
    }

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signatureHeader = $request->header('X-Callback-Signature');
        $secret = env('TRIPAY_PRIVATE_KEY');

        // 1️⃣ Cek signature
        if (empty($signatureHeader) || empty($secret)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $computedSignature = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($computedSignature, $signatureHeader)) {
            Log::warning('Tripay callback: invalid signature');
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        // 2️⃣ Decode data Tripay
        $data = json_decode($payload, true);
        if (!isset($data['reference']) || !isset($data['status'])) {
            Log::error('Tripay callback: incomplete data');
            return response()->json(['success' => false, 'message' => 'Bad request'], 400);
        }

        $reference = $data['reference'];
        $merchantRef = $data['merchant_ref'] ?? null;
        $statusTripay = strtoupper($data['status']);

        Log::info('Tripay callback received', [
            'reference' => $reference,
            'status' => $statusTripay,
        ]);

        try {
            DB::beginTransaction();

            $orders = DB::table('pesanans')->get();
            $targetOrder = null;

            foreach ($orders as $order) {
                $json = json_decode($order->response_faspay, true);
                if (!$json || !isset($json['data'])) {
                    continue;
                }

                $ref = $json['data']['reference'] ?? null;
                $mref = $json['data']['merchant_ref'] ?? null;

                if ($ref === $reference || $mref === $merchantRef) {
                    $targetOrder = $order;
                    break;
                }
            }

            if (!$targetOrder) {
                Log::warning('Tripay callback: order not found', [
                    'reference' => $reference,
                    'merchant_ref' => $merchantRef,
                ]);
                DB::commit();
                return response()->json(['success' => true], 200); // tetap OK ke Tripay
            }

            $statusMap = [
                'PAID' => 'paid',
                'UNPAID' => 'pending',
                'EXPIRED' => 'expired',
                'FAILED' => 'failed',
            ];

            $status = $statusMap[$statusTripay] ?? 'pending';

            DB::table('pesanans')
                ->where('id', $targetOrder->id)
                ->update([
                    'status_pembayaran' => $status,
                    'updated_at' => now(),
                ]);

            DB::commit();

            Log::info('Tripay callback updated', [
                'id' => $targetOrder->id,
                'status_pembayaran' => $status,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Tripay callback error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Internal error'], 500);
        }

        // ✅ Respons sesuai harapan Tripay
        return response()->json(['success' => true], 200);
    }
}
