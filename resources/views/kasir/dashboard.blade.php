@extends('kasir.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2 text-primary fw-bold" style="color: white;">
                                <i class="bi bi-sun text-warning me-2"></i>
                                Selamat Datang, {{ Auth::user()->name }}!
                            </h3>
                            <p class="mb-0 text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                            </p>
                            <p class="mb-0 text-muted small">
                                Kelola bisnis Anda dengan mudah melalui dashboard ini
                            </p>
                        </div>
                        <div class="welcome-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="section-title mb-3">
                <i class="bi bi-bar-chart-line me-2"></i>
                Statistik Bisnis
            </h5>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Total Order Card -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon purple me-3">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="stats-label mb-1">Total Order Hari Ini</p>
                            <h4 class="stats-value mb-0">{{ number_format($totalOrderHariIni) }}</h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +12% dari bulan lalu
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Reservasi Card -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon blue me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="stats-label mb-1">Total Reservasi Hari Ini</p>
                            <h4 class="stats-value mb-0">{{ number_format($totalReservasiHariIni) }}</h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +8% dari bulan lalu
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- <!-- Omzet Order Card -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="stats-label mb-1">Omzet Order</p>
                            <h4 class="stats-value mb-0">Rp {{ number_format($omzetOrder,0,',','.') }}</h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +15% dari bulan lalu
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
        {{-- <!-- Omzet Reservasi Card -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon orange me-3">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="stats-label mb-1">Omzet Reservasi</p>
                            <h4 class="stats-value mb-0">Rp {{ number_format($omzetReservasi,0,',','.') }}</h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +10% dari bulan lalu
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         --}}
        {{-- <!-- Total Omzet Card -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="stats-card highlighted">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon red me-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="stats-label mb-1">Total Omzet</p>
                            <h4 class="stats-value mb-0">Rp {{ number_format($totalOmzet,0,',','.') }}</h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +13% dari bulan lalu
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
        {{-- <!-- Total Pelanggan Card -->
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon info me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="stats-label mb-1">Total Pelanggan</p>
                            <h4 class="stats-value mb-0">{{ $totalPelanggan ?? 0 }}</h4>
                            <small class="text-muted">
                                <i class="bi bi-person-plus"></i> Pelanggan aktif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Quick Actions Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="section-title mb-3">
                <i class="bi bi-lightning me-2"></i>
                Aksi Cepat
            </h5>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="quick-action-card">
                <div class="card-body text-center p-4">
                    <div class="quick-action-icon purple mb-3">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h6 class="mb-2">Tambah Order</h6>
                    <p class="text-muted small mb-3">Buat order baru untuk pelanggan</p>
                    <a href="{{ route('kasir.orderan.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus me-1"></i> Buat Order
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="quick-action-card">
                <div class="card-body text-center p-4">
                    <div class="quick-action-icon blue mb-3">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <h6 class="mb-2">Tambah Reservasi</h6>
                    <p class="text-muted small mb-3">Buat reservasi baru</p>
                    <a href="{{ route('kasir.reservasi.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-plus me-1"></i> Buat Reservasi
                    </a>
                </div>
            </div>
        </div>

        {{-- <div class="col-12 col-md-6 col-lg-3">
            <div class="quick-action-card">
                <div class="card-body text-center p-4">
                    <div class="quick-action-icon green mb-3">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h6 class="mb-2">Laporan</h6>
                    <p class="text-muted small mb-3">Lihat laporan bisnis</p>
                    <a href="{{ route('admin.riwayat.index') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-graph-up me-1"></i> Lihat Laporan
                    </a>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-12 col-md-6 col-lg-3">
            <div class="quick-action-card">
                <div class="card-body text-center p-4">
                    <div class="quick-action-icon orange mb-3">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h6 class="mb-2">Pengaturan</h6>
                    <p class="text-muted small mb-3">Kelola pengaturan sistem</p>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-gear me-1"></i> Pengaturan
                    </a>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <div class="col-12">
            <h5 class="section-title mb-3">
                <i class="bi bi-clock-history me-2"></i>
                Aktivitas Terbaru
            </h5>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card activity-card">
                <div class="card-body p-4">
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="activity-content">
                            <h6 class="mb-1">Order #001 telah selesai</h6>
                            <p class="text-muted small mb-0">2 jam yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="activity-content">
                            <h6 class="mb-1">Reservasi baru ditambahkan</h6>
                            <p class="text-muted small mb-0">3 jam yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <h6 class="mb-1">Stok produk menipis</h6>
                            <p class="text-muted small mb-0">5 jam yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i> Lihat Semua Aktivitas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            border: none;
        }
        
        .welcome-icon {
            font-size: 3rem;
            opacity: 0.3;
        }

        /* Section Titles */
        .section-title {
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stats-card.highlighted {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .stats-card.highlighted .stats-label,
        .stats-card.highlighted .stats-value {
            color: white;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .stats-icon.purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-icon.blue { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); }
        .stats-icon.green { background: linear-gradient(135deg, #55efc4 0%, #00b894 100%); }
        .stats-icon.orange { background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); }
        .stats-icon.red { background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%); }
        .stats-icon.info { background: linear-gradient(135deg, #81ecec 0%, #00cec9 100%); }

        .stats-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0;
            font-weight: 500;
        }

        .stats-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3436;
        }

        /* Quick Action Cards */
        .quick-action-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .quick-action-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto;
        }

        .quick-action-icon.purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .quick-action-icon.blue { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); }
        .quick-action-icon.green { background: linear-gradient(135deg, #55efc4 0%, #00b894 100%); }
        .quick-action-icon.orange { background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); }

        /* Activity Cards */
        .activity-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .activity-icon.success { background-color: #00b894; }
        .activity-icon.info { background-color: #0984e3; }
        .activity-icon.warning { background-color: #fdcb6e; color: #2d3436; }

        .activity-content h6 {
            margin-bottom: 4px;
            font-weight: 600;
            color: #2d3436;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .welcome-icon {
                display: none;
            }
            
            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .stats-value {
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card,
        .quick-action-card,
        .activity-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Button Improvements */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('scripts')
    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 5000,
                gravity: "top",
                position: "right",
                backgroundColor: "#00b894",
                stopOnFocus: true,
                style: {
                    borderRadius: "10px",
                }
            }).showToast();
        </script>
    @endif

    <script>
        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading effect for stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Add click effect for quick action cards
            const quickActionCards = document.querySelectorAll('.quick-action-card');
            quickActionCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('a')) {
                        const link = this.querySelector('a');
                        if (link) {
                            window.location.href = link.href;
                        }
                    }
                });
            });
        });
    </script>
@endpush