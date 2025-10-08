<?php

namespace App\Http\Controllers;

use App\Models\kategory;
use Illuminate\Http\Request;

class KategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        kategory::create([
            'name'=>$request->nama
        ]);
        return back()->with('berhasil', 'Berhasil Menambah Category');
    }

    /**
     * Display the specified resource.
     */
    public function show(kategory $kategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kategory $kategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kategory)
    {
        $id = decrypt($kategory);
        kategory::find($id)->update([
            'name'=>$request->nama
        ]);
        
        return back()->with('berhasil', 'Berhasil Update Kategory');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kategory)
    {
        $id = decrypt($kategory);
        kategory::find($id)->delete();
        return back()->with('gagal','Berhasil Hapus Kategori');
    }
}
