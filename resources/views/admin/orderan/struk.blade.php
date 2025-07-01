<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            width: 80mm;
            margin: auto;
        }
        h2, h3, p {
            text-align: center;
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 4px 0;
            text-align: left;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        hr {
            margin: 16px 0;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    {{-- Bagian 1: Struk Pembayaran --}}
    <h2>Papico</h2>
    <p>Kode Pesanan: {{ $order->kode_pesanan }}</p>
    <p>Meja: {{ $order->meja->nomor_meja ?? '-' }}.{{ $order->meja->lantai }} - {{ $order->meja->lokasi }}</p>
    <p>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->menu->nama }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table>
        <tr>
            <td class="total">Subtotal:</td>
            <td class="total">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="total">Pajak (11%):</td>
            <td class="total">Rp {{ number_format($pajak, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="total">Total:</td>
            <td class="total">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr style="border-top: 1px dashed #000; margin: 16px 0;">

    {{-- Bagian 2: Ringkasan Menu --}}
    <h3>PESANAN</h3>
    <p>Kode Pesanan: {{ $order->kode_pesanan }}</p>
    <p>Meja: {{ $order->meja->nomor_meja ?? '-' }}.{{ $order->meja->lantai }} - {{ $order->meja->lokasi }}</p>
    <p>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items->groupBy('menu.nama') as $menuName => $groupedItems)
                <tr>
                    <td>{{ $menuName }}</td>
                    <td>{{ $groupedItems->sum('qty') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{-- 
    <p class="no-print">
        <button onclick="window.print()">🖨️ Cetak</button>
    </p> --}}
     <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>
</html>
