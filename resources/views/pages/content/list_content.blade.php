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
    <div class="d-flex flex-column-fluid">
        <div class="Container col-12">
            <!-- begin: header search -->
            <!--begin::Pagination-->
            <div class="card card-custom mb-5">
                                    <div class="card-body py-7"><!--begin::Search Form-->
            <div>
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <div class="row align-items-center">
                            <div class="col-10">
                            <div class="card-title">
                                <h3 class="card-label">{{$page_title}}
                                    <div class="text-muted pt-2 font-size-sm">{{$page_description}}</div>
                                </h3>
                            </div>
                            </div>
                            <div class="ml-5">
                                <button data-toggle="modal" data-target="#exampleModal" class="btn btn-light-success px-6 font-weight-bold">Buat Baru</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
                                    </div>
                                </div>
                                <!--end::Pagination-->

            <!-- end: header search -->
                                    <!--begin::Row-->
                                    <?php
                                        $col_count = 3;
                                        $row_count = ceil(count($data) / $col_count);
                                        $index_item = 0;
                                    ?>
                                    @for($row_index = 0; $row_index < $row_count; $row_index ++)
                                        <div class="row">
                                            @for($i = 0; $i < $col_count; $i ++)
                                                <!--begin::Col-->
                                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-custom gutter-b card-stretch">
                                                        <!--begin::Body-->
                                                        <div class="card-body px-0 pt-0">
                                                            <!--begin::User-->
                                                            <div class="d-flex align-items-end mb-5">
                                                                <!--begin::Pic-->
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Pic-->
                                                                    <div>
                                                                        @if($data[$index_item]->file_type == config('constant.FILE_TYPE')['IMAGE'])
                                                                            <img src="{{ asset($data[$index_item]->path ?? config('constant.IMAGE_DEFAULT_PATH')) }}" width="100%"; class="rounded" alt="...">
                                                                        @else
                                                                        <video width="100%" controls="controls" preload="metadata">
                                                                            <source src="{{asset($data[$index_item]->path)}}#t=0.1" type="video/mp4">
                                                                            <!-- <source src="mov_bbb.ogg" type="video/ogg"> -->
                                                                            Your browser does not support HTML video.
                                                                        </video>
                                                                        @endif
                                                                    </div>
                                                                    <!--end::Pic-->
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                            <!--end::User-->
                                                            <!--begin::Title-->
                                                                <div class="d-flex flex-column mb-5 mx-8">
                                                                    <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">{{$data[$index_item]->file_type}}</a>
                                                                </div>
                                                                <!--end::Title-->
                                                                <div class="mx-8">
                                                                    <!--begin::Desc-->
                                                                    <p>{{$data[$index_item]->message}}</p>
                                                                    <!--end::Desc-->
                                                                    <button onclick="viewItem()" id="btn_view_index_{{$index_item}}" class="btn btn-block btn-sm btn-light-success font-weight-bolder text-uppercase py-4">Edit Konten</button>
                                                                    <button onclick="deleteItem()" id="btn_delete_{{$data[$index_item]->id}}" class="btn btn-block btn-sm btn-danger font-weight-bolder text-uppercase py-4">Hapus</button>
                                                                </div>
                                                        </div>
                                                        <!--end::Body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->
                                                @php($index_item++)
                                                @if(count($data) == $index_item)
                                                    @break
                                                @endif
                                            @endfor
                                        </div>
                                    @endfor
                                    <!--end::Row-->
                                <!--begin::Pagination-->
								<div class="card card-custom">
									<div class="card-body py-7">
										<!--begin::Pagination-->
                                        @if ($data->lastPage() > 1)
                                            <ul class="pagination">
                                                <li>
                                                    <a href="{{ $data->url(1) }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->currentPage() == 1) ? ' disabled' : '' }}">
                                                        <i class="ki ki-bold-double-arrow-back icon-xs"></i>
                                                    </a>
                                                </li>

												<a href="{{ $data->previousPageUrl() }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->onFirstPage()) ? ' disabled' : '' }}">
													<i class="ki ki-bold-arrow-back icon-xs"></i>
												</a>
                                                @for ($i = 1; $i <= $data->lastPage(); $i++)
                                                    <?php
                                                    $link_limit = 7;
                                                    $half_total_links = floor($link_limit / 2);
                                                    $from = $data->currentPage() - $half_total_links;
                                                    $to = $data->currentPage() + $half_total_links;
                                                    if ($data->currentPage() < $half_total_links) {
                                                    $to += $half_total_links - $data->currentPage();
                                                    }
                                                    if ($data->lastPage() - $data->currentPage() < $half_total_links) {
                                                        $from -= $half_total_links - ($data->lastPage() - $data->currentPage()) - 1;
                                                    }
                                                    ?>
                                                    @if ($from < $i && $i < $to)
                                                        <li>
												            <a href="{{ $data->url($i) }}" class="btn btn-icon btn-sm border-0 btn-hover-primary {{ ($data->currentPage() == $i) ? ' active' : '' }} mr-2 my-1">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endfor
                                                <a href="{{ $data->nextPageUrl() }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
													<i class="ki ki-bold-arrow-next icon-xs"></i>
												</a>
                                                <li >
                                                    <a href="{{ $data->url($data->lastPage()) }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
                                                        <i class="ki ki-bold-double-arrow-next icon-xs"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif
										<!--end:: Pagination-->
									</div>
								</div>
								<!--end::Pagination-->
        </div>
    </div>


    <!-- Modal-->
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Content</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{route('store_content')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="input_id" name="id">
                        <div class="form-group">
                            <label for="exampleTextarea">Urutan
                            <span class="text-danger">*</span></label>
                            <input class="form-control" required type="number" min="1" id="input_order" name="order">

                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea">Deskripsi
                            <span class="text-danger">*</span></label>
                            <textarea required class="form-control" name="message" id="textarea_description" rows="3" spellcheck="false"></textarea>
                        </div>
                        <div class="form-group">
							<label>Upload File
                            <span class="text-danger">*</span></label>
						<div>
                        </div>
							<div class="custom-file">
								<input id="input_file" type="file" class="custom-file-input" name="file" required>
								<label id="label_file" class="custom-file-label" for="customFile">Choose file</label>
							</div>
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
        $(document).ready(function() {
            $('.select2').select2();


            $('#btn_search').click(function(){
                var search_value = $("#input_search").val();
                var category_value = $("#select_category").val();
                window.location.href = "{{ url('tours') }}" + "/?keyword=" + search_value + "&category=" + category_value;
            });
        });

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
                    window.location.href = "{{ url('content') }}" + "/" + id + "/delete";
                }
            });
        }

        function viewItem(){
            var el_id = event.target.id;
            var index = el_id.replace("btn_view_index_", "");

            var data = {!! json_encode($data) !!};
            var data = data.data[index];

            $("#input_id").val(data.id);
            $("#input_order").val(data.order);
            $("#textarea_description").val(data.message);
            $("#input_file").removeAttr("required");

            $('#exampleModal').modal('show');
        }

        $('#exampleModal').on('hidden.bs.modal', function () {
            $("#input_file").prop('required',true);
            $("#textarea_description").val("");
            $("#label_file").html("Choose File");
            $("#input_id").val(null);
            $("#input_file").val(null);
            $("#input_order").val(null);
        })


    </script>
@endsection
