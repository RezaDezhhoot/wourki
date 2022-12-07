@extends('admin.master')

@section('content')

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <form action="{{ route('saveProductsPhotoCreatePage' , $products->id) }}" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">

                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="photo" class="control-label">انتخاب عکس محصول</label>
                                    <input type="file" name="photo" class="js-fileinput img-upload filestyle" accept="image/jpeg,image/png,image" data-iconname="fa fa-cloud-upload">
                                    @if($errors->has('photo'))
                                        <b class="text-danger">{{ $errors->first('photo') }}</b>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn waves-effect waves-light btn-primary js-export img-export">ثبت</button>
                                    <a href="{{ route('listOfProducts') }}">
                                    <button type="button" class="btn waves-effect waves-light btn-success">بازگشت به لیست محصولات</button>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>


                </form>
                <div class="row">
                    @foreach($product_photos as $product_photo)
                        <div class="col-sm-2">
                            <img style="width: 100%;height: 160px;"
                                 src="{{ url()->to('image') }}/product_photos/{{ $product_photo->name }}" alt="image"
                                 class="img-responsive img-thumbnail" width="200"/>
                            <a href="{{ route('deleteProductPhoto' , $product_photo) }}">
                                <button style="position:absolute;top: 0;" type="button" class="btn btn-danger btn-xs">
                                    حذف
                                </button>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>





@endsection

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


@endsection