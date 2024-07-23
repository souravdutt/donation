@extends('layouts.app')

@section('content')
    <main role="main">
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">
                  <div class="col-md-12">
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
                          <div class="d-flex justify-content-between">
                            <div>
                              Date: {{ Auth::check() ? $album->created_at : date('y-m-d') }}
                            </div>
                            <div>
                              <a href="https://wa.me/?text=Checkout this amazing gallery {{ url('/albums') }}" class="btn btn-warning btn-sm"><i class="fa fa-share-alt"></i> Share</a>
                            </div>
                          </div>
                          <h6 class="card-text"><b>{{$album->name}}</b></h6>
                          <small class="card-text text-italic">{!! $album->description !!}</small>
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
