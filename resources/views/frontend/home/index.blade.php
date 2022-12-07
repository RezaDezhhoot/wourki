@extends('frontend.master')
@section('style')
    <title>وورکی | سامانه کسب و کار</title>
    <link rel="stylesheet" href="{{ url()->to('/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/slick/slick-theme.css') }}">
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

    <div class="featured_area last-today-submitted-stores">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <section>
                        <h2 class="text-center">آخرین فروشگاه های ثبت شده</h2>
                        <a href="{{ route('list.stores') }}" class="see-more-stores-button sell-all-stores-in-homepage">
                            <i class="fa fa-ellipsis-h"></i>
                            مشاهده همه فروشگاه ها</a>
                        <div class="today-submitted-stores-wrapper">
                            <div class="row">
                                @foreach($lastStoresCreate as $store)
                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="today-submitted-store">
                                            <a href="{{ route('show.store' , $store->user_name) }}">
                                                @if($store->photo)
                                                    <div class="cover-photo"
                                                         style="background-image: url('{{ url()->to('/image/store_photos/') }}/{{ $store->photo->photo_name }}')">
                                                        @else
                                                            <div class="cover-photo"
                                                                 style="background-image: url('{{ url()->to('/image/store.jpg') }}')">
                                                                @endif
                                                                @if($store->thumbnail_photo != null)
                                                                    <img src="{{ url()->to('/image/store_photos/') }}/{{ $store->thumbnail_photo }}"
                                                                         class="img-circle" alt="store thumbnail">
                                                                @else
                                                                    <img src="{{ url()->to('/image/logo.png') }}"
                                                                         class="img-circle" alt="store thumbnail">
                                                                @endif
                                                            </div>
                                                            <div class="store-info">
                                                                <h4>
                                                                    <a href="{{ route('show.store' , $store->user_name) }}">{{ str_limit($store->name , 35) }}</a>
                                                                </h4>
                                                                @if($store->rate != null)
                                                                    <div style="margin:10px auto;"
                                                                         class="text-center rateyo rateyo_store_{{ $store->id }}"></div>
                                                                @else
                                                                    <div style="margin:10px auto;"
                                                                         class="text-center rateyo null-rate"></div>
                                                                @endif
                                                                <p class="slowgun">{{ $store->slogan }}</p>
                                                            </div>
                                                    </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="featured_area vip-stores-box-container">{{--top stores--}}
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <section>
                        <div class="section_title">
                            <h3>
                                <i class="fas fa-certificate"></i>
                                فروشگاه های برتر</h3>
                            <span class="more">
                            <a href="{{ route('list.stores' , ['store' => 'top-stores']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه فروشگاه های برتر
                            </a>
                        </span>
                        </div>
                        <div class="featured_products without_tab vip-stores-container">
                            @foreach($topStores as $store)
                                <div class="col-xs-12">
                                    <div class="vip-store-item-wrapper">
                                        @if($store->photo)
                                            <div class="cover-photo-wrapper"
                                                 style="background-image: url('{{ url()->to('/image/store_photos/') }}/{{ $store->photo->photo_name }}')">
                                                @else
                                                    <div class="cover-photo-wrapper"
                                                         style="background-image: url('{{ url()->to('/image/store.jpg') }}')">
                                                        @endif
                                                        @if($store->thumbnail_photo != null)
                                                            <img src="{{ url()->to('/image/store_photos/') }}/{{ $store->thumbnail_photo }}"
                                                                 class="img-circle" alt="thumbnail image">
                                                        @else
                                                            <img src="{{ url()->to('/image/logo.png') }}"
                                                                 class="img-circle"
                                                                 alt="store thumbnail">
                                                        @endif
                                                    </div>
                                                    <div class="store-item-info">
                                                        <h4 class="text-center"><a
                                                                    href="{{ route('show.store' , $store->user_name) }}">{{ str_limit($store->name , 27) }}</a>
                                                        </h4>
                                                        @if($store->rate != null)
                                                            <div style="margin:10px auto;"
                                                                 class="text-center rateyo rateyo_store1_{{ $store->id }}"></div>
                                                        @else
                                                            <div style="margin:10px auto;"
                                                                 class="text-center rateyo null-rate"></div>
                                                        @endif
                                                        <p class="text-center">{{ str_limit($store->slogan , 42) }}</p>
                                                    </div>
                                            </div>
                                    </div>
                                    @endforeach
                                </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>{{--top stores--}}
    @if(count($underBestStores) > 0 )
        <div class="featured_area">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div dir="rtl" class="slick-carousel ads-carousel rtl">
                            @foreach($underBestStores as $ad)
                                <a href="{{ $ad->link_type == 'product' ? route('show.product.seller' , $ad->product_id) : route('show.store' , $ad->user_name) }}">
                                    <div>

                                        <img class="ads-img" src="{{ url()->to('/image/ads/') . '/' . ($ad->final_pic != null ? $ad->final_pic : $ad->pic) }}"
                                             alt="تبلیغ">
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(count($vipProducts) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>محصولات پیشنهاد وورکی</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'product' ,'orderBy' => 'vip']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه محصولات ویژه
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($vipProducts as $product)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($product->photo)
                                                    <a href="{{ route('show.product.seller' , $product->id) }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                                alt="{{ $product->name }}"></a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $product->id) }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/logo.png') }}" alt=""></a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $product->id) }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $product->name }}">{{ str_limit($product->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $product->hint }}--}}
                                                    </span>
                                                @if($product->discount > 0 )
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                @endif
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($product->discount == 0)
                                                        <span class="new-price">{{ number_format($product->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($product->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($product->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $product->id) }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button"><span>مشاهده محصول</span>
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--vip prodcucts--}}
    @endif
    @if(count($vipServices) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>

                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($vipServices as $service)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($service->photo)
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $service->photo }}"
                                                                alt="{{ $service->name }}"></a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/logo.png') }}" alt=""></a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $service->name }}">{{ str_limit($service->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $service->hint }}--}}
                                                    </span>
                                                @if($service->discount > 0 )
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right">{{ $service->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                @endif
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($service->discount == 0)
                                                        <span class="new-price">{{ number_format($service->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($service->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($service->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button"><span>مشاهده خدمت</span>
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--vip prodcucts--}}
    @endif
    @if(count($underWourkiOffers) > 0 )
        <div class="featured_area">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="slick-carousel ads-carousel">
                            @foreach($underWourkiOffers as $ad)
                                <a href="{{ $ad->link_type == 'product' ? route('show.product.seller' , $ad->product_id) : route('show.store' , $ad->user_name) }}">
                                    <div>
                                        <img class="ads-img" src="{{ url()->to('/image/ads/') . '/' . ($ad->final_pic != null ? $ad->final_pic : $ad->pic)  }}"
                                             alt="تبلیغ">
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(count($newProducts) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>جدید ترین محصولات</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'product' , 'orderBy' => 'newest']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه جدید ترین محصولات
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($newProducts as $product)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($product->photo)
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                             alt="{{ $product->name }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/logo.png') }}" class="img-circle"
                                                             alt="product thumbnail">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $product->name }}">{{ str_limit($product->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $product->hint }}--}}
                                                    </span>
                                                <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($product->discount == 0)
                                                        <span class="new-price">{{ number_format($product->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($product->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($product->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                    <span>مشاهده محصول</span>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--newest products--}}
    @endif
    @if(count($newServices) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>جدید ترین خدمات</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'service' , 'orderBy' => 'newest']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه جدید ترین خدمات
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($newServices as $service)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($service->photo)
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $service->photo }}"
                                                             alt="{{ $service->name }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/logo.png') }}" class="img-circle"
                                                             alt="service thumbnail">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $service->name }}">{{ str_limit($service->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $service->hint }}--}}
                                                    </span>
                                                <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $service->discount == 0 ? 'discount-0' : '' }}">{{ $service->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($service->discount == 0)
                                                        <span class="new-price">{{ number_format($service->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($service->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($service->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                    <span>مشاهده خدمت</span>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--newest services--}}
    @endif
    @if(count($underLatestProducts) > 0 )
        <div class="featured_area">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="slick-carousel ads-carousel">
                            @foreach($underLatestProducts as $ad)
                                <a href="{{ $ad->link_type == 'product' ? route('show.product.seller' , $ad->product_id) : route('show.store' , $ad->user_name) }}">
                                    <div>
                                        <img class="ads-img" src="{{ url()->to('/image/ads/') . '/' . ($ad->final_pic != null ? $ad->final_pic : $ad->pic) }}"
                                             alt="تبلیغ">
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(count($hasDiscountProducts) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>حراجی های محصول وورکی</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'product' ,'orderBy' => 'cut-rate']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه محصولات حراجی ها
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($hasDiscountProducts as $product)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($product->photo)
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                                alt="{{ $product->name }}"></a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/logo.png') }}" alt=""></a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $product->name }}">{{ str_limit($product->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $product->hint }}--}}
                                                    </span>
                                                @if($product->discount > 0 )
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                @endif
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($product->discount == 0)
                                                        <span class="new-price">{{ number_format($product->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($product->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($product->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button"><span>مشاهده محصول</span><i
                                                            class="fa fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--vip prodcucts--}}
    @endif
    @if(count($hasDiscountServices) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>خدمات دارای تخفیف وورکی</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'service' ,'orderBy' => 'cut-rate']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه خدمات دارای تخفیف

                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($hasDiscountServices as $service)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class=product_image">
                                                @if($service->photo)
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $service->photo }}"
                                                                alt="{{ $service->name }}"></a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link"><img
                                                                src="{{ url()->to('/image/logo.png') }}" alt=""></a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $service->name }}">{{ str_limit($service->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $service->hint }}--}}
                                                    </span>
                                                @if($service->discount > 0 )
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right">{{ $service->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                @endif
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($service->discount == 0)
                                                        <span class="new-price">{{ number_format($service->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($service->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($service->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button"><span>مشاهده خدمت</span><i
                                                            class="fa fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--vip prodcucts--}}
    @endif
    @if(count($underWourkiDiscount) > 0 )
        <div class="featured_area">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="slick-carousel ads-carousel">
                            @foreach($underWourkiDiscount as $ad)
                                <a href="{{ $ad->link_type == 'service' ? route('show.product.seller' , $ad->service_id) : route('show.store' , $ad->user_name) }}">
                                    <div>
                                        <img class="ads-img" src="{{ url()->to('/image/ads/') . '/' . ($ad->final_pic != null ? $ad->final_pic : $ad->pic)  }}"
                                             alt="تبلیغ">
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(count($highVisitedProducts) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>پربازدیدترین محصولات</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'product' , 'orderBy' => 'high-visited']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه پربازدیدترین محصولات
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($highVisitedProducts as $product)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($product->photo)
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                             alt="{{ $product->name }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/logo.png') }}" class="img-circle"
                                                             alt="product thumbnail">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $product->name }}">{{ str_limit($product->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $product->hint }}--}}
                                                    </span>
                                                <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($product->discount == 0)
                                                        <span class="new-price">{{ number_format($product->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($product->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($product->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                    <span>مشاهده محصول</span>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--high visited products--}}
    @endif
    @if(count($highVisitedServices) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>پربازدیدترین خدمات</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'service' , 'orderBy' => 'high-visited']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه پربازدیدترین خدمات
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($highVisitedServices as $service)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($service->photo)
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $service->photo }}"
                                                             alt="{{ $service->name }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/logo.png') }}" class="img-circle"
                                                             alt="service thumbnail">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $service->name }}">{{ str_limit($service->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $service->hint }}--}}
                                                    </span>
                                                <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $service->discount == 0 ? 'discount-0' : '' }}">{{ $service->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($service->discount == 0)
                                                        <span class="new-price">{{ number_format($service->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($service->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($service->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                    <span>مشاهده خدمت</span>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--high visited services--}}
    @endif
    @if(count($underMostViewedProducts) > 0 )
        <div class="featured_area">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="slick-carousel ads-carousel">
                            @foreach($underMostViewedProducts as $ad)
                                <a href="{{ $ad->link_type == 'product' ? route('show.product.seller' , $ad->product_id) : route('show.store' , $ad->user_name) }}">
                                    <div>
                                        <img class="ads-img" src="{{ url()->to('/image/ads/') . '/' . ($ad->final_pic != null ? $ad->final_pic : $ad->pic)  }}"
                                             alt="تبلیغ">
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(count($highSaleProducts) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>پرفروش ترین محصولات</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'product' , 'orderBy' => 'high-sales']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه پرفروش ترین محصولات
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($highSaleProducts as $product)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($product->photo)
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $product->photo }}"
                                                             alt="{{ $product->name }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/logo.png') }}" alt="">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $product->name }}">{{ str_limit($product->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $product->hint }}--}}
                                                    </span>
                                                <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $product->discount == 0 ? 'discount-0' : '' }}">{{ $product->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($product->discount == 0)
                                                        <span class="new-price">{{ number_format($product->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($product->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($product->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $product->id) . ($product->marketer ? ('?code=' . $product->marketer) : '') }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                    <span>مشاهده محصول</span>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--high sales products--}}
    @endif
    @if(count($highSaleServices) > 0)
        <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>پرفروش ترین خدمات</h3>
                                <span class="more">
                            <a href="{{ route('products.list' , ['search_in' => 'service' , 'orderBy' => 'high-sales']) }}"
                               class="btn btn-xs see-more-stores-button">
                                <i class="fas fa-ellipsis-h"></i>
                                مشاهده همه پرفروش ترین خدمات
                            </a>
                        </span>
                            </div>
                            <div class="featured_products without_tab products-list-of-categories-carousel">
                                @foreach($highSaleServices as $service)
                                    <div class="col-xs-12">
                                        <div class="single_product product-item-container-in-homepage">
                                            <div class="product_image">
                                                @if($service->photo)
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/product_seller_photo/350/') }}/{{ $service->photo }}"
                                                             alt="{{ $service->name }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('show.product.seller' , $service->id) }}"
                                                       class="product_img_link">
                                                        <img src="{{ url()->to('/image/logo.png') }}" alt="">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="product_content">
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="product-name" style="height: 45px;"
                                                   title="{{ $service->name }}">{{ str_limit($service->name , 35) }}</a><br/>
                                                <span>
                                                        {{--<i class="fas fa-eye"></i>--}}
                                                    {{--{{ $service->hint }}--}}
                                                    </span>
                                                <div>
                                                    <span dir="rtl"
                                                          class="text-right discount-percent pull-right {{ $service->discount == 0 ? 'discount-0' : '' }}">{{ $service->discount }}
                                                        درصد تخفیف</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <p class="price">
                                                    <span class="title">قیمت:</span>
                                                    @if($service->discount == 0)
                                                        <span class="new-price">{{ number_format($service->price) }}
                                                            تومان </span>
                                                    @else
                                                        <del>{{ number_format($service->price) }} تومان</del>
                                                        <span class="new-price">{{ number_format($service->discountPrice) }}
                                                            تومان </span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('show.product.seller' , $service->id) }}"
                                                   class="btn btn-block btn-success btn-sm add-to-cart-button">
                                                    <span>مشاهده خدمت</span>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>{{--high sales services--}}
    @endif
    @if(count($discounts) > 0)
    <div class="featured_area best-seller-products-box-wrapper products-list-of-categories">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <section>
                            <div class="section_title">
                                <h3><i class="fas fa-list-ul"></i>تخفیف های فروشگاه ها</h3>
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
    <div class="service_area section-padding">
        <div class="container">
            <div class="row">
                <div class="service_wrap">
                    <div class="col-md-12">
                        <div class="service_bg">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="single_service">
                                        <i class="fas fa-shopping-basket"></i>
                                        <div class="service_info">
                                            <span class="txt2">تنوع محصولات</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="single_service">
                                        <i class="fas fa-credit-card"></i>
                                        {{--<i style="float: right;margin-left: 20px;color: #D50000;" class="fa fa-phone-square fa-5x" aria-hidden="true"></i>--}}
                                        <div class="service_info">
                                            <span class="txt1">پرداخت آنلاین</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 hidden-sm">
                                    <div class="single_service">
                                        <i class="fas fa-key"></i>
                                        {{--<i style="float: right;margin-left: 20px;color: #D50000;" class="fa fa-clock-o fa-5x" aria-hidden="true"></i>--}}
                                        <div class="service_info">
                                            <span class="txt1">امنیت در خرید</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 hidden-sm">
                                    <div class="single_service">
                                        <i class="fas fa-clock"></i>
                                        {{--<i style="float: right;margin-left: 20px;color: #D50000;" class="fa fa-clock-o fa-5x" aria-hidden="true"></i>--}}
                                        <div class="service_info">
                                            <span class="txt1">72 ساعت ضمانت بازگشت</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 hidden-sm">
                                    <div class="single_service">
                                        <i class="fas fa-phone"></i>
                                        {{--<i style="float: right;margin-left: 20px;color: #D50000;" class="fa fa-clock-o fa-5x" aria-hidden="true"></i>--}}
                                        <div class="service_info">
                                            <span class="txt1">پشتیبانی آنلاین</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 hidden-sm">
                                    <div class="single_service">
                                        <i class="fas fa-handshake"></i>
                                        {{--<i style="float: right;margin-left: 20px;color: #D50000;" class="fa fa-clock-o fa-5x" aria-hidden="true"></i>--}}
                                        <div class="service_info">
                                            <span class="txt1">تماس مستقیم با فروشنده</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('style')
    <style>
        .more {
            margin-top: 10px;
            float: left;
            margin-left: 10px;
            margin-bottom: 10px;
        }
        .rtl{
            direction: rtl;
        }
    </style>
