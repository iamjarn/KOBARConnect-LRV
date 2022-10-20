{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
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
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" value="{{ $keyword }}" id="input_search" class="form-control" placeholder="Search..." id="kt_datatable_search_query"/>
                                    <span>
                                        <i class="flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block">Category:</label>
                                    <select class="form-control select2" id="select_category">
                                        <option value="">Semua</option>
                                        @foreach($categories as $id => $value)
                                            <option {{$category == $id ? 'selected' : ''}} value="{{ $id }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button id="btn_search" class="btn btn-light-primary px-6 font-weight-bold">Search</button>
                            <div class="ml-5">
                                <a href="{{route('create_tour')}}" class="btn btn-light-success px-6 font-weight-bold">Buat Baru</a>
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
                                        $col_count = 4;
                                        $row_count = ceil(count($data) / $col_count);
                                        $index_item = 0;
                                    ?>
                                    @for($row_index = 0; $row_index < $row_count; $row_index ++)
                                        <div class="row">
                                            @for($i = 0; $i < $col_count; $i ++)
                                                <!--begin::Col-->
                                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-custom gutter-b card-stretch">
                                                        <!--begin::Body-->
                                                        <div class="card-body pt-4">
                                                            <!--begin::User-->
                                                            <div class="d-flex align-items-end mb-5">
                                                                <!--begin::Pic-->
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Pic-->
                                                                    <div class="mt-lg-3 mt-3">
                                                                        <img src="{{ asset($data[$index_item]->images[0]->path ?? config('constant.IMAGE_DEFAULT_PATH')) }}" width="100%"; class="rounded" alt="...">
                                                                    </div>
                                                                    <!--end::Pic-->
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                            <!--end::User-->
                                                            <!--begin::Title-->
                                                                <div class="d-flex flex-column mb-5">
                                                                    <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">{{$data[$index_item]->name ?? "kosong"}}</a>
                                                                    <span class="text-muted font-weight-bold">{{$data[$index_item]->category->name ?? ""}}</span>
                                                                </div>
                                                                <!--end::Title-->
                                                            <!--begin::Desc-->
                                                            <p style=" -webkit-line-clamp: 5;
                                                            display: -webkit-box;
                                                            -webkit-box-orient: vertical;
                                                            overflow: hidden;">{{$data[$index_item]->description ?? "kosong"}}</p>
                                                            <!--end::Desc-->
                                                            <a href="{{route('edit_tour', ['id' => $data[$index_item]->id])}}" class="btn btn-block btn-sm btn-light-success font-weight-bolder text-uppercase py-4">Detail Wisata</a>
                                                            <button id="btn_delete_{{$data[$index_item]->id}}" onclick="deleteItem()"  class="btn btn-block btn-sm btn-light-danger font-weight-bolder text-uppercase py-4">Delete Wisata</button>
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
                                                    <a href="{{ $data->url(1) .'&keyword='.$keyword.'&category='.$category }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->currentPage() == 1) ? ' disabled' : '' }}">
                                                        <i class="ki ki-bold-double-arrow-back icon-xs"></i>
                                                    </a>
                                                </li>

												<a href="{{ $data->previousPageUrl().'&keyword='.$keyword.'&category='.$category }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->onFirstPage()) ? ' disabled' : '' }}">
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
												            <a href="{{ $data->url($i).'&keyword='.$keyword.'&category='.$category }}" class="btn btn-icon btn-sm border-0 btn-hover-primary {{ ($data->currentPage() == $i) ? ' active' : '' }} mr-2 my-1">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endfor
                                                <a href="{{ $data->nextPageUrl().'&keyword='.$keyword.'&category='.$category }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
													<i class="ki ki-bold-arrow-next icon-xs"></i>
												</a>
                                                <li >
                                                    <a href="{{ $data->url($data->lastPage()).'&keyword='.$keyword.'&category='.$category }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
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

@endsection

{{-- Styles Section --}}
@section('styles')

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $("#select_category").val("{{$category}}").change();

            $('#btn_search').click(function(){
                var search_value = $("#input_search").val();
                var category_value = $("#select_category").val();
                window.location.href = "{{ url('tours') }}" + "/?keyword=" + search_value + "&category=" + category_value;
            })
        });

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
                    window.location.href = "{{ url('tour') }}" + "/" + id + "/delete";
                }
            });
        }
    </script>
@endsection
