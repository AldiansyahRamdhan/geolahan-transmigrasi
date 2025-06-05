<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRekomendasiRequest;
use App\Http\Requests\UpdateRekomendasiRequest;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekomendasis = Rekomendasi::latest()->get();

        return view('dashboard.rekomendasi.index', compact('rekomendasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.rekomendasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penggunaan' => 'required|string|max:255',
            'jenis_tanah' => 'required|string|max:255',
            'kadar_air' => 'required|string|max:255',
            'lereng' => 'required|string|max:255',
            'rekomendasi_tanaman' => 'required|string|max:255',
        ]);

        Rekomendasi::create($validated);

        return redirect()->route('rekomendasi.index')->with('success', 'Data tanah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekomendasi $rekomendasi)
    {
        return view('dashboard.rekomendasi.edit', compact('rekomendasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        $rekomendasi->update([
            'penggunaan' => $request->penggunaan,
            'jenis_tanah' => $request->jenis_tanah,
            'kadar_air' => $request->kadar_air,
            'lereng' => $request->lereng,
            'rekomendasi_tanaman' => $request->rekomendasi_tanaman,
        ]);

        return redirect()->route('rekomendasi.index')
            ->with('success', 'Data rekomendasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        $rekomendasi->delete();

        return redirect()->route('rekomendasi.index')->with('success', 'Data berhasil dihapus');
    }
}
