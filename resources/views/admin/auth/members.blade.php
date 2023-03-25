@extends('admin.auth.layouts.app')
@php

@endphp
@section('content')
    <div class="container-fluid p-0">
        <h1><i class="fa fa-user"></i> Manage Members</h1>
        <div class="text-end my-2">
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add-member-modal-id"><i class="fa fa-plus"></i> Add Member</button>
        </div>
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
        {{-- add member --}}
        <div class="row">
            @foreach ($members as $member)
                <div class="col-md-6 col-lg-4 col-xl-3 member-card">
                    <div class="card">
                        <div class="">
                            <div class="dropdown float-end">
                                <button class="btn" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <button class="dropdown-item update-member-btn" data-bs-toggle="modal" data-bs-target="#update-member-modal-id"
                                            data-id="{{ $member->id }}" data-name="{{ $member->name }}" data-designation="{{ $member->designation }}" data-quote="{{ $member->quote }}"
                                            data-image="{{ asset('images/members/'.$member->image) }}">

                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </li>
                                    <li><button class="dropdown-item delete-member-btn" data-id="{{$member->id}}" ><i class="fa fa-trash text-danger"></i> Delete</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="rounded-circle mb-3 mx-auto" style="width:150px; height:150px; background:url({{ asset('images/members/'.$member->image) }}) center no-repeat ; background-size :cover;" ></div>
                            <h4 class="fw-bold mb-0">{{ $member->name }}</h4>
                            <small class="text-muted">&horbar; {{ $member->designation }}</small>
                            <p class="text-italic fw-light my-2">
                                <i class="fa fa-quote-left text-muted fa-sm"></i>
                                {{ $member->quote }}
                                <i class="fa fa-quote-right text-muted fa-sm"></i>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('modals')
{{-- add form modal --}}
<div class="modal fade" id="add-member-modal-id" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form  class="add-member-form">
                    @csrf
                    <label for="add-member-image" class="mb-md-2">
                        <div class="image-preview rounded-circle mb-2 mx-auto" style="width:150px; height:150px; background:url({{ asset('images/icon/icon.png') }}) center no-repeat ; background-size :cover;" data-default-image="{{ asset('images/icon/icon.png') }}"></div>
                        <input type="file" hidden id="add-member-image" name="image" onchange="loadFile(event)">
                    </label>
                    <input type="text" class="form-control form-control-sm mb-1" name="name" value="{{ old('name') }}" placeholder="Member Name">
                    <input type="text" class="form-control form-control-sm mb-1" name="designation" value="{{ old('designation') }}" placeholder="Member Designation">
                    <textarea class="form-control form-control-sm" name="quote" placeholder="Quote">{{ old('quote') }}</textarea>
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-1 add-member-submit">Add Member</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- update modal form --}}
<div class="modal fade" id="update-member-modal-id" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form class="update-member-form">
                    <input type="hidden" name="id" value="">
                    <label for="update-member-image" class="mb-md-2">
                        <div class="image-preview rounded-circle mb-2 mx-auto" style="width:150px; height:150px; background:url({{ asset('images/icon/icon.png') }}) center no-repeat ; background-size :cover;" data-default-image="{{ asset('images/icon/icon.png') }}"></div>
                        <input type="file" hidden id="update-member-image" name="image" onchange="loadFile(event)">
                    </label>
                    <input type="text" class="form-control form-control-sm mb-1" name="name" value="{{ old('name') }}" placeholder="Member Name">
                    <input type="text" class="form-control form-control-sm mb-1" name="designation" value="{{ old('designation') }}" placeholder="Member Designation">
                    <textarea class="form-control form-control-sm" name="quote" placeholder="Quote">{{ old('quote') }}</textarea>
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-1 update-member-submit">Update Member</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('vendor/datatables-bs-5/DataTables-1.11.3/js/dataTables.bootstrap5.min.js') }}"></script> --}}
    <script>
        var loadFile = function(event) {
            let reader = new FileReader();
            reader.onload = function(){
                let $img = $('.image-preview');
                $img.css({
                    "background": "url("+reader.result+") center no-repeat ",
                    "background-size": "cover",
                });
            };
            reader.readAsDataURL(event.target.files[0]);
        };
        $(function() {
            $(document).on("click", ".delete-member-btn", function(){
                if(!confirm("Are you sure, you want to perform this action?")){ return false; }

                let btn = $(this);
                let id = btn.data("id");

                btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

                let url = "{{ route('auth.members.delete') }}";
                let data = {
                    id: id,
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                };
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    success: function(response){
                        if(response.success){
                            toastr.success(response.success, "Congrats!");
                            btn.closest(".member-card").remove();
                        }else if(response.error){
                            toastr.error(response.error);
                        }else{
                            toastr.error('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response){
                        if(response.responseJSON.errors){
                            toastr.error(
                                $.map(response.responseJSON.errors, function(value, index){
                                    return value;
                                }).join("\n"));
                        }
                        else if(response.responseJSON.error){
                            toastr.error(data.responseJSON.error);
                        }
                        else{
                            toastr.error("Something went wrong. Please try again later.");
                        }
                    },
                });
            });

            $(document).on("show.bs.modal", "#add-member-modal-id", function(){
                $(this).find("form")[0].reset();
                let $img = $(this).find('.image-preview');
                let default_image = $img.data("default-image");
                $img.css({
                    "background": "url("+default_image+") center no-repeat ",
                    "background-size": "cover",
                });
            })

            $(document).on("click", ".update-member-btn", function(){
                let $btn = $(this);

                let id = $btn.data('id');
                let image = $btn.data('image');
                let name = $btn.data('name');
                let designation = $btn.data('designation');
                let quote = $btn.data('quote');

                let $modal = $('#update-member-modal-id');

                $modal.find("[name=id]").val(id)
                $modal.find("[name=name]").val(name)
                $modal.find("[name=designation]").val(designation)
                $modal.find("[name=quote]").val(quote)

                $modal.find("[name=image]").val('')

                let $img = $modal.find(".image-preview");
                $img.css({
                    "background": "url("+image+") center no-repeat ",
                    "background-size": "cover",
                });
            })

            $(document).on("submit", ".add-member-form", function(e){
                e.preventDefault();
                let $form = $(this);
                $btn=$(".add-member-submit");
                let url = "{{ route('auth.members.add') }}";
                $btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")


                let formdata= new FormData($form[0]);
                formdata.append( '_token', '{{ csrf_token() }}' );
                formdata.append( '_method', 'post' );
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    success: function(response){
                        if(response.success){
                            window.location.reload();
                            toastr.success(response.success, "Congrats!").window;
                        }else if(response.error){
                            toastr.error(response.error);
                        }else{
                            toastr.error('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response){
                        if(response.responseJSON.errors){
                            toastr.error(
                                $.map(response.responseJSON.errors, function(value, index){
                                    return value;
                                }).join("\n"));
                        }
                        else if(response.responseJSON.error){
                            toastr.error(data.responseJSON.error);
                        }
                        else{
                            toastr.error("Something went wrong. Please try again later.");
                        }
                    },
                    complete: function(){
                        form.find(":submit").prop("disabled", false).html("Save Changes");
                    }
                });
            });
            $(document).on("submit", ".update-member-form", function(e){
                e.preventDefault();
                let $form = $(this);
                $btn=$(".update-member-submit");
                let url = "{{ route('auth.members.update') }}";
                $btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")


                let formdata= new FormData($form[0]);
                formdata.append( '_token', '{{ csrf_token() }}' );
                formdata.append( '_method', 'put' );
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    success: function(response){
                        if(response.success){
                            window.location.reload();
                            toastr.success(response.success, "Congrats!").window;
                        }else if(response.error){
                            toastr.error(response.error);
                        }else{
                            toastr.error('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response){
                        if(response.responseJSON.errors){
                            toastr.error(
                                $.map(response.responseJSON.errors, function(value, index){
                                    return value;
                                }).join("\n"));
                        }
                        else if(response.responseJSON.error){
                            toastr.error(data.responseJSON.error);
                        }
                        else{
                            toastr.error("Something went wrong. Please try again later.");
                        }
                    },
                    complete: function(){
                        form.find(":submit").prop("disabled", false).html("Save Changes");
                    }
                });
            });
        });
    </script>
@endsection
