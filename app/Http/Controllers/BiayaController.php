<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Parameter default jika tidak disediakan dalam request
        $params = [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'order_dir' => $request->input('order_dir', 'ASC')
        ];

        // Request ke API SIHALAL
        $response = Http::get('http://lph-api.halal.go.id/api/v1/costs', $params);

        // Cek apakah request berhasil
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'Failed to fetch data from SIHALAL API'], $response->status());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari request
        $fields = $request->validate([
            'id_reg' => 'required|string',
            'keterangan' => 'required|string',
            'qty' => 'required|integer',
            'harga' => 'required|numeric'
        ]);

        // Kirim data ke API SIHALAL untuk disimpan
        $response = Http::post('http://lph-api.halal.go.id/api/v1/costs', $fields);

        // Cek apakah request berhasil
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            return response()->json(['error' => 'Failed to store data in SIHALAL API'], $response->status());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Request untuk menampilkan data berdasarkan ID
        $response = Http::get("http://lph-api.halal.go.id/api/v1/costs/{$id}");

        // Cek apakah request berhasil
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'Failed to fetch data from SIHALAL API'], $response->status());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari request
        $fields = $request->validate([
            'id_reg' => 'required|string',
            'keterangan' => 'required|string',
            'qty' => 'required|integer',
            'harga' => 'required|numeric'
        ]);

        // Kirim data ke API SIHALAL untuk diperbarui
        $response = Http::put("http://lph-api.halal.go.id/api/v1/costs/{$id}", $fields);

        // Cek apakah request berhasil
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'Failed to update data in SIHALAL API'], $response->status());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Kirim request untuk menghapus data berdasarkan ID
        $response = Http::delete("http://lph-api.halal.go.id/api/v1/costs/{$id}");

        // Cek apakah request berhasil
        if ($response->successful()) {
            return response()->json(['message' => 'Data deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to delete data from SIHALAL API'], $response->status());
        }
    }
}
