{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">This is our administrators
                    <div class="text-muted pt-2 font-size-sm">Berikut adalah administrator yang terdaftar dalam pengaturan konten untuk aplikasi ini</div>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button data-toggle="modal" data-target="#exampleModal" href="#" class="btn btn-primary font-weight-bolder ml-2">
                    <i class="ki ki-plus "></i> Tambah User
                </button>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">

            <!--begin: Datatable-->
            <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
            </table>
            <!--end: Datatable-->
        </div>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{route('create_user')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="input_id" name="id">
                        <div class="form-group">
                            <label>Nama
                            <span class="text-danger">*</span></label>
                            <input type="text" id="input_name" required name="name" class="form-control" placeholder="Nama Event" >
                        </div>
                        <div class="form-group">
                            <label>Email
                            <span class="text-danger">*</span></label>
                            <input type="email" id="input_email" required name="email" class="form-control" placeholder="Email" >
                        </div>
                        <div class="form-group">
                            <label>Password
                            <span class="text-danger">*</span></label>
                            <input type="password" id="input_password" name="password" class="form-control" placeholder="Password" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

{{-- Styles Section --}}
@section('styles')

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script>

        $('#kt_datatable_search_status').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'Status');
        });
        $('#kt_datatable_search_type').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });
        $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();

        var datatable = $('#kt_datatable').KTDatatable({
            pagination: true,
            search: {
                onEnter: true,
                delay: 400,
            },
            data: {
                // autoColumns: true,
                serverSorting: true,
                serverFiltering: true,
                serverPaging: true,
                pageSize: 10,
                type: 'remote',
                source: {
                    read: {
                        url : "/get_users",
                        method: 'GET',
                        contentType: 'application/json',
                        timeout: 30000,
                        params: {
                            generalSearch: '',
                            EmployeeID: 1,
                            _token: '{{ csrf_token() }}'
                        },
                    }
                }
            },
            layout: {
                theme: 'default',
                class: 'datatable-bordered',
                customScrollbar: true,
                scroll: true,
                footer: false,
                header: true,
                customScrollbar: true,
                // minHeight: 500,
                spinner: {
                    overlayColor: '#000000',
                    opacity: 0,
                    type: 'loader',
                    state: 'brand',
                    message: 'Loading..',
                },
                icons: {
                    pagination: {
                        next: 'la la-angle-right',
                        prev: 'la la-angle-left',
                        first: 'la la-angle-double-left',
                        last: 'la la-angle-double-right',
                        more: 'la la-ellipsis-h'
                    }
                }
            },
            columns: [
				{
					field: 'id',
					title: 'ID',
					sortable: false,
					type: 'number',
					selector: false,
					textAlign: 'left',
                    width: 50,
					template: function(data) {
						return '<span class="font-weight-bolder">' + data.id + '</span>';
					}
				},
                {
                    field: 'name',
                    title: 'User',
                    width:250,
                    template: function(data){
                        var number = KTUtil.getRandomInt(1, 14);
						var user_img = '100_' + number + '.jpg';

						var output = '';
						var stateNo = KTUtil.getRandomInt(0, 7);
						var states = [
								'success',
								'primary',
								'danger',
								'success',
								'warning',
								'dark',
								'primary',
								'info'];
						var state = states[stateNo];

						output = '<div class="d-flex align-items-center">\
								<div class="symbol symbol-40 symbol-light-'+state+' flex-shrink-0">\
									<span class="symbol-label font-size-h4 font-weight-bold">' + data.name.substring(0, 1) + '</span>\
								</div>\
								<div class="ml-4">\
									<div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + data.name + '</div>\
									<a href="#" class="text-muted font-weight-bold text-hover-primary">' + data.email + '</a>\
								</div>\
							</div>';

						return output;
                    }
                },
                {
					field: 'Role',
					title: 'Role',
					template: function(row) {
						var output = '';

						output += '<div class="font-weight-bold text-muted">' + row.roles[0].name + '</div>';

						return output;
					}
				},
                {
					field: 'id_status',
					title: 'Status',
					// callback function support for column rendering
					template: function(row) {
						var status = {
							1: {'title': 'Active', 'class': ' label-light-success'},
							2: {'title': 'Banned', 'class': ' label-light-danger'},
							3: {'title': 'Not Active', 'class': ' label-light-warning'},
						};
						return '<span class="label label-lg font-weight-bold ' + status[row.id_status].class + ' label-inline">' + status[row.id_status].title + '</span>';
					},
				},
                {
					field: 'created_at',
					title: 'Join Date',
					type: 'date',
					format: 'MM/DD/YYYY',
					template: function(row) {
						var output = '';
                        var currentDate = new Date(row.created_at);

                        var date = currentDate.getDate();
                        var month = currentDate.getMonth();
                        var year = currentDate.getFullYear();

                        var dateString = date + "-" +(month + 1) + "-" + year;
						output += '<div class="font-weight-bolder text-primary mb-0">' + dateString + '</div>';

						return output;
					},
				},
                {
					field: 'deleted_at',
					title: 'Action',
					type: 'date',
					template: function(data) {
                        var output = ``;

                        var encoded_data = JSON.stringify(data);
                        var encoded_data = JSON.stringify(encoded_data);

                        if(data.id == '{{Auth::user()->id}}'){
						    output += `<button id="btn_view_`+data.id+`" onclick='viewItem(`+encoded_data+`)' class="btn btn-light-warning btn-sm fas fa-edit mx-1"  data-toggle="modal" data-target="#staticBackdrop"></button>`;
                        }

                        if(data.id_status == 3){
                            output += `<button id="btn_restore_`+data.id+`" onclick="restoreItem()" class="btn btn-light-success btn-sm far fa-window-restore mx-1"></button>`;
                        }else{
						    output += `<button id="btn_delete_`+data.id+`" onclick="deleteItem()" class="btn btn-light-danger btn-sm far fa-trash-alt mx-1"></button>`;
                        }

						return output;
					},
				},

            ]
        });


        function viewItem(data){
            data = JSON.parse(data);

            $("#input_id").val(data.id);
            $("#input_name").val(data.name);
            $("#input_email").val(data.email);

            $('#exampleModal').modal('show');
        }


        function deleteItem(){
            var el_id = event.target.id;
            var id = el_id.replace("btn_delete_", "");

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ url('delete_user') }}" + "/" + id;
                }
            });
        }

        function restoreItem(){
            var el_id = event.target.id;
            var id = el_id.replace("btn_restore_", "");

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, restore it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ url('restore_user') }}" + "/" + id;
                }
            });
        }
    </script>
    <!-- <script src="{{ asset('js/pages/crud/ktdatatable/base/html-table.js') }}" type="text/javascript"></script> -->
@endsection
