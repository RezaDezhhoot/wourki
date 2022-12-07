@extends('frontend.master')
@section('style')
    <link rel="stylesheet" href="{{ url()->to('/css/mightyslider.css') }}">
    <title>{{ $store->name }}</title>
    <style>
        .toast-close{
            float: right !important;
        }
        .store-panel-container{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        .store-panel{
            max-width: 400px;
            width: 50%;
            height: auto;
        }
        .conversation{
          font-weight: 300;
          color: white;
          background-color: #FC494C;
          border-radius: 40px;
          padding: 8px 32px;
          display: inline-block;
          margin-left: 5px;
        }
        .conversation:hover{
          color: white;
        }
        #chat-form{
        }
        .discount-box{
            margin-top : 20px;
            background-color: white;
            width : 100%;
            height : 100px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .right-part{
            width: 30%;
            background-color: #ddd;
            height : 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
        }
        .left-part{
            width: 70%;
            height : 100%;
            border-right: 3px #aaa dashed;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;

        }
        .tabs{
            background-color: white;
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center
        }
        .tab-item{
            background-color: white;
            padding : 10px 60px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .active{
            border-bottom: 4px solid #D32F2F;
        }
        #discountables {
            display: none;
        }
        .about-store{
            margin-bottom : 10px
        }

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="store-page-container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="store-name-and-info">
                                <div class="row">
                                    <div class="col-xs-12 col-md-2" style="text-align: center;">
                                        @if($store->thumbnail_photo != null)
                                            <img src="{{ url()->to('/image/store_photos/') }}/{{ $store->thumbnail_photo }}"
                                                 alt="عکس بندانگشتی فروشگاه" style="border-radius : 1000px" class="img-thumbnail store-thumbnail">
                                        @endif
                                    </div>
                                    <div class="col-xs-12 col-md-10">
                                        <h1>
                                            <a class="store-name" href="{{ url()->current() }}">{{ $store->name }}</a>
                                            <a style="cursor: pointer;" data-toggle="modal" data-target="#report-abuse"
                                               class="pull-left report">گزارش تخلف</a>
                                        </h1>
                                        
                                        <div class="rateyo"></div>
                                        <h2 class="slowgun">{{ $store->slogan }}</h2>
                                        @if($store->about && $store->about != "")
                                        <p class="about-store">{{ $store->about}}</p>
                                        @endif
                                        <p class="address"><i class="fas fa-map-marker-alt"></i>{{ $store->address }}
                                            @if($address->latitude && $address->longitude)
                                                <a href="#" onclick="window.resizeBy(1 , 1)" data-toggle="modal"
                                                   data-target="#store-location-on-map">مشاهده محل فروشگاه روی نقشه</a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($address->latitude && $address->longitude)
                        <div class="modal fade" tabindex="-1" role="dialog" id="store-location-on-map">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">محل فروشگاه روی نقشه</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="map-canvas" style="width:100%;height:300px;"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="modal fade" tabindex="-1" role="dialog" id="report-abuse">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">گزارش تخلف</h4>
                                </div>
                                <form action="{{ route('report.store') }}" id="report-abuse-form" method="post">
                                    {{ csrf_field() }}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="store_id" value="{{ $store->id }}">
                                            <label for="report-body">متن گزارش خود را بنویسید:</label>
                                            <textarea style="height:100px;" name="body" id="report-body" cols="30"
                                                      required rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-pink" id="report-btn">ذخیره</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="communication-ways">
                        <div class="col-xs-12">
                            <h3><i class="fas fa-address-card"></i> راه های ارتباطی</h3>
                            <div class="row" id="communication-way-container-box">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 text-center">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3">
                                                    <img src="{{ url()->to('/img/icons8-phone-64.png') }}"
                                                         alt="تلفن تماس" width="60px">
                                                </div>
                                                <div class="col-xs-12 col-sm-9 col-md-9">
                                                    <h5>تلفن فروشگاه</h5>
                                                    @if($store->phone_number_visibility != 'hide')
                                                        <p>{{ $store->phone_number }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 text-center">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3">
                                                    <img src="{{ url()->to('/img/svg/smartphone.svg') }}"
                                                         alt="تلفن تماس" width="60px">
                                                </div>
                                                <div class="col-xs-12 col-sm-9 col-md-9">
                                                    <h5>موبایل</h5>
                                                    <p>{{ $store->user->mobile }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 text-center">
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 text-center">
                                            <form action="{{route('chats.create')}}" method="POST" id="chat-form">
                                                {{ csrf_field() }}
                                            <a style="cursor: pointer;" id="start-chat"
                                               class="pull-left conversation">شروع گفت و گو</a>
                                            <input hidden name="type" value="store" />
                                            <input hidden name="id" value="{{$store->id}}" />
                                        </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="alert alert-danger pink-alert">
                                        وورکی تنها امنیت خرید هایی که مستقیم از درون ان انجام می شود را تضمین نموده و
                                        هیچگونه منفعت و تضمینی در قبال پرداخت و خرید مستقیم از فروشنده نخواهد داشت
                                    </div>
                                    <div class="store-panel-container">
                                            <img src="{{ url()->to('/image/store_photos/') }}/{{ optional($store->photo)->photo_name }}"
                                                 alt="تابلو فروشگاه" class="store-panel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(count($photos) > 0)
                        {{-- <div class="row">
                            <div class="col-xs-12 store-page-slider-area">
                                <div class="box page-slider">
                                    <div class="slider-wrapper" dir="ltr">
                                        <div class="slider mightyslider_modern_skin black">
                                            <div class="frame" data-mightyslider="width: 900,height: 400">
                                                <div class="slide_element">
                                                    @foreach($photos as $index =>  $photo)
                                                    @if($photo)
                                                        <div class="slide {{ $index == 0 ? 'active' : '' }}"
                                                             data-mightyslider="
                cover: '{{ url()->to('/image/store_photos') . '/' . $photo->photo_name }}',
                thumbnail: '{{ url()->to('/image/store_photos')  . '/' . $photo->photo_name }}'
            "></div>
                                                    @endif
                                                    @endforeach
                                                </div>
                                                <a class="mSButtons mSPrev"></a>
                                                <a class="mSButtons mSNext"></a>
                                            </div>
                                            <canvas width="160" height="160" class="slider-progress"></canvas>
                                            <div class="slider-thumbnail">
                                                <div>
                                                    <ul></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    @endif
                    <div class="row">
                        <div class="col-xs-12">
                            <form action="{{ url()->current() }}">
                                <section class="products-section">
                                    @if($store->store_type == "product")
                                    <h2 class="text-center">لیست محصولات فروشگاه</h2>
                                    @elseif($store->store_type == 'service')
                                    <h2 class="text-center">لیست خدمات فروشگاه</h2>
                                    @else
                                    <h2 class="text-center">لیست محصولات / خدمات فروشگاه</h2>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3 box-container">
                                            <div class="orderBy">
                                                <label for="orderBy">ترتیب مرتب سازی:</label>
                                                <select name="orderBy" id="orderBy" class="form-control">
                                                    <option {{ request()->orderBy == 'newest' ? 'selected' : '' }} value="newest">
                                                        جدیدترین ها
                                                    </option>
                                                    <option {{ request()->orderBy == 'popular' ? 'selected' : '' }} value="popular">
                                                        محبوب ترین ها
                                                    </option>
                                                    <option {{ request()->orderBy == 'lowest_price' ? 'selected' : '' }} value="lowest_price">
                                                        کمترین قیمت
                                                    </option>
                                                    <option {{ request()->orderBy == 'highest_price' ? 'selected' : '' }} value="highest_price">
                                                        بیشترین قیمت
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-3 box-container">
                                            <div class="priceRange">
                                                <label for="price_range">محدوده قیمت:</label>
                                                <div id="price-range" style="width:100%;"
                                                     class="price-filter-range"></div>
                                                <p class="text-center" id="range_text">
                                                    بین
                                                    <b class="min">{{ number_format($lowestPrice) }}</b>
                                                    تا
                                                    <b class="max">{{ number_format($highestPrice) }}</b>
                                                    تومان
                                                </p>
                                                <input type="hidden" name="min_price" id="min_price_in_filter"
                                                       value="{{ $lowestPrice }}">
                                                <input type="hidden" name="max_price" id="max_price_in_filter"
                                                       value="{{ $highestPrice }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-3 box-container"
                                             id="show_exists_product_box_container">
                                            <label for="show_exists_products">نمایش کالاهای موجود</label>
                                            <input type="checkbox" name="visible" id="show_exists_products"
                                                   class="switchery" {{ request()->visible == 'on' ? 'checked' : '' }}>
                                        </div>
                                        <div class="col-xs-12 col-md-3 box-container">
                                            <div class="form-group">
                                                <label for="category_in_filter">انتخاب دسته بندی</label>
                                                <select name="category" id="category_in_filter"
                                                        class="form-control">
                                                    <option selected value="all">:: بدون انتخاب ::</option>
                                                    @foreach($categories as $category)
                                                        <option {{ request()->category == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <button type="submit" class="btn btn-success btn-sm">اعمال فیلتر</button>
                                        </div>
                                    </div>
                                </section>
                            </form>

                            <section class="products-list-section">
                            <ul class="tabs">
                                <li class="tab-item active" id="products-tab">
                                    @if($store->store_type == "product")
                                    <span class="tab-link">محصولات</span>
                                    @else
                                    @if($store->store_type == "service")
                                    <span class="tab-link">خدمات</span>
                                    @else
                                    <span class="tab-link">محصولات / خدمات</span>
                                    @endif
                                    @endif
                                </li>
                                <li class="tab-item" id="discountable-tab">
                                    <span class="tab-link" >حراجی ها</span>
                                </li>
                                @if($store->store_type != 'market')
                                <li class="tab-item" id="discounts-tab">
                                    <span class="tab-link" >کد های تخفیف</span>
                                </li>
                                @endif
                            </ul>
                                <div class="row" id="append">
                                    @foreach($products as $product)
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <div class="product-box-container">
                                                <div class="product_image">
                                                    @if($product->photo)
                                                        <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                           class="product_img_link">
                                                            <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                                 alt="محصولات فروشگاه">
                                                        </a>
                                                    @else
                                                        <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                           class="product_img_link">
                                                            <img src="{{ url()->to('/image/logo.png') }}"
                                                                 alt="محصولات فروشگاه">
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="product_content">
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                       class="product-name"
                                                       style="height: 45px;">{{ $product->name }} (کد {{ $product->id }}
                                                        )</a><br/>
                                                    {{--<span><i class="fas fa-eye"></i>{{ $product->hint }}</span>--}}
                                                    <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <p class="price">
                                                        <span class="title">قیمت:</span>
                                                        @if($product->discount == 0)
                                                            <span class="new-price text-right">{{ number_format($product->discountPrice) }}
                                                                تومان</span>
                                                        @else
                                                            <del>{{ number_format($product->price) }} تومان</del>
                                                            <span class="new-price text-right">{{ number_format($product->discountPrice) }}
                                                                تومان</span>
                                                        @endif
                                                    </p>
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                       class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                        <span>مشاهده محصول</span>
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-xs-12 text-center">
                                        <button class="btn btn-sm load-more-products-in-store-page-via-ajax" type="button"
                                                id="view-more" data-page="2">
                                            <i class="fa fa-ellipsis-h"></i>مشاهده موارد بیشتر
                                            <i class="fa fa-refresh fa-spin" style="display:none;"
                                            id="load-more-products-ajax-loader"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row" id="discountables">
                                    @foreach($discountables as $product)
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <div class="product-box-container">
                                                <div class="product_image">
                                                    @if($product->photo)
                                                        <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                           class="product_img_link">
                                                            <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                                 alt="محصولات فروشگاه">
                                                        </a>
                                                    @else
                                                        <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                           class="product_img_link">
                                                            <img src="{{ url()->to('/image/logo.png') }}"
                                                                 alt="محصولات فروشگاه">
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="product_content">
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                       class="product-name"
                                                       style="height: 45px;">{{ $product->name }} (کد {{ $product->id }}
                                                        )</a><br/>
                                                    {{--<span><i class="fas fa-eye"></i>{{ $product->hint }}</span>--}}
                                                    <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <p class="price">
                                                        <span class="title">قیمت:</span>
                                                        @if($product->discount == 0)
                                                            <span class="new-price text-right">{{ number_format($product->discountPrice) }}
                                                                تومان</span>
                                                        @else
                                                            <del>{{ number_format($product->price) }} تومان</del>
                                                            <span class="new-price text-right">{{ number_format($product->discountPrice) }}
                                                                تومان</span>
                                                        @endif
                                                    </p>
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($store->store_type == 'market' ? ('?code=' . $store->id) : '') }}"
                                                       class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                        <span>مشاهده محصول</span>
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                    <div id="store-discounts" class="row" style="display: none">
                                        @foreach ($discounts as $discount)
                                        <div class="col-md-4 d-flex justify-content-center align-items-center discount-container" data-code="{{$discount->code}}" data-productid="{{$discount->product_seller_id ? $discount->product_seller_id : 0}}" style="cursor: pointer">
                                            <div class="discount-box">
                                                <div class="right-part">
                                                    <b style="font-size : 14px;color:red">
                                                        {{$discount->type == "percentage" ? $discount->percentage . ' درصد' : str($discount->percentage) . ' تومان'}}
                                                    </b>
                                                    <b style="font-size : 12px;">
                                                        تخفیف
                                                    </b>
                                                    <b style="font-size : 17px;">
                                                        {{$discount->name}}
                                                    </b>
                                                </div>
                                                <div class="left-part">
                                                    <div class="w-100" >
                                                        کد تخفیف : <span style="color : green">{{$discount->code}}</span>
                                                    </div>
                                                    <div class="w-100" style="color : darkorange">
                                                        اعتبار تا تاریخ :  {{\Morilog\Jalali\Jalalian::forge($discount->end_date)->format('%d %B %Y')}}
                                                    </div>
                                                    <div class="w-100" >
                                                        <b>{{'حداقل میزان خرید : '.$discount->min_price.' تومان'}}</b>
                                                    </div>
                                                    <div class="w-100" >
                                                        <b>{{$discount->max_price ? 'حداکثر میزان خرید : '.$discount->max_price.' تومان' : 'بدون سقف خرید'}}</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HbDPHxl75YCub0FIOxXroYRyRd_dm9U&language=fa&region=IR&libraries=places"></script> --}}
    <script src="{{ url()->to('/js/jquery.mobile.just-touch.js') }}"></script>
    <script src="{{ url()->to('/js/jquery.easing-1.3.pack.js') }}" type="text/javascript"></script>
    <script src="{{ url()->to('/js/tweenlite.js') }}" type="text/javascript"></script>
    <script src="{{ url()->to('/js/mightyslider.min.js') }}" type="text/javascript"></script>
    <script>
        $('.discount-container').click(function(){
            if($(this).data('productid') && $(this).data('productid') != 0){
                location.href = "{{url()->to('product')}}" + '/' + $(this).data('productid')
                return;
            }
            navigator.clipboard.writeText($(this).data('code'));
            Toastify({
                text: "کد تخفیف کپی شد !",
                duration: 3000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "#FC2A23",
                    direction : 'rtl'
                },
                onClick: function(){} // Callback after click
            }).showToast();
        });
    </script>
    <script>
        $('#start-chat').click(function(e){
            e.preventDefault();
            $('#chat-form').submit();
        });
        $('#report-btn').click(function (e) {
            e.preventDefault();
            @if(auth()->guard('web')->check())
            $('#report-abuse-form').submit();
            @else
            $('#report-abuse').modal('hide');
            swal('ناموفق!', 'جهت ثبت گزارش ابتدا ثبت نام یا وارد حساب خود شوید.', 'error');
            @endif
        });
        $('.tab-item').click(function(){
            $('.tab-item').removeClass('active')
            $(this).addClass('active');
            var id = $(this).attr('id');
            if(id == "discountable-tab"){
                $('#discountables').fadeIn(500);
                $('#append').hide();
                $('#store-discounts').hide();
            }else{
                if(id == "products-tab"){
                    $('#discountables').hide();
                    $('#append').fadeIn(500);
                    $('#store-discounts').hide();
                }
                else{
                    $('#discountables').hide();
                    $('#append').hide();
                    $('#store-discounts').fadeIn(500);
                }

            }
        });
        $('#view-more').click(function () {
            var $this = $(this);
            var viewMore = $('#view-more').attr('data-page');
            var appendRow = $('#append');
            var ajaxLoader = $('#load-more-products-ajax-loader');
            ajaxLoader.css('display', 'inline-block');
            $.ajax({
                type: 'get',
                url: '{{ route('show.store' , ['slug' => $store->user_name]) }}',
                data: {
                    'viewMore': viewMore,
                },

                success: function (response) {
                    if (response.length > 0) {
                        viewMore = Number(viewMore) + 1;
                        $('#view-more').attr('data-page', viewMore);
                        for (var i = 0; i < response.length; i++) {
                            var photo = response[i].photo ? '{{ url()->to('/image/product_seller_photo/350/') }}/' + response[i].photo : '{{ url()->to('/image/logo.png') }}';
                            var productUrl = "{{ url()->to('/product') }}/" + response[i].id;
                            var discount = response[i].discount;
                            var discountClass = (discount == 0 ? 'discount-0' : '');

                            if (discount == 0) {
                                var priceContent = '<span class="new-price text-right">' + response[i].discountPrice + 'تومان</span>';
                            } else {
                                var priceContent = '<del>' + response[i].price + 'تومان</del>';
                                priceContent += '<span class="new-price text-right">' + response[i].discountPrice + 'تومان</span>';
                            }

                            appendRow.append(
                                '<div class="col-xs-12 col-sm-6 col-md-3">' +
                                '<div class="product-box-container">' +
                                '<div class="product_image">' +
                                '<a href="' + productUrl + '"' +
                                ' class="product_img_link">' +
                                '<img src="' + photo + '" alt="محصول فروشگاه" />' +
                                '</a>' +
                                '</div>' +
                                '<div class="product_content">' +
                                '<a href="' + productUrl + '" class="product-name" style="height: 45px;">' + response[i].name + '</a></br>' +
                                '<div>' +
                                '<span dir="rtl" class="text-right discount-percent pull-right ' + discountClass + '"> ' + discount + ' درصد تخفیف </span>' +
                                '<div class="clearfix"></div>' +
                                '</div>' +
                                '<p class="price">' +
                                '<span class="title">قیمت</span>' +
                                priceContent +
                                '</p>' +
                                '<a href="' + productUrl + '" class="btn btn-block btn-success btn-sm add-to-cart-button">' +
                                '<span>مشاهده محصول</span>' +
                                '<i class="fa fa-eye"></i>' +
                                '</a>' +
                                '</div>' +
                                '</div>' +
                                '</div>')
                        }
                    } else {
                        $this.attr('disabled', 'disabled');
                    }
                    ajaxLoader.css('display', 'none');
                }
            });
        });
        // city.append('<option value=" ' + response[i].id + ' ">' + response[i].name + '</option>');
        var min = $('#min_price_in_filter').val();
        var max = $('#max_price_in_filter').val();
        $('#price-range').slider({
            range: true,
            orientation: "horizontal",
            min: parseInt(min),
            max: parseInt(max),
            values: [parseInt(min), parseInt(max)],
            step: 1000,
            slide: function (event, ui) {
                $('#min_price_in_filter').val(ui.values[0]);
                $('#max_price_in_filter').val(ui.values[1]);
                $('#range_text .min').html(ui.values[0]);
                $('#range_text .max').html(ui.values[1]);
            }
        });

        function percentToValue(percent, total) {
            return parseInt((total / 100) * percent);
        }

        function degreeToRadian(degree) {
            return ((degree - 90) * Math.PI) / 180;
        }

        jQuery(document).ready(function ($) {
            var $win = $(window),
                isTouch = !!('ontouchstart' in window),
                clickEvent = isTouch ? 'tap' : 'click';

            (function () {
                // Global slider's DOM elements
                var $example = $('.store-page-container .page-slider .slider-wrapper .slider'),
                    $frame = $('.frame', $example),
                    $slides = $('.slide_element', $frame).children(),
                    $thumbnailsBar = $('div.slider-thumbnail ul', $example),
                    $timerEL = $('canvas', $example),
                    ctx = $timerEL[0] && $timerEL[0].getContext("2d"),
                    slideSize = '70%',
                    lastIndex = -1;

                /**
                 * Draw arc on canvas element
                 *
                 * @param {Number}   angle
                 *
                 * @return {Void}
                 */
                function drawArc(angle) {
                    var startingAngle = degreeToRadian(0),
                        endingAngle = degreeToRadian(angle),
                        size = 160,
                        center = size / 2;

                    //360Bar
                    ctx.clearRect(0, 0, size, size);
                    ctx.beginPath();
                    ctx.arc(center, center, center - 4, startingAngle, endingAngle, false);
                    ctx.lineWidth = 8;
                    ctx.strokeStyle = "#E91E63";
                    ctx.lineCap = "round";
                    ctx.stroke();
                    ctx.closePath();
                }

                // Calling mightySlider via jQuery proxy
                $frame.mightySlider({
                        speed: 1500,
                        startAt: 2,
                        autoScale: 1,
                        easing: 'easeOutExpo',

                        // Navigation options
                        navigation: {
                            slideSize: slideSize,
                            keyboardNavBy: 'slides',
                            activateOn: clickEvent
                        },

                        // Thumbnails options
                        thumbnails: {
                            thumbnailsBar: $thumbnailsBar,
                            thumbnailNav: 'forceCentered',
                            activateOn: clickEvent,
                            scrollBy: 0
                        },

                        // Dragging options
                        dragging: {
                            mouseDragging: 0,
                            onePage: 1
                        },

                        // Buttons options
                        buttons: !isTouch ? {
                            prev: $('a.mSPrev', $frame),
                            next: $('a.mSNext', $frame)
                        } : {},

                        // Cycling options
                        cycling: {
                            cycleBy: 'slides'
                        }
                    },

                    // Register callbacks to the events
                    {
                        // Register mightySlider :active event callback
                        active: function (name, index) {
                            if (lastIndex !== index) {
                                // Hide the timer
                                $timerEL.stop().css({opacity: 0});

                                // Remove next and previous classes from the slides
                                $slides.removeClass('next_1 next_2 prev_1 prev_2');

                                // Detect next and prev slides
                                var next1 = this.slides[index + 1],
                                    next2 = this.slides[index + 2],
                                    prev1 = this.slides[index - 1],
                                    prev2 = this.slides[index - 2];

                                // Add next and previous classes to the slides
                                next1 && $(next1.element).addClass('next_1');
                                next2 && $(next2.element).addClass('next_2');
                                prev1 && $(prev1.element).addClass('prev_1');
                                prev2 && $(prev2.element).addClass('prev_2');
                            }

                            lastIndex = index;
                        },

                        // Register mightySlider :moveEnd event callback
                        moveEnd: function () {
                            // Reset cycling progress time elapsed
                            this.progressElapsed = 0;
                            // Fade in the timer
                            $timerEL.animate({opacity: 1}, 800);
                        },

                        // Register mightySlider :progress event callback
                        progress: function (name, progress) {
                            // Draw circle bar timer based on progress
                            drawArc(360 - (360 / 1 * progress));
                        },

                        // Register mightySlider :initialize and :resize event callback
                        'initialize resize': function (name) {
                            var self = this,
                                frameSize = self.relative.frameSize,
                                slideSizePixel = percentToValue(slideSize.replace('%', ''), frameSize),
                                remainedSpace = (frameSize - slideSizePixel),
                                margin = (slideSizePixel - remainedSpace / 0.5) / 2;

                            // Sets slides margin
                            $slides.css('margin', '0 -' + margin + 'px');
                            // Reload immediate
                            self.reload(1);
                        }
                    });
            })();
        });

                @if($address && $address->latitude && $address->longitude)
        var center = {
                lat: {{ $address->latitude }},
                lng: {{ $address->longitude }}
            };
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: center,
            zoom: 13
        });
        marker = new google.maps.Marker({
            position: center,
            map: map
        });
        @endif

        $(".rateyo").rateYo({
            starWidth: "15px",
            readOnly: false,
            rating: '{{ $store->rate }}',
            ratedFill: '#FF9800',

            onSet: function (rating, rateYoInstance) {
                        @if(auth()->guard('web')->check())
                var rate = rating;
                var store_id = '{{ $store->id }}';

                $.ajax({
                    url: '{{ route('set.rate.store') }}',
                    type: 'post',
                    data: {
                        'rate': rate,
                        'store_id': store_id,
                        '_token': '{{ csrf_token() }}'
                    },

                    success: function () {
                        swal('موفقیت آمیز.', 'ارزیابی شما از این فروشگاه با موفقیت ثبت شد با تشکر از شما.', 'success');
                    }
                });
                @else
                swal('ناموفق!', 'جهت ارزیابی فروشگاه ابتدا ثبت نام یا وارد حساب خود شوید.', 'error');
                @endif
            }
        });
    </script>
@endsection