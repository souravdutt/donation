@extends('layouts.app')

@section('content')
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Album example</h1>
                <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator,
                    etc. Make it short and sweet, but not too short so folks don't simply skip over it entirely.</p>
                <p>
                    <a href="#" class="btn btn-primary my-2">Main call to action</a>
                    <a href="#" class="btn btn-secondary my-2">Secondary action</a>
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">
                  @foreach($albums as $album)
                  {{-- card --}}
                  <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                      <div id="carousel{{$album->id}}" class="carousel slide">
                        <div class="carousel-inner">
                          @foreach($album->media as $k => $media)
                          <div class="carousel-item {{ $k == 0 ? "active" : "" }}">
                            <img src="{{ asset('images/albums/'.$media->name)}}" class="d-block w-100" alt="...">
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
                          <p class="card-text"><b>{{$album->name}}</b></p>
                          <p class="card-text">{{$album->description}}</p>
                          <div class="d-flex justify-content-between align-items-center">
                          {{-- <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-4">View</button>
                          </div>
                          <small class="text-muted">9 mins</small> --}}
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
            </div>
        </div>

    </main>
@endsection
@section('javascript')
    <script></script>
@endsection
