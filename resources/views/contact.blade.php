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
            <h3>Where to reach us!</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card my-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 d-flex align-items-center justify-content-center">
                                    <i class="text-success fa fa-map-marker fa-3x me-1"></i>
                                </div>
                                <div class="col-9 d-flex align-items-center">
                                    <p class="h5">
                                        {{ env('TRUST_NAME') }},
                                        {{ env('TRUST_ADDRESS') }},
                                        {{ env('TRUST_CITY') }},
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 d-flex align-items-center justify-content-center">
                                    <i class="text-primary fa fa-phone-alt fa-3x me-1"></i>
                                </div>
                                <div class="col-9 d-flex align-items-center">
                                    <p class="h5">
                                        {{ env('TRUST_PHONE') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 d-flex align-items-center justify-content-center">
                                    <i class="text-warning fa fa-envelope fa-3x me-1"></i>
                                </div>
                                <div class="col-9 d-flex align-items-center">
                                    <p class="h5">
                                        {{ env('TRUST_EMAIL') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55001.99524583153!2d75.85696307234565!3d30.50336988855665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391067c8b5a52e61%3A0x871d0e364930905e!2sBlind%20School%20%2CMalerkotla%2CPunjab!5e0!3m2!1sen!2sin!4v1678371460033!5m2!1sen!2sin"
                    class="rounded shadow" width="100%" height="340" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <div class="row mt-md-5">
            <div class="col-md-12 col-lg-10 col-xxl-8 mt-md-0 mt-5 mx-auto">
                <h3 class="mb-3">Get in touch with us</h3>
                <p>Please fill in all following fields to get in touch with us.</p>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('home.contact.submit') }}">
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
                                <label class="form-label">Your name</label>
                                <input type="text" class="form-control required" name="name" placeholder="Your name" value="{{ old('name') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email address</label>
                                <input type="text" class="form-control required" name="email" placeholder="Email address" value="{{ old('email') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile number</label>
                                <input type="text" class="form-control required mobile" name="mobile" placeholder="Mobile number" value="{{ old('mobile') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea class="form-control required" name="message" rows="5" placeholder="Write a message for us...">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>
@endsection
@section('javascript')
<script>
    $(function(){
        $('form').on("submit", function(e){
            e.preventDefault();

            let $form = $(this);
            let has_error = false;
            $.each($form.find("input.required"), function(){
                let $field = $(this);
                if($field.val().trim() == ""){
                    $field.addClass("is-invalid");
                    has_error = true;
                }else{
                    $field.removeClass("is-invalid");
                }
            })

            $.each($form.find("select.required"), function(){
                let $field = $(this);
                if($field.val().trim() == ""){
                    $field.addClass("is-invalid");
                    has_error = true;
                }else{
                    $field.removeClass("is-invalid");
                }
            })

            $.each($form.find("textarea.required"), function(){
                let $field = $(this);
                if($field.val().trim() == ""){
                    $field.addClass("is-invalid");
                    has_error = true;
                }else{
                    $field.removeClass("is-invalid");
                }
            });

            if(has_error){
                toastr.error("Please fill in all required fields.", "Oops!")
                return false;
            }

            $form.find(":submit").prop("disabled",true).html("<i class='fa fa-spin fa-spinner'></i> Please wait...");
            $form.unbind("submit").submit();
        });
    });
</script>
@endsection
