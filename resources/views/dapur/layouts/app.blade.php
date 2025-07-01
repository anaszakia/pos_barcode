<!DOCTYPE html>
<html lang="en">
<head>
    @include('dapur.partials.header')
    <style>
        /* Fix gap antara sidebar dan content */
        #main {
            margin-left: 150px !important;
            padding-top: 5px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
        
        /* Responsive untuk mobile */
        @media (max-width: 1199.98px) {
            #main {
                margin-left: 0 !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
        }
        
        .page-content {
            padding: 0 !important;
        }
        
        #main-content {
            padding: 0 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        {{-- Sidebar --}}
        @include('dapur.partials.sidebar')
        
        {{-- Main Content --}}
        <div id="main">
            {{-- Header/Topbar --}}
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
            {{-- Page Content --}}
            <div id="main-content">
                {{-- Page Heading --}}
                <div class="page-heading">
                    <h3>@yield('page-title', 'Dashboard')</h3>
                </div>
                
                {{-- Page Content --}}
                <div class="page-content">
                    @yield('content')
                </div>
            </div>
            
            {{-- Footer --}}
            @include('dapur.partials.footer')
        </div>
    </div>
    
    {{-- Scripts --}}
    @include('dapur.partials.script')
    <script>
    document.getElementById('logout-button').addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda akan keluar dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
</body>
</html>