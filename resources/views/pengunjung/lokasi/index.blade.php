@extends('pengunjung.layouts.app')
@section('content')

<section class="book_section">
    <div class="form_container">
        <div class="container">
            <div class="row justify-content-center">
                @foreach ($blocknumber as $bn)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="https://dummyimage.com/600x400/000/fff&text=Parking" class="card-img-top" alt="...">
                            <div class="card-body text-center">
                                <h3 class="card-title">Lantai {{ $bn->parkingfloor->floor_number }}</h3>
                                <h5 class="card-title">{{ $bn->block }}</h5>
                                @if ($bn->is_occupied)
                                    <button type="button" class="btn btn-secondary" disabled>Tidak Tersedia</button>
                                @else
                                    <button type="button" class="btn btn-primary">Tersedia</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    {{ $blocknumber->links() }} <!-- Pagination links -->
                </div>
            </div>
        </div>
    </div>
    <div class="img-box">
      <img src="{{ asset('pengunjung/images/book-car.png') }}" alt="">
    </div>
</section>

@endsection