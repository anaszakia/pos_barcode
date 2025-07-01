@extends('kasir.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Selamat Datang {{ Auth::user()->name }} di Sistem Order !</h4>
                    <p>Ini adalah halaman Kasir !</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 5000,
                gravity: "top",
                position: "right",
                backgroundColor: "#4fbe87",
                stopOnFocus: true,
            }).showToast();
        </script>
    @endif
@endpush

{{-- @push('scripts')
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
@endpush --}}


