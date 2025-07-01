<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderanController extends Controller
{
    // Menampilkan semua order
   public function index()
    {
        $today = Carbon::today();

        $orders = Order::with(['meja', 'items.menu'])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $mejas = Meja::all();
        $menus = Menu::all();
        $kategoriMenus = Menu::with('kategori')->get()->groupBy('kategori.nama');

        if (auth()->user()->role === 'kasir') {
            return view('kasir.orderan.index', compact('orders', 'mejas', 'menus', 'kategoriMenus'));
        } else {
            return view('admin.orderan.index', compact('orders', 'mejas', 'menus', 'kategoriMenus'));
        }
    }

    // Menampilkan form buat order manual (jika pakai halaman terpisah)
    public function create()
    {
        $mejas = Meja::all();
        $menus = Menu::all();

        return view('admin.orders.create', compact('mejas', 'menus'));
    }

    // Menyimpan order baru (manual/kasir)
    public function store(Request $request)
    {
        $request->validate([
            'meja_id' => 'required|exists:mejas,id',
            'jenis_pembayaran' => 'required|in:cash,transfer',
            'status_pembayaran' => 'required|in:pending,sukses',
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.qty' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // Buat kode pesanan format: ORD + tanggal (Ymd) + nomor urut hari ini
            $today = Carbon::today()->format('Ymd');
            $countToday = Order::whereDate('created_at', Carbon::today())->count() + 1;
            $kode_pesanan = 'ORD' . $today . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // Buat order utama
            $order = Order::create([
                'meja_id' => $request->meja_id,
                'kode_pesanan' => $kode_pesanan,
                'status' => 'pending',
                'status_pembayaran' => $request->status_pembayaran,
                'total_bayar' => 0, // sementara, nanti akan di-update
                'jenis_pembayaran' => $request->jenis_pembayaran,
            ]);

            $subtotal = 0;

            // Simpan semua item order
            foreach ($request->menus as $item) {
                $menu = Menu::findOrFail($item['id']);
                $qty = (int) $item['qty'];
                $harga = $menu->harga;
                $itemSubtotal = $qty * $harga;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'qty' => $qty,
                    'subtotal' => $itemSubtotal,
                ]);

                $subtotal += $itemSubtotal;
            }

            // Hitung total bayar + pajak 11%
            $pajak = round($subtotal * 0.11);
            $grandTotal = $subtotal + $pajak;

            // Simpan total bayar
            $order->update(['total_bayar' => $grandTotal]);

            DB::commit();

            return redirect()->route('orderan.index')->with('success', 'Order berhasil disimpan. Total Bayar: Rp ' . number_format($grandTotal, 0, ',', '.'));

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan order: ' . $e->getMessage());
        }
    }

    // Menampilkan detail order
    public function show($id)
    {
        $order = Order::with(['meja', 'items.menu'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Menampilkan form edit status order
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    // Update status order
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai',
            'jenis_pembayaran' => 'required|in:cash,transfer',
            'status_pembayaran' => 'required|in:pending,sukses',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'status_pembayaran' => $request->status_pembayaran
        ]);

        // Redirect berdasarkan role user
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.orderan.index')->with('success', 'Data Order Berhasil Diperbarui.');
        } else {
            return redirect()->route('kasir.orderan.index')->with('success', 'Data Order Berhasil Diperbarui.');
        }
    }

    // Hapus order dan semua item-nya
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete(); // akan otomatis hapus order_items karena relasi cascade

        return redirect()->route('admin.orderan.index')->with('success', 'Order berhasil dihapus.');
    }

    public function print($id)
    {
        $order = Order::with(['meja', 'items.menu'])->findOrFail($id);

        $subtotal = $order->items->sum('subtotal');
        $pajak = round($subtotal * 0.11);
        $total = $subtotal + $pajak;

        return view('admin.orderan.struk', compact('order', 'subtotal', 'pajak', 'total'));
    }

}
