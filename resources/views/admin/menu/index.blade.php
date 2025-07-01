@extends('admin.layouts.app')

@section('title', 'Data Menu')
@section('page-title', 'Data Menu')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Menu</h4>
        <button class="btn btn-primary" onclick="openCreateModal()">Tambah Menu</button>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $menu->kategori->nama ?? '-' }}</td>
                        <td>{{ $menu->nama }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>{{ $menu->deskripsi }}</td>
                        <td>
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="gambar" width="60">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="openEditModal({{ $menu->id }}, `{{ $menu->nama }}`, '{{ $menu->harga }}', `{{ $menu->deskripsi }}`, '{{ $menu->kategori_id }}')">
                                Edit
                            </button>
                            <form id="delete-form-{{ $menu->id }}" action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $menu->id }}, `{{ $menu->nama }}`)">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data menu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" id="edit_kategori_id" class="form-control" required>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" id="edit_harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ganti Gambar</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
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
    {{-- <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script> --}}

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
            document.querySelector('#createModal form').reset();
            
            const modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
        }

        function openEditModal(id, nama, harga, deskripsi, kategori_id) {
            const form = document.getElementById('editForm');
            form.action = `/admin/menu/${id}`; 
            document.getElementById('edit_nama').value = nama || '';
            document.getElementById('edit_harga').value = harga || '';
            document.getElementById('edit_deskripsi').value = deskripsi || '';
            document.getElementById('edit_kategori_id').value = kategori_id || '';

            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Yakin ingin menghapus menu "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
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
                placeholder: "Cari menu...",
                perPage: "per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} data"
            }
        });
    </script>
@endpush