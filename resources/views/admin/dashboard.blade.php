@extends('admin.layouts.app')
@section('content')
<div class="container text-center">
    <div class="row justify-content-around mb-3">
        <div class="col-4 col-lg-6 col-md-6">
            <div class="card card-stat">
                <div class="card-body px-4 py-4-5">
                    <h3 class="ps-2">Total Pegawai</h3>
                    <div class="d-flex align-items-start flex-column p-2 mb-2">
                        <p class="fs-1 p-3 rounded fw-bolder text-primary">{{ $employees }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 col-lg-6 col-md-6">
            <div class="card card-stat">
                <div class="card-body px-4 py-4-5">
                    <h3 class="ps-2">Total Pengunjung</h3>
                    <div class="d-flex align-items-start flex-column p-2 mb-2">
                        <p class="fs-1 p-3 rounded fw-bolder text-primary">{{ $visitors }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemilik</th>
                        <th>Plat Nomor</th>
                        <th>Jenis Kendaraan</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemilik</th>
                        <th>Plat Nomor</th>
                        <th>Jenis Kendaraan</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($parkingSession as $ps)
                        @php
                            $paymentStatus = $ps->payment ? $ps->payment->payment_status : 'Belum Dibayar';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ps->user->username }}</td>
                            <td>{{ $ps->vehicle->licence_plate }}</td>
                            <td>{{ $ps->vehicle->vehicleType->name }}</td>
                            <td>Lantai: {{ $ps->blocknumber->parkingfloor->floor_number }}, {{ $ps->blocknumber->block }}</td>
                            <td>{{ $paymentStatus === 'Paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}</td>
                            <td>
                                <div class="btn-group me-2" role="group" aria-label="Basic mixed styles example">
                                    <a href="{{ route('detail.approval', $ps->id) }}" class="btn btn-sm btn-primary mr-2">Detail</a>
                                </div>
                                <div class="btn-group me-2" role="group" aria-label="Basic mixed styles example">
                                    <form onsubmit="return confirm('Apakah anda yakin ingin menerima permintaan?')" class="d-inline" action="{{ route('admin.parkingSession.accept', $ps->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success mr-2">Terima</button>
                                    </form>
                                </div>
                                <div class="btn-group me-2" role="group" aria-label="Basic mixed styles example">
                                    <form onsubmit="return confirm('Apakah anda yakin ingin menolak permintaan?')" class="d-inline" action="{{ route('admin.parkingSession.decline', $ps->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Tambah Pegawai</h5>
</div>
<div class="modal-body">
    <form abframeid="iframe.0.621681743693705" abineguid="6CBCF04EEF434DF783A5C40E835D891D" action="/admin/daftar-staff/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="username" class="col-4 col-form-label">Nama</label> 
            <div class="col-8">
            <input id="username" name="username" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="full_name" class="col-4 col-form-label">Full Name</label> 
            <div class="col-8">
            <input id="full_name" name="full_name" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="role_id" class="col-4 col-form-label">Position</label> 
            <div class="col-8">
            <select id="role_id" name="role_id" class="custom-select">
                {{-- @foreach ($role as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option> 
                @endforeach --}}
            </select>
            </div>
        </div> 
        <div class="form-group row justify-content-center">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>
@endsection