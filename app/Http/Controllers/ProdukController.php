<?php

namespace App\Http\Controllers;

use App\Models\free_ongkir;
use App\Models\kategory;
use App\Models\media_produk;
use App\Models\pesanan;
use App\Models\produk;
use App\Models\produk_user;
use App\Models\provinsi;
use App\Models\supplier;
use App\Models\varian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('produk.index', [
            'produks' => produk::with('media', 'kategory')->orderBy('created_at', 'desc')->paginate(50),
            'kategory' => kategory::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.create', [
            'kategoryes' => kategory::orderBy('name', 'ASC')->get(),
            'provinsis' => provinsi::orderBy('nama', 'ASC')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'keterangan' => 'required',
            'category_id' => 'required',
            'hargajual' => 'required',
            'media' => 'required|array|min:1',
            'hargaAsli.*' => 'required|numeric',
            'link' => 'required|array|min:1',
            'hargaAsli' => 'required|array|min:1',
            'link.*' => 'required|url',
        ]);

        $produk = produk::create([
            'kategory_id' => $request->category_id,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'harga' => $request->hargajual,
        ]);
        // Enkripsi ID
        $encryptedId = Crypt::encryptString($produk->id);
        // Karena encryptedId ada karakter khusus, harus diubah supaya ramah URL, misal base64 encode + ganti karakter
        $base64Id = strtr(base64_encode($encryptedId), '+/=', '-_,');
        // Buat slug dari nama + enkripsi id yang sudah diubah
        $slug = Str::slug($request->nama) . '-' . $base64Id;
        // Update slug
        $produk->slug = $slug;
        $produk->save();

        //masukan supplier
        if ($request->link && $request->hargaAsli) {
            $countLinks = count($request->link);
            $countHarga = count($request->hargaAsli);
            $count = min($countLinks, $countHarga); // pastikan tidak out of range

            for ($i = 0; $i < $count; $i++) {
                // Cek apakah link dan harga tidak kosong/null
                if (!empty($request->link[$i]) && !empty($request->hargaAsli[$i])) {
                    supplier::create([
                        'produk_id' => $produk->id,
                        'supplier' => $request->link[$i],
                        'harga' => $request->hargaAsli[$i],
                    ]);
                }
            }
        }

        // masukan daerah gratis ongkir
        if ($request->provinsi) {
            foreach ($request->provinsi as $p) {
                free_ongkir::create([
                    'produk_id' => $produk->id,
                    'daerah_id' => $p,
                ]);
            }
        }

        //simpan Media
        if ($request->file('media')) {
            foreach ($request->file('media') as $media) {
                media_produk::create([
                    'produk_id' => intval($produk->id),
                    'file' => $media->store('media_produk', 'public'),
                ]);
            }
        }

        //simpan Varian
        if ($request->input_varian) {
            if (count($request->input('input_varian', [])) > 0) {
                foreach ($request->input('input_varian') as $varianNama) {
                    Varian::create([
                        'produk_id' => $produk->id,
                        'nama' => $varianNama,
                    ]);
                }
            } else {
                varian::create([
                    'produk_id' => $produk->id,
                    'nama' => 'Original',
                ]);
            }
        }

        return redirect('/produk')->with('berhasil', 'Produk Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        // dd($slug);
        $id = $this->getIdFromSlug($slug);

        return view('produk.show', [
            'produk' => produk::find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($produk)
    {
        $id = decrypt($produk);

        return view('produk.edit', [
            'produk' => produk::find($id),
            'kategoryes' => kategory::orderBy('name', 'ASC')->get(),
            'provinsis' => provinsi::orderBy('nama', 'ASC')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $produk)
    {
        $idProduk = decrypt($produk);
        $request->validate([
            'nama' => 'required',
            'keterangan' => 'required',
            'category_id' => 'required',
            'keterangan' => 'required',
            'harga' => 'required',
        ]);

        // dd($request->hargaAsli, produk::find($idProduk)->supplier->pluck('harga')->toArray());

        //update produk
        produk::find($idProduk)->update([
            'kategory_id' => $request->category_id,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'harga' => $request->harga,
        ]);

        //update supplier
        //cek perubahan supplier
        $supplierLama = produk::find($idProduk)->supplier->pluck('supplier')->toArray();
        $supplierBaru = $request->link;
        $hargaLama = produk::find($idProduk)->supplier->pluck('harga')->toArray();
        $hargaBaru = $request->hargaAsli;
        if ($supplierBaru) {
            if (array_diff($supplierLama, $supplierBaru) || array_diff($hargaLama, $hargaBaru)) {
                supplier::where('produk_id', $idProduk)->delete();
                for ($x = 0; $x < count($request->link); $x++) {
                    if ($request->link[$x] != null) {
                        supplier::create([
                            'produk_id' => $idProduk,
                            'supplier' => $request->link[$x],
                            'harga' => $request->hargaAsli[$x],
                        ]);
                    }
                }
            } elseif (count($supplierLama) == 0) {
                for ($x = 0; $x < count($request->link); $x++) {
                    if ($request->link[$x] != null) {
                        supplier::create([
                            'produk_id' => $idProduk,
                            'supplier' => $request->link[$x],
                            'harga' => $request->hargaAsli[$x],
                        ]);
                    }
                }
            }
        }

        // update daerah gratis ongkir

        $daerahLama = produk::find($idProduk)->provinsi->pluck('id')->toArray();
        $daerahBaru = $request->provinsi;
        if ($daerahBaru) {
            if (array_diff($daerahLama, $daerahBaru)) {
                free_ongkir::where('produk_id', $idProduk)->delete();
                for ($x = 0; $x < count($request->provinsi); $x++) {
                    free_ongkir::create([
                        'produk_id' => $idProduk,
                        'daerah_id' => $request->provinsi[$x],
                    ]);
                }
            } elseif (count($daerahLama) == 0) {
                for ($x = 0; $x < count($request->provinsi); $x++) {
                    free_ongkir::create([
                        'produk_id' => $idProduk,
                        'daerah_id' => $request->provinsi[$x],
                    ]);
                }
            }
        }

        //jika ada foto baru masukan
        if ($request->file('media')) {
            foreach ($request->file('media') as $media) {
                media_produk::create([
                    'produk_id' => intval($idProduk),
                    'file' => $media->store('media_produk', 'public'),
                ]);
            }
        }

        //tambah varian di update
        if ($request->datavarian) {
            //simpan Varian
            if ($request->datavarian) {
                for ($x = 0; $x < count($request->datavarian); $x++) {
                    varian::create([
                        'produk_id' => $idProduk,
                        'nama' => $request->datavarian[$x],
                    ]);
                }
            }
        }

        return back()->with('berhasil', 'Berhasil Update Produk');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($produk)
    {
        $id = decrypt($produk);
        dd($produk);
        $produk = produk::find($id);

        // hapus foto terkait
        foreach ($produk->media as $media) {
            $filePath = storage_path('app/public/' . $media->file);
            if (File::exists($filePath)) {
                File::delete($filePath);
                media_produk::find($media->id)->delete();
            }
        }
        // hapus produk
        $cek = $produk->delete();
        if ($cek) {
            return redirect('/produk')->with('berhasil', 'Produk Dan Foto Berhasil Dihapus');
        } else {
            return redirect('/produk')->with('gagal', 'Produk Dan Foto gagal Dihapus');
        }
    }

    public function hapusFotoProduk(Request $request)
    {
        $id = decrypt($request->id);
        // Nama file yang akan dihapus
        $filePath = storage_path('app/public/' . media_produk::find($id)->file);

        // Cek apakah file ada
        if (File::exists($filePath)) {
            // Hapus file
            File::delete($filePath);
            media_produk::find($id)->delete();

            return back();
        } else {
            return back()->with('gagal', 'File Tidak Ditemukan');
        }
    }

    public function lihatProduk(Request $request, $slug)
    {
        $produk_id = $this->getIdFromSlug($slug);

        // Ambil produk beserta review dan media (jika ada)
        $produk = produk::with(['review', 'media'])->findOrFail($produk_id);

        // Simpan histori lihat jika user login
        if (Auth::check()) {
            $log = produk_user::firstOrNew([
                'user_id' => Auth::id(),
                'produk_id' => $produk_id,
            ]);
            $log->times = $log->exists ? $log->times + 1 : 1;
            $log->save();
        }

        // Produk serupa dari kategori yang sama, tidak termasuk produk ini
        $produkSerupa = produk::with('media')->where('kategory_id', $produk->kategory_id)->where('id', '!=', $produk->id)->inRandomOrder()->take(10)->get();

        return view('produk.lihatProduk', [
            'produk' => $produk,
            'reviews' => $produk->review()->paginate(10),
            'produkSerupa' => $produkSerupa,
            'kategorys' => kategory::all(),
        ]);
    }

    function hapusVarian($id)
    {
        $id_varian = decrypt($id);

        varian::find($id_varian)->delete();
        return back();
    }

    function review(Request $request)
    {
        return view('produk.review', [
            'kategorys' => kategory::all(),
            'pesanans' => pesanan::where('id', decrypt($request->keranjang))->get(),
        ]);
    }

    function updateTerjual(Request $request, $id)
    {
        $produk = produk::find(decrypt($id));

        if ($request->terjual) {
            $produk->update([
                'terjual' => $request->terjual,
            ]);

            return back()->with('berhasil', 'Berhasil Update Data');
        }

        return back()->with('gagal', 'Nominal Terjual tidak Boleh Kosong');
    }

    function getIdFromSlug($slug)
    {
        // Pecah slug berdasarkan tanda '-'
        $parts = explode('-', $slug);

        // Ambil bagian terakhir (encoded id)
        $encodedId = end($parts);

        // Reverse karakter agar bisa base64_decode
        $base64 = strtr($encodedId, '-_,', '+/=');

        // Decode base64
        $encryptedId = base64_decode($base64);

        // Decrypt string untuk dapat id asli
        $id = Crypt::decryptString($encryptedId);

        return $id;
    }
}
