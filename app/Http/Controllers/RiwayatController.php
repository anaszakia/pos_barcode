<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Exports\RiwayatExport;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatController extends Controller
{
    public function index()
    {
        $orders = Order::with(['meja', 'items.menu'])
            ->where('status', 'selesai') // hanya order selesai
            ->latest()
            ->get();
        $reservations = Reservation::with(['items.menu'])
            ->where('status_pembayaran', 'sukses') // hanya reservasi selesai
            ->latest()
            ->get();

        return view('admin.riwayat.index', compact('orders', 'reservations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Coba cari sebagai Order terlebih dahulu
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return back()->with('success', 'Transaksi order berhasil dihapus.');
        }

        // Jika bukan order, coba cari sebagai Reservasi
        $reservasi = Reservation::find($id);
        if ($reservasi) {
            $reservasi->items()->delete(); // hapus item terlebih dahulu jika ada relasi
            $reservasi->delete();
            return back()->with('success', 'Reservasi berhasil dihapus.');
        }

        return back()->with('error', 'Data tidak ditemukan.');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from'
        ]);

        $from = $request->from . ' 00:00:00';
        $to = $request->to . ' 23:59:59';

        return Excel::download(new RiwayatExport($from, $to), 'riwayat-transaksi.xlsx');
    }
}
