@extends('layouts.app')


@section('content')
    <main role="main">

        <div class="container mt-md-5">
            <!-- Three columns of text below the carousel -->
            <div class="row">
              <h2 class="text-center">Team Members</h2>
              @foreach ($member as $person)
                  <div class="col-md-4 text-center">
                    <div class="card">
                        <div class="card-title mt-2">
                            <div class="rounded-circle mx-auto" style="width:150px; height:150px; background:url({{ asset('images/members/'.$person->image) }}) center no-repeat ; background-size :cover;" ></div>
                        </div>
                        <div class="card-body">
                            <h4 class="fw-bold mb-0">{{ $person->name }}</h4>
                            <small class="text-muted">&horbar; {{ $person->designation }}</small>
                            <p class="text-italic fw-light my-2">
                                <i class="fa fa-quote-left text-muted fa-sm"></i>
                                {{ $person->quote }}
                                <i class="fa fa-quote-right text-muted fa-sm"></i>
                            </p>
                        </div>
                    </div>
                </div>
              @endforeach
            </div><!-- /.row -->

            <!-- START THE FEATURETTES -->
              <div class="row featurette my-md-5 mt-5 mt-md-5">
                <div class="col-md-7">
                    <h2 class="featurette-heading">lorem10 </h2>
                    <p class="lead">n publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available</p>
                </div>
                <div class="col-md-5">
                    <img class="featurette-image rounded mx-auto img-fluid mx-auto" src="{{ asset('images/1.jpg') }}"
                        alt="Generic placeholder image">
                </div>
              </div>

              <div class="row featurette my-md-5 mt-5 mt-md-5">
                <div class="col-md-7 order-md-2">
                  <h2 class="featurette-heading">lorem10 </h2>
                  <p class="lead">n publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available</p>
                </div>
                <div class="col-md-5 order-md-1">
                  <img class="featurette-image rounded mx-auto img-fluid mx-auto" src="{{ asset('images/2.jpg') }}"
                      alt="Generic placeholder image">
                </div>
              </div>

              <div class="row featurette my-md-5 mt-5 mt-md-5">
                <div class="col-md-7">
                    <h2 class="featurette-heading">lorem10 </h2>
                    <p class="lead">n publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available</p>
                </div>
                <div class="col-md-5">
                    <img class="featurette-image rounded mx-auto img-fluid mx-auto" src="{{ asset('images/3.jpg') }}"
                        alt="Generic placeholder image">
                </div>
              </div>



            <!-- /END THE FEATURETTES -->

        </div><!-- /.container -->

        <p class="text-center"><a href="#">Back to top</a></p>
    </main>
@endsection
@section('javascript')
    <script></script>
@endsection
