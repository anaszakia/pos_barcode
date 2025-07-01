<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Meja</th>
            <th>Status</th>
            <th>Jumlah Item</th>
            <th>Total Bayar</th>
            <th>Jenis Pembayaran</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $order->kode_pesanan }}</td>
            <td>{{ $order->meja->nomor_meja ?? '-' }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ $order->items->sum('qty') }}</td>
            <td>Rp {{ number_format($order->total_bayar ?? 0, 0, ',', '.') }}</td>
            <td>{{ $order->jenis_pembayaran }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
