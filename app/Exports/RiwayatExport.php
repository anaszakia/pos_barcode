<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RiwayatExport implements FromView
{
    protected $from, $to;

    public function __construct(string $from, string $to)
    {
        $this->from = Carbon::parse($from);
        $this->to = Carbon::parse($to);
    }

    public function view(): View
    {
        $orders = Order::with('meja', 'items')
            ->whereBetween('created_at', [$this->from, $this->to])
            ->where('status', 'selesai')
            ->get();

        $reservasis = Reservation::with('items')
            ->whereBetween('tanggal', [$this->from->toDateString(), $this->to->toDateString()])
            ->where('status_reservasi', 'selesai')
            ->get();

        return view('admin.riwayat.export', compact('orders', 'reservasis'));
    }
}
