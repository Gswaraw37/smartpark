@extends('pengunjung.layouts.app')
@section('content')

<section class="book_section">
    <div class="form_container">
        <form action="" class="text-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        @if ($approval->action === 'Entry' || $approval->action === 'Pending')
                            <div class="detail_container">
                                <div class="detail-box text-dark text-start">
                                    <h5>Nama Pengunjung: {{ Auth::user()->username }}</h5><br>
                                    <h5>Jenis Kendaraan: {{ $vehicle->vehicleType->name }}</h5><br>
                                    <h5>Plat Nomor: {{ $vehicle->licence_plate }}</h5><br>
                                    <h5>Lokasi Parkir: Lantai {{ $block->floor_id }} | {{ $block->block }}</h5><br>
                                    <h5>
                                        Waktu Parkir: @if($approval->exit_time)
                                            {{ \Carbon\Carbon::parse($approval->exit_time)->diffForHumans($approval->entry_time) }}
                                        @else
                                            {{ \Carbon\Carbon::parse($approval->entry_time)->diffForHumans() }}
                                        @endif
                                    </h5><br>
                                    <h5>Keterangan: {{ $paymentStatus === 'Paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}</h5><br>
                                </div>
                            </div>
                        @endif
                        <p>Silahkan Bayar Terlebih Dahulu Sebelum Keluar Parkir</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            Bayar Parkir
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="img-box">
        <img src="{{ asset('pengunjung/images/book-car.png') }}" alt="">
    </div>
</section>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pembayaran Parkir</h5>
            </div>
            <div class="modal-body">
                <h5>Jenis Kendaraan: {{ $vehicle->vehicleType->name }}</h5><br>
                <h5>Plat Nomor: {{ $vehicle->licence_plate }}</h5><br>
                <h5>Total Biaya Parkir: Rp{{ number_format($amount, 0, ',', '.') }}</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                @if ($snapToken)
                    <button id="pay-button" class="btn btn-primary">Bayar Parkir</button>
                @else
                    <p>Token pembayaran tidak tersedia. Silakan coba lagi nanti.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($snapToken)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('pay-button').onclick = function () {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        alert('Pembayaran berhasil');
                        window.location.href = '/';
                    },
                    onPending: function (result) {
                        alert('Pembayaran tertunda');
                        window.location.href = '/';
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal');
                        window.location.href = '/';
                    }
                });
            };
        });
    </script>
@endif

@endsection