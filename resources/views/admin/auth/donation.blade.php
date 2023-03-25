@extends('admin.auth.layouts.app')
@section('content')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><i class="fa fa-credit-card"></i> Donation</h1>
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
                        <table class="donation-datatable table table-striped table-bordered table-hover w-100">
                            <div class="card row">
                                <div class="col-6">
                                    <select id="sortby" class="form-select sortby" aria-label="Default select example">
                                        <option value="paid">paid</option>
                                        <option value="unpaid">unpaid</option>
                                        <option value="failed">failed</option>
                                    </select>
                                </div>
                            </div>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Recieved on</th>
                                    <th>Leaderboard Status</th>
                                    <th>Delete/Change Leaderboard Status</th>
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

@section('scripts')
    <script src="{{ asset('vendor/datatables-bs-5/DataTables-1.11.3/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function() {
            loadTable(".donation-datatable");
        });

        function loadTable(table, data = {}) {
            $(table).DataTable({
                ajax: {
                    url: '{{ route('auth.donation') }}',
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
                            let email = row.email ? '<br>' + row.email : '';
                            let mobile = row.mobile ? '<br>' + row.mobile : '';
                            return data ? '<span class="name">' + data + email + mobile + '</span>' : '' +
                                email + mobile;
                        }
                    },
                    {
                        data: 'street_address',
                        name: 'street_address',
                        render: function(data, type, row) {
                            return data ? '<span class="street_address">' + data + '</span>' : '---';
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, row) {
                            return data ? '<span class="amount">' + data + '</span>' : '';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            let bg = 'bg-warning';
                            if(data == 'paid'){
                                bg = 'bg-success';
                            }else if(data == 'failed'){
                                bg = 'bg-danger';
                            }
                            return data ? '<span class="status badge '+bg+'">' + data + '</span>' : '';
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
                        data: 'add_to_leaderboard',
                        name: 'add_to_leaderboard',
                        render: function(data, type, row) {
                            let bg = 'bg-success';
                            if(data == 'yes'){
                                bg = 'bg-success';
                            }else if(data == 'no'){
                                bg = 'bg-danger';
                            }
                            return data ? '<span class="add_to_leaderboard badge '+bg+'">' + data + '</span>' : '<span class="add_to_leaderboard badge bg-danger">no</span>';
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
                // searching: true ,
                // lengthChange: false,
            });
        }
        $(document).on("click", ".change-leaderboard-status", function() {
            if (!confirm("Are you sure, you want to perform this action?")) {
                return false;
            }

            let btn = $(this);
            let id = btn.data("id");
            let status = btn.data('status') == 'yes' ? "yes" : "no";

            btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

            let url = "{{ route('auth.donation.leaderboard-status') }}";
            let data = {
                id: id,
                status: status,
                _token: "{{ csrf_token() }}",
                _method: "PUT"
            };
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        alert(response.success);
                        $(".donation-datatable").DataTable().ajax.reload(null, false);
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
                complete: function() {
                    btn.prop("disabled", false).html('<i class="fa fa-check"></i>')
                }
            });
        });
        $(document).on("click", ".deletedonation", function() {
            if (!confirm("Are you sure, you want to perform this action?")) {
                return false;
            }

            let btn = $(this);
            let id = btn.data("id");

            btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>")

            let url = "{{ route('auth.donation.delete') }}";
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
                        $(".donation-datatable").DataTable().ajax.reload(null, false);
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
        // $(document).on("click",".sortby" function(){
        //     let btn = $(this);
        //     let status = btn.value();

        //     ajax: {
        //             // url: '{{ route('auth.donation') }}',
        //             // status: data,
        //         },
        // });

    </script>
@endsection
