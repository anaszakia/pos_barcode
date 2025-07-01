@extends('admin.layouts.app')

@section('title', 'Data Kategori')
@section('page-title', 'Data Kategori')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Kategori</h4>
        <button class="btn btn-primary" onclick="openCreateModal()">Tambah Kategori</button>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $kategori)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kategori->nama }}</td>
                        <td>{{ $kategori->deskripsi }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="openEditModal({{ $kategori->id }}, `{{ addslashes($kategori->nama) }}`, `{{ addslashes($kategori->deskripsi) }}`)">
                                Edit
                            </button>

                            <form id="delete-form-{{ $kategori->id }}" action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data kategori</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.kategori.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama_create" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" id="nama_create" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi_create" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi_create"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama_edit" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" id="nama_edit" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi_edit" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi_edit"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Update</button>
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
                stopOnFocus: true,
            }).showToast();
        </script>
    @endif

    <script>
        function openCreateModal() {
            // Reset form
            document.getElementById('nama_create').value = '';
            document.getElementById('deskripsi_create').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
        }

        function openEditModal(id, nama, deskripsi) {
            const form = document.getElementById('editForm');

            // Set form action dengan benar
            form.action = "{{ url('admin/kategori') }}/" + id;

            // Set input values
            document.getElementById('nama_edit').value = nama || '';
            document.getElementById('deskripsi_edit').value = deskripsi || '';

            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Yakin ingin menghapus kategori "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form delete
                    document.getElementById('delete-form-' + id).submit();
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
                placeholder: "Cari kategori...",
                perPage: "per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} data"
            }
        });
    </script>
@endpush