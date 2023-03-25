@extends('admin.auth.layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><i class="fa fa-comments"></i> Queries</h1>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- datatable table --}}
                        <table class="queries-datatable table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Query</th>
                                    <th>Recieved on</th>
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

@endsection

@section('scripts')
    <script src="{{ asset("vendor/datatables-bs-5/DataTables-1.11.3/js/dataTables.bootstrap5.min.js") }}"></script>
    <script>
        $(function() {
            loadTable(".queries-datatable");

            $(document).on("click", ".deletequery", function(){
                if(!confirm("Are you sure, you want to perform this action?")){ return false; }

                let btn = $(this);
                let id = btn.data("id");

                btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

                let url = "{{ route('auth.querie.delete') }}";
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
                            alert(response.success);
                            $(".queries-datatable").DataTable().ajax.reload(null, false);
                        }else if(response.error){
                            alert(response.error);
                        }else{
                            alert('Something went wrong, please try again later.');
                        }
                    },
                    error: function(response){
                        if(response.responseJSON.errors){
                            alert(
                                $.map(response.responseJSON.errors, function(value, index){
                                    return value;
                                }).join("\n"));
                        }
                        else if(response.responseJSON.error){
                            alert(data.responseJSON.error);
                        }
                        else{
                            alert("Something went wrong. Please try again later.");
                        }
                    },
                });
            });

        });

        function loadTable(table, data = {}){
            $(table).DataTable({
                ajax: {
                    url: '{{ route("auth.queries") }}',
                    data: data,
                },
                processing: true,
                serverSide: true,
                scrollX: true,
                distroyable: true,
                stateSave: true,
                columns: [
                    {data: 'name', name: 'name', render: function(data, type, row) {
                        let email = row.email ? '<br>'+row.email : '';
                        let mobile = row.mobile ? '<br>'+row.mobile : '';
                        return data ? '<span class="name">'+ data + email + mobile +'</span>' : '' + email + mobile;
                    }},
                    {data: 'message', name: 'message', render: function(data, type, row) {
                        return data ? '<span class="message">'+ data +'</span>' : '';
                    }},
                    {data: 'created_at', name: 'created_at', render: function(data, type, row) {
                        return getFormattedDateTime(data);
                    }},
                    {data: 'action', name: 'action', orderable: false, searchable: false, },
                ],
                drawCallback: function(settings, json) {
                    // Enable tooltip everywhere
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                },
                order: [[2, 'desc']],
                // ordering: false,
                // searching: false,
                // lengthChange: false,
            });
        }
    </script>
@endsection
