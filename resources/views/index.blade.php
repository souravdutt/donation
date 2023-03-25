@extends('layouts.app-banner')

@section('content')
<div class="w-100 h-100">
    <div class="position-fixed w-100 h-100 z-n1 overflow-hidden">
        <div class="position-absolute w-100 h-100 bg-dark opacity-75 z-2"></div>
        <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset("images/3.jpg")}}" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset("images/2.jpg")}}" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset("images/4.jpg")}}" class="d-block w-100" alt="...">
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex w-100 h-100 p-3 mx-auto flex-column text-white position-relative">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">{{ env('APP_NAME') }}</h3>
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.index')) active @endif" aria-current="page"
                        href="{{ route('home.index') }}">Home</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.donate')) active @endif" aria-current="page"
                        href="{{ route('home.donate') }}" href="{{ route('home.donate') }}">Donate</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.about')) active @endif" aria-current="page"
                        href="{{ route('home.about') }}" href="{{ route('home.about') }}">About Us</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.albums')) active @endif" aria-current="page"
                        href="{{ route('home.albums') }}" href="{{ route('home.albums') }}">Gallery</a>
                    <a class="nav-link fw-bold py-1 px-0
                        @if(Route::is('home.contact')) active @endif" aria-current="page"
                        href="{{ route('home.contact') }}" href="{{ route('home.contact') }}">Contact</a>
                </nav>
            </div>
        </header>

        <main class="px-3">
            <h1>Come And Make Them Smile.</h1>
            <p class="lead">For India's bright future it is also important to </p>
            <p class="lead">
                <a href="{{ route('home.donate') }}" class="btn btn-lg btn-light fw-bold border-white bg-white">
                    Donate Now <i class="fa fa-arrow-circle-right"></i>
                </a>
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <ul class="list-inline">
                <p><small>&copy; Copyright 2023</small> </p>
            </ul>
        </footer>
    </div>

</div>
@endsection
