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
                <h3 class="card-label">{{ $page_type == "CREATE" ? 'Buat Data Lokasi' : 'Edit Data Lokasi' }}
                    <div class="text-muted pt-2 font-size-sm">{{ $page_type == "CREATE" ? 'Lokasi apa yang ingin Anda tambahkan?' : 'Di bagian mana yang ingin Anda perbarui?' }}</div>
                </h3>
            </div>
        </div>
        <hr>
        <!-- Start Form -->
        <form action="{{ $page_type == 'CREATE' ? route('store_tour') : route('update_tour', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Start Body -->
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Wisata
                    <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Nama Wisata" value="{{ $data->name ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="exampleTextarea">Deskripsi Wisata
                    <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" id="descriptionTextarea" rows="3" spellcheck="false">{{ $data->description ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Latitude
                    <span class="text-danger">*</span></label>
                    <input type="number" step="any" name="latitude" class="form-control" placeholder="Latitude" value="{{ $data->latitude ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Longitude
                    <span class="text-danger">*</span></label>
                    <input type="number" step="any" name="longitude" class="form-control" placeholder="Longitude" value="{{ $data->longitude ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Category
                    <span class="text-danger">*</span></label>
                        <select class="form-control kt-select2 select2" name="id_category" id="category_select2" name="param">
                            @foreach($categories as $id => $name)
                                @if(isset($data))
                                    <option {{$data->id_category == $id ? 'selected' : ''}} value="{{ $id }}">{{$name}}</option>
                                @else
                                    <option value="{{ $id }}">{{$name}}</option>
                                @endif
                            @endforeach
                        </select>
                </div>
                <div class="form-group">
                    <label for="exampleTextarea">Alamat Wisata
                    <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="address" id="addressTextarea" rows="3" spellcheck="false">{{ $data->address ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Jam Buka</label>
                    <input type="text" name="operational_days" class="form-control" placeholder="Jam Buka Wisata" value="{{ $data->operational_days ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Harga Dewasa</label>
                    <input type="number" name="adult_prices" class="form-control" min="0" placeholder="Harga Dewasa" value="{{ $data->adult_prices ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Harga Anak-Anak</label>
                    <input type="number" name="kid_prices" class="form-control" min="0" placeholder="Harga Anak-Anak" value="{{ $data->kid_prices ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Biaya Parkir</label>
                    <input type="number" name="transport_prices" class="form-control" min="0" placeholder="Biaya Parkir" value="{{ $data->transport_prices ?? '' }}">
                </div>

                <label>Gambar Wisata</label>
                <div class="row">
                    @for($i = 0; $i < $image_count; $i++)
                        <div class="text-center ml-5">
                            <div class="image-input image-input-outline kt_image " id="kt_image_{{ $i }}">
                                <div id="image_wrapper_{{$i}}" class="image-input-wrapper" style="background-image: url( {{ isset($data->images[$i]) ? asset($data->images[$i]->path) : asset(config('constant.IMAGE_DEFAULT_PATH')) }});"></div>
                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Upload Gambar">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="images[{{ $i }}][file]" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" id="is_update_file_{{$i}}" name="images[{{ $i }}][is_update]" value="0">
                                    <input type="hidden" id="is_delete_file_{{$i}}" name="images[{{ $i }}][is_delete]" value="0">
                                    <input type="hidden" name="images[{{ $i }}][id]" value="{{ isset($data->images[$i]) ? $data->images[$i]->id : '' }}">
                                </label>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="" data-original-title="Batalkan">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>

                            @if($i != 0 && isset($data->images[$i]))
                                <br>
                                <button type="button" id="btn_delete_{{$i}}" class="btn btn-danger" onclick="deleteImage()">Hapus Foto</button>
                                <button type="button"  id="btn_restore_{{$i}}" style="display:none" class="btn btn-warning " onclick="restoreImage('{{ asset($data->images[$i]->path) }}')">Batalkan Hapus</button>
                            @elseif($i == 0)
                                <span class="form-text text-muted">Gambar Utama</span>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
            <!-- End Body -->
            <!-- Start Footer -->
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 ">
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
            <!-- End Footer -->
        </form>
        <!-- End Form -->
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
            initImageInput();
        });

        function initImageInput(){
            var count_image = '{{$image_count}}';
            for (let index = 0; index < count_image; index++) {
                new KTImageInput('kt_image_' + index)
                    .on('change', function (imageInput) {
                        $("#is_update_file_" + index).val("1");
                    })
                    .on('cancel', function (imageInput) {
                        $("#is_update_file_" + index).val("0");
                    });
            }
        }

        function deleteImage(){
            var id = event.target.id;
            var index = id.replace("btn_delete_", "");

            $("#btn_restore_"+index).css("display", "inline");
            $("#btn_delete_"+index).css("display", "none");
            $("#is_delete_file_"+index).val("1");

            $("#image_wrapper_"+index).css("background-image", "url({{asset(config('constant.IMAGE_DEFAULT_PATH'))}})");
        }

        function restoreImage(url){
            var id = event.target.id;
            var index = id.replace("btn_restore_", "");

            $("#btn_restore_"+index).css("display", "none");
            $("#btn_delete_"+index).css("display", "inline");
            $("#is_delete_file_"+index).val("0");

            $("#image_wrapper_"+index).css("background-image", "url('"+url+"')");
        }
    </script>
@endsection
