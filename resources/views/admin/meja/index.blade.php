@extends('admin.layouts.app')

@section('title', 'Data Meja')
@section('page-title', 'Data Meja')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Meja</h4>
        <button class="btn btn-primary" onclick="openCreateModal()">Tambah Meja</button>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Meja</th>
                    <th>Lantai</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mejas as $meja)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td id="row-nomor-meja-{{ $meja->id }}">{{ $meja->nomor_meja }}</td>
                        <td>{{ $meja->lantai }}</td>
                        <td>{{ $meja->lokasi }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="openEditModal({{ $meja->id }}, '{{ $meja->nomor_meja }}', '{{ $meja->lantai }}', '{{ $meja->lokasi }}')">
                                Edit
                            </button>
                            <form action="{{ route('admin.meja.destroy', $meja->id) }}" method="POST" class="d-inline" id="delete-form-{{ $meja->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $meja->id }}, '{{ $meja->nomor_meja }}')">Hapus</button>
                            </form>
                                <button class="btn btn-secondary btn-sm mb-1" onclick="downloadBarcode({{ $meja->id }})">
                                    <i class="bi bi-download"></i> Download Barcode
                                </button>
                                <!-- Tempat QR code tersembunyi -->
                                <div id="qrcode-{{ $meja->id }}" style="display: none;"></div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Belum ada data meja</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.meja.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Meja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nomor Meja</label>
                    <input type="text" name="nomor_meja" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Lantai</label>
                    <input type="text" name="lantai" class="form-control">
                </div>
               <div class="mb-3">
                    <label>Lokasi</label>
                    <select name="lokasi" name="lokasi" class="form-control">
                        <option value="">-- Pilih Lokasi --</option>
                        <option value="Indoor">Indoor</option>
                        <option value="Outdoor">Outdoor</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Meja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nomor Meja</label>
                    <input type="text" name="nomor_meja" id="edit_nomor_meja" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Lantai</label>
                    <input type="text" name="lantai" id="edit_lantai" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Lokasi</label>
                    <select name="lokasi" id="edit_lokasi" class="form-control">
                        <option value="">-- Pilih Lokasi --</option>
                        <option value="Indoor">Indoor</option>
                        <option value="Outdoor">Outdoor</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-warning">Update</button>
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
    function openCreateModal() {
        const modal = new bootstrap.Modal(document.getElementById('createModal'));
        modal.show();
    }

    function openEditModal(id, nomor, lantai, lokasi) {
        const form = document.getElementById('editForm');
        form.action = `/admin/meja/${id}`;
        document.getElementById('edit_nomor_meja').value = nomor;
        document.getElementById('edit_lantai').value = lantai;
        document.getElementById('edit_lokasi').value = lokasi;

        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }

    function confirmDelete(id, nomor) {
        Swal.fire({
            title: 'Hapus Meja?',
            text: `Yakin ingin menghapus meja "${nomor}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
<!-- jQuery dan qrcode.js -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>

<script>
    function downloadBarcode(id) {
        const nomorMeja = document.querySelector(`#row-nomor-meja-${id}`).textContent.trim();
        const qrContainer = document.getElementById(`qrcode-${id}`);

        // Kosongkan isi sebelumnya
        qrContainer.innerHTML = "";

        // Isi QR dengan URL pemesanan
        const urlPesan = `${window.location.origin}/order/meja/${id}`;

        $(qrContainer).qrcode({
            width: 200,
            height: 200,
            text: urlPesan
        });

        setTimeout(() => {
            const qrCanvas = qrContainer.querySelector("canvas");
            if (!qrCanvas) return;

            const qrImage = new Image();
            qrImage.onload = function () {
                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");

                const qrSize = qrImage.width;
                const textHeight = 40;
                canvas.width = qrSize;
                canvas.height = qrSize + textHeight;

                // Background putih
                ctx.fillStyle = "#fff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Gambar QR
                ctx.drawImage(qrImage, 0, 0);

                // Tulis teks nomor meja
                ctx.fillStyle = "#000";
                ctx.font = "bold 18px Arial";
                ctx.textAlign = "center";
                ctx.fillText("Meja " + nomorMeja, qrSize / 2, qrSize + 25);

                const imgData = canvas.toDataURL("image/png");
                const link = document.createElement("a");
                link.href = imgData;
                link.download = `qr_meja_${nomorMeja}.png`;
                link.click();
            };

            qrImage.src = qrCanvas.toDataURL("image/png");
        }, 300);
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
                placeholder: "Cari Meja...",
                perPage: "per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} data"
            }
        });
    </script>
@endpush
