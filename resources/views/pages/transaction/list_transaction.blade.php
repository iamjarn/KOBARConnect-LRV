{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{$page_title}}
                    <div class="text-muted pt-2 font-size-sm">{{$page_description}}</div>
                </h3>
            </div>
        </div>
        <div class="filter-container p-5">
            <div class="row col-12">
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Status:</label>
                    <select class="form-control datatable-input" id="status_filter" data-col-index="6">
                        <option value="">Select</option>
                        <option value="PENDING">PENDING</option>
                        <option value="WAITING_PAYMENT">WAITING_PAYMENT</option>
                        <option value="PAID">PAID</option>
                        <option value="EXPIRED">EXPIRED</option>
                        <option value="CANCELED">CANCELED</option>
                        <option value="REJECTED">REJECTED</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Date Start</label>
                    <input type="date" id="date_start_filter" class="form-control">
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Date End</label>
                    <input type="date" id="date_end_filter" class="form-control">
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>User</label>
                    <input type="text" id="username_filter" class="form-control" placeholder="username">
                </div>
                <div class="col-12 mt-5">
                    <button onclick="filtertable()" type="button" class="btn btn-light-primary font-weight-bold" >Search</button>
                    <button onclick="resetfilter()" type="button" class="btn btn-secondary btn-secondary--icon" >Reset Filter</button>
                </div>
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
                    <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tour Place</label>
                        <input type="text" id="tour_place" class="form-control" placeholder="Tour Place" disabled>
                    </div>
                    <div class="form-group">
                        <label>Invoice Number</label>
                        <input type="text" id="invoice_number" name="invoice_number" class="form-control" placeholder="Nomor Invoice" disabled>
                    </div>
                    <div class="form-group">
                        <label>Visit Date</label>
                        <input type="text" id="visit_date" name="visit_date" class="form-control" placeholder="Visit Date" disabled>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name" disabled>
                    </div>
                    <div class="form-group">
                        <label>Identity Number</label>
                        <input type="text" id="identity_number" name="identity_number" class="form-control" placeholder="Identity Number" disabled>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="email" name="email" class="form-control" placeholder="email" disabled>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" id="phone_number" class="form-control" placeholder="Phone Number" disabled>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="address" class="form-control" placeholder="Address" disabled>
                    </div>
                    <div class="form-group">
                        <label>Adult Quantity</label>
                        <input type="text" id="adult_quantity" class="form-control" placeholder="Adult Quantity" disabled>
                    </div>
                    <div class="form-group">
                        <label>Child Quantity</label>
                        <input type="text" id="child_quantity" class="form-control" placeholder="Child Quantity" disabled>
                    </div>
                    <div class="form-group">
                        <label>Transport Price</label>
                        <input type="text" id="transport_prices" class="form-control" placeholder="Transport Price" disabled>
                    </div>
                    <div class="form-group">
                        <label>Total Price</label>
                        <input type="text" id="total_prices" class="form-control" placeholder="Total Price" disabled>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" id="status" class="form-control" placeholder="Status" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
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
                        url : "/get_transactions",
                        method: 'GET',
                        contentType: 'application/json',
                        timeout: 30000,
                        params: {
                            _token: '{{ csrf_token() }}',
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
					type: 'number',
					textAlign: 'left',
                    width: 50,
					template: function(data) {
						return '<span class="font-weight-bolder">' + data.id + '</span>';
					}
				},
                {
					field: 'invoice_number',
					title: 'Invoice Number',
					sortable: false,
					selector: false,
					textAlign: 'left',
					template: function(data) {
						return '<span class="font-weight-bolder">' + data.invoice_number + '</span>';
					}
				},
                {
                    field: 'name',
                    title: 'User',
					sortable: false,
					selector: false,
                    width:250,
                    template: function(data){
						return '<span class="font-weight-bolder">' + data.name + '</span>';
                    }
                },
                {
					field: 'visit_date',
					title: 'Visit Date',
					type: 'date',
					format: 'MM/DD/YYYY',
					template: function(row) {
						var output = '';
                        var currentDate = new Date(row.visit_date);

                        var date = currentDate.getDate();
                        var month = currentDate.getMonth();
                        var year = currentDate.getFullYear();

                        var dateString = date + "-" +(month + 1) + "-" + year;
						output += '<div class="font-weight-bolder text-primary mb-0">' + dateString + '</div>';

						return output;
					},
				},
				{
					field: 'total_prices',
					title: 'Total Prices',
					type: 'number',
					textAlign: 'left',
                    width: 50,
					template: function(data) {
						return '<span class="font-weight-bolder">' + data.total_prices + '</span>';
					}
				},
                {
					field: 'status',
					title: 'Status',
					sortable: false,
					selector: false,
					template: function(row) {
						var status = {
							"PENDING": {'title': 'PENDING', 'class': ' label-secondary'},
							"WAITING_PAYMENT": {'title': 'WAITING PAYMENT', 'class': ' label-light-primary'},
							"PAID": {'title': 'PAID', 'class': ' label-light-success'},
							"EXPIRED": {'title': 'EXPIRED', 'class': ' label-light-warning'},
							"CANCELED": {'title': 'CANCELED', 'class': ' label-light-danger'},
							"REJECTED": {'title': 'REJECTED', 'class': ' label-light-danger'},
						};
						return '<span class="label label-lg font-weight-bold ' + status[row.status].class + ' label-inline">' + status[row.status].title + '</span>';
					},
				},
                {
					field: 'created_at',
					title: 'Order Date',
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
					field: 'updated_at',
					title: 'Action',
					sortable: false,
					selector: false,
					template: function(data) {
                        var output = ``;

                        var encoded_data = JSON.stringify(data);
                        var encoded_data = JSON.stringify(encoded_data);


						output += `<button id="btn_view_`+data.id+`" onclick='viewItem(`+encoded_data+`)' class="btn btn-light-warning btn-sm fas fa-edit mx-1"  data-toggle="modal" data-target="#staticBackdrop"></button>`;

						return output;
					},
				},
            ]
        });

        function filtertable(){
            datatable.setDataSourceParam("status", $("#status_filter").val());
            datatable.setDataSourceParam("start_date", $("#date_start_filter").val());
            datatable.setDataSourceParam("end_date", $("#date_end_filter").val());
            datatable.setDataSourceParam("name", $("#username_filter").val());
            datatable.reload();
        }

        function resetfilter(){
            $("#status_filter").val('');
            $("#date_start_filter").val('');
            $("#date_end_filter").val('');
            $("#username_filter").val('');

            filtertable()
        }

        function viewItem(data){
            data = JSON.parse(data);

            $("#tour_place").val(data.tour.name);
            $("#invoice_number").val(data.invoice_number);
            $("#visit_date").val(data.visit_date);
            $("#name").val(data.name);
            $("#identity_number").val(data.identity_number);
            $("#email").val(data.email);
            $("#phone_number").val(data.phone_number);
            $("#address").val(data.address);
            $("#adult_quantity").val(data.adult_quantity);
            $("#child_quantity").val(data.kid_quantity);
            $("#transport_prices").val(data.transport_prices);
            $("#total_prices").val(data.total_prices);
            $("#status").val(data.status);

            $('#exampleModal').modal('show');
        }
    </script>
    <!-- <script src="{{ asset('js/pages/crud/ktdatatable/base/html-table.js') }}" type="text/javascript"></script> -->
@endsection
