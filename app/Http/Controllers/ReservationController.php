<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\ReservationItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservasis = Reservation::with('items.menu')->latest()->get();
        $menus = Menu::all();
        if (auth()->user()->role === 'kasir') {
            return view('kasir.reservasi.index', compact('reservasis', 'menus'));
        } else {
            return view('admin.reservasi.index', compact('reservasis', 'menus'));
        }
    }
    
    public function show($id)
    {
        $reservasi = Reservation::with('items.menu')->findOrFail($id);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,sukses'
        ]);

        $reservasi = Reservation::findOrFail($id);
        $reservasi->status_pembayaran = $request->status_pembayaran;
        $reservasi->save();

       if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.reservasi.index')->with('success', 'Data Reservasi Berhasil Diperbarui.');
        } else {
            return redirect()->route('kasir.reservasi.index')->with('success', 'Data Reservasi Berhasil Diperbarui.');
        }
    }

    public function destroy($id)
    {
        $reservasi = Reservation::findOrFail($id);
        $reservasi->delete();

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.reservasi.index')->with('success', 'Data Reservasi Berhasil Dihapus.');
        } else {
            return redirect()->route('kasir.reservasi.index')->with('success', 'Data Reservasi Berhasil Dihapus.');
        }
    }

    public function create()
    {
        $menus = Menu::all();
        return view('customer.reservasi', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'jumlah_orang' => 'required|integer|min:1',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required',
            'metode_dp' => 'required|in:transfer,kasir',
            'menus' => 'required|array',
        ]);

        // Hitung total harga dari menu yang dipilih
        $total = 0;
        foreach ($request->menus as $menuId => $qty) {
            if ($qty > 0) {
                $menu = Menu::findOrFail($menuId);
                $total += $menu->harga * $qty;
            }
        }

        // Hitung pajak 11% dan jumlah bayar
        $pajak = round($total * 0.11);
        $grandTotal = $total + $pajak;

        // Buat kode reservasi: RSV + tanggal (ymd) + urutan reservasi hari itu
        $tanggal = Carbon::parse($request->tanggal);
        $prefix = 'RSV' . $tanggal->format('ymd');
        $countToday = Reservation::whereDate('tanggal', $tanggal)->count() + 1;
        $kode = $prefix . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        // Simpan data reservasi
        $reservasi = Reservation::create([
            'kode_reservasi' => $kode,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'jumlah_orang' => $request->jumlah_orang,
            'tanggal' => $tanggal->format('Y-m-d'),
            'jam' => $request->jam,
            'catatan' => $request->catatan,
            'status_pembayaran' => 'pending',
            'metode_dp' => $request->metode_dp,
            'jumlah_bayar' => $grandTotal,
            'status_reservasi' => 'pending',
        ]);

        // Simpan item menu ke tabel reservation_items
        foreach ($request->menus as $menuId => $qty) {
            if ($qty > 0) {
                ReservationItem::create([
                    'reservation_id' => $reservasi->id,
                    'menu_id' => $menuId,
                    'qty' => $qty,
                ]);
            }
        }

        return redirect()
        ->route('reservasi.download', $reservasi->id)
        ->with('success', "Reservasi berhasil! Kode Anda: $kode. Bukti reservasi diunduh otomatis.");
    }

    public function download($id)
    {
        $reservasi = Reservation::with('items.menu')->findOrFail($id);

        $pdf = Pdf::loadView('customer.bukti_reservasi', compact('reservasi'))
                ->setPaper('A6', 'portrait');   // slip kecil

        $filename = 'Bukti_Reservasi_'.$reservasi->kode_reservasi.'.pdf';
        return $pdf->download($filename);
}
}