@endsection

@section('script')
    <script src="{{ url()->to('/slick/slick.min.js') }}"></script>
    <script>

        $('.owl-carousel').owlCarousel({
            loop: true,
            smartSpeed: 450,
            responsiveClass: true,
            responsiveRefreshRate: 10,
            items: 3,
        });

        $(function () {
            $(".null-rate").rateYo({
                starWidth: "15px",
                readOnly: true,
                rating: '0',
                ratedFill: '#FF9800'
            });

            @foreach($lastStoresCreate as $store)
            $(".rateyo_store_{{ $store->id }}").rateYo({
                starWidth: "15px",
                readOnly: true,
                rating: '{{ $store->rate }}',
                ratedFill: '#FF9800'
            });
            @endforeach

            @foreach($topStores as $store)
            $(".rateyo_store1_{{ $store->id }}").rateYo({
                starWidth: "15px",
                readOnly: true,
                rating: '{{ $store->rate }}',
                ratedFill: '#FF9800'
            });
            @endforeach
        });

        $('.slick-carousel').slick({
            dots : true,
            infinite: true,
            speed: 300,
            centerMode: false,
            variableWidth: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            rtl: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        $('#ensign-nivoslider a').click(function (e) {
            var $this = $(this);
            if ($this.attr('href') == '#' || $this.attr('href') == '') {
                e.preventDefault();
            }
        });
    </script>
    <script>
        $('.discount-container').click(function(){
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
@endsection