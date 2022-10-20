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
            <div class="card-toolbar">
                <!--begin::Button-->
                <button data-toggle="modal" data-target="#exampleModal" href="#" class="btn btn-primary font-weight-bolder ml-2">
                    <i class="ki ki-plus "></i> Tambah Kategori
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
                    <h5 class="modal-title" id="exampleModalLabel">Detail Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{route('store_category')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="input_id" name="id">
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" required id="input_kategori" name="name" class="form-control" placeholder="Nama Kategori" >
                        </div>
                        <div class="form-group">
                            <label>Apakah Bisa Beli Tiket Online?</label>
                            <select name="is_enable_ticket" id="is_enable_ticket" class="form-control">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
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
                        url : "/get_categories",
                        method: 'GET',
                        contentType: 'application/json',
                        timeout: 30000,
                        params: {
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
						return '<span class="font-weight-bolder">' + data.name + '</span>';
                    }
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

						output += `<button id="btn_view_`+data.id+`" onclick='viewItem(`+encoded_data+`)' class="btn btn-light-warning btn-sm fas fa-edit mx-1"  data-toggle="modal" data-target="#staticBackdrop"></button>`;
						output += `<button id="btn_delete_`+data.id+`" onclick="deleteItem()" class="btn btn-light-danger btn-sm far fa-trash-alt mx-1"></button>`;

						return output;
					},
				},
            ]
        });

        function viewItem(data){
            data = JSON.parse(data);

            $("#input_id").val(data.id);
            $("#input_kategori").val(data.name);

            val = 0;
            if(data.is_enable_ticket){
                val = 1;
            }
            $("#is_enable_ticket").val(val).change();

            $('#exampleModal').modal('show');
        }

        function deleteItem(){
            var el_id = event.target.id;
            var id = el_id.replace("btn_delete_", "");

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ url('delete_category') }}" + "/" + id ;
                }
            });
        }
    </script>
    <!-- <script src="{{ asset('js/pages/crud/ktdatatable/base/html-table.js') }}" type="text/javascript"></script> -->
@endsection
