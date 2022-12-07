@extends('admin.master')
@section('styles')
    <style>
        table tbody th {
            font-size: 12px;
            font-weight: normal;
            color: #202020;
        }

        table thead th {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }
        h3 , label , span , b {font-size: 12px;font-family: IRANSans-web;font-weight: bolder;}
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            @if($errors->any())
                            <div class="alert alert-danger text-center">
                                <ul class="list-unstyled">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="row">
                                <h3 class="text-center" style="font-size:16px;color:#444;"><b> افزودن تصویر به محصول <span class="text-success">{{ $productSeller->name }}</span></b></h3>
                                <form action="{{ route('updateProductPhotos' , [$store , $product]) }}" method="post" enctype="multipart/form-data" id="form">
                                    {{ csrf_field() }}
                                    {{ method_field('put') }}
                                    <div class="form-group">
                                        <label class="control-label">تصویر 1</label>
                                        <input type="file" name="photo[]" id="photo" class="form-control input-sm">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">تصویر 2</label>
                                        <input type="file" name="photo[]" id="photo" class="form-control input-sm">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">تصویر 3</label>
                                        <input type="file" name="photo[]" id="photo" class="form-control input-sm">
                                    </div>
                                    <button class="btn btn-sm btn-default" type="submit">ارسال</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <h3 class="text-center" style="font-size:16px;color:#444;"><b> ویرایش تصاویر محصول <span class="text-success">{{ $productSeller->name }}</span></b></h3>
                                <br>
                                @foreach($productPhotos as $productPhoto)
                                    <div class="col-sm-2">
                                        <img style="width: 100%;height: 160px;" src="{{ url()->to('image') }}/product_seller_photo/{{ $productPhoto->file_name }}" alt="image" class="img-responsive img-thumbnail" width="200"/>
                                        <a style="position:absolute;top: 0;left: 10px;" class="btn btn-danger btn-xs photo" href="{{ route('deleteProductPhotos' , [$store , $productPhoto->id]) }}">حذف</a>
                                    </div>
                                @endforeach
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <a href="{{ route('listOfProductSeller' , $storess->user_name) }}" class="btn btn-sm btn-pinterest">بازگشت به فروشگاه</a>
                                    <a href="{{ route('showSingleProduct' , [$store , $product]) }}" class="btn btn-sm btn-facebook">بازگشت به محصول</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection
@section('scripts')

@endsection
