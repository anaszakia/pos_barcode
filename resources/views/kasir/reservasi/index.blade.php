@extends('kasir.layouts.app')

@section('title', 'Reservasi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Reservasi</h4>
        <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        + Tambah Reservasi
    </button>
    </div>
    <div class="card-body">

   <table class="table table-striped" id="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status Pembayaran</th>
                <th>Bayar</th>
                <th>Status Reservasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasis as $res)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $res->kode_reservasi }}</td>
                    <td>{{ $res->nama }}</td>
                    <td>{{ $res->no_hp }}</td>
                    <td>{{ $res->tanggal }}</td>
                    <td>{{ $res->jam }}</td>
                    <td>
                        <span class="badge {{ $res->status_pembayaran == 'sukses' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($res->status_pembayaran) }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($res->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $res->status_reservasi == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($res->status_reservasi) }}
                        </span>
                    </td>
                    <td>
                       <button class="btn btn-info btn-sm" onclick="showDetail({{ $res->toJson() }}, {{ $res->items->load('menu')->toJson() }})">Detail</button>
                        <button class="btn btn-warning btn-sm" onclick="editStatus({{ $res }})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $res->id }})">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.reservasi.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Tambah Reservasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Jumlah Orang</label>
                <input type="number" name="jumlah_orang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Jam</label>
                <input type="time" name="jam" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Pilih Menu</label>
                @foreach ($menus as $menu)
                    <div class="input-group mb-2">
                        <span class="input-group-text w-50">{{ $menu->nama }} (Rp {{ number_format($menu->harga, 0, ',', '.') }})</span>
                        <input type="number" name="menus[{{ $menu->id }}]" class="form-control" min="0" placeholder="Qty">
                    </div>
                @endforeach
            </div>
            <div class="mb-3">
                <label>Metode DP</label>
                <select name="metode_dp" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="transfer">Transfer</option>
                    <option value="kasir">Bayar di Kasir</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
  </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Detail Reservasi</h5></div>
            <div class="modal-body" id="detailBody"></div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Status</h5></div>
                <div class="modal-body">
                    <label>Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="sukses">Sukses</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formDelete">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Hapus Reservasi</h5></div>
                <div class="modal-body">Yakin ingin menghapus reservasi ini?</div>
                <div class="modal-footer">
                    <button class="btn btn-danger">Ya, Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

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
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    const modalDelete = new bootstrap.Modal(document.getElementById('modalDelete'));

    function showDetail(res, items) {
        let html = `
            <table class="table">
                <tr><th>Kode</th><td>${res.kode_reservasi}</td></tr>
                <tr><th>Nama</th><td>${res.nama}</td></tr>
                <tr><th>No HP</th><td>${res.no_hp}</td></tr>
                <tr><th>Tanggal</th><td>${res.tanggal}</td></tr>
                <tr><th>Jam</th><td>${res.jam}</td></tr>
                <tr><th>Jumlah Orang</th><td>${res.jumlah_orang}</td></tr>
                <tr><th>Metode DP</th><td>${res.metode_dp}</td></tr>
                <tr><th>Status</th><td>${res.status_pembayaran}</td></tr>
                <tr><th>Catatan</th><td>${res.catatan || '-'}</td></tr>
                <tr><th>Total Bayar</th><td>Rp ${parseInt(res.jumlah_bayar).toLocaleString('id-ID')}</td></tr>
            </table>
            <h6 class="mt-3">Daftar Menu Dipesan:</h6>
            <ul>`;
        items.forEach(item => {
            html += `<li>${item.menu.nama} x ${item.qty}</li>`;
        });
        html += `</ul>`;

        document.getElementById('detailBody').innerHTML = html;
        modalDetail.show();
    }


    function editStatus(res) {
        const form = document.getElementById('formEdit');
        form.action = `/kasir/reservasi/${res.id}`;
        form.querySelector('[name="status_pembayaran"]').value = res.status_pembayaran;
        modalEdit.show();
    }

    function confirmDelete(id) {
        const form = document.getElementById('formDelete');
        form.action = `/kasir/reservasi/${id}`;
        modalDelete.show();
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
                placeholder: "Cari Reservasi...",
                perPage: "per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} data"
            }
        });
    </script>
    <script>
        setInterval(function () {
            location.reload();
        }, 5000); // 5000 ms = 5 detik
    </script>
@endpush
