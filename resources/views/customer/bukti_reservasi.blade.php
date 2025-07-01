<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h3   { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th,td { border: 1px solid #ddd; padding: 4px; }
    </style>
</head>
<body>
    <h3>Bukti Reservasi</h3>
    <p>Kode Reservasi: <strong>{{ $reservasi->kode_reservasi }}</strong></p>
    <p>Nama: <strong>{{ $reservasi->nama }}</strong></p>
    <p>Tanggal: {{ $reservasi->tanggal }} ‑ {{ $reservasi->jam }}</p>
    <p>Jumlah Orang: {{ $reservasi->jumlah_orang }}</p>

    <table>
        <thead>
            <tr><th>Menu</th><th>Qty</th></tr>
        </thead>
        <tbody>
            @foreach($reservasi->items as $it)
                <tr>
                    <td>{{ $it->menu->nama }}</td>
                    <td style="text-align:center">{{ $it->qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:10px;">
        Total + Pajak (11 %): <strong>Rp {{ number_format($reservasi->jumlah_bayar,0,',','.') }}</strong>
    </p>

    <p>Metode DP: {{ ucfirst($reservasi->metode_dp) }}</p>
    <p>DP yang harus dibayar (30%): <strong>Rp {{ number_format($reservasi->jumlah_bayar * 0.3, 0, ',', '.') }}</strong></p>

    <small>Silakan tunjukkan bukti ini saat datang / kirim melalui WhatsApp untuk konfirmasi.</small>
</body>
</html>
