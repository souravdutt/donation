@extends('layouts.app')


@section('content')
<div class="container">
    <main>
        <div class="pt-5 text-center">
            <h2>Contact Us</h2>
            <p class="lead">
                For any enquery! Please Contact us
            </p>
        </div>

        <div class="mt-md-5">
            <h4>Our Address:</h4>
            <p class="ms-1"><i class="fa fa-map-marker me-1"></i>{{ env('TRUST_NAME') }}, {{ env('TRUST_ADDRESS') }}, {{
                env('TRUST_CITY') }}</p>
            <p class="ms-1"><i class="fa fa-phone-alt"></i> {{ env('TRUST_PHONE') }}</p>
            <p class="ms-1"><i class="fa fa-envelope"></i> {{ env('TRUST_EMAIL') }}</p>
        </div>

        <div class="row mt-md-5">
            <div class="col-md-6">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55001.99524583153!2d75.85696307234565!3d30.50336988855665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391067c8b5a52e61%3A0x871d0e364930905e!2sBlind%20School%20%2CMalerkotla%2CPunjab!5e0!3m2!1sen!2sin!4v1678371460033!5m2!1sen!2sin"
                    width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6 mt-md-0 mt-5">
                <h4 class="mb-3">Send Message</h4>
                <form id="contactform" method="POST" action="{{ route('contact.form') }}">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Oops!</strong> Please clear below errors:
                        <ul>{!! implode('', $errors->all('<li>:message</li>')) !!}</ul>
                    </div>
                    @endif

                    @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        <strong>Oops!</strong> {{ Session::get('error') }}
                    </div>
                    @endif
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        {{ Session::get('success') }}
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label required">Your name</label>
                        <input type="text" class="form-control" name="name" placeholder="Your name" value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Email address</label>
                        <input type="text" class="form-control" name="email" placeholder="Email address" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label required">Mobile number</label>
                        <input type="text" class="form-control" name="mobile" placeholder="Mobile number" value="{{ old('mobile') }}">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label required">Message</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Write a message for us..." value="{{ old('message') }}"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </main>

</div>
@endsection
@section('javascript')
<script>
</script>
@endsection
