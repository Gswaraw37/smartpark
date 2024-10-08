@extends('auth.layouts.app')
@section('content')
<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-5 d-none d-lg-block">
                <img src="{{ asset('storage/parkiregis.jpg') }}" alt="">
            </div>
            <div class="col-lg-7">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>
                    <form class="user" method="POST" action="/register">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control form-control-user" name="username" id="exampleFirstName"
                                    placeholder="Username">
                            </div>
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control form-control-user" name="full_name" id="exampleFirstName"
                                    placeholder="Full Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" name="email" id="exampleInputEmail"
                                placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                @foreach ($gender as $g)
                                @php
                                    $cek = (old('g') == $g) ? 'checked' : '';
                                @endphp
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input name="jk" id="jk{{ $g }}" type="radio" class="custom-control-input" value="{{ $g }}" {{ $cek }}> 
                                        <label for="jk{{ $g }}" class="custom-control-label">{{ $g }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" name="password"
                                    id="exampleInputPassword" placeholder="Password">
                            </div>
                            <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user" name="password_confirmation"
                                    id="exampleRepeatPassword" placeholder="Repeat Password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Register Account
                        </button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="/login">Already have an account? Login!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection