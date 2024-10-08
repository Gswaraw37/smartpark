@extends('pengunjung.layouts.app')
@section('content')

<!-- book section -->
<section class="book_section">
  <div class="form_container">
      @if (auth()->user()->vehicle->isEmpty())
          <form action="" class="text-center">
              <p>Harap Tambahkan Data Kendaraan Terlebih Dahulu.</p>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vehicleModal">+ Tambah Kendaraan</button>
          </form>
      @else
          @if($approvalStatus === 'Pending')
          <div class="form_container">
            <form action="" class="text-center">
              <div class="container">
                <div class="row justify-content-center">
                  <div class="col-md-8">
                    <div class="detail_container">
                        <div class="detail-box text-dark text-start">
                            <h5>Nama Pengunjung: {{ Auth::user()->username }}</h5><br>
                            <h5>Jenis Kendaraan: {{ $vehicles->first()->vehicleType->name }}</h5><br>
                            <h5>Plat Nomor: {{ $vehicles->first()->licence_plate }}</h5><br>
                            <h5>Lokasi Parkir: Lantai {{ $blocknumber->floor_id }} | {{ $blocknumber->block }}</h5><br>
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
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <p>Sedang menunggu persetujuan...</p>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          @elseif($approvalStatus === 'Rejected')
          <div class="form_container">
            <form action="" class="text-center">
              <div class="container">
                <div class="row justify-content-center">
                  <div class="col-md-8">
                    <div class="detail_container">
                        <div class="detail-box text-dark text-start">
                            <h5>Nama Pengunjung: {{ Auth::user()->username }}</h5><br>
                            <h5>Jenis Kendaraan: {{ $vehicles->first()->vehicleType->name }}</h5><br>
                            <h5>Plat Nomor: {{ $vehicles->first()->licence_plate }}</h5><br>
                            <h5>Lokasi Parkir: Lantai {{ $blocknumber->floor_id }} | {{ $blocknumber->block }}</h5><br>
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
                    <p>Harap Selesaikan Pembayaran Terlebih Dahulu.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exitModal">
                        Bayar Parkir
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          @else
              <div class="container">
                  <div class="row justify-content-center">
                      <div class="col-md-4 mb-4">
                          <div class="card">
                              <img src="https://dummyimage.com/600x400/000/fff&text=Parking" class="card-img-top" alt="...">
                              <div class="card-body text-center">
                                  @if ($blocknumber)
                                      <h3 class="card-title">Lantai {{ $blocknumber->parkingfloor->floor_number }}</h3>
                                      <h5 class="card-title">{{ $blocknumber->block }}</h5>
                                      @if ($approval && $approval->action === 'Entry')
                                          <a href="{{ url('/parkir') }}" class="btn btn-primary">Kembali</a>
                                      @else
                                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#parkModal">Masuk Parkir</button>
                                      @endif
                                  @else
                                      <h3 class="card-title">Tidak ada blok tersedia</h3>
                                  @endif
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          @endif
      @endif
  </div>
  <div class="img-box">
      <img src="{{ asset('pengunjung/images/book-car.png') }}" alt="">
  </div>
</section>
<!-- end book section -->

  <!-- Modal -->
  <div class="modal fade" id="vehicleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Kendaraan</h5>
        </div>
        <div class="modal-body">
            <form abframeid="iframe.0.621681743693705" abineguid="6CBCF04EEF434DF783A5C40E835D891D" action="/" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="licence_plate" class="col-4 col-form-label">Plat Nomor</label> 
                    <div class="col-8">
                    <input id="licence_plate" name="licence_plate" type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_type_id" class="col-4 col-form-label">Tipe Kendaraan</label> 
                    <div class="col-8">
                      @foreach ($vehicletype as $vt)
                      @php
                          $cek = (old('vehicle_type_id') == $vt->id) ? 'checked' : '';
                      @endphp
                        <div class="custom-control custom-radio custom-control-inline">
                          <input name="vehicle_type_id" id="vehicle_type_id{{ $vt->id }}" type="radio" class="custom-control-input" value="{{ $vt->id }}" {{ $cek }}> 
                          <label for="vehicle_type_id{{ $vt->id }}" class="custom-control-label">{{ $vt->name }}</label>
                        </div>
                      @endforeach
                    </div>
                </div>
                </div> 
                <div class="form-group row justify-content-center">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="parkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Masuk Parkir</h5>
        </div>
        <div class="modal-body">
            <form abframeid="iframe.0.621681743693705" abineguid="6CBCF04EEF434DF783A5C40E835D891D" action="{{ url('/parkIn') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="block_id" value="{{ $blocknumber->id }}">
                <div class="form-group row">
                    <label for="vehicle_id" class="col-4 col-form-label">Pilih Kendaraan</label> 
                    <div class="col-8">
                      <select id="vehicle_id" name="vehicle_id" class="custom-select" required>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->vehicleType->name }} {{ $v->licence_plate }}</option> 
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Parkir</button>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Keluar Parkir</h5>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin keluar dari tempat parkir?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('parkir.resubmit') }}" method="POST">
                    @csrf
                    @foreach($vehicles as $veh)
                        <input type="hidden" name="vehicle_id" value="{{ $veh->id }}">
                    @endforeach
                    <button type="submit" class="btn btn-primary">Bayar Parkir</button>
                </form>
            </div>
        </div>
    </div>
  </div>

@endsection