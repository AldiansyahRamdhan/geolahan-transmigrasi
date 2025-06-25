<?php

namespace App\Http\Controllers;

use App\Models\Batas;
use Illuminate\Http\Request;

class BatasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'batas' => 'required|json',
        ]);

        // Ambil row pertama (id = 1), atau buat jika belum ada
        $batas = Batas::get()->first();

        // Update field batas
        $batas->update([
            'batas' => $request->batas
        ]);

        return redirect()->back()->with('success', 'Data batas berhasil diperbarui.');
    }
}
