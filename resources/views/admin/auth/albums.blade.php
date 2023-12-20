@extends('admin.auth.layouts.app')

@section('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/ckeditor5/sample/styles.css') }}"> --}}
@endsection

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
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen" role="document">
            <form class="modal-content add-album-form">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold">Add new Album</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-4 order-md-2">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h5 class="mb-0 fw-bold">Album Media</h5>
                                </div>
                                <div class="card-body text-center">
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-lg mb-1 fw-bold" name="title"
                                placeholder="Album Title">
                            <textarea class="form-control mb-1 editor" name="description"></textarea>
                            <div class="form-check form-control-sm mb-1 text-end">
                                <label class="form-check-label" role="button">
                                    <input class="form-check-input" type="checkbox" value="1" name="status" checked>
                                    Show in Albums?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top py-1">
                    <button type="submit" class="btn btn-primary px-4 add-album">Add Album</button>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
    {{-- update modal form --}}
    <div class="modal fade" id="update-album-modal-id" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen" role="document">
            <form class="modal-content update-album-form">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title fw-bold">Update Album</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-4 order-md-2">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h5 class="mb-0 fw-bold">Album Media</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="media-container">
                                        <div class="old-media-container row"></div>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm d-none delete-images-btn mb-2"><i
                                                    class="fa fa-trash"></i> Empty Album</button>
                                        </div>
                                        <div class="new-media-container row"></div>
                                    </div>
                                    <label for="edit-album" role="button" class="mb-md-2">
                                        <div class="border border-2 rounded p-2">
                                            <i class="fa fa-upload fa-3x text-muted"></i>
                                            <span class="d-block">Click here to upload media</span>
                                        </div>
                                        <input type="file" hidden id="edit-album" name="images[]" accept="image/*" multiple>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="hidden" name="id" value="">
                            <input type="text" class="form-control form-control-lg mb-1 fw-bold" name="title" placeholder="Album Title">
                            <textarea class="form-control mb-1 update-editor" name="description"></textarea>
                            <div class="form-check mb-1 text-end">
                                <label class="form-check-label" role="button">
                                    <input class="form-check-input" type="checkbox" value="1" name="status" checked>
                                    Show in Albums?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer border-top py-1 py-2">
                    <button type="submit" class="btn btn-primary px-4 update-album-submit">Update album</button>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('vendor/ckeditor5-clasic-36.0.1/ckeditor.js') }}"></script>
    <script>
        $(function() {
            let AddEditor;
            ClassicEditor
                .create( document.querySelector( 'textarea.editor' ),{
                    ckfinder: {
                        uploadUrl: "{{ route('auth.upload.media', 'album') . '?_token=' . csrf_token() }}",
                    }
                })
                .then( editor => {
                    AddEditor = editor;
                    // console.log( editor );
                })
                .catch( error => {
                    console.error( error );
            });

            let UpdateEditor;
            ClassicEditor
                .create( document.querySelector( 'textarea.update-editor' ),{
                    ckfinder: {
                        uploadUrl: "{{ route('auth.upload.media', 'album') . '?_token=' . csrf_token() }}",
                    }
                })
                .then( editor => {
                    UpdateEditor = editor;
                })
                .catch( error => {
                    console.error( error );
            });

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
                                return data ? '<span class="status badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
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
                            alert(response.responseJSON.error);
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
                $btn = $(".add-album");
                let url = "{{ route('auth.albums.add') }}";

                let formdata = new FormData($form[0]);
                formdata.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    beforeSend: function(){
                        $btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> Adding...")
                    },
                    success: function(response) {
                        if (response.success) {
                            $(".albums-datatable").DataTable().ajax.reload(null, false);
                            $form.closest('.modal').modal('hide');
                            $form[0].reset();
                            AddEditor.setData('');
                            // reset upload image preview area
                            $form.find(".delete-images-btn").addClass("d-none");
                            $form.find(".image-preview").remove();
                            $("#add-album").parent().show();
                            $("#add-album").val(null);

                            // window.location.reload();
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
                                }).join("<br>"));
                        } else if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        } else {
                            toastr.error("Something went wrong. Please try again later.");
                        }
                    },
                    complete: function() {
                        $btn.prop("disabled", false).html("Add Album");
                    }
                });
            });

            $(document).on("click", ".edit-album-btn", function() {
                let $btn = $(this);
                let id = $btn.data('id');
                let $modal = $('#update-album-modal-id');

                $modal.find("[name=id]").val(id)

                $.ajax({
                    url:"{{ route('auth.album.detail') }}",
                    type: 'GET',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // toastr.success(response.success, "Congrats!");
                            let image = response.album.media;
                            var images = '';
                            $.each(image, function(k, v){
                                images +=""
                                images += `<div class='col-auto p-1 mb-2 rounded position-relative'>
                                                <button type="button" class='btn btn-sm btn-danger remove-album rounded-circle px-1 py-0 position-absolute top-0 end-0' data-id='${v.id}'><i class='fa fa-times'></i></button>
                                                <div class="rounded" style="width:100px;height:100px;background:url({{ asset('images/albums') }}/${v.name}) center no-repeat;background-size:cover;"></div>
                                            </div>`;

                            });
                            $modal.find(".media-container .old-media-container").html(images);
                            $modal.find("[name=title]").val(response.album.name)
                            $modal.find("[name=description]").val(response.album.description)
                            $modal.find("[name=status]").prop("checked", response.album.status ? true : false)

                            $modal.modal("show");
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
                            toastr.error(response.responseJSON.error);
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
                            alert(response.responseJSON.error);
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
                            toastr.error(response.responseJSON.error);
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
