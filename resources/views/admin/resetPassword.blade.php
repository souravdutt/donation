@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1 class="h1">Reset Password</h1>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                @if(session('error'))
                                    <div class="alert alert-danger alert-autohide">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if(session('success'))
                                    <div class="alert alert-success alert-autohide">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form action="{{ route('resetPassword-submit') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input class="form-control form-control-lg" type="password" name="password"
                                            placeholder="Enter new password" value="{{ old('password') }}" />
                                        @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password_confirmation"
                                            placeholder="Confirm new password" value="{{ old('password_confirmation') }}" />
                                        @error('password_confirmation')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary">Change Password</button>
                                    </div>

                                    <div class="text-center text-muted mt-3">
                                        &horbar; OR &horbar;
                                    </div>

                                    <div class="text-center mt-3">
                                        <a href="{{ route('signin') }}">Clck Here</a> to Go Back to SignIn
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
