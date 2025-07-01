<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Reservasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .reservation-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
        }
        
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .menu-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        
        .menu-name {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        .menu-price {
            color: #28a745;
            font-weight: 600;
        }
        
        .total-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border: 1px solid #dee2e6;
        }
        
        .total-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .total-label {
            font-weight: 500;
            color: #495057;
        }
        
        .total-value {
            font-weight: 600;
            color: #28a745;
            font-size: 1.1rem;
        }
        
        .dp-value {
            color: #fd7e14;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .alert-success {
            background-color: #d1edff;
            color: #0f5132;
            border-left: 4px solid #28a745;
        }
        
        .qty-input {
            max-width: 100px;
            margin-top: 0.5rem;
        }
        
        .icon {
            margin-right: 0.5rem;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .reservation-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .total-section {
                padding: 1rem;
            }
            
            .menu-item {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="reservation-container">
            <div class="text-center mb-4">
                <h2 class="section-title">
                    <i class="fas fa-calendar-check icon"></i>
                    Form Reservasi
                </h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('reservasi.store') }}" method="POST">
                @csrf
                
                <!-- Informasi Pribadi -->
                <div class="mb-4">
                    <h5 class="section-title">
                        <i class="fas fa-user icon"></i>
                        Informasi Pribadi
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user-tag icon"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fab fa-whatsapp icon"></i>
                                No HP (WhatsApp)
                            </label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" required>
                        </div>
                    </div>
                </div>

                <!-- Informasi Reservasi -->
                <div class="mb-4">
                    <h5 class="section-title">
                        <i class="fas fa-info-circle icon"></i>
                        Detail Reservasi
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users icon"></i>
                                Jumlah Orang
                            </label>
                            <input type="number" name="jumlah_orang" min="1" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt icon"></i>
                                Tanggal Reservasi
                            </label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-clock icon"></i>
                                Jam Reservasi
                            </label>
                            <input type="time" name="jam" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-sticky-note icon"></i>
                            Catatan Tambahan
                        </label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan catatan khusus jika ada..."></textarea>
                    </div>
                </div>

                <!-- Menu Selection -->
                <div class="mb-4">
                    <h5 class="section-title">
                        <i class="fas fa-utensils icon"></i>
                        Pilih Menu
                    </h5>
                    
                    @foreach ($menus as $menu)
                        <div class="menu-item">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div class="flex-grow-1">
                                    <div class="menu-name">{{ $menu->nama }}</div>
                                    <div class="menu-price">Rp {{ number_format($menu->harga) }}</div>
                                </div>
                                <div class="qty-input">
                                    <label class="form-label small">Qty</label>
                                    <input type="number" name="menus[{{ $menu->id }}]" min="0" 
                                           data-harga="{{ $menu->harga }}" 
                                           class="form-control menu-qty" 
                                           placeholder="0">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total Section -->
                <div class="total-section">
                    <h6 class="mb-3">
                        <i class="fas fa-calculator icon"></i>
                        Ringkasan Pesanan
                    </h6>

                    <div class="total-item">
                        <span class="total-label">Subtotal Harga:</span>
                        <span class="total-value">Rp <span id="subtotal">0</span></span>
                    </div>

                    <div class="total-item">
                        <span class="total-label">Pajak (11%):</span>
                        <span class="total-value">Rp <span id="pajak">0</span></span>
                    </div>

                    <div class="total-item">
                        <span class="total-label">Total Harga + Pajak:</span>
                        <span class="total-value">Rp <span id="total">0</span></span>
                    </div>

                    <div class="total-item">
                        <span class="total-label">DP 30%:</span>
                        <span class="total-value dp-value">Rp <span id="dp">0</span></span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-4">
                    <h5 class="section-title">
                        <i class="fas fa-credit-card icon"></i>
                        Metode Pembayaran DP
                    </h5>
                    
                    <select name="metode_dp" class="form-select" required>
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="transfer">💳 Transfer Bank</option>
                        <option value="kasir">🏪 Bayar di Kasir</option>
                    </select>
                </div>

                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Penting:</strong> Setelah pembayaran DP, silakan konfirmasi dan kirim bukti pembayaran ke WhatsApp kami untuk memastikan reservasi Anda.
                </div>

                <button type="submit" class="btn btn-primary btn-submit">
                    <i class="fas fa-paper-plane me-2"></i>
                    Kirim Reservasi
                </button>

   <script>
    const qtyInputs = document.querySelectorAll('.menu-qty');
    const subtotalSpan = document.getElementById('subtotal');
    const pajakSpan = document.getElementById('pajak');
    const totalSpan = document.getElementById('total');
    const dpSpan = document.getElementById('dp');

    qtyInputs.forEach(input => input.addEventListener('input', updateTotal));

    function updateTotal() {
        let subtotal = 0;
        qtyInputs.forEach(input => {
            const harga = parseInt(input.dataset.harga) || 0;
            const qty = parseInt(input.value) || 0;
            subtotal += harga * qty;
        });

        const pajak = Math.round(subtotal * 0.11);
        const total = subtotal + pajak;
        const dp = Math.round(total * 0.3);

        subtotalSpan.textContent = subtotal.toLocaleString('id-ID');
        pajakSpan.textContent = pajak.toLocaleString('id-ID');
        totalSpan.textContent = total.toLocaleString('id-ID');
        dpSpan.textContent = dp.toLocaleString('id-ID');
    }

    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="tanggal"]');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    });
</script>

</body>
</html>