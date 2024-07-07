@extends('layouts.app')

@section('css')
    <style>
        .hidden {
            display: none;
        }

        #payment-message {
            color: rgb(105, 115, 134);
            font-size: 16px;
            line-height: 20px;
            padding-top: 12px;
            text-align: center;
        }

        #payment-element {
            margin-bottom: 24px;
        }

        /* Buttons and links */
        button[type=submit] {
            background: #5469d4;
            font-family: Arial, sans-serif;
            color: #ffffff;
            border-radius: 4px;
            border: 0;
            padding: 12px 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: block;
            transition: all 0.2s ease;
            box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
            width: 100%;
        }

        button[type=submit]:hover {
            filter: contrast(115%);
        }

        button[type=submit]:disabled {
            opacity: 0.5;
            cursor: default;
        }

        /* spinner/processing state, errors */
        .spinner,
        .spinner:before,
        .spinner:after {
            border-radius: 50%;
        }

        .spinner {
            color: #ffffff;
            font-size: 22px;
            text-indent: -99999px;
            margin: 0px auto;
            position: relative;
            width: 20px;
            height: 20px;
            box-shadow: inset 0 0 0 2px;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }

        .spinner:before,
        .spinner:after {
            position: absolute;
            content: "";
        }

        .spinner:before {
            width: 10.4px;
            height: 20.4px;
            background: #5469d4;
            border-radius: 20.4px 0 0 20.4px;
            top: -0.2px;
            left: -0.2px;
            -webkit-transform-origin: 10.4px 10.2px;
            transform-origin: 10.4px 10.2px;
            -webkit-animation: loading 2s infinite ease 1.5s;
            animation: loading 2s infinite ease 1.5s;
        }

        .spinner:after {
            width: 10.4px;
            height: 10.2px;
            background: #5469d4;
            border-radius: 0 10.2px 10.2px 0;
            top: -0.1px;
            left: 10.2px;
            -webkit-transform-origin: 0px 10.2px;
            transform-origin: 0px 10.2px;
            -webkit-animation: loading 2s infinite ease;
            animation: loading 2s infinite ease;
        }

        @-webkit-keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @media only screen and (max-width: 600px) {
            form {
                width: 80vw;
                min-width: initial;
            }
        }
    </style>
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <i class="fa fa-hand-holding-usd fa-5x"></i>
                <p class="lead mb-0 text-italic fs-4"><i class="fa fa-quote-left fa-sm text-muted"></i> No one has ever become poor by giving. <i class="fa fa-quote-right fa-sm text-muted"></i></p>
                <p class="lead text-italic" class="mb-0">Donate a tiny part of your income in charity!</p>
            </div>
        </main>

        {{-- donation form --}}
        <div class="row g-5">
                <h2 class="mb-3 text-center">Donor Information</h2>
                <!-- Display a payment form -->
                <form id="payment-form" method="POST" action="{{ route('process.checkout') }}" class="mt-0">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                    <strong>Oops!</strong> Please clear below errors:
                                    <ul>{!! implode('', $errors->all('<li>:message</li>')) !!}</ul>
                                </div>
                            @endif

                            @if (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>

                                    <strong>Oops!</strong> {{ Session::get('error') }}
                                </div>
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>

                                    {{ Session::get('success') }}
                                </div>
                            @endif


                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>

                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                    <strong>Test CC Details:</strong>
                                    <span class="badge text-bg-light text-secondary">4111 1111 1111 1111</span>
                                    <span class="badge text-bg-light text-secondary">{{ now()->addMonths(rand(15, 50))->format('m/y') }}</span>
                                    <span class="badge text-bg-light text-secondary">{{ rand(100, 999) }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="mb-0">First name</label>
                                    <input type="text" class="form-control mb-3 required" name="first_name"
                                        value="{{ old('first_name') }}" placeholder="First Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">Last name</label>
                                    <input type="text" class="form-control mb-3 required" name="last_name"
                                        value="{{ old('last_name') }}" placeholder="Last Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">Email address</label>
                                    <input type="text" class="form-control mb-3 required" name="email"
                                        value="{{ old('email') }}" placeholder="Email Address">
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">Mobile number</label>
                                    <input type="text" class="form-control mb-3 optional mobile" name="mobile"
                                        value="{{ old('mobile') }}" placeholder="Mobile number">
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">Street address</label>
                                    <input type="text" class="form-control mb-3 optional" name="street_address"
                                        value="{{ old('street_address') }}" placeholder="Street address">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="mb-0">Country Name</label>
                                    <input type="hidden" name="country_name" value="{{ old('country_name') }}">
                                    <select class="form-select required" name="country">
                                        @if (old('country') && old('country_name'))
                                            <option value="{{ old('country') }}" selected>{{ old('country_name') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="mb-0">State Name</label>
                                    <input type="hidden" name="state_name" value="{{ old('state_name') }}">
                                    <select class="form-select required" name="state">
                                        @if (old('state') && old('state_name'))
                                            <option value="{{ old('state') }}" selected>{{ old('state_name') }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="mb-0">City Name</label>
                                    <input type="hidden" name="city_name" value="{{ old('city_name') }}">
                                    <select class="form-select required" name="city">
                                        @if (old('city') && old('city_name'))
                                            <option value="{{ old('city') }}" selected>{{ old('city_name') }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0">Amount you want to donate <small class="text-muted">(Minimum
                                           @if(env("DONATION_CURRENCY") == "USD") $@elseif(env("DONATION_CURRENCY") == "INR")&#8377;@else{{ env("DONATION_CURRENCY") }}@endif{{number_format(env('MIN_DONATION_AMOUNT'),2,".",",")}})</small></label>
                                    <input type="number" class="form-control form-control-lg mb-1 required"
                                        name="amount" value="{{ old('amount') }}"
                                        placeholder="Donation Amount in @if(env("DONATION_CURRENCY") == "USD") $ (USD) @elseif(env("DONATION_CURRENCY") == "INR") &#8377; (INR) @else {{ env("DONATION_CURRENCY") }} @endif" min="{{env('MIN_DONATION_AMOUNT')}}">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch fw-bold">
                                        <input type="checkbox" class="form-check-input border-secondary" name="add_to_leaderboard"
                                            @if (old('add_to_leaderboard') == 'yes') checked="checked" @endif value="yes"
                                            id="flexSwitchCheckDefault" role="button">
                                        <label class="form-check-label" for="flexSwitchCheckDefault" role="button">
                                            Show your name on Donor's <a href="{{ route('home.leaderboard') }}" target="_blank">Leaderboard</a>?</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit">
                                <span id="button-text">Pay now</span>
                            </button>
                        </div>
                    </div>
                </form>
        </div>

        {{-- leaderboard section --}}
        @include('components.leaderboard')

    </div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $(document).on('submit', '#payment-form', function(e) {
                let form = $(this);


                $(".required").removeClass("is-invalid");
                $(".required").siblings(".select2").find(".select2-selection").removeClass("border-danger");

                if ($("input.required").val() == "" || $("select.required").val() == null) {
                    toastr.error("Please enter all required fields.", "Oops!");
                    $(".required").each(function() {
                        if ($(this).val() == "" || $(this).val() == null) {
                            $(this).addClass("is-invalid");
                            $(this).siblings(".select2").find(".select2-selection").addClass(
                                "border-danger");
                        }
                    })
                    return false
                }
                // if(first_name == ''){
                //     toastr.error("Please enter your first name.", "Oops!");
                //     return false;
                // }
                // if(last_name == ''){
                //     toastr.error("Please enter your last name.", "Oops!");
                //     return false;
                // }
                // if(email == ''){
                //     toastr.error("Please enter your email.", "Oops!");
                //     return false;
                // }
                // if(amount == ''){
                //     toastr.error("Please enter your amount.", "Oops!");
                //     return false;
                // }
                // if(address == ''){
                //     toastr.error("Please enter your address.", "Oops!");
                //     return false;
                // }
                form.find('[type=submit]').prop('disabled', true);
            });

            $("[name=country]").each(function() {
                let $this = $(this)
                $this.select2({
                    placeholder: 'Select country',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('find.countries') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                    // more: (params.page * 20) < data.total
                                }
                            };
                        },
                        cache: false,
                    }
                }).on("change", function(e) {
                    if ($this.val()) {
                        let text = $this.find('option:selected').text();
                        $("[name=country_name]").val(text);
                    } else {
                        $("[name=country_name]").val(null);
                    }
                    $("[name=state]").val(null).trigger("change")
                    $("[name=city]").val(null).trigger("change")
                });
            }).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            $("[name=state]").each(function() {
                let $this = $(this)
                $this.select2({
                    placeholder: 'Select state',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('find.states') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                                country_id: $("[name=country]").val(),
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                    // more: (params.page * 20) < data.total
                                }
                            };
                        },
                        cache: false,
                    }
                }).on("select2:opening", function(e) {
                    let country = $("[name=country]").val();
                    if (country == null) {
                        setTimeout(() => {
                            $this.select2('close');
                            $this.val(null).trigger("change");
                        }, 100);
                        toastr.error("Please select country first.", "Oops!");
                    }
                }).on("change", function(e) {
                    if ($this.val()) {
                        let text = $this.find('option:selected').text();
                        $("[name=state_name]").val(text);
                    } else {
                        $("[name=state_name]").val(null);
                    }
                    $("[name=city]").val(null).trigger("change");
                }).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            })

            $("[name=city]").each(function() {
                let $this = $(this)
                $this.select2({
                    placeholder: 'Select city',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('find.cities') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1,
                                country_id: $("[name=country]").val(),
                                state_id: $("[name=state]").val(),
                            }
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                    // more: (params.page * 20) < data.total
                                }
                            };
                        },
                        cache: false,
                    }
                }).on("select2:opening", function(e) {
                    let country = $("[name=country]").val();
                    let state = $("[name=state]").val();
                    if (country == null || state == null) {
                        setTimeout(() => {
                            $this.select2('close');
                            $this.val(null).trigger("change");
                        }, 100);
                        toastr.error("Please select country and state first.", "Oops!");
                    }
                }).on('change', function() {
                    if ($this.val()) {
                        let text = $this.find('option:selected').text();
                        $("[name=city_name]").val(text);
                    } else {
                        $("[name=city_name]").val(null);
                    }
                }).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            });
        });
    </script>
@endsection
