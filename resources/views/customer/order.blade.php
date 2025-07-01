<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu - Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahkan CDN SweetAlert2 di bagian head -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#EF4444'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl md:text-2xl font-bold">🍽️ Menu Restaurant</h1>
                <div class="flex items-center space-x-2">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">Meja #{{ $meja->nomor_meja }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Filter Kategori -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
            <div class="p-4">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <span class="mr-2">🏷️</span>
                    Pilih Kategori
                </h3>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterMenu('all')" class="category-btn bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        Semua Menu
                    </button>
                    @foreach($kategoriMenus->keys() as $kategoriNama)
                        <button onclick="filterMenu('{{ strtolower(str_replace(' ', '-', $kategoriNama)) }}')" class="category-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                            {{ $kategoriNama }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Menu Section -->
            <div class="lg:w-2/3">
                <form id="orderForm" action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="meja_id" value="{{ $meja->id }}">
                    
                    <!-- Pilihan Jenis Pembayaran -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-4 flex items-center">
                                <span class="mr-2">💳</span>
                                Metode Pembayaran
                            </h3>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="jenis_pembayaran" value="cash" class="mr-2" checked>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">💵 Cash</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="jenis_pembayaran" value="transfer" class="mr-2">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">🏦 Transfer</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="space-y-6">
                        @foreach($kategoriMenus as $kategori => $menus)
                            <div class="menu-category bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-category="{{ strtolower(str_replace(' ', '-', $kategori)) }}">
                                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4">
                                    <h2 class="text-xl font-bold">{{ $kategori }}</h2>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                    @foreach($menus as $menu)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-200 hover:border-orange-300">
                                            <div class="aspect-square bg-gray-300 rounded-lg mb-3 overflow-hidden">
                                                @if($menu->image)
                                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->nama }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                        <span class="text-4xl">🍽️</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-1 text-sm md:text-base">{{ $menu->nama }}</h4>
                                            <p>{{ $menu->deskripsi }}</p>
                                            <p class="text-red-600 font-bold mb-3 text-lg">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center border border-gray-300 rounded-lg bg-white">
                                                    <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 decrease-btn transition-colors" data-menu-id="{{ $menu->id }}">-</button>
                                                    <input type="number" 
                                                           name="menus[{{ $menu->id }}][qty]" 
                                                           min="0" 
                                                           value="0" 
                                                           class="w-12 md:w-16 text-center border-0 focus:ring-0 qty-input text-sm md:text-base" 
                                                           data-price="{{ $menu->harga }}"
                                                           data-menu-id="{{ $menu->id }}">
                                                    <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 increase-btn transition-colors" data-menu-id="{{ $menu->id }}">+</button>
                                                </div>
                                                <input type="hidden" name="menus[{{ $menu->id }}][id]" value="{{ $menu->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 sticky top-24">
                    <div class="p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-t-xl">
                        <h3 class="font-bold text-lg flex items-center">
                            <span class="mr-2">🧾</span>
                            Ringkasan Pesanan
                        </h3>
                    </div>
                    
                    <div class="p-4">
                        <!-- Daftar Item Pesanan -->
                        <div id="order-items" class="mb-4 max-h-60 overflow-y-auto">
                            <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
                        </div>
                        
                        <hr class="border-gray-200 my-4">
                        
                        <!-- Summary Harga -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Pajak (11%):</span>
                                <span id="pajak" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg text-gray-900">Total:</span>
                                <span id="total" class="font-bold text-xl text-green-600">Rp 0</span>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                form="orderForm" 
                                id="submit-btn"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-6 rounded-lg font-bold text-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <span class="flex items-center justify-center">
                                <span class="mr-2">🚀</span>
                                Kirim Pesanan
                            </span>
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center mt-2">
                            Minimal 1 item untuk melakukan pemesanan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk menampilkan SweetAlert jika ada session success -->
@if(session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            // timer: 20000,
            timerProgressBar: true,
            showConfirmButton: true,
            allowOutsideClick: true
        });
    </script>
@endif

