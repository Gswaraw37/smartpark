@extends('admin.layouts.app')
@section('content')
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Pegawai</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between">
                    <div class="col-4">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Pegawai</h6>
                    </div>
                    @if (Auth::user()->role == 'admin')
                        <div class="col-4">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                + Tambah
                            </button>
                        </div>
                    @endif
                  </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($staff as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $s->username }}</td>
                                    <td>{{ $s->role }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                            <a href="{{ url('/admin/daftar-staff/' . $s->id)  }}" class="btn btn-sm btn-success mr-1"><i class="fa-solid fa-eye"></i></a>
                                            @if (Auth::user()->role == 'admin')
                                                <form action="{{ url('/admin/daftar-staff/' . $s->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmHapusModal">Delete</button>
                                                </form>
                                            @endif
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
            <form abframeid="iframe.0.621681743693705" abineguid="6CBCF04EEF434DF783A5C40E835D891D" action="{{ url('/admin/daftar-staff/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="username" class="col-4 col-form-label">Username</label> 
                    <div class="col-8">
                    <input id="username" name="username" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="full_name" class="col-4 col-form-label">Full Name</label> 
                    <div class="col-8">
                    <input id="full_name" name="full_name" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-4 col-form-label">Email</label> 
                    <div class="col-8">
                    <input id="email" name="email" type="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jk" class="col-4 col-form-label">Jenis Kelamin</label> 
                    <div class="col-8">
                        @foreach ($gender as $g)
                        @php
                            $cek = (old('g') == $g) ? 'checked' : '';
                        @endphp
                            <div class="custom-control custom-radio custom-control-inline">
                                <input name="jk" id="jk{{ $g }}" type="radio" class="custom-control-input" value="{{ $g }}" {{ $cek }} required> 
                                <label for="jk{{ $g }}" class="custom-control-label">{{ $g }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bio" class="col-4 col-form-label">Bio</label> 
                    <div class="col-8">
                    <textarea id="bio" name="bio" cols="40" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="role" class="col-4 col-form-label">Position</label> 
                    <div class="col-8">
                    <select id="role" name="role" class="custom-select" required>
                        @foreach ($role as $r)
                            <option value="{{ $r }}">{{ $r }}</option> 
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="foto" class="col-4 col-form-label">Foto</label> 
                    <div class="col-8">
                        <input id="foto" name="foto" type="file" class="form-control">
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

  <div class="modal" id="confirmHapusModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body modal-bg1 text-center">
                <h5>Anda Yakin Ingin Menghapus Data Staff ini?</h5>
            </div>
            <div class="modalFooter text-center modal-bg1">
                <div class="mb-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection