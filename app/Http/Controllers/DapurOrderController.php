<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return view('dapur.orderan.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'selesai']);

        return redirect()->route('dapur.dapur.orderan.index')->with('success', 'Status pesanan telah diubah menjadi selesai.');
    }

    public function json()
    {
        $orders = Order::with(['meja', 'items.menu'])
            ->where('status_pembayaran', 'sukses')
            ->where('status', '!=', 'selesai')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->latest()
            ->get();

        return response()->json($orders);
    }

}
