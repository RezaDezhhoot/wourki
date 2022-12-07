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

        .dropdown-menu li button {
            border-radius: 0;
        }

        .product-info span, .product-info a, .product-info b {
            font-size: 11px;
            font-weight: bold;
        }
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
                            <div class="row">
                                @foreach($productPhotos as $photo)
                                    <div class="col-sm-4">
                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $photo->file_name }}"
                                             class="img-thumbnail" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row product-info">
                                <div class="col-xs-6">
                                    <h3 class="text-center" style="font-size:16px;color:#444;"><b>مشخصات پایه محصول</b>
                                    </h3>
                                    <hr>
                                    <span>نام محصول :</span>
                                    <span style="color:#777">{{ $productInfo->product_seller_name }}</span><br>
                                    <span>توضیحات :</span>
                                    <span style="color:#777">{{ $productInfo->description }}</span><br>
                                    <span>دسته محصول :</span>
                                    <span style="color:#777">{{ $productInfo->category_name }}</span><br>
                                    <span>صنف :</span>
                                    <span style="color:#777">{{ $productInfo->guild_name }}</span><br>
                                    <span>نام فروشگاه :</span>
                                    <span style="color:#777">{{ $productInfo->store_name }}<a
                                                href="{{ route('listOfProductSeller' , $productInfo->user_name) }}"
                                                class="view-links">&nbsp;&nbsp;&nbsp; مشاهده فروشگاه </a>
                                    </span><br>
                                    <span style="color: #ff5a5f">کل فروش :</span>
                                    <span style="color:#777">0 تومان</span><br>
                                    <span>وضعیت نمایش:</span>
                                    @if($productInfo->status == 'deleted')
                                        <span class="text-danger"><b>عدم نمایش</b></span>
                                    @else
                                        @if($productInfo->visible == 1)
                                            <span class="text-success"><b>نمایان</b></span>
                                        @elseif($productInfo->visible == 0)
                                            <span class="text-danger"><b>عدم نمایش</b></span>
                                        @endif
                                    @endif

                                    <br>
                                    <span>وضعیت تایید:</span>
                                    @if($productInfo->status == 'approved')
                                        <span class="text-success"><b>تایید شده</b></span>
                                    @elseif($productInfo->status == 'rejected')
                                        <span class="text-warning"><b>رد شده</b></span>
                                    @elseif($productInfo->status == 'pending')
                                        <span class="text-info"><b>در انتظار تایید</b></span>
                                    @elseif($productInfo->status == 'deleted')
                                        <span class="text-danger"><b>حذف شده توسط کاربر</b></span>
                                    @endif
                                    <br>
                                    <span style="color: #ff5a5f">تعداد تصاویر:</span>
                                    <span style="color:#777">{{ count($productPhotos) }}</span>
                                    <a href="{{ route('editProductPhotos' , [$productInfo->user_name , $productInfo->id]) }}"
                                       class="view-links">&nbsp;&nbsp;&nbsp; ویرایش تصاویر</a>
                                    <br>
                                    <span style="color: #ff5a5f">تعداد نظرات ثبت شده:</span>
                                    <span style="color:#777">{{ count($productComments) }}</span>
                                    <a href="{{ route('productComment' , $productInfo->id) }}" class="view-links">&nbsp;&nbsp;&nbsp;
                                        مشاهده</a>
                                </div>
                                <div class="col-xs-6">
                                    <div class="col-md-offset-3">
                                        <h3 class="text-center" style="color:#444;font-size:16px;"><b>قیمت</b></h3>
                                        <hr>
                                        <span>قیمت خالص :</span>
                                        <span style="color:#777"><b class="text-danger">{{ $productInfo->price }}</b> تومان </span><br>
                                        <span>درصد تخفیف :</span>
                                        <span style="color:#777"><b class="text-danger">{{ $productInfo->discount }}</b> درصد </span><br>
                                        <span>قیمت تمام شده :</span>
                                        <span style="color:#777"><b
                                                    class="text-danger">{{ $productInfo->price - (($productInfo->price * $productInfo->discount) / 100) }}</b> تومان </span><br>
                                        <hr>
                                        <span>هزینه حمل به تهران:</span>
                                        <span><b class="text-primary">{{ $productInfo->shipping_price_to_tehran }} تومان</b></span><br>
                                        <span>هزینه حمل به شهرستان ها:</span>
                                        <span><b class="text-primary">{{ $productInfo->shipping_price_to_other_towns }} تومان</b></span><br>
                                        <span>زمان تحویل در تهران:</span>
                                        <span><b class="text-primary">{{ $productInfo->deliver_time_in_tehran }} روز</b></span><br>
                                        <span>زمان تحویل در شهرستان ها:</span>
                                        <span><b class="text-primary">{{ $productInfo->deliver_time_in_other_towns }} روز</b></span><br>

                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-5">
                                    <a href="{{ \Illuminate\Support\Facades\URL::previous() }}"
                                       class="btn btn-sm btn-pinterest">بازگشت به فروشگاه</a>
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
