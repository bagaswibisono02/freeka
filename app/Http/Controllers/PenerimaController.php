<?php

namespace App\Http\Controllers;

use App\Models\alamat_penerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaController extends Controller
{
    function create()  {
        return view('penerima.create');
    }
    function store(Request $request){
       $keranjang =  strpos($request->url, 'keranjang');
       $checkout =  strpos($request->url, 'checkout');
      
       alamat_penerima::create([
        'nama'=>$request->nama,
        'alamat'=>$request->dusun,
        'kelurahan_id'=> decrypt($request->kel),
        'contact'=>$request->contact,
        'user_id'=>Auth::user()->id
       ]);
       if($keranjang){


        return response()->json([
            'pesan'=>' Berhasil'
        ], 200);
       }
       return response()->json([
        'pesan'=>' Berhasil'
    ], 200);
        
    }

    function load() {
        return view('ajax.list-penerima');
    }
    function tambah(Request $request)  {

  $validated = $request->validate([
        'penerima'  => 'required|string|max:255',
        'contact'   => 'required|string|max:255',
        'provinsi'  => 'required',
        'kabupaten' => 'required',
        'kecamatan' => 'required',
        'kelurahan' => 'required|string|max:255',
        'rt_rw'     => 'required|string|max:20',
        'jalan'     => 'required|string|max:255',
    ]);
    $alamat = $this->formatAlamat($request->provinsi, $request->kabupaten, $request->kecamatan, $request->kelurahan,$request->rt_rw, $request->jalan);

    alamat_penerima::create([
        'user_id'=>Auth::user()->id,
        'penerima'=>$request->penerima,
        'contact'=>$request->contact,
        'alamat'=>$alamat
    ]);
       return redirect()->back()->with('berhasil', 'Berhasil');
    }



function formatAlamat($idProvinsi, $idKab, $idKec, $kel, $rt, $jalan)
{
    // Ambil data dari file JSON di public/wilayah
    $provinsiData  = collect(json_decode(file_get_contents(public_path('wilayah/provinsi.json'))));
    $kabupatenData = collect(json_decode(file_get_contents(public_path('wilayah/kabupaten.json'))));
    $kecamatanData = collect(json_decode(file_get_contents(public_path('wilayah/kec.json'))));

    // Ambil nama berdasarkan ID
    $namaProvinsi  = optional($provinsiData->firstWhere('id', (int) $idProvinsi))->nama ?? '';
    $namaKabupaten = optional($kabupatenData->firstWhere('id', (int) $idKab))->nama ?? '';
    $namaKecamatan = optional($kecamatanData->firstWhere('id', (int) $idKec))->nama ?? '';

    // Format alamat lengkap
    $alamat = "{$jalan}, RT/RW {$rt}, Kel. {$kel}, Kec. {$namaKecamatan}, {$namaKabupaten}, Prov. {$namaProvinsi}";

    return $alamat;
}

}
