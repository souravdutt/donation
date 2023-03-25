@extends('admin.auth.layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Dashboard</h1>

        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Hi, {{ Auth::user()->name }}</h5>
                            </div>

                            <div class="col-auto">
                                <div class="stat text-primary">
                                    <i class="align-middle" data-feather="smile"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3">
                            @php
                                if(date('h') < 12) {
                                    echo 'Good Morning!';
                                } else if(date('h') < 16) {
                                    echo 'Good Afternoon!';
                                } else {
                                    echo 'Good Evening!';
                                }
                            @endphp
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
