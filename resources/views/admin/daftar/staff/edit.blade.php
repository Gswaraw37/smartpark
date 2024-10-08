@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<h1 align="center">Edit {{ $staff->username }}</h1>
<form abframeid="iframe.0.9274323699700593" abineguid="6CBCF04EEF434DF783A5C40E835D891D" action="/admin/daftar-staff/{{ $staff->id }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group row">
    <label for="username" class="col-4 col-form-label">Username</label> 
    <div class="col-8">
        <input id="username" name="username" type="text" class="form-control" value="{{ $staff->username }}" readonly>
    </div>
    </div>
    <div class="form-group row">
    <label for="email" class="col-4 col-form-label">Email</label> 
    <div class="col-8">
        <input id="email" name="email" type="email" class="form-control" value="{{ $staff->email }}" readonly>
    </div>
    </div>
    <div class="form-group row">
    <label for="password" class="col-4 col-form-label">Password</label> 
    <div class="col-8">
        <input id="password" name="password" type="password" class="form-control" placeholder="Masukan Password Baru">
    </div>
    </div>
    <div class="form-group row">
    <label for="full_name" class="col-4 col-form-label">Nama Lengkap</label> 
    <div class="col-8">
        <input id="full_name" name="full_name" type="text" class="form-control" value="{{ $staff->full_name }}">
    </div>
    </div>
    <div class="form-group row">
    <label for="jk" class="col-4 col-form-label">Jenis Kelamin</label>
    <div class="col-8">
        @foreach ($gender as $g)
        @php
            $cek = ($g == $staff->jk) ? 'checked' : '';
        @endphp
            <div class="custom-control custom-radio custom-control-inline">
                <input name="jk" id="jk{{ $g }}" type="radio" class="custom-control-input" value="{{ $g }}" {{ $cek }}> 
                <label for="jk{{ $g }}" class="custom-control-label">{{ $g }}</label>
            </div>
        @endforeach
    </div>
    </div>
    <div class="form-group row">
    <label for="bio" class="col-4 col-form-label">Bio</label> 
    <div class="col-8">
        <textarea id="bio" name="bio" cols="40" rows="5" class="form-control">{{ $staff->bio }}</textarea>
    </div>
    </div>
    <div class="form-group row">
        <label for="foto" class="col-4 col-form-label">Foto</label> 
        <div class="col-8">
            @if(!empty($staff->foto))
                <div class="mb-3">
                    <img id="preview" src="{{ asset('storage/' . $staff->foto) }}" alt="Product Image" class="img-fluid" style="max-width: 200px;">
                </div>
                <p>Current file: {{ $staff->foto }}</p>
            @else
                <div class="mb-3">
                    <img id="preview" src="{{ asset('storage/nophoto.jpg') }}" alt="Placeholder Image" class="img-fluid" style="max-width: 200px;">
                </div>
                <p>No file chosen</p>
            @endif
            <input id="foto" name="foto" type="file" class="form-control">
        </div>
    </div>
    <div class="form-group row">
    <div class="offset-4 col-8">
        <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
    </div>
</form>
@endsection