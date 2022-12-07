@extends('admin.master')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form action="{{ route('saveSlider') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="photo" class="control-label">انتخاب عکس اسلایدر</label>
                                            <input type="file" name="photo" required id="photo" class="filestyle" data-iconname="fa fa-cloud-upload">
                                            @if($errors->has('photo'))
                                                <b class="text-danger">{{ $errors->first('photo') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="alt" class="control-label">توضیحات عکس</label>
                                            <input class="form-control" autocomplete="off" type="text" value="{{ old('alt') }}" name="alt" id="alt">
                                            @if($errors->has('alt'))
                                                <b class="text-danger">{{ $errors->first('alt') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="slider_type" class="control-label">نمایش در</label>
                                            <select name="slider_type" id="slider_type" class="form-control">
                                                <option value="{{App\Slider::HOME}}">صفحه اصلی</option>
                                                <option value="{{App\Slider::PRODUCT}}">صفحه محصولات</option>
                                                <option value="{{App\Slider::STORE}}">صفحه فروشگاه ها</option>
                                                <option value="{{App\Slider::SERVICE}}">صفحه خدمات</option>
                                            </select>
                                            @if($errors->has('slider_type'))
                                                <b class="text-danger">{{ $errors->first('slider_type') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="link_to" class="control-label">لینکه به</label>
                                            <select name="link_to" id="link_to" class="form-control">
                                                <option value="none">هیچ کدام</option>
                                                <option value="product">محصول</option>
                                                <option value="store">فروشگاه</option>
                                            </select>
                                            @if($errors->has('link_to'))
                                                <b class="text-danger">{{ $errors->first('link_to') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="link_to_product">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="product_id" class="control-label">انتخاب محصول</label>
                                            <select name="product_id" id="product_id" class="form-control"></select>
                                            @if($errors->has('product_id'))
                                                <b class="text-danger">{{ $errors->first('product_id') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="link_to_store">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="store_id" class="control-label">انتخاب فروشگاه </label>
                                            <select name="store_id" id="store_id" class="form-control"></select>
                                            @if($errors->has('store_id'))
                                                <b class="text-danger">{{ $errors->first('store_id') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-facebook">ثبت</button>
                                            <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-sm btn-pinterest">انصراف</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@section('styles')

    <style>

    </style>
@endsection--}}

@section('scripts')
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"
            type="text/javascript"></script>

    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    {{--<script type="text/javascript" src="{{ url()->to('/admin') }}/assets/pages/autocomplete.js"></script>--}}

    <script>
        $('#store_id').select2({
            placeholder: 'جستجوی فروشگاه...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('stores.get_via_ajax.in_support_page') }}',
                dataType: 'json',
                data: function(params){
                    return {
                        q: params.term
                    };
                },
                processResults: function(data){
                    return {
                        results: data.map(function(dt){
                            return {
                                id: dt.id,
                                text: dt.name
                            };
                        })
                    }
                }
            }
        });

        $('#product_id').select2({
            placeholder: 'جستجوی محصول...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('getProductsListViaAjax') }}',
                dataType: 'json',
                data: function(params){
                    return {
                        q: params.term
                    };
                },
                processResults: function(data){
                    return {
                        results: data.map(function(dt){
                            return {
                                id: dt.id,
                                text: dt.name
                            };
                        })
                    }
                }
            }
        });
        $(document).ready(function () {
            var link_to = $('#link_to');
            if(link_to.val() == 'store'){
                $('#link_to_product').hide();
                $('#link_to_store').show();
            }else if(link_to.val() == 'product'){
                $('#link_to_product').show();
                $('#link_to_store').hide();
            }else{
                $('#link_to_product').hide();
                $('#link_to_store').hide();
            }
        });
        $('#link_to').change(function () {
            var $this = $(this);
            if($this.val() == 'store'){
                $('#link_to_product').hide();
                $('#link_to_store').show();
            }else if($this.val() == 'product'){
                $('#link_to_product').show();
                $('#link_to_store').hide();
            }else{
                $('#link_to_product').hide();
                $('#link_to_store').hide();
            }
        });
    </script>
@endsection