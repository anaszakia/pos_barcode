<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::all();
        return view('admin.meja.index', compact('mejas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:255',
            'lantai' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        Meja::create($request->all());
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:255',
            'lantai' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $meja->update($request->all());
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil dihapus.');
    }
}
