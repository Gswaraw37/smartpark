@extends('admin.layouts.app')
@section('content')

<section class="about_section layout_padding2-top layout_padding-bottom ">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9 px-0">
          <div class="img-box">
            <img src="{{ asset('pengunjung/images/about-img.png') }}" alt="">
          </div>
        </div>
        <div class="col-md-4 col-lg-3">
          <div class="detail-box">
            <h2>
              About Vehicles
            </h2>
            <h5>
                <b>Nama Pemilik:</b> {{ $parkingSession->user->username }}
            </h5>
            <h5>
                <b>Jenis Kendaraan:</b> {{ $parkingSession->vehicle->vehicleType->name }}
            </h5>
            <h5>
                <b>Plat Nomor:</b> {{ $parkingSession->vehicle->licence_plate }}
            </h5>
            <h5>
                <b>Lokasi Parkir:</b> Lantai {{ $parkingSession->blocknumber->parkingfloor->floor_number }} | {{ $parkingSession->blocknumber->block }} 
            </h5>
            <h5>
                <b>Jam Masuk Parkir:</b> {{ \Carbon\Carbon::parse($approval->entry_time)->format('H:i:s') }} | {{ \Carbon\Carbon::parse($approval->entry_time)->diffForHumans() }}
            </h5>
            <h5>
                @php
                    $paymentStatus = $parkingSession->payment ? $parkingSession->payment->payment_status : 'Belum Dibayar';
                @endphp
                <b>Status Pembayaran:</b> {{ $paymentStatus === 'Paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
            </h5>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection