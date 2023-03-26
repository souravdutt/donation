@extends('layouts.app')

@section('content')
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading mt-5">Album</h1>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem alias, minima ut,
                    ab repudiandae nostrum mollitia vero, vel sit deserunt molestias fugit inventore
                    dicta omnis natus aut culpa. Quos pariatur, a voluptates minima nemo fugiat amet
                    non corporis, quaerat placeat esse tempora exercitationem dicta distinctio quasi
                    vel accusantium nulla modi.
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
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  <div class="d-flex justify-content-center mt-2">
                    {{ $albums->onEachSide(0)->links() }}
                  </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('javascript')
    <script></script>
@endsection
