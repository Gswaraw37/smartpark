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
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exitModal">
                    Keluar Parkir
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

<div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exitModalLabel">Konfirmasi Keluar Parkir</h5>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin keluar dari tempat parkir?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <form action="{{ url('/parkOut') }}" method="POST">
          @csrf
          <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
          <button type="submit" class="btn btn-danger">Keluar Parkir</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection