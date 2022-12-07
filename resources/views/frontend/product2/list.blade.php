@extends('frontend.master')
@section('style')
    @if(request()->filled('category'))
        @php
            $category = \App\Category::find(request()->input('category'));
        @endphp
        <title>وورکی | محصولات دسته {{ $category->name }}</title>
    @elseif(request()->filled('orderBy'))
        @if(request()->input('orderBy') == 'high-sales')
            <title>وورکی | پر فروش ترین ها</title>
        @elseif(request()->input('orderBy') == 'high-visited')
            <title>وورکی | پر بازدید ترین ها</title>
        @elseif(request()->input('orderBy') == 'newest')
            <title>وورکی | جدید ترین ها</title>
        @elseif(request()->input('orderBy') == 'cut-rate')
            <title>وورکی | حراجی های وورکی</title>
        @elseif(request()->input('orderBy') == 'vip')
            <title>وورکی | پیشنهاد های وورکی</title>
        @else
        <title>وورکی | جستجو در {{request()->search_in == 'product' ? 'محصولات' : 'خدمات' }}</title>
        @endif
        @else
        <title>وورکی | جستجو در {{request()->search_in == 'product' ? 'محصولات' : 'خدمات' }}</title>
    @endif
        <style>
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
    </style>
@endsection
@section('content')
    <div class="slider_area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="slider-area">
                        <div id="ensign-nivoslider" class="slides" style="margin-top: 30px">
                            @foreach($sliders as $slider)
                                <a style="width:100%"
                                   href="{{ $slider->link ? $slider->link : '' }}">
                                    <img class="img-responsive" src="{{ URL::to('/image/slider') }}/{{ $slider->pic }}"
                                         alt="{{ $slider->alt }}">
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- shop grid start -->
    <div class="grid_area all-products-list-page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="breadcrumb">
                        <a href="{{ route('mainPage') }}"><i class="fa fa-home"></i>خانه</a>

                        @if(request()->has('category'))
                            <span class="navigation-pipe"><i class="fa fa-angle-left"></i> دسته بندی : </span>
                            <a style="color: red;"
                               href="{{ route('products.list' , ['category' => request()->category]) }}">
                                {{ \App\Category::where('id' , request()->category)->first()->name }}
                            </a>
                        @endif
                        @if(request()->has('keyword'))
                            @if(request()->keyword != '')
                                <span class="navigation-pipe"><i class="fas fa-angle-left"></i></span>
                                <span style="color: #000;" class="navigation_page">جستجو : <a
                                            style="color: red;">{{ request()->keyword }}</a></span>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="all-products">
                        <h1 class="page-heading product-listing">
                            <span class="cat-name">نتایج جستجو</span>
                            <span class="heading-counter"><span class="heading-counter"><span
                                            style="color: red;">{{ $products->total() }}</span> مورد یافت شد.</span></span>
                        </h1>
                        <div class="content_sortPagiBar clearfix">
                            <div class="sortPagiBar clearfix">
                                <ul class="display" role="tablist">
                                    <li class="display-title">نمایش:</li>
                                    <li role="presentation" class="active"><a href="#grid" aria-controls="grid"
                                                                              role="tab" data-toggle="tab"><i
                                                    class="fa fa-th-large"></i>گرید</a></li>
                                    <li role="presentation"><a href="#list" aria-controls="list" role="tab"
                                                               data-toggle="tab"><i class="fa fa-th-list"></i>لیست</a>
                                    </li>
                                </ul>
                                <div class="shop-tab-selectors">
                                    <label for="selectProductSort">مرتب سازی بر اساس</label>
                                    <div class="selector" style="width: 190px;border-radius: 4px;float: right">
                                        <select class="form-control" id="selectProductSort">
                                            <option disabled selected>::انتخاب کنید::</option>
                                            <option {{ request()->orderBy == 'newest' ? 'selected' : '' }} value="newest">
                                                &nbsp;جدیدترین
                                            </option>
                                            <option {{ request()->orderBy == 'vip' ?  'selected' : '' }} value="vip">
                                                &nbsp;محصولات ویژه
                                            </option>
                                            <option {{ request()->orderBy == 'most-expensive' ? 'selected' : '' }} value="most-expensive">
                                                &nbsp;گرانترین
                                            </option>
                                            <option {{ request()->orderBy == 'cheapest' ? 'selected' : '' }} value="cheapest">
                                                &nbsp;ارزانترین
                                            </option>
                                            <option {{ request()->orderBy == 'high-sales' ? 'selected' : '' }} value="high-sales">
                                                &nbsp;پرفروش ترین
                                            </option>
                                            <option {{ request()->orderBy == 'high-visited' ? 'selected' : '' }} value="high-visited">
                                                &nbsp;پربازدیدترین
                                            </option>
                                            <option {{ request()->orderBy == 'cut-rate' ? 'selected' : '' }} value="cut-rate">
                                                &nbsp;حراجی ها
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab_container block_content">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="grid">
                                    <div class="shop_products_area">
                                        <div class="row">
                                            @if(count($products) > 0)
                                                @foreach($products as $product)
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="single_product product-item-container-in-list-page">
                                                            <div class="product_image">
                                                                @if($product->photo != null)
                                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                       class="product_img_link"><img
                                                                                src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                                                alt=""></a>
                                                                @else
                                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                       class="product_img_link"><img
                                                                                src="{{ url()->to('/image/logo.png') }}"
                                                                                alt=""></a>
                                                                @endif
                                                            </div>
                                                            <div class="product_content">
                                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                   class="product-name"
                                                                   style="height: 45px;font-weight: bold;"
                                                                   title="{{ $product->name }}">{{ $product->name }}</a>
                                                                <br/>
                                                                <div class="discount-container">
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                <div>
                                                                    <span class="pull-left">
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                                        {{--{{ $product->hint }}--}}
                                                    </span>
                                                                </div>
                                                                <p class="price">
                                                                    <span class="title">قیمت:</span>
                                                                    @if($product->discount == 0)
                                                                        <span class="new-price">{{ number_format($product->price) }}
                                                                            تومان </span>
                                                                    @else
                                                                        <del>{{ number_format($product->price) }}
                                                                            تومان
                                                                        </del>
                                                                        <span class="new-price">{{ number_format($product->discountPrice) }}
                                                                            تومان </span>
                                                                    @endif
                                                                </p>
                                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                                   @if($product->store_type == 'product')
                                                                    <span style="font-size: 12px;">مشاهده محصول</span>
                                                                    @else
                                                                    <span style="font-size: 12px;">مشاهده خدمت</span>
                                                                    @endif
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="alert alert-danger text-center">موردی یافت نشد</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="list">
                                    <ul class="product_list list row">
                                        @foreach($products as $product)

                                            <li class="ajax_block_product col-xs-12">
                                                <div class="product-container">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-5 col-md-4">
                                                            <div class="left-block">
                                                                @if($product->photo != null)
                                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                       class="product_img_link"><img
                                                                                src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                                                alt=""></a>
                                                                @else
                                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                       class="product_img_link"><img
                                                                                src="{{ url()->to('/image/logo.png') }}"
                                                                                alt=""></a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-7 col-md-8">
                                                            <div class="right-block">
                                                                <h5><a title="{{ $product->name }}"
                                                                       href="{{ route('singlePage' , \Cviebrock\EloquentSluggable\Services\SlugService::createSlug(\App\Products::class, 'slug', $product->name, ['unique' => true]))}}"
                                                                       class="product-name">{{ $product->name }}</a>
                                                                </h5>

                                                                @if($product->discount != 0)
                                                                    <div class="discount-container">
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                @endif
                                                                <div class="price-box">
                                                                    <p class="price">
                                                                        <span class="title">قیمت:</span>
                                                                        @if($product->discount == 0)
                                                                            <span class="new-price">{{ number_format($product->price) }}
                                                                                تومان </span>
                                                                        @else
                                                                            <del>{{ number_format($product->price) }}
                                                                                تومان
                                                                            </del>
                                                                            <span class="new-price">{{ number_format($product->discountPrice) }}
                                                                                تومان </span>
                                                                        @endif
                                                                    </p>
                                                                    <span class="pull-left">
                                                        <i class="fas fa-eye"></i>
                                                                        {{ $product->hint }}
                                                    </span>
                                                                    <div style="width: 100%;padding-top: 10px;"
                                                                         class="more-text ellipsis-rest-of-text">
                                                                        <h6>{!! $product->description !!}</h6>
                                                                    </div>
                                                                    <div class="button_content"
                                                                         style="margin-bottom: 10px;">
                                                                        <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                                           class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                                           @if($product->store_type == "product")
                                                                            <span>مشاهده محصول</span>
                                                                            @else
                                                                            <span>مشاهده خدمت</span>
                                                                            @endif
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(count($discounts) > 0)
    <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>{{request()->search_in == 'product' ? 'نخفیف های فروشگاه های محصولات' : (request()->search_in == 'service' ? 'تخفیف های فروشگاه های خدمات' : '')}}</h3>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach ($discounts as $discount)
                                <div class="col-xs-12 d-flex justify-content-center align-items-center discount-container" data-code="{{$discount->code}}" style="cursor: pointer;direction: rtl">
                                    <div class="discount-box">
                                        <div class="right-part">
                                            <b style="font-size : 13px;color:red">

                                                {{$discount->type == "percentage" ? $discount->percentage . ' درصد' : str($discount->percentage) . ' تومان'}}
                                            </b>
                                            <b style="font-size : 12px;">
                                                تخفیف
                                            </b>
                                            <b style="font-size : 15px;">
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
    @endif
@endsection

@section('script')
    <script>
        // $('.read-rest-of-test1').click(function (e) {
        //     e.preventDefault();
        //     var $this = $(this);
        //     var quickDesc = $this.closest('.right-block').find('.more-text');
        //     if (quickDesc.hasClass('ellipsis-rest-of-text')) {
        //         quickDesc.removeClass('ellipsis-rest-of-text');
        //         $this.html('کوتاه سازی مطلب');
        //     } else {
        //         quickDesc.addClass('ellipsis-rest-of-text');
        //         $this.html('ادامه مطلب...');
        //     }
        // });

        $(document).ready(function () {
            $('#selectProductSort').change(function () {
                var orderBy = $('#selectProductSort').val();
                if (orderBy == 'vip') {
                    @if(request()->has('category'))
                        location.href = '{!! route('products.list' , ['search_in' => 'product' , 'category' => request()->category , 'orderBy' => 'vip']) !!}';
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'keyword' => request()->keyword , 'orderBy' => 'vip']) !!}');
                    @else
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'orderBy' => 'vip']) !!}');
                    @endif
                }
                if (orderBy == 'cut-rate') {
                    @if(request()->has('category'))
                        location.href = '{!! route('products.list' , ['search_in' => 'product' , 'category' => request()->category , 'orderBy' => 'cut-rate']) !!}';
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'keyword' => request()->keyword , 'orderBy' => 'cut-rate']) !!}');
                    @else
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'orderBy' => 'cut-rate']) !!}');
                    @endif
                }
                if (orderBy == 'newest') {
                    @if(request()->has('category'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'category' => request()->category , 'orderBy' => 'newest']) !!}');
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'keyword' => request()->keyword , 'orderBy' => 'newest']) !!}');
                    @else
                    location.assign('{!! route('products.list' , ['search_in' => 'product' , 'orderBy' => 'newest']) !!}');
                    @endif
                }
                if (orderBy == 'high-sales') {
                    @if(request()->has('category'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'category' => request()->category , 'orderBy' => 'high-sales']) !!}');
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'keyword' => request()->keyword , 'orderBy' => 'high-sales']) !!}');
                    @else
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'orderBy' => 'high-sales']) !!}');
                    @endif
                }
                if (orderBy == 'high-visited') {
                    @if(request()->has('category'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'category' => request()->category , 'orderBy' => 'high-visited']) !!}');
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'keyword' => request()->keyword , 'orderBy' => 'high-visited']) !!}');
                    @else
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'orderBy' => 'high-visited']) !!}');
                    @endif
                }
                if (orderBy == 'most-expensive') {
                    @if(request()->has('category'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'category' => request()->category , 'orderBy' => 'most-expensive']) !!}');
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'keyword' => request()->keyword , 'orderBy' => 'most-expensive']) !!}');
                    @else
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'orderBy' => 'most-expensive']) !!}');
                    @endif
                }
                if (orderBy == 'cheapest') {
                    @if(request()->has('category'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'category' => request()->category , 'orderBy' => 'cheapest']) !!}');
                    @elseif(request()->has('keyword'))
                    location.assign('{!! route('products.list' , ['search_in' => 'product' ,'keyword' => request()->keyword , 'orderBy' => 'cheapest']) !!}');
                    @else
                    location.assign('{{ route('products.list' , ['search_in' => 'product' ,'orderBy' => 'cheapest']) }}');
                    @endif
                }

            });
        });

    </script>
@endsection

