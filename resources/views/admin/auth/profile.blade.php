@extends('user.layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Profile</h1>

        <div class="row">
            <div class="col-sm-12">
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
                        <form action="{{ route('user.profile-submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ old('name', $user->name) }}" name="name" placeholder="Full name" autocomplete="off">
                                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>Mobile Number</label>
                                    <input type="text" class="form-control" value="{{ old('mobile', $user->mobile) }}" name="mobile" placeholder="Mobile number" autocomplete="off">
                                    @error('mobile')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $user->email }}" name="mobile" placeholder="Email Address" autocomplete="off" disabled>
                                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>Street Address</label>
                                    <input type="text" class="form-control" value="{{ old('address', $user->address) }}" name="address" placeholder="Street Address" autocomplete="off">
                                    @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>City</label>
                                    <input type="text" class="form-control" value="{{ old('city', $user->city) }}" name="city" placeholder="City" autocomplete="off">
                                    @error('city')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>Country</label>
                                    <input type="text" class="form-control" value="{{ old('country', $user->country) }}" name="country" placeholder="Country" autocomplete="off">
                                    @error('country')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-2 mt-1">
                                    <label>Zipcode</label>
                                    <input type="text" class="form-control" value="{{ old('zipcode', $user->zipcode) }}" name="zipcode" placeholder="Zipcode" autocomplete="off">
                                    @error('zipcode')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12 mb-2 mt-1">
                                    <button class="btn btn-primary px-3"><i class="far fa-check-circle"></i> Update</button>
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
