@extends('admin.auth.layouts.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><i class="fa fa-file-image"></i> Albums</h1>
        <div class="text-end my-2">
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add-album-modal-id"><i
                    class="fa fa-plus"></i> Add Album</button>
        </div>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- datatable table --}}
                        <table class="albums-datatable table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created on</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    {{-- add form modal --}}
    <div class="modal fade" id="add-album-modal-id" tabindex="-1" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <form class="add-album-form">
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm d-none delete-images-btn mb-2"><i
                                    class="fa fa-trash"></i> Empty Album</button>
                        </div>
                        <div class="image-gallery row">
                            {{-- media gallery when uploaded --}}
                            <div class="new-media-container"></div>
                        </div>
                        <label for="add-album" role="button" class="mb-md-2">
                            <div class="border border-2 rounded p-2">
                                <i class="fa fa-upload fa-3x text-muted"></i>
                                <span class="d-block">Click here to upload media</span>
                            </div>
                            {{-- <div class="default-image-preview rounded-circle mb-2 mx-auto" style="width:150px; height:150px; background:url({{ asset('images/icon/icon.png') }}) center no-repeat ; background-size :cover;" data-default-image="{{ asset('images/icon/icon.png') }}"></div> --}}
                            <input type="file" hidden id="add-album" name="images[]" accept="image/*" multiple>
                        </label>
                        <input type="text" class="form-control form-control-sm mb-1" name="name"
                            value="{{ old('name') }}" placeholder="Album Name">
                        <input type="text" class="form-control form-control-sm mb-1" name="description"
                            value="{{ old('description') }}" placeholder="Album Description">
                        <div class="form-check form-control-sm mb-1">
                            <input class="form-check-input" type="checkbox" value="1" name="status">
                            <label class="form-check-label" for="flexCheckDefault">
                                Show in albums
                            </label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-1 addalbum">Add Album</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- update modal form --}}
    <div class="modal fade" id="update-album-modal-id" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">
                    <form class="update-album-form">
                        <div class="media-container">
                            <div class="old-media-container row"></div>
                            <div class="text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm d-none delete-images-btn mb-2"><i
                                        class="fa fa-trash"></i> Empty Album</button>
                            </div>
                            <div class="new-media-container row"></div>
                        </div>
                        <input type="hidden" name="id" value="">
                        <label for="edit-album" role="button" class="mb-md-2">
                            <div class="border border-2 rounded p-2">
                                <i class="fa fa-upload fa-3x text-muted"></i>
                                <span class="d-block">Click here to upload media</span>
                            </div>
                            <input type="file" hidden id="edit-album" name="images[]" accept="image/*" multiple>
                        </label>
                        <input type="text" class="form-control form-control-sm mb-1" name="name" value="{{ old('name') }}" placeholder="Album Name">
                        <input type="text" class="form-control form-control-sm mb-1" name="description" value="{{ old('description') }}" placeholder="Album Description">
                        <textarea class="form-control form-control-sm" name="status" placeholder="status">{{ old('status') }}</textarea>
                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-1 update-album-submit">Update album</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            loadTable(".albums-datatable");

            function loadTable(table, data = {}) {
                $(table).DataTable({
                    ajax: {
                        url: '{{ route('auth.albums') }}',
                        data: data,
                    },
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    distroyable: true,
                    stateSave: true,
                    columns: [{
                            data: 'name',
                            name: 'name',
                            render: function(data, type, row) {
                                return data ? '<span class="name">' + data + '</span>' : '';
                            }
                        },
                        {
                            data: 'description',
                            name: 'description',
                            render: function(data, type, row) {
                                return data ? '<span class="description">' + data + '</span>' : '';
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data, type, row) {
                                return data ? '<span class="status">' + data + '</span>' : '';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, row) {
                                return getFormattedDateTime(data);
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    drawCallback: function(settings, json) {
                        // Enable tooltip everywhere
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'))
                        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl)
                        });
                    },
                    order: [
                        [4, 'desc']
                    ],
                    // ordering: false,
                    // searching: false,
                    // lengthChange: false,
                });
            }

            $(document).on("click", ".deletealbum", function() {
                if (!confirm("Are you sure, you want to perform this action?")) {
                    return false;
                }

                let btn = $(this);
                let id = btn.data("id");

                btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

                let url = "{{ route('auth.albums.delete') }}";
                let data = {
                    id: id,
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                };
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            alert(response.success);
                            $(".albums-datatable").DataTable().ajax.reload(null, false);
                        } else if (response.error) {
                            alert(response.error);
                        } else {
                            alert('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            alert(
                                $.map(response.responseJSON.errors, function(value, index) {
                                    return value;
                                }).join("\n"));
                        } else if (response.responseJSON.error) {
                            alert(data.responseJSON.error);
                        } else {
                            alert("Something went wrong. Please try again later.");
                        }
                    },
                });
            });

            $(document).on('change', '#add-album', function() {
                loadMultipleFiles(this, '.add-album-form .image-gallery');
            });

            $(document).on('click', '.delete-images-btn', function() {
                if (!confirm("Are you sure? You have to re-upload all media files if you delete them.")) {
                    return false;
                }
                $(this).addClass("d-none");
                $(document).find(".image-preview").remove();
                $("#add-album").parent().show();
                $("#add-album").val(null);
            });

            $(document).on("submit", ".add-album-form", function(e) {
                e.preventDefault();
                let $form = $(this);
                $btn = $(".addalbum");
                let url = "{{ route('auth.albums.add') }}";
                $btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")


                let formdata = new FormData($form[0]);
                formdata.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    success: function(response) {
                        if (response.success) {
                            $(".albums-datatable").DataTable().ajax.reload(null, false);
                            $form.closest('.modal').modal('hide');
                            window.location.reload();
                            toastr.success(response.success, "Congrats!").window;
                        } else if (response.error) {
                            toastr.error(response.error);
                        } else {
                            toastr.error('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            toastr.error(
                                $.map(response.responseJSON.errors, function(value, index) {
                                    return value;
                                }).join("\n"));
                        } else if (response.responseJSON.error) {
                            toastr.error(data.responseJSON.error);
                        } else {
                            toastr.error("Something went wrong. Please try again later.");
                        }
                    },
                    complete: function() {
                        form.find(":submit").prop("disabled", false).html("Save Changes");
                    }
                });
            });

            $(document).on("click", ".edit-album-btn", function() {
                let $btn = $(this);

                let id = $btn.data('id');
                let name = $btn.data('name');
                let description = $btn.data('description');
                let status = $btn.data('status');

                let $modal = $('#update-album-modal-id');

                $modal.find("[name=id]").val(id)
                $modal.find("[name=name]").val(name)
                $modal.find("[name=description]").val(description)
                $modal.find("[name=status]").val(status)

                $.ajax({
                    url:"{{ route('auth.media') }}",
                    type: 'GET',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // toastr.success(response.success, "Congrats!");
                            let image = response.data;
                            var images = '';
                            $.each(image, function(k, v){
                                console.log(k, v);
                                images +=""
                                images += `<div class='col-auto p-1 mb-2 rounded position-relative'>
                                                <button type="button" class='btn btn-sm btn-danger remove-album rounded-circle px-1 py-0 position-absolute top-0 end-0' data-id='${v.id}'><i class='fa fa-times'></i></button>
                                                <div class="rounded" style="width:100px;height:100px;background:url({{ asset('images/albums') }}/${v.name}) center no-repeat;background-size:cover;"></div>
                                            </div>`;
                            $("#update-album-modal-id").find(".media-container .old-media-container").html(images);
                            });
                        } else if (response.error) {
                            toastr.error(response.error);
                        } else {
                            toastr.error('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            toastr.error(
                                $.map(response.responseJSON.errors, function(value, index) {
                                    return value;
                                }).join("\n"));
                        } else if (response.responseJSON.error) {
                            toastr.error(data.responseJSON.error);
                        } else {
                            toastr.error("Something went wrong. Please try again later.");
                        }
                    },
                });
            });

            $(document).on('change', '#edit-album', function() {
                loadMultipleFiles(this, '.update-album-form .media-container .new-media-container');
            });

            $(document).on('click', '.remove-album', function() {
                if (!confirm("Are you sure, you want to perform this action?")) {
                    return false;
                }

                let btn = $(this);
                let id = btn.data("id");
                console.log('id');

                btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

                let url = "{{ route('auth.media.delete') }}";
                let data = {
                    id: id,
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                };
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            alert(response.success);
                            btn.parent().remove();
                        } else if (response.error) {
                            alert(response.error);
                        } else {
                            alert('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            alert(
                                $.map(response.responseJSON.errors, function(value, index) {
                                    return value;
                                }).join("\n"));
                        } else if (response.responseJSON.error) {
                            alert(data.responseJSON.error);
                        } else {
                            alert("Something went wrong. Please try again later.");
                        }
                    },
                });
            });

            $(document).on("submit", ".update-album-form", function(e){
                e.preventDefault();

                let $form = $(this);
                $btn = $form.find(":submit");
                let url = "{{ route('auth.albums.update') }}";
                $btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

                let formdata = new FormData($form[0]);
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
                            $('#update-album-modal-id').hide();
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
                        $form.find(":submit").prop("disabled", false).html("Changes Saved");
                    }
                });
            });
            // Multiple images preview in browser
            var loadMultipleFiles = function(input, placeToInsertImagePreview) {

                if (input.files) {
                    var filesAmount = input.files.length;
                    $(document).find(".image-preview").remove();

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            $(placeToInsertImagePreview).append(`
                                <div class="col-auto image-preview rounded mb-2 mx-1" style="width:100px;height:100px;background:url(${event.target.result}) center no-repeat;background-size:cover;"></div>
                            `)
                        }

                        reader.readAsDataURL(input.files[i]);
                    }

                    $(input).parent().hide();
                    $(".delete-images-btn").removeClass("d-none");
                } else {
                    toastr.error("Unable to upload images.");
                }

            };

        });
    </script>
    <script src="{{ asset('vendor/datatables-bs-5/DataTables-1.11.3/js/dataTables.bootstrap5.min.js') }}"></script>
@endsection
