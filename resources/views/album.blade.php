@extends('layouts.app')

@section('content')
    <main role="main">
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <div class="d-flex justify-content-between mb-2 text-end">
                        <span class="text-muted">
                        {{ $album->created_at?->format('M d, \'y') }}
                        </span>
                        <a href="https://wa.me/?text=Checkout%20this%20amazing%20gallery%0A{{ route('home.album', $album->id) }}" class="btn btn-success btn-sm" target="_blank"><i class="fab fa-whatsapp fa-lg text-light"></i> Share</a>
                    </div>
                    <div class="card mb-4 box-shadow">

                      <div id="carousel{{$album->id}}" class="carousel slide">
                        <div class="carousel-inner">
                          @foreach($album->media as $k => $media)
                          <div class="carousel-item {{ $k == 0 ? "active" : "" }}">
                            <div class="w-100 h-200px" style="background: #333 url('{{ asset('images/albums/'.$media->name)}}') center center no-repeat;background-size:cover;"></div>
                          </div>
                          @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{$album->id}}" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{$album->id}}" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      </div>
                      <div class="card-body">
                          <h2 class="card-text fw-bold">{{$album->name}}</h2>
                          <div class="card-text">{!! $album->description !!}</div>
                          <div class="d-flex justify-content-between align-items-center">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('javascript')
    <script></script>
@endsection
