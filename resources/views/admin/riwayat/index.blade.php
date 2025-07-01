@extends('admin.layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<div class="card">
    <div class="card-header"><h4>Semua Transaksi</h4></div>
    <div class="card-body">
       <form action="{{ route('admin.riwayat.export') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Dari Tanggal</label>
                <input type="date" name="from" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Sampai Tanggal</label>
                <input type="date" name="to" class="form-control" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-success w-100" type="submit">
                    <i class="fas fa-file-excel me-1"></i> Download Excel
                </button>
            </div>
        </form>
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Meja</th>
                    <th>Status Pesanan</th>
                    <th>Jumlah Item</th>
                    <th>Total Bayar</th>
                    <th>Jenis Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->kode_pesanan }}</td>
                    <td>{{ $order->meja->nomor_meja ?? '-' }}</td>
                    <td><span class="badge 
                        @if($order->status == 'pending') bg-danger 
                        @elseif($order->status == 'diproses') bg-warning 
                        @else bg-success @endif">
                        {{ ucfirst($order->status) }}</span>
                    </td>
                    <td>{{ $order->items->sum('qty') }}</td>
                    <td>Rp {{ number_format($order->total_bayar ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $order->jenis_pembayaran }}</td>
                    <td>
                        {{-- <button onclick="openEditModal({{ $order->id }}, '{{ $order->status }}')" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button> --}}
                        <button onclick="confirmDelete({{ $order->id }}, '{{ $order->kode_pesanan }}')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                @foreach($reservations as $res)
                <tr>
                    <td>{{ $loop->iteration + count($orders) }}</td>
                    <td>{{ $res->kode_reservasi }}</td>
                    <td>-</td> <!-- Reservasi biasanya tidak ada nomor meja -->
                    <td><span class="badge bg-success">Selesai</span></td>
                    <td>{{ $res->items->sum('qty') }}</td>
                    <td>Rp {{ number_format($res->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($res->metode_dp) }}</td>
                    <td>
                        <button onclick="confirmDelete({{ $res->id }}, '{{ $res->kode_reservasi }}')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

<!-- Script -->
@push('scripts')
@if(session('success'))
<script>
    Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: "#4fbe87",
    }).showToast();
</script>
@endif

<script>
   function confirmDelete(id, kode) {
        Swal.fire({
            title: `Hapus Transaksi?`,
            text: `Yakin ingin menghapus transaksi ${kode}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/riwayat-transaksi/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    </script>

<!-- Simple DataTables -->
    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        // Initialize DataTable with options
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1, {
            searchable: true,
            fixedHeight: false,
            perPage: 10,
            perPageSelect: [5, 10, 15, 20, 25],
            sortable: true,
            pagination: true,
            labels: {
                placeholder: "Cari Transaksi...",
                perPage: "per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} data"
            }
        });
    </script>
@endpush
@endsection
