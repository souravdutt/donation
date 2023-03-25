@extends('admin.auth.layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Change Password</h1>

        <div class="row">
            <div class="col-sm-6 col-xxl-4">
                <div class="card">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-autohide">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-autohide">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('auth.changePassword-submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-2 mt-1">
                                    <label>Old Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="old_password" placeholder="Old Password" autocomplete="off">
                                    @error('old_password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12 mb-2 mt-1">
                                    <label>New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="new_password" placeholder="New Password" autocomplete="off">
                                    @error('new_password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12 mb-2 mt-1">
                                    <label>Confirm New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm New Password" autocomplete="off">
                                    @error('new_password_confirmation')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12 mb-2 mt-1">
                                    <button class="btn btn-primary px-3"><i class="far fa-check-circle"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
