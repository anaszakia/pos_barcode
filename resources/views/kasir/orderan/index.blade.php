@extends('kasir.layouts.app')

@section('title', 'Data Orderan')
@section('page-title', 'Data Orderan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Orderan</h4>
        <button class="btn btn-primary" onclick="openCreateModal()">Tambah Order Manual</button>
    </div>
    <div class="card-body">
        {{-- @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pesanan</th>
                    <th>Meja</th>
                    <th>Status Pesanan</th>
                    <th>Jumlah Item</th>
                    <th>Total Bayar</th>
                    <th>Jenis Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->kode_pesanan }}</td>
                        <td>{{ $order->meja->nomor_meja ?? '-' }}</td>
                        <td>
                            @php
                                $badgeClass = match($order->status) {
                                    'pending' => 'bg-danger',
                                    'diproses' => 'bg-warning text-dark',
                                    'selesai' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>{{ $order->items->sum('qty') }}</td>
                        <td>Rp {{ number_format($order->total_bayar ?? 0, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($order->jenis_pembayaran) }}</td>
                        <td>
                            @php
                                $badgeClass = match($order->status_pembayaran) {
                                    'pending' => 'bg-danger',
                                    'sukses' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status_pembayaran) }}</span>
                        </td>
                       <td>
                            <button 
                                class="btn btn-info btn-sm" 
                                title="Detail"
                                onclick='showDetail(@json($order), @json($order->items->load("menu")))'>
                                <i class="fas fa-eye"></i> {{-- Icon mata untuk detail --}}
                            </button>
                            
                            <button 
                                onclick="openEditModal({{ $order->id }}, '{{ $order->status }}', '{{ $order->jenis_pembayaran }}')" 
                                class="btn btn-warning btn-sm" 
                                title="Edit">
                                <i class="fas fa-edit"></i> {{-- Icon pensil untuk edit --}}
                            </button>
                            
                            <a href="{{ route('kasir.orderan.print', $order->id) }}" 
                                class="btn btn-secondary btn-sm" 
                                target="_blank"
                                title="Cetak Struk">
                                    <i class="fas fa-print"></i>
                            </a>

                            {{-- <form action="{{ route('kasir.orderan.destroy', $order->id) }}" method="POST" class="d-inline" id="delete-form-{{ $order->id }}">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus" 
                                    onclick="confirmDelete({{ $order->id }}, '{{ $order->kode_pesanan }}')">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create --> 
<div class="modal fade" id="createModal" tabindex="-1">     
    <div class="modal-dialog modal-lg">         
        <form action="{{ route('kasir.orderan.store') }}" method="POST" class="modal-content">             
            @csrf             
            <div class="modal-header">                 
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Order Manual</h5>                 
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>             
            </div>             
            <div class="modal-body">                 
                <div class="mb-4">                     
                    <label class="form-label fw-bold"><i class="fas fa-table me-2"></i>Pilih Meja</label>                     
                    <select name="meja_id" class="form-select" required>                         
                        <option value="">-- Pilih Meja --</option>                         
                        @foreach($mejas as $meja)                             
                            <option value="{{ $meja->id }}">Meja {{ $meja->nomor_meja }}</option>                         
                        @endforeach                     
                    </select>                 
                </div>                  

                <div class="mb-4">                     
                    <label class="form-label fw-bold"><i class="fas fa-utensils me-2"></i>Pilih Menu</label>                     
                    <select id="menu-select" class="form-select mb-3">                         
                        <option value="">-- Pilih Menu --</option>                         
                       @foreach($kategoriMenus as $kategoriNama => $menus)
                            <optgroup label="{{ $kategoriNama }}">
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}"
                                        data-nama="{{ $menu->nama }}"
                                        data-deskripsi="{{ $menu->deskripsi }}"
                                        data-harga="{{ $menu->harga }}"
                                        data-gambar="{{ asset('storage/' . $menu->image) }}">
                                        {{ $menu->nama }} - ({{ $menu->deskripsi }}) - Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach                    
                    </select>
                    
                    <div id="selected-menu" class="d-none mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img id="menu-image" src="" alt="Menu" width="60" height="60" class="rounded-3">
                                    </div>
                                    <div class="col">
                                        <h6 id="menu-name" class="mb-1"></h6>
                                        <p id="menu-price" class="text-muted mb-0"></p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="qty-minus">-</button>
                                            <input type="number" id="menu-qty" min="1" value="1" class="form-control form-control-sm text-center">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="qty-plus">+</button>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-success btn-sm" id="add-menu">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="fas fa-shopping-cart me-2"></i>Menu Dipilih</label>
                    <div id="selected-items" class="border rounded-3 p-3 bg-light">
                        <p class="text-muted text-center mb-0">Belum ada menu yang dipilih</p>
                    </div>
                </div>

                <div id="menu-inputs"></div>
                 
                <div class="mb-4">                     
                    <label class="form-label fw-bold"><i class="fas fa-credit-card me-2"></i>Jenis Pembayaran</label>                     
                    <select name="jenis_pembayaran" class="form-select" required>                         
                        <option value="cash">💰 Cash</option>                         
                        <option value="transfer">🏦 Transfer</option>                     
                    </select>                 
                </div>   

                <div class="mb-4">                     
                    <label class="form-label fw-bold"><i class="fas fa-credit-card me-2"></i>Status Pembayaran</label>                     
                    <select name="status_pembayaran" class="form-select" required>                         
                        <option value="pending">Pending</option>                         
                        <option value="sukses">Sukses</option>                     
                    </select>                 
                </div>                  

                <div class="mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label mb-1"><i class="fas fa-shopping-cart me-2"></i>Subtotal</label>
                                </div>
                                <div class="col-6 text-end">
                                    <input type="text" id="subtotal_display" class="form-control-plaintext text-end fw-bold" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label mb-1"><i class="fas fa-percentage me-2"></i>Pajak (11%)</label>
                                </div>
                                <div class="col-6 text-end">
                                    <input type="text" id="pajak_display" class="form-control-plaintext text-end fw-bold" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mb-1 fs-5"><i class="fas fa-calculator me-2"></i>Total Bayar</label>
                                </div>
                                <div class="col-6 text-end">
                                    <input type="text" id="manual_total" class="form-control-plaintext text-end fw-bold fs-5 text-primary" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">                     
                            <label class="form-label fw-bold"><i class="fas fa-money-bill me-2"></i>Jumlah Pembayaran</label>                     
                            <input type="number" id="uang_bayar" class="form-control" placeholder="Masukkan jumlah uang">                 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">                     
                            <label class="form-label fw-bold"><i class="fas fa-hand-holding-usd me-2"></i>Kembalian</label>                     
                            <input type="text" id="kembalian" class="form-control bg-light" readonly>                 
                        </div>
                    </div>
                </div>
            </div>             
            <div class="modal-footer bg-light">                 
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Order
                </button>             
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
                <h5 class="modal-title">Edit Status Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_status">Status</label>
                    <select name="status" id="edit_status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="edit_payment">Jenis Pembayaran</label>
                    <select name="jenis_pembayaran" id="edit_payment" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="edit_bayar">Status Pembayaran</label>
                    <select name="status_pembayaran" id="edit_bayar" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="sukses">Sukses</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Kode Pesanan:</strong> <span id="detail_kode"></span></p>
                <p><strong>Nomor Meja:</strong> <span id="detail_meja"></span></p>
                <p><strong>Status:</strong> <span id="detail_status"></span></p>
                <p><strong>Subtotal:</strong> Rp <span id="detail_subtotal"></span></p>
                <p><strong>Pajak (11%):</strong> Rp <span id="detail_pajak"></span></p>
                <p><strong>Total Bayar:</strong> <strong>Rp <span id="detail_total"></span></strong></p>
                <p><strong>Pembayaran:</strong> <span id="detail_pembayaran"></span></p>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detail_items">
                        <!-- akan diisi lewat JS -->
                    </tbody>
                </table>
            </div>
        </div>
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

    function openEditModal(id, status, payment, bayar = 'pending') {
        const form = document.getElementById('editForm');
        form.action = `/kasir/orderan/${id}`;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_payment').value = payment;
        document.getElementById('edit_bayar').value = bayar;

        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }


    function confirmDelete(id, kode) {
        Swal.fire({
            title: 'Hapus Order?',
            text: `Yakin ingin menghapus order ${kode}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

   function showDetail(order, items) {
        document.getElementById('detail_kode').textContent = order.kode_pesanan;
        document.getElementById('detail_meja').textContent = order.meja?.nomor_meja || '-';
        document.getElementById('detail_status').textContent = order.status;

        const tbody = document.getElementById('detail_items');
        tbody.innerHTML = '';

        let subtotal = 0;

        items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.menu?.nama || '-'}</td>
                <td>${item.qty}</td>
                <td>Rp ${item.subtotal.toLocaleString()}</td>
            `;
            tbody.appendChild(tr);
            subtotal += item.subtotal;
        });

        const pajak = Math.round(subtotal * 0.11);
        const total = subtotal + pajak;

        document.getElementById('detail_subtotal').textContent = subtotal.toLocaleString();
        document.getElementById('detail_pajak').textContent = pajak.toLocaleString();
        document.getElementById('detail_total').textContent = total.toLocaleString();
        document.getElementById('detail_pembayaran').textContent = order.jenis_pembayaran;

        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }
      const hargaMenus = @json($menus->pluck('harga', 'id'));

    document.querySelectorAll('#menu-container input[type="number"]').forEach(input => {
        input.addEventListener('input', hitungTotal);
    });

    // document.getElementById('uang_bayar').addEventListener('input', hitungKembalian);

    // function hitungTotal() {
    //     let total = 0;

    //     document.querySelectorAll('#menu-container .d-flex').forEach(row => {
    //         const id = row.querySelector('input[type="hidden"]').value;
    //         const qty = parseInt(row.querySelector('input[type="number"]').value) || 0;
    //         total += qty * hargaMenus[id];
    //     });

    //     const pajak = Math.round(total * 0.11);
    //     const grandTotal = total + pajak;

    //     document.getElementById('manual_total').value = `Rp ${grandTotal.toLocaleString()}`;
    //     document.getElementById('manual_total').dataset.total = grandTotal;

    //     hitungKembalian();
    // }

    // function hitungKembalian() {
    //     const bayar = parseInt(document.getElementById('uang_bayar').value) || 0;
    //     const total = parseInt(document.getElementById('manual_total').dataset.total || 0);
    //     const kembali = bayar - total;
    //     document.getElementById('kembalian').value = kembali >= 0 ? `Rp ${kembali.toLocaleString()}` : 'Rp 0';
    // }

    // function openCreateModal() {
    //     document.querySelectorAll('#menu-container input[type="number"]').forEach(i => i.value = '');
    //     document.getElementById('uang_bayar').value = '';
    //     document.getElementById('kembalian').value = '';
    //     document.getElementById('manual_total').value = '';
    //     document.getElementById('manual_total').dataset.total = 0;

    //     new bootstrap.Modal(document.getElementById('createModal')).show();
    // }

    //baru
    document.addEventListener('DOMContentLoaded', function() {
        const menuSelect = document.getElementById('menu-select');
        const selectedMenu = document.getElementById('selected-menu');
        const menuImage = document.getElementById('menu-image');
        const menuName = document.getElementById('menu-name');
        const menuPrice = document.getElementById('menu-price');
        const menuQty = document.getElementById('menu-qty');
        const qtyMinus = document.getElementById('qty-minus');
        const qtyPlus = document.getElementById('qty-plus');
        const addMenuBtn = document.getElementById('add-menu');
        const selectedItems = document.getElementById('selected-items');
        const menuInputs = document.getElementById('menu-inputs');
        const totalInput = document.getElementById('manual_total');
        const uangBayar = document.getElementById('uang_bayar');
        const kembalian = document.getElementById('kembalian');
        
        let selectedMenusData = [];
        let menuCounter = 0;

        // Show selected menu details
        menuSelect.addEventListener('change', function() {
            if (this.value) {
                const option = this.options[this.selectedIndex];
                menuImage.src = option.dataset.gambar;
                menuName.textContent = option.dataset.nama;
                menuPrice.textContent = 'Rp ' + parseInt(option.dataset.harga).toLocaleString('id-ID');
                menuQty.value = 1;
                selectedMenu.classList.remove('d-none');
            } else {
                selectedMenu.classList.add('d-none');
            }
        });

        // Quantity controls
        qtyMinus.addEventListener('click', function() {
            if (menuQty.value > 1) {
                menuQty.value = parseInt(menuQty.value) - 1;
            }
        });

        qtyPlus.addEventListener('click', function() {
            menuQty.value = parseInt(menuQty.value) + 1;
        });

        // Add menu to order
        addMenuBtn.addEventListener('click', function() {
            const selectedOption = menuSelect.options[menuSelect.selectedIndex];
            const menuId = selectedOption.value;
            const nama = selectedOption.dataset.nama;
            const harga = parseInt(selectedOption.dataset.harga);
            const qty = parseInt(menuQty.value);
            const subtotal = harga * qty;

            // Check if menu already exists
            const existingIndex = selectedMenusData.findIndex(item => item.id === menuId);
            
            if (existingIndex !== -1) {
                // Update existing menu
                selectedMenusData[existingIndex].qty += qty;
                selectedMenusData[existingIndex].subtotal = selectedMenusData[existingIndex].harga * selectedMenusData[existingIndex].qty;
            } else {
                // Add new menu
                selectedMenusData.push({
                    id: menuId,
                    nama: nama,
                    harga: harga,
                    qty: qty,
                    subtotal: subtotal
                });
            }

            updateSelectedItems();
            updateTotal();
            
            // Reset form
            menuSelect.value = '';
            selectedMenu.classList.add('d-none');
        });

        function updateSelectedItems() {
            if (selectedMenusData.length === 0) {
                selectedItems.innerHTML = '<p class="text-muted text-center mb-0">Belum ada menu yang dipilih</p>';
                menuInputs.innerHTML = '';
                return;
            }

            let itemsHtml = '';
            let inputsHtml = '';
            
            selectedMenusData.forEach((item, index) => {
                itemsHtml += `
                    <div class="d-flex justify-content-between align-items-center mb-2 ${index > 0 ? 'border-top pt-2' : ''}">
                        <div>
                            <strong>${item.nama}</strong><br>
                            <small class="text-muted">${item.qty} x Rp ${item.harga.toLocaleString('id-ID')}</small>
                        </div>
                        <div class="text-end">
                            <strong>Rp ${item.subtotal.toLocaleString('id-ID')}</strong><br>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                inputsHtml += `
                    <input type="hidden" name="menus[${index}][id]" value="${item.id}">
                    <input type="hidden" name="menus[${index}][qty]" value="${item.qty}">
                `;
            });

            selectedItems.innerHTML = itemsHtml;
            menuInputs.innerHTML = inputsHtml;
        }

        function updateTotal() {
            const subtotal = selectedMenusData.reduce((sum, item) => sum + item.subtotal, 0);
            const pajak = Math.round(subtotal * 0.11); // Pajak 11%
            const total = subtotal + pajak;
            
            document.getElementById('subtotal_display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('pajak_display').value = 'Rp ' + pajak.toLocaleString('id-ID');
            totalInput.value = 'Rp ' + total.toLocaleString('id-ID');
            calculateKembalian();
        }

        function calculateKembalian() {
            const subtotal = selectedMenusData.reduce((sum, item) => sum + item.subtotal, 0);
            const pajak = Math.round(subtotal * 0.11);
            const total = subtotal + pajak;
            const bayar = parseInt(uangBayar.value) || 0;
            const kembali = bayar - total;
            
            if (kembali >= 0) {
                kembalian.value = 'Rp ' + kembali.toLocaleString('id-ID');
                kembalian.classList.remove('text-danger');
                kembalian.classList.add('text-success');
            } else {
                kembalian.value = 'Kurang Rp ' + Math.abs(kembali).toLocaleString('id-ID');
                kembalian.classList.remove('text-success');
                kembalian.classList.add('text-danger');
            }
        }

        // Global function to remove item
        window.removeItem = function(index) {
            selectedMenusData.splice(index, 1);
            updateSelectedItems();
            updateTotal();
        };

        // Calculate kembalian when payment amount changes
        uangBayar.addEventListener('input', calculateKembalian);
    });
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
    <script>
        setInterval(function () {
            location.reload();
        }, 5000); // 5000 ms = 5 detik
    </script>
@endpush
