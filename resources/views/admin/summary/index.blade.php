@extends('admin.layouts.app')
@section('content')

    @if(Auth::user()->role == 'admin')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Summary</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row justify-content-between">
                <div class="col-8">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Pengunjung</h6>
                </div>
                <div class="col-4">
                    <form action="{{ url('/admin/summary/export/excel') }}" method="GET">
                        <button type="submit" class="btn btn-primary font-weight-bold">Export Data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengunjung</th>
                            <th>Jenis Kendaraan</th>
                            <th>Plat Nomor</th>
                            <th>Jam Masuk Parkir</th>
                            <th>Jam Keluar Parkir</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengunjung</th>
                            <th>Jenis Kendaraan</th>
                            <th>Plat Nomor</th>
                            <th>Jam Masuk Parkir</th>
                            <th>Jam Keluar Parkir</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($approval as $a)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $a->vehicle->user->username }}</td>
                                <td>{{ $a->vehicle->vehicleType->name }}</td>
                                <td>{{ $a->vehicle->licence_plate }}</td>
                                <td>{{ $a->entry_time }}</td>
                                <td>{{ $a->exit_time }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

@endsection