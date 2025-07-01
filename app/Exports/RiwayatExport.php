<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RiwayatExport implements FromView
{
    protected $from, $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $orders = Order::with(['meja', 'items.menu'])
            ->whereBetween('created_at', [$this->from, $this->to])
            ->get();

        return view('admin.riwayat.export', compact('orders'));
    }
}
