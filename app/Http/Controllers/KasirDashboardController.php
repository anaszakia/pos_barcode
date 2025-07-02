<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalOrderHariIni      = Order::whereDate('created_at', $today)->count();
        $totalReservasiHariIni  = Reservation::whereDate('tanggal', $today)->count();

        return view('kasir.dashboard', compact('totalOrderHariIni', 'totalReservasiHariIni'));
    }
}
