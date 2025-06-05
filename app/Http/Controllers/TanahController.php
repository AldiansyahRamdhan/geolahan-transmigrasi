<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use App\Models\TanahTransmigrasi;

class TanahController extends Controller
{
    public function index()
    {
        $tanahs = TanahTransmigrasi::latest()->get();
        return view('dashboard.geojson.index', compact('tanahs'));
    }

    /**
     * Tampilkan form untuk input data tanah transmigrasi
     */
    public function create()
    {
        $rekomendasis = Rekomendasi::all();

        return view('dashboard.geojson.create', [
            'rekomendasis' => $rekomendasis,
        ]);
    }

    /**
     * Simpan data tanah transmigrasi ke database
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'tipe_hak' => 'required|string|max:255',
            'tahun' => 'required|string|max:255',
            'nib' => 'required|string|max:10',
            'luas' => 'required|numeric',
            'penggunaan' => 'required|string|max:255',
            'jenis_tanah' => 'required|string|max:255',
            'kadar_air' => 'required|string|max:255',
            'lereng' => 'required|string|max:255',
            'rekomendasi_tanaman' => 'required|string|max:255',
            'geojson' => 'required|json', // Harus valid JSON
        ]);

        TanahTransmigrasi::create($validated);

        return redirect()->route('tanah.index')->with('success', 'Data tanah berhasil disimpan!');
    }

    public function edit($id)
    {
        $rekomendasis = Rekomendasi::all();
        $tanah = TanahTransmigrasi::findOrFail($id);
        return view('dashboard.geojson.edit', compact('tanah', 'rekomendasis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([

            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'tipe_hak' => 'required|string|max:255',
            'tahun' => 'required|string|max:255',
            'nib' => 'required|string|max:10',
            'luas' => 'required|numeric',
            'penggunaan' => 'required|string|max:255',
            'jenis_tanah' => 'required|string|max:255',
            'kadar_air' => 'required|string|max:255',
            'lereng' => 'required|string|max:255',
            'rekomendasi_tanaman' => 'required|string|max:255',
            'geojson' => 'required|json', // Harus valid JSON
        ]);

        $tanah = TanahTransmigrasi::findOrFail($id);
        $tanah->update($request->all());

        return redirect()->route('tanah.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tanah = TanahTransmigrasi::findOrFail($id);
        $tanah->delete();

        return redirect()->route('tanah.index')->with('success', 'Data berhasil dihapus!');
    }

    // Method untuk mengembalikan data GeoJSON
    public function geojson()
    {
        // Ambil semua data GeoJSON dari database
        $tanah = TanahTransmigrasi::all();

        // Format data GeoJSON
        $geoJsonData = $tanah->map(function ($item) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'kecamatan' => $item->kecamatan,
                    'desa' => $item->kelurahan,
                    'hak_milik' => $item->tipe_hak,
                    'tahun' => $item->tahun,
                    'luas' => $item->luas,
                    'nib' => $item->nib,
                    'penggunaan_tanah' => $item->penggunaan,
                    'jenis_tanah' => $item->jenis_tanah,
                    'kadar_air' => $item->kadar_air,
                    'lereng' => $item->lereng,
                    'rekomendasi_tanaman' => $item->rekomendasi_tanaman,
                ],
                'geometry' => json_decode($item->geojson), // Pastikan kolom geojson disimpan dalam format JSON
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $geoJsonData
        ]);
    }
}