<!-- Script untuk menampilkan SweetAlert jika ada session error -->
@if(session('error'))
    <script>
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444',
            // timer: 20000,
            timerProgressBar: true,
            showConfirmButton: true,
            allowOutsideClick: true
        });
    </script>
@endif

    <script>
        // Format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Filter Menu berdasarkan kategori
        function filterMenu(category) {
            const menuCategories = document.querySelectorAll('.menu-category');
            const categoryBtns = document.querySelectorAll('.category-btn');
            
            // Reset button styles
            categoryBtns.forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            // Highlight active button
            event.target.classList.remove('bg-gray-200', 'text-gray-700');
            event.target.classList.add('bg-blue-500', 'text-white');
            
            if (category === 'all') {
                menuCategories.forEach(cat => cat.style.display = 'block');
            } else {
                menuCategories.forEach(cat => {
                    if (cat.dataset.category === category) {
                        cat.style.display = 'block';
                    } else {
                        cat.style.display = 'none';
                    }
                });
            }
        }

        // Update order items display
        function updateOrderItems() {
            const orderItemsContainer = document.getElementById('order-items');
            const qtyInputs = document.querySelectorAll('.qty-input');
            let hasItems = false;
            let itemsHtml = '';

            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    hasItems = true;
                    const menuCard = input.closest('.bg-gray-50');
                    const menuName = menuCard.querySelector('h4').textContent;
                    const price = parseInt(input.dataset.price);
                    const subtotal = price * qty;
                    
                    itemsHtml += `
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-sm">${menuName}</p>
                                <p class="text-xs text-gray-500">${qty} x Rp ${formatRupiah(price)}</p>
                            </div>
                            <p class="font-semibold text-green-600">Rp ${formatRupiah(subtotal)}</p>
                        </div>
                    `;
                }
            });

            if (hasItems) {
                orderItemsContainer.innerHTML = itemsHtml;
            } else {
                orderItemsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada pesanan</p>';
            }
        }

        // Hitung total
        function hitungTotal() {
            let subtotal = 0;
            const qtyInputs = document.querySelectorAll('.qty-input');

            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    const price = parseInt(input.dataset.price);
                    subtotal += price * qty;
                }
            });

            const pajak = Math.round(subtotal * 0.11);
            const total = subtotal + pajak;

            document.getElementById('subtotal').textContent = 'Rp ' + formatRupiah(subtotal);
            document.getElementById('pajak').textContent = 'Rp ' + formatRupiah(pajak);
            document.getElementById('total').textContent = 'Rp ' + formatRupiah(total);

            // Enable/disable submit button
            const submitBtn = document.getElementById('submit-btn');
            if (subtotal > 0) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }

            updateOrderItems();
        }

        // Event listeners untuk tombol + dan -
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('increase-btn')) {
                const menuId = e.target.dataset.menuId;
                const input = document.querySelector(`input[data-menu-id="${menuId}"]`);
                input.value = parseInt(input.value || 0) + 1;
                hitungTotal();
            }
            
            if (e.target.classList.contains('decrease-btn')) {
                const menuId = e.target.dataset.menuId;
                const input = document.querySelector(`input[data-menu-id="${menuId}"]`);
                const currentValue = parseInt(input.value || 0);
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    hitungTotal();
                }
            }
        });

        // Event listener untuk input langsung
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input')) {
                hitungTotal();
            }
        });

        // Form validation
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const qtyInputs = document.querySelectorAll('.qty-input');
            let hasValidItems = false;
            
            // Filter input yang memiliki nilai > 0
            const validInputs = [];
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    hasValidItems = true;
                    validInputs.push(input);
                }
            });
            
            if (!hasValidItems) {
                e.preventDefault();
                alert('Pilih minimal 1 menu untuk melakukan pemesanan!');
                return;
            }
            
            // Hapus input yang qty-nya 0 agar tidak dikirim
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty === 0) {
                    input.disabled = true;
                    input.closest('.bg-gray-50').querySelector('input[type="hidden"]').disabled = true;
                }
            });
        });

        // Initial calculation
        hitungTotal();
    </script>
</body>
</html>