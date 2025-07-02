<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    // public function index()
    // {
    //     $today = Carbon::today();

    //     $totalOrderHariIni      = Order::whereDate('created_at', $today)->count();
    //     $totalReservasiHariIni  = Reservation::whereDate('tanggal', $today)->count();

    //     return view('admin.dashboard', compact('totalOrderHariIni', 'totalReservasiHariIni'));
    // }
     public function index()
    {
        /*  -- 1. TOTAL DATA -------------------------------------------------- */
        $totalOrder        = Order::count();              // seluruh order
        $totalReservasi    = Reservation::count();        // seluruh reservasi

        /*  -- 2. TOTAL PEMASUKAN -------------------------------------------- */
        //
        //   • Order   → kolom `total_bayar`
        //   • Reservasi→ kolom `jumlah_bayar`
        //   Hanya menjumlahkan data yang sudah LUNAS / SUKSES
        //
        $omzetOrder        = Order::where('status','selesai')
                                  ->sum('total_bayar');

        $omzetReservasi    = Reservation::where('status_reservasi','selesai')
                                        ->sum('jumlah_bayar');

        $totalOmzet        = $omzetOrder + $omzetReservasi;

        /*  -- 3. Kirim ke view ---------------------------------------------- */
        return view('admin.dashboard', [
            'totalOrder'       => $totalOrder,
            'totalReservasi'   => $totalReservasi,
            'omzetOrder'       => $omzetOrder,
            'omzetReservasi'   => $omzetReservasi,
            'totalOmzet'       => $totalOmzet,
        ]);
    }
}
