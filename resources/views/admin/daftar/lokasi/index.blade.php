@extends('admin.layouts.app')
@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Lokasi Parkir</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row justify-content-between">
            <div class="col-8">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Lokasi</h6>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lantai</th>
                        <th>Block</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Lantai</th>
                        <th>Block</th>
                        <th>Status</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($block as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->parkingfloor->floor_number }}</td>
                            <td>{{ $b->block }}</td>
                            <td>{{ $b->is_occupied == 0 ? 'Tersedia' : 'Tidak Tersedia' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection