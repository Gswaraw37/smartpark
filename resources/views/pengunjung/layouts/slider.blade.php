<!-- slider section -->
<section class=" slider_section position-relative">
    <div class="slider_container">
      <div class="img-box">
        <img src="{{ asset('pengunjung/images/hero-img.jpg') }}" alt="">
      </div>

      @if (session('messages'))
      <div class="alert alert-info">
          Anda memiliki notifikasi baru:
          <ul>
              @foreach (session('messages') as $message)
                  <li>{{ $message }}</li>
              @endforeach
          </ul>
      </div>
      @endif

        <div class="detail_container">
          <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <div class="detail-box">
                  <h1>
                    Selamat Datang <br>
                    Di <br>
                    SmartPark
                  </h1>
                </div>
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
    </div>
  </section>
  <!-- end slider section -->
</div>