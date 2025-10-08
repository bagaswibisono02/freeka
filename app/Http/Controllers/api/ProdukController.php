<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProdukController extends Controller
{
     // Get all produk
    public function index()
    {
        return response()->json(produk::all(), 200);
    }

    // Get produk by ID
    public function show($id)
    {
        $produk = produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json($produk, 200);
    }

    // Create produk
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategory_id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'harga' => 'required|string|max:255',
            'terjual' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        $produk = produk::create($validated);

        return response()->json($produk, 201);
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'kategory_id' => 'sometimes|integer',
            'nama' => 'sometimes|string|max:255',
            'keterangan' => 'sometimes|string',
            'harga' => 'sometimes|string|max:255',
            'terjual' => 'nullable|string|max:255',
        ]);

        if (isset($validated['nama'])) {
            $validated['slug'] = Str::slug($validated['nama']);
        }

        $produk->update($validated);

        return response()->json($produk, 200);
    }

    // Delete produk
    public function destroy($id)
    {
        $produk = produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $produk->delete();

        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}
