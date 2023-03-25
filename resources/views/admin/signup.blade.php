@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1 class="h1">SignUp</h1>
                        <p class="lead">
                            Join our community by creating an account for free.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                @if (Session::has('error'))
                                    <div class="alert alert-danger d-flex align-items-center alert-autohide" role="alert">
                                        <div>
                                            <i class="fa fa-exclamation-triangle"></i>
                                            {{ session('error') }}
                                        </div>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('signup-submit') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input class="form-control form-control-lg" type="text" name="name"
                                            placeholder="Enter your name" value="{{ old('name') }}" />
                                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control form-control-lg" type="text" name="email"
                                            placeholder="Enter your email" value="{{ old('email') }}" />
                                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password"
                                            placeholder="Enter password" />
                                        @error('password') <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input class="form-control form-control-lg" type="password" name="confirm_password"
                                            placeholder="Confirm your password" />
                                        @error('confirm_password') <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary">Sign up</button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p>Already have an account? Go to <a href="{{ route('signin') }}">SignIn</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
