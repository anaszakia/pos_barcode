<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index($id)
    {
        $meja = Meja::findOrFail($id);
        $menus = Menu::with('kategori')->get(); // Ambil semua menu dengan relasi kategorinya
        $kategoriMenus = $menus->groupBy('kategori.nama'); // Group berdasarkan nama kategori

        return view('customer.order', compact('meja', 'kategoriMenus', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meja_id' => 'required|exists:mejas,id',
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.qty' => 'required|integer|min:1',
            'jenis_pembayaran' => 'required|in:cash,transfer',
        ]);

        DB::beginTransaction();

        try {
            // Generate kode pesanan: ORD + tanggal + urutan hari ini
            $today = Carbon::today()->format('Ymd');
            $countToday = Order::whereDate('created_at', Carbon::today())->count() + 1;
            $kode_pesanan = 'ORD' . $today . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'meja_id' => $request->meja_id,
                'kode_pesanan' => $kode_pesanan,
                'status' => 'pending',
                'jenis_pembayaran' => $request->jenis_pembayaran,
            ]);

            $total = 0;

            foreach ($request->menus as $menuItem) {
                $menu = Menu::findOrFail($menuItem['id']);
                $qty = (int) $menuItem['qty'];
                $subtotal = $qty * $menu->harga;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $pajak = round($total * 0.11);
            $totalBayar = $total + $pajak;

            // Simpan total bayar
            $order->update([
                'total_bayar' => $totalBayar,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil dikirim! Silahkan datang ke kasir untuk melakukan pembayaran dengan nominal Rp. ' . number_format($totalBayar, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

