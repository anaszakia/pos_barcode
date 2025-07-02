@extends('dapur.layouts.app')

@section('title', 'Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')

@section('content')
<div class="card">
    <div class="card-body">
        <div id="order-container">
            <p>Memuat data pesanan...</p>
        </div>
    </div>
</div>

{{-- Audio Notifikasi --}}
<audio id="notifSound" preload="auto">
    <source src="{{ asset('sounds/notif.mp3') }}" type="audio/mpeg">
    Browser tidak mendukung pemutaran audio.
</audio>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let previousOrderIds = [];
    let audioEnabled = false;

    // Izinkan audio saat user pertama kali berinteraksi
    document.addEventListener('click', () => {
        const audio = document.getElementById('notifSound');
        if (audio && !audioEnabled) {
            audio.play().then(() => {
                audio.pause(); // hanya aktifkan permission
                audioEnabled = true;
                console.log('Audio permission enabled');
            }).catch(() => {
                console.warn('Autoplay ditolak, user belum interaksi');
            });
        }
    }, { once: true });

    function playNotifSound() {
        const audio = document.getElementById('notifSound');
        if (audio && audioEnabled) {
            audio.play().catch(err => console.warn('Gagal memutar audio:', err));
        }
    }

    function loadOrders() {
        fetch("{{ route('dapur.dapur.orderan.json') }}")
            .then(response => response.json())
            .then(data => {
        const container = document.getElementById('order-container');
        let html = '';

        const orders = data.orders || [];
        const reservations = data.reservations || [];

        const currentOrderIds = orders.map(order => `order-${order.id}`);
        const currentReservasiIds = reservations.map(res => `reservasi-${res.id}`);
        const currentIds = currentOrderIds.concat(currentReservasiIds);

        const isFirstLoad = previousOrderIds.length === 0;

        const newIds = currentIds.filter(id => !previousOrderIds.includes(id));

        if (!isFirstLoad && newIds.length > 0) {
            Swal.fire({
                title: 'Pesanan Baru!',
                text: 'Ada pesanan atau reservasi baru yang masuk!',
                icon: 'info',
                toast: true,
                position: 'top-end',
                timer: 5000,
                showConfirmButton: false
            });

            playNotifSound();
        }

        previousOrderIds = currentIds;

        if (orders.length === 0 && reservations.length === 0) {
            html = '<p class="text-center text-muted">Belum ada pesanan atau reservasi masuk.</p>';
        } else {
            // Tampilkan orders
            orders.forEach(order => {
                html += `
                    <div class="card mb-3 shadow-sm border">
                        <div class="card-body">
                            <h5 class="card-title mb-2"><strong>Kode Pesanan:</strong> ${order.kode_pesanan}</h5>
                            <p><strong>Meja:</strong> ${order.meja.nomor_meja}</p>
                            <p><strong>Pesanan:</strong></p>
                            <ul>
                                ${order.items.map(item => `<li>${item.menu.nama} x ${item.qty}</li>`).join('')}
                            </ul>
                            <form action="/dapur/dapur/orderan/${order.id}/selesai" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm mt-2">
                                    Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                `;
            });

            // Tampilkan reservations
            reservations.forEach(res => {
                html += `
                    <div class="card mb-3 shadow-sm border border-warning">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-warning"><strong>Reservasi:</strong> ${res.kode_reservasi}</h5>
                            <p><strong>Nama:</strong> ${res.nama}</p>
                            <p><strong>Jumlah Orang:</strong> ${res.jumlah_orang}</p>
                            <p><strong>Jam:</strong> ${res.jam}</p>
                            <p><strong>Menu Dipesan:</strong></p>
                            <ul>
                                ${res.items.map(item => `<li>${item.menu.nama} x ${item.qty}</li>`).join('')}
                            </ul>
                            <form action="/dapur/dapur/reservasi/${res.id}/selesai" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm mt-2">
                                    Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                `;
            });
        }

        container.innerHTML = html;
    })
            .catch(error => {
                console.error("Gagal memuat data:", error);
            });
    }

    // Jalankan saat load pertama dan polling setiap 5 detik
    loadOrders();
    setInterval(loadOrders, 5000);
</script>
@endpush
