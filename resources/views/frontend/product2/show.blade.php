@extends('frontend.master')
@section('meta')
    <title>{{ $product->name }} - کد {{ $product->id }}</title>
    {{--<meta name="description" content="{{ str_limit($product->description , 300) }}">--}}
    @if(count($product->photos) > 0)
        <meta property="og:image" content="{{ $product->photos[0]->name }}"/>
    @endif
    <meta property="og:title" content="{{ $product->name }}"/>
    <meta property="og:description"
          content="{{ str_limit(htmlspecialchars(strip_tags($product->description)) , 300) }}"/>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ url()->to('/css/mightyslider.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/css/image-magnifier.css') }}">
    <style>
                .conversation{
          font-weight: 300;
          color: white;
          background-color: #FC494C;
          border-radius: 40px;
          padding: 8px 32px;
          display: inline-block;
          margin-left: 5px;
          border : 0
        }
        .conversation:hover{
          color: white;
        }
        #chat-form{
            display: inline;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="product-page-container">
                    <div class="row">
                        <div class="col-xs-12">
                            <header>
                            </header>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            @if(count($photos) > 0)
                                <div class="store-page-slider-area">
                                    <div class="row no-gutters">
                                        <div class="col-xs-12">
                                            <div class="content-container">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-5">
                                                        <div class="zoomWrapper" style="">
                                                            <div id="img-1" class="zoomWrapper single-zoom">
                                                                <a>
                                                                    @if(count($photos) > 0)
                                                                        <img id="zoom1"
                                                                             width="100%"
                                                                             src="{{ url()->to('/image/product_seller_photo/') }}/{{ $photos[0]->file_name }}"
                                                                             data-zoom-image="{{ url()->to('/image/product_seller_photo/') }}/{{ $photos[0]->file_name }}"
                                                                             alt="{{ $photos[0]->file_name }}">
                                                                    @else
                                                                        <img id="zoom1"
                                                                             width="100%"
                                                                             src="{{ url()->to('/image/logo.png') }}"
                                                                             data-zoom-image="{{ url()->to('/image/logo.png') }}"
                                                                             alt="">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="product-thumb row">
                                                                <ul class="p-details-slider" id="gallery_01">
                                                                    @if(count($photos) > 0)
                                                                        @foreach($photos as $photo)
                                                                            <li class="col-md-4">
                                                                                <a class="elevatezoom-gallery" href="#"
                                                                                   data-image="{{ url()->to('/image/product_seller_photo') }}/{{ $photo->file_name }}"
                                                                                   data-zoom-image="{{ url()->to('/image/product_seller_photo') }}/{{ $photo->file_name }}"><img
                                                                                            style="height: 90px;"
                                                                                            src="{{ url()->to('/image/product_seller_photo') }}/{{ $photo->file_name }}"
                                                                                            alt="{{ $photo->alt }}"></a>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-7">
                                                        <div class="product-description-container">
                                                            <ol class="breadcrumb">
                                                                <li><a href="{{ url()->to('/') }}"
                                                                       class="black">خانه</a></li>
                                                                <li><span class="black">دسته بندی: </span><a href="{{ route('products.list' , [
                                                                    'category' => $product->category_id,
                                                                    'keyword' => '',
                                                                    'search_in' => 'product'
                                                                ]) }}"
                                                                class="category-name">{{ $product->category->name }}</a>
                                                                </li>
                                                                <li><a href="{{ url()->current() }}"
                                                                       class="black">{{ $product->name }}</a></li>
                                                            </ol>
                                                            <hr>
                                                            <div class="options-container text-left" dir="ltr">
                                                                <span class="eye-container">
                                            {{--<i class="fa fa-eye"></i>
                                            <span style="text-align: center;">{{ $product->hint }}</span>--}}
                                                                    </span>
                                                                <span class="divider">|</span>
                                                                @if(auth()->guard('web')->check())
                                                                    <span class="heart-container">
                                                                        <a {{--href="{{ route('toggle.favorite.product' , $product->id) }}"--}}
                                                                           class="{{ $productFavoriteExists == true ? 'filled' : 'not-filled' }}"
                                                                           id="toggleFav"
                                                                           title="افزودن به علاقه مندی ها"
                                                                           style="cursor:pointer;">
                                                                            <i class="fa fa-heart"></i>
                                                                        </a>
                                                                    </span>
                                                                    <span class="divider">|</span>
                                                                @endif
                                                                    <span>
                                                                        <form action="{{route('chats.create')}}" method="POST" id="chat-form">
                                                                            {{ csrf_field() }}
                                                                        <button type="submit" style="cursor: pointer;" id="start-chat"
                                                                        class="pull-left conversation">شروع گفت و گو</button>
                                                                        <input hidden name="type" value="{{$store->store_type}}" />
                                                                        <input hidden name="id" value="{{$product->id}}" />
                                                                    </form>
                                                                    </span>

                                                                <span dir="rtl">
                                                                <span class="share-title">اشتراک گذاری:</span>
                                                                <a class="social-media-icon"
                                                                   href="https://www.facebook.com/sharer/sharer.php?u=http%3A//wourki.com/product/{{ $product->id }}"
                                                                   target="_blank" title="فیس بوک">
                                                                    <img src="{{ url()->to('/img/svg/facebook.svg') }}"
                                                                         alt="facebook share icon"
                                                                         width="25px">
                                                                </a>
                                                                <a style="cursor:pointer;" class="social-media-icon"
                                                                   onclick="void(location.href='https://telegram.me/share/url?url=http://wourki.com/product/{{ $product->id }}');"
                                                                   target="_blank" title="تلگرام">
                                                                    <img src="{{ url()->to('/img/svg/telegram.svg') }}"
                                                                         alt="facebook share icon"
                                                                         width="25px">
                                                                </a>
                                                                <a class="social-media-icon"
                                                                   href="https://twitter.com/intent/tweet/?url=https://www.wourki.com/product/{{ $product->id }}"
                                                                   target="_blank" title="توییتر">
                                                                    <img src="{{ url()->to('/img/svg/twitter.svg') }}"
                                                                         alt="facebook share icon"
                                                                         width="25px">
                                                                </a>
                                                                <a class="social-media-icon"
                                                                   href="https://www.linkedin.com/shareArticle?mini=true&url=http%3A//wourki.com/{{ $product->id }}/&title=wourki&summary=&source="
                                                                   target="_blank" title="لینکدین">
                                                                    <img src="{{ url()->to('/img/svg/linkedin.svg') }}"
                                                                         alt="linkedin share icon"
                                                                         width="25px">
                                                                </a>
                                                                    </span>
                                                                <div class="clearfix"></div>

                                                            </div>

                                                            <hr>
                                                            @if($product->guarantee_mark == 1 && $store->store_type == "product")
                                                                <div class="text-right">
                                                                    <img src="{{ url()->to('image/guarantee-mark.png') }}"
                                                                         width="60px" alt="" class="float-right">
                                                                    <span class="float-right">گارانتی  اصالت کالا</span>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                            @endif
                                                            <h1 class="text-right">{{ $product->name }}
                                                                (کد {{ $product->id }}
                                                                )
                                                            </h1>
                                                            @if(auth()->guard('web')->check() && auth()->guard('web')->user()->mobile_confirmed == 1 )
                                                                <p class="text-right"
                                                                   style="margin-bottom:10px;margin-right:10px;">رضایت
                                                                    از خرید</p>
                                                                <div class="text-right product-rate-container">
                                                                    <div id="product_rate"></div>
                                                                </div>
                                                                <p style="margin-bottom:10px;margin-right:10px">
                                                                    میانگین رضایت از خرید:
                                                                    <span>{{ round($rate , 2) / 5 * 100 }}%</span>
                                                                </p>
                                                            @endif
                                                            <div class="text-right store-info-wrapper">
                                                                @if($store->thumbnail_photo != null)
                                                                    <img width="100px" height="100px"
                                                                         src="{{ url()->to('/image/store_photos/') }}/{{ $store->thumbnail_photo }}"
                                                                         alt="store photo">
                                                                @endif
                                                                <h2 class="text-right">نام فروشگاه : <a
                                                                            href="{{ route('show.store' , ['slug' => $store->user_name]) }}">{{ $store->name }}</a>
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="product-description-container-box">
                                                            @if($store->store_type == "product")
                                                            <h3 class="text-right">توضیحات محصول</h3>
                                                            @else
                                                            <h3 class="text-right">توضیحات خدمت</h3>
                                                            @endif
                                                            <p class="text-right product-description">{{ $product->description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <form action="{{ route('cart.create' , $product->id) }}" method="post"
                              target="_blank">
                            {{ csrf_field() }}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="col-xs-12 col-md-8 col-lg-9" id="product-attributes-wrapper">
                                <script>
                                    var attributesArr = [];
                                </script>
                                @foreach($productAttrs as $index => $attr)
                                    @if(count($attr->productSellerAttributes) != 0 )
                                        <script>
                                            attributesArr.push({
                                                id: {{ $index }},
                                                extra_price: 0
                                            });
                                        </script>
                                        <section class="attribute-container">
                                            <h3>{{ $attr->type }}</h3>
                                            <input type="hidden" id="hidden-{{ $index }}">
                                            @foreach($attr->productSellerAttributes as $attribute)
                                                <label for="attribute-{{ $attribute->id }}"
                                                       class="radio-inline btn btn-pink btn-bordered btn-xs"
                                                       data-toggle-radio>
                                                    <input required type="radio" name="{{ $index }}"
                                                           id="attribute-{{ $attribute->id }}"
                                                           value="{{ $attribute->id }}">
                                                    <span>{{ $attribute->title }}</span>
                                                    <input type="hidden"
                                                           class="extra-price-{{ $attribute->id }}"
                                                           value="{{ $attribute->extra_price }}">
                                                </label>
                                                <script>
                                                    $(document).ready(function () {
                                                        $('#attribute-{{ $attribute->id }}').click(function () {
                                                            $('#hidden-{{ $index }}').val($('#attribute-{{ $attribute->id }}').val());
                                                        });
                                                        $('#addToCart').click(function () {
                                                            var val = $('#hidden-{{ $index }}').val();
                                                            if (!val) {
                                                                swal("ناموفق", "ویژگی {{ $attr->type }} موبوط به کالا را انتخاب کنید.", "error");
                                                                return false;
                                                            }
                                                        });

                                                        $('#attribute-{{ $attribute->id }}').click(function () {
                                                            var attrib = attributesArr.find((row) => {
                                                                return row.id == {{ $index }}
                                                            });
                                                            var new_attrib = attrib;
                                                            new_attrib.extra_price = {{ $attribute->extra_price }};
                                                            attributesArr[attributesArr.indexOf(attrib)] = attrib;
                                                            var sum = 0;
                                                            attributesArr.forEach((element) => {
                                                                sum += element.extra_price;
                                                            });
                                                            sum += Number($('#price').val());
                                                            $('#real-price').html(sum.toLocaleString() + 'تومان');
                                                        });
                                                    });
                                                </script>
                                            @endforeach
                                        </section>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-3">
                                <section class="attribute-container text-left" id="final-price-container">
                                    <div class="text-center cart">
                                        <p>
                                            <span class="title">قیمت نهایی:</span>
                                            @if($product->discount == 0)
                                                <input type="hidden" id="price"
                                                       value="{{ $product->price }}">
                                                <span id="real-price" class="price">{{ number_format($product->price) }}
                                                    تومان </span>
                                            @else
                                                <del>{{ number_format($product->price) }} تومان</del>
                                                <input type="hidden" id="price"
                                                       value="{{ $product->discountPrice }}">
                                                <span id="real-price" class="price">{{ number_format($product->discountPrice) }}
                                                    تومان </span>
                                            @endif
                                        </p>
                                        <button type="submit"
                                                class="btn btn-block btn-success btn-sm add-to-cart-button" style="margin-bottom: 5px">
                                            <span id="addToCart">افزودن به سبد خرید</span>
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                        @if(auth()->guard('web')->check() && App\Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'market')->exists())
                                        <span>
                                        <a href="{{route('market.products.add' , ['product_id' => $product->id])}}">
                                        <button type="button"
                                                class="btn btn-block btn-success btn-sm add-to-cart-button">
                                            <span>افزودن به فروشگاه بازاریابی</span>
                                            <i class="fas fa-box"></i>
                                        </button>
                                        </a>
                                        </span>
                                        @endif
                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                    <hr style="border-top-color: #333;max-width: 75%;">
                    @if(count($similarProducts) > 0)
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="similar-product-box-container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2>
                                                محصولات مشابه
                                            </h2>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="similar-product-container" dir="ltr">
                                                <div class="mightyslider_carouselSimple_skin clearfix slider">
                                                    <ul class="mSPages">
                                                    </ul>
                                                    <div class="frame">
                                                        <ul class="slide_element">
                                                            @foreach($similarProducts as $similarProduct)
                                                                <li class="slide"
                                                                    data-mightyslider="cover:'{{ url()->to('/image/product_seller_photo/350/') }}/{{ $similarProduct->photo }}'">
                                                                    <div class="details">
                                                                        <a href="{{ route('show.product.seller' , $similarProduct->id) }}"
                                                                           class="text-right"
                                                                           dir="rtl">{{ $similarProduct->name }}
                                                                            (کد {{ $similarProduct->id }}
                                                                            )</a>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(count($othersSeen) > 0)
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="other-product-box-container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2>
                                                دیگران دیده اند
                                            </h2>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="other-product-container" dir="ltr">
                                                <div class="mightyslider_carouselSimple_skin clearfix slider">
                                                    <ul class="mSPages">
                                                    </ul>
                                                    <div class="frame">
                                                        <ul class="slide_element">
                                                            @foreach($othersSeen as $seen)
                                                                <li class="slide"
                                                                    data-mightyslider="cover:'{{ url()->to('/image/product_seller_photo/350/') }}/{{ $seen->photo }}'">
                                                                    <div class="details">
                                                                        <a href="{{ route('show.product.seller' , $seen->id) }}"
                                                                           class="text-right"
                                                                           dir="rtl">{{ $seen->name }}
                                                                            (کد {{ $seen->id }}
                                                                            )</a>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(count($suggestions) > 0)
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="suggestion-product-box-container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2>
                                                پیشنهاد به شما
                                            </h2>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="suggestion-product-container" dir="ltr">
                                                <div class="mightyslider_carouselSimple_skin clearfix slider">
                                                    <ul class="mSPages">
                                                    </ul>
                                                    <div class="frame">
                                                        <ul class="slide_element">
                                                            @foreach($suggestions as $suggestion)
                                                                <li class="slide"
                                                                    data-mightyslider="cover:'{{ url()->to('/image/product_seller_photo/350/') }}/{{ $suggestion->photo }}'">
                                                                    <div class="details">
                                                                        <a href="{{ route('show.product.seller' , $suggestion->id) }}"
                                                                           class="text-right"
                                                                           dir="rtl">{{ $suggestion->name }}
                                                                            (کد {{ $suggestion->id }}
                                                                            )</a>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="comments-list-container-box">
                                <div class="row">
                                    @if($store->store_type == 'product')
                                    <h2>نظرات این محصول</h2>
                                    @else
                                    <h2>نظرات این خدمت</h2>
                                    @endif
                                    @if(!auth()->check())
                                        <div class="col-xs-12">
                                            <div class="comment-item even unauthorized-users-section">
                                                <div class="row">
                                                    <div class="hidden-xs col-sm-1"></div>
                                                    <div class="col-xs-12 col-sm-11">
                                                        <div class="alert alert-danger"><i
                                                                    class="fas fa-user-times"></i> برای
                                                            ارسال نظر باید
                                                            وارد شوید یا <a style="color: #337ab7;"
                                                                            href="{{ route('show.login.form' , ['redirectTo' => url()->current()]) }}">ثبت
                                                                نام</a> کنید.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach($comments as $comment)
                                        <div class="col-xs-12">
                                            <div class="comment-item even">
                                                <div class="row">
                                                    <div class="hidden-xs col-sm-1"
                                                         style="text-align:left;">
                                                        <img src="{{ url()->to('/img/avatar.png') }}"
                                                             alt="avatar"
                                                             class="img-circle" width="45px">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-11">
                                                        <div class="comment-text-container">
                                                            <p class="title"><span
                                                                        class="author">{{ $comment->user->first_name . ' ' . $comment->user->last_name }}</span> {{ \Morilog\Jalali\Jalalian::forge($comment->created_at)->ago() }}
                                                                گفته است:</p>
                                                            <p class="body">{{ $comment->comment }}</p>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-10 col-md-offset-1">
                                                                @foreach($comment->responses as $res)
                                                                    <div class="row" style="margin-top:10px;">
                                                                        <div class="col-xs-12">
                                                                            <div class="comment-item even">
                                                                                <div class="row">
                                                                                    <div class="hidden-xs col-sm-1"
                                                                                         style="text-align:left;">
                                                                                        <img src="{{ url()->to('/img/avatar.png') }}"
                                                                                             alt="avatar"
                                                                                             class="img-circle"
                                                                                             width="45px">
                                                                                    </div>
                                                                                    <div class="col-xs-12 col-sm-11">
                                                                                        <div class="comment-text-container">
                                                                                            <p class="title"><span
                                                                                                        class="author">صاحب فروشگاه گفته است.</span>
                                                                                                گفته است:</p>
                                                                                            <p class="body">{{ $res->comment }}</p>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-xs-12 col-md-10 col-md-offset-1">

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- this alert display only for unauthorized users --}}
                                    @if(auth()->check())
                                        {{-- below section shows only for logged in users who can leave comment --}}
                                        <div class="col-xs-12">
                                            <div class="comment-item even">
                                                <div class="row">
                                                    <div class="hidden-xs col-sm-1"
                                                         style="text-align:left;">
                                                        <img src="{{ url()->to('/img/user_tick.png') }}"
                                                             alt="avatar"
                                                             class="img-circle" width="45px">
                                                    </div>
                                                    <div class="col-sm-11 col-xs-12">
                                                        <div class="comment-text-container write-your-comment-container">
                                                            @if($store->store_type == 'product')
                                                            <p class="title write-your-comment">
                                                                شما هم نظر خود را در رابطه با این محصول بیان
                                                                کنید
                                                            </p>
                                                            @else
                                                            <p class="title write-your-comment">
                                                                شما هم نظر خود را در رابطه با این خدمت بیان
                                                                کنید
                                                            </p>
                                                            @endif
                                                            <form action="{{ route('comment.store') }}"
                                                                  method="post">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="product_id"
                                                                       value="{{ $product->id }}">
                                                                <p class="body">
                                                                <textarea name="comment" cols="30" class="form-control"
                                                                          rows="8"
                                                                          placeholder="شروع به نوشتن کنید...">{{ old('comment') }}</textarea>
                                                                </p>
                                                                <div class="form-group">
                                                                    <button type="submit"
                                                                            class="btn btn-pink btn-sm">
                                                                        ارسال نظر
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url()->to('/js/jquery.mobile.just-touch.js') }}"></script>
    <script src="{{ url()->to('/js/jquery.easing-1.3.pack.js') }}" type="text/javascript"></script>
    <script src="{{ url()->to('/js/tweenlite.js') }}" type="text/javascript"></script>
    <script src="{{ url()->to('/js/mightyslider.min.js') }}" type="text/javascript"></script>
    <script src="{{ url()->to('/js/image-magnifier.js') }}" type="text/javascript"></script>
    <script>

        $('#price-range').slider({
            range: true,
            orientation: "horizontal",
            min: 0,
            max: 10000000,
            values: [0, 10000000],
            step: 100000,
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
                var $example = $('.product-page-container .page-slider .slider-wrapper .slider'),
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

        $('#toggleFav').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{{ route('toggle.favorite.product') }}',
                data: {
                    'product': '{{ $product->id }}',
                    '_token': '{{ csrf_token() }}',
                },

                success: function () {
                    $('#toggleFav').toggleClass('not-filled filled');
                }
            });
        });


        jQuery(document).ready(function ($) {

            var $win = $(window),
                isTouch = !!('ontouchstart' in window),
                clickEvent = isTouch ? 'tap' : 'click';

            (function () {

                function calculator(width) {
                    var percent = '25%';

                    if (width <= 480) {
                        percent = '100%';
                    } else if (width <= 768) {
                        percent = '50%';
                    } else if (width <= 980) {
                        percent = '33.33%';
                    }

                    return percent;
                };
                @if(count($similarProducts) > 0)
                // Global slider's DOM elements
                var $carousel = $('.product-page-container .similar-product-container .slider'),
                    $pagesbar = $('.mSPages', $carousel),
                    $frame = $('.frame', $carousel);

                // Calling new mightySlider class
                var slider = new mightySlider($frame, {
                    speed: 1000,
                    easing: 'easeOutExpo',
                    viewport: 'fill',

                    // Navigation options
                    navigation: {
                        navigationType: 'basic',
                        slideSize: calculator($win.width())
                    },

                    // Commands options
                    commands: {
                        buttons: 1
                    },

                    // Pages options
                    pages: {
                        pagesBar: $pagesbar[0],
                        activateOn: clickEvent
                    },

                    // Dragging options
                    dragging: {
                        mouseDragging: 0,
                        touchDragging: 0
                    }
                }).init();
                @endif
                @if(count($othersSeen) > 0)
                // Global slider's DOM elements
                var $carousel = $('.product-page-container .other-product-container .slider'),
                    $pagesbar = $('.mSPages', $carousel),
                    $frame = $('.frame', $carousel);

                // Calling new mightySlider class
                var slider = new mightySlider($frame, {
                    speed: 1000,
                    easing: 'easeOutExpo',
                    viewport: 'fill',

                    // Navigation options
                    navigation: {
                        navigationType: 'basic',
                        slideSize: calculator($win.width())
                    },

                    // Commands options
                    commands: {
                        buttons: 1
                    },

                    // Pages options
                    pages: {
                        pagesBar: $pagesbar[0],
                        activateOn: clickEvent
                    },

                    // Dragging options
                    dragging: {
                        mouseDragging: 0,
                        touchDragging: 0
                    }
                }).init();
                @endif
                @if(count($suggestions) > 0)
                // Global slider's DOM elements
                var $carousel = $('.product-page-container .suggestion-product-container .slider'),
                    $pagesbar = $('.mSPages', $carousel),
                    $frame = $('.frame', $carousel);

                // Calling new mightySlider class
                var slider = new mightySlider($frame, {
                    speed: 1000,
                    easing: 'easeOutExpo',
                    viewport: 'fill',

                    // Navigation options
                    navigation: {
                        navigationType: 'basic',
                        slideSize: calculator($win.width())
                    },

                    // Commands options
                    commands: {
                        buttons: 1
                    },

                    // Pages options
                    pages: {
                        pagesBar: $pagesbar[0],
                        activateOn: clickEvent
                    },

                    // Dragging options
                    dragging: {
                        mouseDragging: 0,
                        touchDragging: 0
                    }
                }).init();
                @endif
                // Register window :resize event callback
                $win.resize(function () {
                    // Update slider options using 'set' method
                    slider.set({
                        navigation: {
                            slideSize: calculator($win.width())
                        }
                    });
                });
            })();
        });

        $('#product_rate').rateYo({
            starWidth: "15px",
            readOnly: false,
            rating: '{{ $rate }}',
            ratedFill: '#FF9800',
            fullStar: false,
            onSet: function (rating, rateYoInstance) {
                        @if(auth()->guard('web')->check() && auth()->guard('web')->user()->mobile_confirmed == 1)
                var rate = rating;

                $.ajax({
                    url: '{{ route('product_seller.set_rate' , $product->id) }}',
                    type: 'post',
                    data: {
                        'rate': rate,
                        '_token': '{{ csrf_token() }}'
                    },

                    success: function () {
                        swal('موفقیت آمیز.', 'ارزیابی شما از این مصحول با موفقیت ثبت شد با تشکر از شما.', 'success');
                    }
                });
                @else
                swal('ناموفق!', 'حساب کاربری شما تایید نشده است.', 'error');
                @endif
            }
        });
    </script>
@endsection