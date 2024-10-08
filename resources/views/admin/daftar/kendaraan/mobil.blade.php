@extends('admin.layouts.app')
@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Kendaraan</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row justify-content-between">
                <div class="col-8">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Mobil</h6>
                </div>
                </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemilik</th>
                            <th>Plat Nomor</th>
                            <th>Jenis Kendaraan</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemilik</th>
                            <th>Plat Nomor</th>
                            <th>Jenis Kendaraan</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($vehicle as $v)
                            @if ($v->vehicleType->name == 'Mobil')
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $v->user->username }}</td>
                                    <td>{{ $v->licence_plate }}</td>
                                    <td>{{ $v->vehicleType->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection