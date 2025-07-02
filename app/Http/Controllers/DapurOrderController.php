<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DapurOrderController extends Controller
{
    public function index()
    {
        // Ambil semua order yang status_pembayaran = sukses DAN status belum selesai
        $orders = Order::with(['meja', 'items.menu'])
            ->where('status_pembayaran', 'sukses')
            ->where('status', '!=', 'selesai')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();
        
        $reservations = Reservation::with(['items.menu'])
            ->where('status_pembayaran', 'sukses')
            ->where('status_reservasi', '!=', 'selesai')
            ->whereDate('tanggal', Carbon::today())
            ->latest()
            ->get();

        return view('dapur.orderan.index', compact('orders', 'reservations'));
    }

  public function updateStatus(Request $request, $id, $tipe = 'order')
    {
        if ($tipe === 'order') {
            $order = Order::findOrFail($id);
            $order->update(['status' => 'selesai']);
        } else {
            // $tipe = 'reservasi'
            $reservasi = Reservation::findOrFail($id);
            $reservasi->update(['status_reservasi' => 'selesai']);   // kolom apa pun yg dipakai
        }

        return back()->with('success', 'Status berhasil diubah menjadi selesai.');
    }

    public function selesaiReservasi($id)
    {
        $reservasi = Reservation::findOrFail($id);
        $reservasi->status_pembayaran = 'selesai'; 
        $reservasi->save();

        return back()->with('success', 'Reservasi ditandai selesai');
    }

    public function json()
    {
        // Order yang SUDAH dibayar & BELUM selesai, hari ini
        $orders = Order::with(['meja', 'items.menu'])
            ->where('status_pembayaran', 'sukses')
            ->where('status', '!=', 'selesai')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        // Reservasi yang SUDAH dibayar (status_pembayaran = sukses) & untuk tanggal hari ini
        $reservations = Reservation::with(['items.menu'])
            ->where('status_pembayaran', 'sukses')
            ->where('status_reservasi', '!=', 'selesai') 
            ->whereDate('tanggal', Carbon::today())
            ->latest()
            ->get();

        // 👉  kembalikan dalam satu objek JSON
        return response()->json([
            'orders'       => $orders,
            'reservations' => $reservations,
        ]);
    }

}
