<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Jenis</th>
            <th>Meja / Nama</th>
            <th>Status</th>
            <th>Jumlah Item</th>
            <th>Total Bayar</th>
            <th>Jenis Pembayaran</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp

        {{-- Data Orders --}}
        @foreach($orders as $order)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $order->kode_pesanan }}</td>
            <td>Order</td>
            <td>{{ $order->meja->nomor_meja ?? '-' }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ $order->items->sum('qty') }}</td>
            <td>{{ $order->total_bayar }}</td>
            <td>{{ $order->jenis_pembayaran }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach

        {{-- Data Reservasi --}}
        @foreach($reservasis as $res)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $res->kode_reservasi }}</td>
            <td>Reservasi</td>
            <td>{{ $res->nama }}</td>
            <td>{{ ucfirst($res->status_reservasi) }}</td>
            <td>{{ $res->items->sum('qty') }}</td>
            <td>{{ $res->jumlah_bayar }}</td>
            <td>{{ $res->metode_dp }}</td>
            <td>{{ $res->tanggal }} {{ $res->jam }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
