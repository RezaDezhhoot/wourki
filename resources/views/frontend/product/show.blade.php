@extends('frontend.master')

@section('meta')
    <title>{{ $product->name }}</title>
    {{--<meta name="description" content="{{ str_limit($product->description , 300) }}">--}}
    @if(count($product->photos) > 0)
        <meta property="og:image" content="{{ $product->photos[0]->name }}"/>
    @endif
    <meta property="og:title" content="{{ $product->name }}"/>
    <meta property="og:description" content="{{ str_limit(htmlspecialchars(strip_tags($product->description)) , 300) }}"/>
@endsection

@section('content')
    <!-- single product start -->
    <div class="single_product_area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="breadcrumb">
                        <a href="{{ route('mainPage') }}"><i class="fa fa-home"></i>خانه</a>
                        <span class="navigation-pipe"><i class="fa fa-angle-left"></i></span>
                        <span style="color: #000;" class="navigation_page">دسته بندی : <a style="color: red;"
                                                                                          href="{{ route('listPage' , ['category' => $product->category_id]) }}">{{ $product->category_name }}</a></span>
                        <span class="navigation-pipe"><i class="fa fa-angle-left"></i></span>
                        <span style="color: #000;" class="navigation_page">{{ $product->name }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="primary_block">{{---------------------------------------------------------------------------------------------}}
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <div class="zoomWrapper" style="">
                            <div id="img-1" class="zoomWrapper single-zoom">
                                <a>
                                    @if(count($product->photos) > 0)
                                        <img id="zoom1" src="{{ $product->photos[0]->name }}"
                                             data-zoom-image="{{ $product->photos[0]->name }}"
                                             alt="{{ $product->photos[0]->name }}">
                                    @else
                                        <img id="zoom1"
                                             src="{{ url()->to('/image/product_photos/default-product.png') }}"
                                             data-zoom-image="{{ url()->to('/image/product_photos/default-product.png') }}"
                                             alt="">
                                    @endif
                                </a>
                            </div>
                            <div class="product-thumb row">
                                <ul class="p-details-slider" id="gallery_01">
                                    @if(count($product->photos) > 0)
                                        @foreach($product->photos as $photo)
                                            <li class="col-md-4">
                                                <a class="elevatezoom-gallery" href="#" data-image="{{ $photo->name }}"
                                                   data-zoom-image="{{ $photo->name }}"><img style="height: 90px;"
                                                                                             src="{{ $photo->name }}"
                                                                                             alt="{{ $photo->alt }}"></a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-7">{{---------------------------------------------------------desc------------------------------------------------------------}}
                        <div class="primary_block_details">
                            <blockquote style="padding: 20px 15px;margin-top: 0;">
                                <h1>{{ $product->name }}</h1>
                            </blockquote>

                            <div class="more-text ellipsis-rest-of-text">
                                <p>{!! $product->description !!}</p>
                            </div>

                            <p class="rest-text-wrapper">
                                <a class="read-rest-of-test1" href="#">ادامه مطلب...</a>
                            </p>

                            <div class="stock" style="margin-top: 20px;">

                                @if($product->quantity == null || $product->quantity > 0)
                                    <div class="stock_button">
                                        <a>موجود</a>
                                    </div>
                                @else
                                    <div style="background-color: darkred;" class="stock_button">
                                        <a>نا موجود</a>
                                    </div>
                                @endif
                            </div>
                            @if($product->discount != 0)
                                <div style="color: red;font-size: 20px;margin-top: 15px;font-weight: bold;">تخفیف
                                    %{{ $product->discount }}</div>
                            @endif
                            <div style="margin: 20px 0;">
                                <span style="font-size: 20px;" class="old-price product-price"> {{ number_format($product->total_price) }}
                                    تومان </span>
                                @if($product->discount != 0)
                                    <s style="font-size: 15px;" class="price">{{ number_format($product->price) }}
                                        تومان </s>
                                @endif
                            </div>
                            <div class="product_attributes clearfix">

                                <p style="display: none;" id="minimal_quantity_wanted_p">مقدار حداقل تعداد سفارش خرید
                                    برای محصول <b id="minimal_quantity_label">1</b> است</p>
                                <div id="attributes">
                                    <div class="clearfix"></div>
                                    <div class="button_content">
                                        <a href="{{ route('addToUserCart' , $product->id) }}" class="cart_button">افزودن
                                            به سبد</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                {{----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------}}


                <div class="col-lg-12 col-md-12 col-sm-12">

                    <h2 class="page-header" style="font-size: 20px;color: red">نظرات</h2>


                    @if(count($product->comments) > 0)

                        <section class="comment-list">
                            <!-- First Comment -->
                            @foreach($product->comments as $comment)
                                <article class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="panel panel-default arrow left">
                                            <div class="panel-body">
                                                <header class="text-right">
                                                    <div class="comment-user"><i class="fa fa-user"></i> ارسال شده
                                                        توسط {{ $comment->user_first_name }}
                                                        &nbsp;{{ $comment->user_last_name }}
                                                    </div>
                                                    <time class="comment-date" datetime="16-12-2014 01:05"><i
                                                                class="fa fa-clock-o"></i>ارسال شده در تاریخ
                                                        <u>{{ jdate($comment->created_at)->format('%B %d، %Y') }}</u>
                                                    </time>
                                                </header>
                                                <br>
                                                <div class="comment-post">
                                                    <p>{{ $comment->comment }}</p>
                                                    @if(auth()->guard('web')->check())
                                                        <div>
                                                            <button data-toggle="modal"
                                                                    data-target="#update-category-{{ $comment->id }}-modal"
                                                                    style="float: left;margin: 10px;"
                                                                    class="btn btn-default">پاسخ
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="text-right">
                                            </div>
                                        </div>
                                    </div>
                                </article>

                                @php
                                    $childComments = new \App\Comment();
                                    $childComments = $childComments->dbSelect(\App\Comment::FIELDS)
                                        ->where('comment.status', '=', 'approved')
                                        ->where('comment.parent_comment_id' , '=' , $comment->id)
                                        ->get();
                                @endphp
                            <!-- Second Comment Reply -->
                                @foreach($childComments as $parentComment)
                                    <article class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="panel panel-default arrow left">

                                                <div class="panel-heading right"
                                                     style="color: #fff;background-color: #000;"><i
                                                            class="fa fa-reply"></i>پاسخ
                                                </div>
                                                <div class="panel-body">
                                                    <header class="text-right">
                                                        <div class="comment-user"><i class="fa fa-user"></i> ارسال شده
                                                            توسط {{ $parentComment->user_first_name }}
                                                            &nbsp;{{ $parentComment->user_last_name }}
                                                        </div>
                                                        <time class="comment-date" datetime="16-12-2014 01:05"><i
                                                                    class="fa fa-clock-o"></i>ارسال شده در تاریخ
                                                            <u>{{ jdate($parentComment->created_at)->format('%B %d، %Y') }}</u>
                                                        </time>
                                                    </header>
                                                    <br>
                                                    <div class="comment-post">
                                                        <p>{{ $parentComment->comment }}</p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach

                                <div id="update-category-{{ $comment->id }}-modal" class="modal fade" tabindex="-1"
                                     role="dialog" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                                <h4 class="modal-title">پاسخ نظر</h4>
                                            </div>
                                            <form action="{{ route('addComment') }}" method="post">
                                                {{ csrf_field() }}
                                                {{--<input type="hidden" name="_method" value="PUT">--}}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="hidden" name="parent_comment_id"
                                                                   value="{{ $comment->id }}">
                                                            <input type="hidden" name="productId"
                                                                   value="{{ $product->id }}">
                                                            <div class="form-group">
                                                                <textarea class="form-control" style="max-width: 100%"
                                                                          name="comment" id="comment" cols="30"
                                                                          rows="10"
                                                                          placeholder="متن نظر را وارد نمایید..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-info waves-effect waves-light">
                                                        ثبت
                                                    </button>
                                                    <button type="button" class="btn btn-default waves-effect"
                                                            data-dismiss="modal">بستن
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </section>

                        @if(count($product->comments) > 0)
                            <div class="row">
                                <div class="col-xs-12">
                                    <ul class="pagination pagination-split">
                                        @if($product->comments->currentPage() != 1)
                                            <li>
                                                <a href="{{ $product->comments->previousPageUrl() }}"><i
                                                            class="fa fa-angle-left"></i></a>
                                            </li>
                                        @endif
                                        @for($i =1 ; $i <= $product->comments->lastPage() ; $i++)
                                            <li class="{{ $i == $product->comments->currentPage() ? 'active' : '' }}">
                                                <a href="{{ $product->comments->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        @if($product->comments->currentPage() != $product->comments->lastPage())
                                            <li>
                                                <a href="{{ $product->comments->nextPageUrl() }}"><i
                                                            class="fa fa-angle-right"></i></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif

                    @else
                        <div role="tabpanel" class="tab-pane" id="reviews">
                            <div id="product-comments-block-tab">
                                <a style="margin: 0;"
                                   class="comment-btn"><span>تاکنون نظری برای این محصول ثبت نشده است!</span></a>
                            </div>
                        </div>
                    @endif

                </div>


                {{----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------}}
                @if(auth()->guard('web')->check())
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="p-details-tab">
                            <h2 class="page-header" style="font-size: 20px;color: red">ثبت نظر</h2>
                        </div>
                        <div class="clearfix"></div>
                        <div class="tab-content review">
                            <div role="tabpanel" class="tab-pane active" id="reviews">
                                <div id="product-comments-block-tab">
                                    <form action="{{ route('addComment') }}" method="post">
                                        {{ csrf_field() }}
                                        @if($errors->has('comment'))
                                            <p class="text-danger">{{ $errors->first('comment') }}</p>
                                        @endif
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment" id="comment" cols="30"
                                                      rows="5" style="max-width: 100%;max-height: 200px;"
                                                      placeholder="متن نظر وارد نمایید..."></textarea>
                                        </div>
                                        <input type="hidden" name="productId" value="{{ $product->id }}">
                                        <div class="form-group">
                                            <button class="btn btn-default" type="submit">ارسال</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div role="tabpanel" class="tab-pane" id="reviews">
                        <div id="product-comments-block-tab">
                            <a href="{{ route('showLoginForm' , ['route' => url()->current()] ) }}" class="comment-btn"><span>اولین کسی باشید که نظر خود را می نویسید!</span></a>
                        </div>
                    </div>
                @endif
                {{----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------}}
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="featured_area">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="section_title">
                                        <h3><span class="angle"><i class="fa fa-pie-chart"></i></span>محصولات مرتبط</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="featured_products single">
                                    @foreach($similarProducts as $similarProduct)
                                        <div class="col-xs-12">
                                            <div class="single_product">
                                                <div class="product_image">
                                                    <a href="{{  route('singlePage' , \Cviebrock\EloquentSluggable\Services\SlugService::createSlug(\App\Products::class, 'slug', $similarProduct->name, ['unique' => true])) }}"
                                                       class="product_img_link">
                                                        <img src="{{ $similarProduct->first_photo }}" alt="">
                                                    </a>
                                                    {{--@if($similarProduct->discount != 0)
                                                        <a class="new-box"><span>{{ $similarProduct->discount }} % تخفیف</span></a>
                                                    @endif--}}
                                                    <a href="#" class="quick-view modal-view hidden-xs"
                                                       data-toggle="modal"
                                                       data-target="#productModal-{{ $similarProduct->id }}">
                                                        <i class="fa fa-eye"></i>مشاهده سریع
                                                    </a>
                                                </div>
                                                <div class="product_content">
                                                    <a href="{{  route('singlePage' , \Cviebrock\EloquentSluggable\Services\SlugService::createSlug(\App\Products::class, 'slug', $similarProduct->name, ['unique' => true])) }}"
                                                       class="product-name" style="height: 45px;"
                                                       title="{{ $similarProduct->name }}">{{ $similarProduct->name }}</a>
                                                    @if($similarProduct->discount != 0)
                                                        <div class="product-name" style="color: red;font-weight: bold;">
                                                            {{ $similarProduct->discount }} % تخفیف
                                                        </div>
                                                    @endif
                                                    <div class="price_box">
                                                        @if($similarProduct->discount != 0)
                                                            <span class="old-price product-price"> {{ number_format($similarProduct->price) }}
                                                                تومان </span>
                                                        @endif
                                                        <span class="price"> تومان {{ number_format($similarProduct->total_price) }}</span>
                                                    </div>
                                                    @if($similarProduct->discount != 0)
                                                        <div class="button_content"
                                                             @if($similarProduct->discount == 0) style="padding-top: 41px;" @endif>
                                                            <a @if(auth()->guard('web')->check()) style="width: 80%;margin: 11px 0 0;"
                                                               @else style="width: 100%;margin: 11px 0 0;"
                                                               @endif href="{{ route('addToUserCart' , $similarProduct->id) }}"
                                                               class="cart_button">افزودن به سبد خرید</a>
                                                            @if(auth()->guard('web')->check())
                                                                <a style="margin: 11px 5px 0 0;"
                                                                   href="{{ route('addToFav' , $similarProduct->id) }}"
                                                                   class="heart"><i class="fa fa-heart"></i></a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="button_content"
                                                             @if($similarProduct->discount == 0) style="padding-top: 41px;" @endif>
                                                            <a @if(auth()->guard('web')->check()) style="width: 80%;margin: 14px 0 0;"
                                                               @else style="width: 100%;margin: 14px 0 0;"
                                                               @endif href="{{ route('addToUserCart' , $similarProduct->id) }}"
                                                               class="cart_button">افزودن به سبد خرید</a>
                                                            @if(auth()->guard('web')->check())
                                                                <a href="{{ route('addToFav' , $similarProduct->id) }}"
                                                                   class="heart"><i class="fa fa-heart"></i></a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- featured end -->
                </div>

                <div style="margin-top: 25px;" class="col-lg-12 col-md-12 col-xs-12">
                    <div class="featured_area">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="section_title">
                                        <h3><span class="angle"><i class="fa fa-arrow-circle-down"></i></span>محصولات
                                            جدید</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="featured_products single">
                                    @foreach($newests as $newest)
                                        <div class="col-xs-12">
                                            <div class="single_product">
                                                <div class="product_image">
                                                    <a href="{{  route('singlePage' , \Cviebrock\EloquentSluggable\Services\SlugService::createSlug(\App\Products::class, 'slug', $newest->name, ['unique' => true])) }}"
                                                       class="product_img_link">
                                                        <img src="{{ $newest->first_photo }}" alt="">
                                                    </a>
                                                    {{--@if($newest->discount != 0)
                                                        <a class="new-box"><span>{{ $newest->discount }} % تخفیف</span></a>
                                                    @endif--}}
                                                    <a href="#" class="quick-view modal-view hidden-xs"
                                                       data-toggle="modal"
                                                       data-target="#productModal-{{ $newest->id }}">
                                                        <i class="fa fa-eye"></i>مشاهده سریع
                                                    </a>
                                                </div>
                                                <div class="product_content">
                                                    <a href="{{  route('singlePage' , \Cviebrock\EloquentSluggable\Services\SlugService::createSlug(\App\Products::class, 'slug', $newest->name, ['unique' => true])) }}"
                                                       class="product-name" style="height: 45px;"
                                                       title="{{ $newest->name }}">{{ $newest->name }}</a>
                                                    @if($newest->discount != 0)
                                                        <div class="product-name" style="color: red;font-weight: bold;">
                                                            {{ $newest->discount }} % تخفیف
                                                        </div>
                                                    @endif
                                                    <div class="price_box">
                                                        @if($newest->discount != 0)
                                                            <span class="old-price product-price"> {{ number_format($newest->price) }}
                                                                تومان </span>
                                                        @endif
                                                        <span class="price"> تومان {{ number_format($newest->total_price) }}</span>
                                                    </div>
                                                    @if($newest->discount != 0)
                                                        <div class="button_content"
                                                             @if($newest->discount == 0) style="padding-top: 41px;" @endif>
                                                            <a @if(auth()->guard('web')->check()) style="width: 80%;margin: 11px 0 0;"
                                                               @else style="width: 100%;margin: 11px 0 0;"
                                                               @endif href="{{ route('addToUserCart' , $newest->id) }}"
                                                               class="cart_button">افزودن به سبد خرید</a>
                                                            @if(auth()->guard('web')->check())
                                                                <a style="margin: 11px 5px 0 0;"
                                                                   href="{{ route('addToFav' , $newest->id) }}"
                                                                   class="heart"><i class="fa fa-heart"></i></a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="button_content"
                                                             @if($newest->discount == 0) style="padding-top: 41px;" @endif>
                                                            <a @if(auth()->guard('web')->check()) style="width: 80%;margin: 14px 0 0;"
                                                               @else style="width: 100%;margin: 14px 0 0;"
                                                               @endif href="{{ route('addToUserCart' , $newest->id) }}"
                                                               class="cart_button">افزودن به سبد خرید</a>
                                                            @if(auth()->guard('web')->check())
                                                                <a href="{{ route('addToFav' , $newest->id) }}"
                                                                   class="heart"><i class="fa fa-heart"></i></a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- featured end -->
                </div>
            </div>
        </div>
    </div>
    <!-- shop grid end -->

    <!-- QUICKVIEW PRODUCT -->
    @foreach($similarProducts as $similarProduct)
        <div id="quickview-wrapper">
            <!-- Modal -->
            <div class="modal fade productModal" id="productModal-{{ $similarProduct->id }}" tabindex="-1"
                 role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-product">
                                <div class="product-images">
                                    <div class="main-image images">
                                        <img src="{{ $similarProduct->first_photo }}" alt="">
                                    </div>
                                </div><!-- .product-images -->

                                <div class="product-info">
                                    <h1 style="cursor: pointer;"
                                        title="{{ $similarProduct->name }}">{{ $similarProduct->name }}</h1>
                                    <div class="price-box">
                                        <p class="price"><span class="special-price"><span class="amount">{{ $similarProduct->price }}
                                                    تومان</span></span></p>
                                    </div>
                                    <div class="quick-add-to-cart">
                                        <form method="post" class="cart">
                                            <div class="numbers-row">
                                                <input type="number" id="french-hens" value="3">
                                            </div>
                                            <button class="single_add_to_cart_button" type="submit">افزودن به سبد خرید
                                            </button>
                                        </form>
                                    </div>

                                    <div class="quick-desc ellipsis-rest-of-text">
                                        {!! $similarProduct->description !!}
                                    </div>
                                    <p class="rest-text-wrapper">
                                        <a class="read-rest-of-test" href="#">ادامه مطلب...</a>
                                    </p>

                                    <div class="social-sharing">
                                        <div class="widget widget_socialsharing_widget">
                                            <h3 class="widget-title-modal">به اشتراک گذاری این محصول</h3>
                                            <div class="footer_area" style="border-top: 0">
                                                <div id="social_block" style="margin: 0;overflow: visible;">
                                                    <a target="_blank" title="تلگرام" onclick="void(location.href='https://telegram.me/share/url?url=http://injaskala.ir/product/{{ $similarProduct->slug }}');" class="twitter"><i class="fa fa-send" aria-hidden="true"></i></a>
                                                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//injaskala.ir/product/{{ $similarProduct->slug }}" class="facebook social-icon" title="فیس بوک"><i style="font-size: 24px;margin-top: 4px;" class="fa fa-facebook"></i></a>
                                                    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=http%3A//injaskala.ir/{{ $similarProduct->slug }}/&title=injaskala&summary=&source=" style="background-color: #0077B5;" title="لینکدین"><i class="fa fa-linkedin" style="font-size:25px;color: #fff;"></i></a>
                                                    {{--<a target="_blank" title="آپارات" href="https://www.aparat.com/injaskala" class="rss"><i class="gb gb_aparat"></i></a>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .product-info -->
                            </div><!-- .modal-product -->
                        </div><!-- .modal-body -->
                    </div><!-- .modal-content -->
                </div><!-- .modal-dialog -->
            </div>
            <!-- END Modal -->
        </div>
    @endforeach
    <!-- END QUICKVIEW PRODUCT -->

    @foreach($newests as $newest)
        <div id="quickview-wrapper">
            <!-- Modal -->
            <div class="modal fade productModal" id="productModal-{{ $newest->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-product">
                                <div class="product-images">
                                    <div class="main-image images">
                                        <img src="{{ $newest->first_photo }}" alt="">
                                    </div>
                                </div><!-- .product-images -->

                                <div class="product-info">
                                    <h1 style="cursor: pointer;"
                                        title="{{ $newest->name }}">{{ $newest->name }}</h1>
                                    <div class="price-box">
                                        <p class="price"><span class="special-price"><span class="amount">{{ $newest->price }}
                                                    تومان</span></span></p>
                                    </div>
                                    <div class="quick-add-to-cart">
                                        <form method="post" class="cart">
                                            <div class="numbers-row">
                                                <input type="number" id="french-hens" value="3">
                                            </div>
                                            <button class="single_add_to_cart_button" type="submit">افزودن به سبد خرید
                                            </button>
                                        </form>
                                    </div>

                                    <div class="quick-desc ellipsis-rest-of-text">
                                        {!! $newest->description !!}
                                    </div>
                                    <p class="rest-text-wrapper">
                                        <a class="read-rest-of-test" href="#">ادامه مطلب...</a>
                                    </p>

                                    <div class="social-sharing">
                                        <div class="widget widget_socialsharing_widget">
                                            <h3 class="widget-title-modal">به اشتراک گذاری این محصول</h3>
                                            <div class="footer_area" style="border-top: 0">
                                                <div id="social_block" style="margin: 0;overflow: visible;">
                                                    <a target="_blank" title="تلگرام" onclick="void(location.href='https://telegram.me/share/url?url=http://injaskala.ir/product/{{ $newest->slug }}');" class="twitter"><i class="fa fa-send" aria-hidden="true"></i></a>
                                                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//injaskala.ir/product/{{ $newest->slug }}" class="facebook social-icon" title="فیس بوک"><i style="font-size: 24px;margin-top: 4px;" class="fa fa-facebook"></i></a>
                                                    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=http%3A//injaskala.ir/{{ $newest->slug }}/&title=injaskala&summary=&source=" style="background-color: #0077B5;" title="لینکدین"><i class="fa fa-linkedin" style="font-size:25px;color: #fff;"></i></a>
                                                    {{--<a target="_blank" title="آپارات" href="https://www.aparat.com/injaskala" class="rss"><i class="gb gb_aparat"></i></a>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .product-info -->
                            </div><!-- .modal-product -->
                        </div><!-- .modal-body -->
                    </div><!-- .modal-content -->
                </div><!-- .modal-dialog -->
            </div>
            <!-- END Modal -->
        </div>
    @endforeach
    <!-- END QUICKVIEW PRODUCT -->

@endsection

