@php
    $app_url = (new App\Helpers\ApplicationHelper())->getUrl();
@endphp
<!doctype html>
<html class="no-js" lang="fa">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ URL::to('/image/favicon.png') }}">
    @yield('meta')
    <meta name="description"
          content="فروشگاه اینترنتی وورکی فروش محصولات لوکس و تزئینی منزل، لوازم خانگی، کالای دیجیتال، صنایع دستی، فرهنگ و هنر، ورزش و تندرستی، لوازم تحریر،تخفیف های شگفت انگیز و دکوراسیون داخلی">
    <meta name="keywords"
          content="وورکی , خرید , فروش , حراج , کیف , کفش , بازی , فروشگاه اینترنتی , لوازم , لباس , خانگی , تخفیف , wourki">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Master CSS -->
    <script src="{{ URL::to('/js') }}/jquery-1.9.1.min.js"></script>
    <link rel="stylesheet" href="{{ URL::to('/css') }}/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/animate.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/nivo-slider.css">
    {{--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />--}}
    <link rel="stylesheet" href="{{ URL::to('/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ URL::to('/css/price_range_style.css') }}">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/meanmenu.min.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/owl.carousel.css">
    <link rel="stylesheet" href="{{ URL::to('/fontawesome/css') }}/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/fontiran.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/style.css">
    <link rel="stylesheet" href="{{ url()->to('/css/extra-style.css') }}">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/responsive.css">
    <link rel="stylesheet" href="{{ url()->to('/css/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/css/bootoast.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/gorbeh-icons.min.css">
    <link rel="stylesheet" href="{{ url()->to('/css/jquery.rateyo.min.css') }}">
    <link href="{{ URL::to('/admin') }}/assets/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet"
          type="text/css">
    <!-- modernizr css -->
    <script src="{{ URL::to('/js') }}/vendorOld/modernizr-2.8.3.min.js"></script>
    @yield('style')
    <style>
        body{
            width:100%
        }
        .morecontent span {
            display: none;
        }

        .panel-body table tr td {
            padding-left: 15px
        }

        .panel-body .table {
            margin-bottom: 0px;
        }

        .tbl > tbody > tr > td {
            padding: 0 !important;
        }

        .tbl a {
            font-family: IRANSans;
        }
        .guild-item{
            display: block;
            width: 100%;
            padding: 10px;
        }
        #mgmenu-right-section{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            background-color: #EBECED;
            min-height: 100%;
            height: 100%;
            padding-left: 0;
        }
        .greenlink{
            color:blue !important;
            text-decoration-color: blue !important
        }
        .left-section-insider{
            display: flex;
            padding: 20px;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;

        }
        
        .mgmenu-left-section{
            display: none;
            background-color: #F5F5F5;
        }
        .category-item{
            width:20%;
            font-size: 10pt;
        }
        .collapsed-category{
            display : none;
        }
        @media screen and (max-width: 992px) {
            .collapsed-category{
                display : flex;
                justify-content: flex-start;
                align-items: center;
                padding: 5px;
                width: 100%;
                flex-wrap: wrap;
            }
            .left-section-insider{
                display: none;
            }
            .mgmenu-left-section{
                display: none;
                max-height: 0
            }
        }
        

        
    </style>
</head>
<body>
<div class="progress" id="top-fixed-progress-bar">
    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0"
         aria-valuemax="100" style="width: 80%">
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="selectProvinceModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        انتخاب استان
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="text" name="" id="searchInProvinces" class="form-control"
                                       placeholder="نام استان مورد نظر خود را وارد کنید...">
                            </div>
                        </div>
                        <div class="row" id="province-btn-container">
                            @foreach(\App\Province::where('deleted' , 0)->get() as $item)
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <a data-province-name="{{ $item->name }}"
                                       href="{{ route('setProvinceCookie' , $item->id) }}"
                                       class="btn btn-pink btn-bordered btn-block">{{ $item->name }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<header>
    <div class="nav-bar-container">
        <nav class="navbar navbar-default workee-navbar">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#top_nav_bar_1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="container-fluid">
                    <div class="collapse navbar-collapse " id="top_nav_bar_1">
                        <ul class="nav navbar-nav nav-bar-menu-right">
                            <li class="{{ Route::currentRouteName() == 'mainPage' ? 'active' : '' }}"><a
                                        href="{{ route('mainPage') }}">صفحه اصلی </a></li>
                            <li><a
                                        href="{{ route('products.list' , ['search_in' => 'store' , 'keyword' => '']) }}">فروشگاه ها </a></li>
                            <li><a
                                        href="{{ route('products.list' , ['search_in' => 'product' , 'keyword' => '']) }}">محصولات</a></li>
                            <li><a
                                        href="{{ route('products.list' , ['search_in' => 'service' , 'keyword' => '']) }}">خدمات</a></li>
                            <li class="{{ Route::currentRouteName() == 'user.guid' ? 'active' : '' }}"><a
                                        href="{{ route('user.guid') }}">راهنما</a></li>

                           {{-- <li class="{{ Route::currentRouteName() == 'showContactUsPage' ? 'active' : '' }}"><a
                                        href="{{ route('showContactUsPage') }}">تماس با ما</a></li>--}}
                            {{--<li class="{{ Route::currentRouteName() == 'showTermsAndConditionPage' ? 'active' : '' }}">
                                <a
                                        href="{{ route('showTermsAndConditionPage') }}">قوانین و مقررات</a></li>--}}
                            {{--<li class="{{ Route::currentRouteName() == 'reffer_back_products.index' ? 'active' : '' }}">
                                <a href="{{ route('reffer_back_products.index') }}">شرایط بازگرداندن کالا</a></li>--}}
                            {{--<li class="{{ Route::currentRouteName() == 'showAboutPage' ? 'active' : '' }}"><a
                                        href="{{ route('showAboutPage') }}">درباره ما</a></li>--}}
                            <li style="margin-right: 10px;"><a class="btn btn-pink download-app-link"
                                                               href="{{$app_url}}">اپلیکیشن
                                    وورکی</a></li>
                        </ul>

                        <ul class="nav navbar-nav navbar-left">
                            @if(auth()->guard('web')->check())
                                <?php
                                $carts = \App\Cart::join('product_seller', 'product_seller.id', '=', 'cart.product_seller_id')
                                    ->where('cart.user_id', auth()->guard('web')->user()->id)
                                    ->select('product_seller.name', 'product_seller.id', 'product_seller.price', 'product_seller.discount',
                                        'product_seller.price', 'cart.quantity', 'cart.id as cartId')
                                    ->addSelect(\Illuminate\Support\Facades\DB::raw('(
                                        select product_seller_photo.file_name from product_seller_photo
                                        where product_seller.id = product_seller_photo.seller_product_id limit 1
                                    ) as photo'))
                                    ->get();
                                foreach ($carts as $i => $r) {
                                    $carts[$i]->product = \App\ProductSeller::find($r->id);
                                    $r->attributesProduct = \App\CartAttribute::where('cart_id', $r->cartId)->get();
                                    $r->totalPrice = ($r->product->price - (($r->product->price * $r->product->discount) / 100)) * $r->quantity;
                                }
                                $attrPrice = 0;
                                foreach ($carts as $index => $cart) {
                                    foreach ($carts[$index]->attributesProduct as $attribute) {
                                        $carts[$index]->sumAttrPrice = ($carts[$index]->sumAttrPrice + $attribute->attribute->extra_price) * $cart->quantity;
                                        $carts[$index]->totalPrice = $cart->totalPrice + $carts[$index]->sumAttrPrice;
                                    }
                                    $attrPrice += $carts[$index]->sumAttrPrice;
                                }
                                $sumPrice = $carts->sum('totalPrice');

                                $cartController = new \App\Http\Controllers\CartController();
                                $totalPrice = $cartController->calcUserCartPrice();
                                ?>
                                <li><a style="cursor: pointer;" href="{{url()->to('/my-account/chats/get')}}">شروع گفت و گو
                                </a></li>
                                <li style="position:relative;"><a style="cursor: pointer;" id="cart-open-menu">سبد
                                        خرید<span
                                                class="caret"></span></a>
                                    <div class="cart-widget-menu-container" style="display:none;">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="top-section">
                                                    <span>مبلغ کل خرید: <b>{{ number_format($sumPrice) }}
                                                            تومان</b></span>
                                                    <a href="" class="pull-left">مشاهده سبد خرید</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table">
                                                    <tbody>
                                                    @foreach($carts as $cart)
                                                        <tr>
                                                            <td>
                                                                <form action="{{ route('delete.cart' , $cart->cartId) }}"
                                                                      method="post">
                                                                    {{ csrf_field() }}
                                                                    {{ method_field('delete') }}
                                                                    <button type="submit" class="btn btn-secondary"><i
                                                                                class="fas fa-times"></i></button>
                                                                </form>
                                                            </td>
                                                            <td>
                                                                @if($cart->photo != null)
                                                                    <img style="border-radius: 50%;" width="50px"
                                                                         src="{{ url()->to('/image/product_seller_photo/') }}/{{ $cart->photo }}"
                                                                         alt="عکس محصول">
                                                                @else
                                                                    <img style="border-radius: 50%;" width="50px"
                                                                         src="{{ url()->to('/image/logo.png') }}"
                                                                         alt="عکس محصول">
                                                                @endif
                                                            </td>
                                                            <td>{{ $cart->name }}</td>
                                                            <td>{{ $cart->quantity }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <a href="{{ route('user.carts') }}" class="btn btn-block btn-success">ورود
                                                    و
                                                    ثبت سفارش</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li><a style="cursor: pointer;" data-toggle="modal" data-target="#selectProvinceModal">استان
                                    :
                                    @if(request()->cookie('province'))
                                        <?php
                                        $cookie = \Illuminate\Support\Facades\Cookie::get('province');
                                        $province = \App\Province::where('id', $cookie)->first()->name;
                                        echo $province;
                                        ?>
                                    @else اصفهان
                                    @endif
                                </a></li>
                            @if(!auth()->guard('web')->check())
                                <li><a href="{{ route('show.login.form') }}">ورود / ثبت نام</a></li>
                            @else
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true"
                                       aria-expanded="false">حساب من <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('user.profile') }}">محیط کاربری</a></li>
                                        <li><a href="{{ route('user.carts') }}">سبد خرید</a></li>
                                        <li><a href="{{ route('user.favorite.product') }}">علاقمندی ها</a></li>
                                        <li><a href="{{ route('logout.user') }}">خروج</a></li>
                                    </ul>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div id="search-and-logo-wrapper-in-master" class="container">
        <div class="col-xs-12">
            <div class="search-wrapper">
                <form action="{{ route('products.list') }}">
                    <div class="row">
                        <div class="col-xs-12 text-center col-md-2" style="position:relative;">
                            <a href="{{ url()->to('/') }}" class="logo-link">
                                <img width="150px" src="{{ url()->to('/image/logoheadersite.png') }}" alt="header logo">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <div class="row" id="search-form-filters">
                                <div class="col-xs-12 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="search_in">جستجو در:</label>
                                        <select name="search_in" class="form-control" id="search_in">
                                            <option {{ request()->search_in == 'product' ? 'selected' : '' }} value="product">
                                                محصولات
                                            </option>
                                            <option {{ request()->search_in == 'service' ? 'selected' : '' }} value="service">
                                                خدمات
                                            </option>
                                            <option {{ request()->search_in == 'store' ? 'selected' : '' }} value="store">
                                                فروشگاه ها
                                            </option>
                                            <option {{ request()->search_in == 'store_id' ? 'selected' : '' }} value="store_id">
                                                آیدی فروشگاه ها
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <?php
                                $categories = \App\Category::join('guild', 'guild.id', '=', 'category.guild_id')
                                    ->join('store', 'store.guild_id', '=', 'guild.id')
                                    ->select('category.id', 'category.name')
                                    ->distinct()
                                    ->get();
                                ?>
                                <div class="col-xs-12 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="select_category">انتخاب دسته بندی:</label>
                                        <select name="category" class="form-control" id="select_category">
                                            <option disabled selected>:: انتخاب کنید ::</option>
                                            @foreach($categories as $cat)
                                                <option {{ request()->category == $cat->id ? 'selected' : '' }} value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $cities = \App\Store::join('address', 'address.id', '=', 'store.address_id')
                                    ->join('city', 'city.id', '=', 'address.city_id')
                                    ->where('store.status', 'approved')
                                    ->select('city.id', 'city.name')
                                    ->distinct()
                                    ->get();
                                ?>
                                <div class="col-xs-12 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="select_city">انتخاب شهر:</label>
                                        <select name="city" class="form-control" id="select_city">
                                            <option disabled selected>:: انتخاب کنید ::</option>
                                            @foreach($cities as $city)
                                                <option {{ request()->city == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group input-wrapper">
                                        <i class="fas fa-search"></i>
                                        <input autocomplete="off" type="text" name="keyword" id="search"
                                               class="form-control" value="{{ request()->input('keyword') }}"
                                               placeholder="کالای مورد نظر خود را جستجو کنید...">
                                        <div style="display:none;" id="search-result-container">
                                            <ul>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row submit-btn-container">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-pink">جستجو</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
@if(!str_contains(Illuminate\Support\Facades\Route::current()->uri() , 'my-account'))
<div class="container-fluid">
    <div class="row">
        <nav class="mgmenu-container">
            <ul>

                    <li><i class="fa fa-shopping-bag" aria-hidden="true" style="margin-left: 7px" ></i>
دسته بندی کالاها
                        <div class="mega-menu">
                            <div class="row g-0">
                            <div id="mgmenu-right-section" class="col-md-3">
                                @foreach($guildAndCategoriesForMegaMenu as $guild)
                                @if($guild->guild_type == "product")
                                <div class="guild-item" id="guild-{{$guild->id}}" data-toggle="collapse" data-target="#guild-{{$guild->id}}-mobile-categories" >
                                        <i class="fas fa-angle-left"></i>
                                        {{$guild->name}}
                                </div>
                                <div id="guild-{{$guild->id}}-mobile-categories" class="collapse">
                                    <div class="collapsed-category">
                                        @foreach ($guild->categories as $category)
                                                <a class="collapsed-category" style="padding: 10px" href="{{ route('products.list' , [
                                        'search_in' => 'product',
                                        'category' => $category->id
                                    ]) }}">
                                                    {{$category->name}}</a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @foreach($guildAndCategoriesForMegaMenu as $guild)
                            @if($guild->guild_type == "product")
                            <div  class="col-md-9 mgmenu-left-section"  id="guild-{{$guild->id}}-categories">
                                <div class="left-section-insider">
                                @foreach($guild->categories as $category)
                                <p class="category-item">
                                        <a href="{{ route('products.list' , [
                                'search_in' => 'product',
                                'category' => $category->id
                            ]) }}">
                                            <i class="fas fa-angle-left"></i>
                                            {{$category->name}}</a>
                                </p>
                                
                                @endforeach
                                </div>
                            </div>
                            @endif
                            @endforeach
                            </div>
                        </div>
                    </li>
                    <li><i class="fa fa-briefcase" aria-hidden="true" style="margin-left: 7px" ></i>
                            دسته بندی خدمات
                        <div class="mega-menu">
                            <div class="row g-0">
                            <div id="mgmenu-right-section" class="col-md-3">
                                @foreach($guildAndCategoriesForMegaMenu as $guild)
                                @if($guild->guild_type == "service")
                                <div class="guild-item" id="guild-{{$guild->id}}" data-toggle="collapse" data-target="#guild-{{$guild->id}}-mobile-categories" >
                                        <i class="fas fa-angle-left"></i>
                                        {{$guild->name}}
                                </div>
                                <div id="guild-{{$guild->id}}-mobile-categories" class="collapse">
                                    <div class="collapsed-category">
                                        @foreach ($guild->categories as $category)
                                                <a class="collapsed-category" style="padding: 10px" href="{{ route('products.list' , [
                                        'search_in' => 'product',
                                        'category' => $category->id
                                    ]) }}">
                                                    {{$category->name}}</a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @foreach($guildAndCategoriesForMegaMenu as $guild)
                            @if($guild->guild_type == "service")
                            <div  class="col-md-9 mgmenu-left-section"  id="guild-{{$guild->id}}-categories">
                                <div class="left-section-insider">
                                @foreach($guild->categories as $category)
                                <p class="category-item">
                                        <a href="{{ route('products.list' , [
                                'search_in' => 'product',
                                'category' => $category->id
                            ]) }}">
                                            <i class="fas fa-angle-left"></i>
                                            {{$category->name}}</a>
                                </p>
                                
                                @endforeach
                                </div>
                            </div>
                            @endif
                            @endforeach
                            </div>
                        </div>
                    </li>
            </ul>
        </nav>
    </div>
</div>
@endif
{{-- <nav class="navbar navbar-default" id="mobile-categories-menu">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#mobile_categories_menu_content" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">دسته بندی محصولات</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="mobile_categories_menu_content">
            <ul class="nav navbar-nav">
                @foreach(\App\Guild::all() as $guild)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{ $guild->name }}<span class="caret"></span></a>
                        @php
                            $categories = $guild->categories;
                        @endphp
                        <ul class="dropdown-menu">
                            @foreach($categories as $category)
                                <li><a href="{{ route('products.list' , [
                            'search_in' => 'product',
                            'category' => $category->id
                        ]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav> --}}
<!-- header end -->


@yield('content')



<!-- footer start -->
<footer class="footer_area">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
                <div class="footer_widget">
                    <a class="footer_logo" href="{{ route('mainPage') }}"><img
                                style="width: auto;height: 70px;border-radius: 3px;margin-bottom: 5px;"
                                src="{{ URL::to('/image') }}/logo_footer.png"
                                alt="logo"></a>
                    {{--<p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است...</p>--}}
                </div>
                <div class="contact_info_footer">
                    <ul>
                        <li>
                            <span class="lbl">فروشگاه</span>
                            <p>وورکی</p>
                        </li>
                        <li>
                            <span class="lbl">آدرس</span>
                            <p>
                                اصفهان - خیابان پروین
                            </p>
                        </li>
                        <br>
                        {{--<li>
                            <span class="lbl">ایمیل فروشگاه : </span>
                            <p>info@injaskala.com</p>
                            <span class="lbl">واحد پشتیبانی : </span>
                            <p>injaskala@yahoo.com</p>
                        </li>--}}
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 hidden-sm hidden-md col-lg-2">
                <div class="footer_widget">
                    <h4>فروشگاه ما</h4>
                    <ul class="toggle-footer" style="">
                        <li class="item"><a href="{{ route('products.list' , ['orderBy' => 'vip']) }}">محصولات ویژه</a>
                        </li>
                        <li class="item"><a href="{{ route('products.list' , ['orderBy' => 'newest']) }}">جدیدترین
                                محصولات</a></li>
                        <li class="item"><a href="{{ route('products.list' , ['orderBy' => 'high-sales']) }}">پرفروش
                                ترین مجصولات</a></li>
                        <li class="item"><a href="{{ route('products.list' , ['orderBy' => 'high-visited']) }}">پربازدیدترین
                                محصولات</a></li>
                        <li class="item"><a href="{{ route('showContactUsPage') }}">تماس با ما</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 hidden-sm hidden-md col-lg-2">
                <div class="footer_widget">
                    <h4>اطلاعات</h4>
                    <ul class="toggle-footer" style="">
                        <li class="item"><a href="{{ route('products.list' , ['search_in' => 'store' , 'keyword' => '']) }}">فروشگاه ها</a></li>
                        <li class="item"><a href="{{ route('products.list' , ['search_in' => 'product' , 'keyword' => '']) }}">محصولات</a></li>
                        <li class="item"><a href="{{ route('user.guid') }}">راهنما</a></li>
                        <li style="margin-right: 10px;" class="item"><a href="{{ $app_url }}">اپلیکیشن
                                وورکی</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                <div class="footer_widget">
                    <h4>شبکه های اجتماعی</h4>
                    <p>ما را در شبکه های اجتماعی دنبال کنید.</p>
                    <div id="social_block" class="text-center">
                        <a target="_blank" title="تلگرام" href="https://t.me/wourki" class="twitter"><i
                                    class="fa fa-paper-plane fa-sm" aria-hidden="true"></i></a>
                        <a target="_blank" title="اینستاگرام" href="https://www.instagram.com/wourkii"
                           class="instagram"><i
                                    class="gb gb_instagram"></i></a>
                        {{--<a target="_blank" title="لینکدین" href="https://www.linkedin.com/in/wourki-business-94898a80" class="linked-in"><i
                                    class="gb gb_linkedin"></i></a>
                        <a target="_blank" title="توییتر" href="https://twitter.com/wourki" class="twitter"><i
                                    class="gb gb_twitter"></i></a>
                        <a target="_blank" title="فیس بوک" href="https://www.facebook.com/wourkii" class="facebook"><i
                                    class="gb gb_facebook"></i></a>--}}

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-6 col-lg-3">
                <div class="footer_widget">
                    <h4>نمادهای اعتماد</h4>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-6">
                            <a referrerpolicy="origin" target="_blank" href="https://trustseal.enamad.ir/?id=236268&amp;Code=5LeTv8AlZs5rofKbTUX3"><img referrerpolicy="origin" src="https://Trustseal.eNamad.ir/logo.aspx?id=236268&amp;Code=5LeTv8AlZs5rofKbTUX3" alt="" style="cursor:pointer" id="5LeTv8AlZs5rofKbTUX3"></a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</footer>
<!-- footer bottom end -->

<a style="border-radius:0;z-index:1000" id="download-workee-application-fixed-button" class="btn btn-danger btn-lg"
   target="_blank"
   href="{{$app_url}}">دانلود اپلیکیشن وورکی</a>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132326586-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-132326586-1');
</script>

<script src="{{ URL::to('/js/jquery-ui.min.js') }}"></script>
<script src="{{ URL::to('/js/price_range_script.js') }}"></script>
<script src="{{ URL::to('/js') }}/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- meanmenu js -->
<script src="{{ URL::to('/js') }}/jquery.meanmenu.js"></script>
<!-- countdown js -->
<script src="{{ URL::to('/js') }}/jquery.countdown.min.js"></script>
<!-- Nevo Slider js -->
<script src="{{ URL::to('/js') }}/jquery.nivo.slider.pack.js"></script>
<!-- owl.carousel js -->
<script src="{{ URL::to('/js') }}/owl.carousel3.min.js"></script>
<!-- jquery-ui js -->
{{--<script src="{{ URL::to('/js') }}/jquery-ui.min.js"></script>--}}
<!-- plugins js -->
<script src="{{ url()->to('/js/jquery.rateyo.min.js') }}"></script>
<script src="{{ URL::to('/js') }}/plugins.js"></script>
<!-- Elevatezoom JS -->
<script src="{{ URL::to('/js') }}/jquery.elevateZoom-3.0.8.min.js"></script>
<!-- wow js -->
<script src="{{ URL::to('/js') }}/wow.min.js"></script>
<script src="{{ url()->to('/js/switchery.min.js') }}"></script>
<!-- main js -->
<script type="text/javascript" src="{{ url()->to('/js/jquery.validate.min.js') }}"></script>
<script src="{{ url()->to('/js/bootoast.min.js') }}"></script>
<script src="{{ URL::to('/js') }}/main.js"></script>
<script src="{{ URL::to('admin') }}/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>
@include('swal.swal')
<script src="{{ URL::to('/js') }}/login.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/select2.full.min.js"></script>

<script type="text/javascript">
    function transformHyperlink(el){
        var test = $(el);
        var txt = test.html();
        txt = txt.replace(/<\/?[^>]+(>|$)/g, "");
        var pattern = /((?:http|ftp|https):\/\/[\w\-_]+(?:\.[\w\-_]+)+(?:[\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?)/gi;
        test.html(txt.replace(pattern, '<a class="greenlink" href="$1">$1</a>'));
    }
    $(document).ready(function () {
        $('#search_in').change(function () {
            var $this = $(this);
            if($this.val() == 'store_id'){
                $('#select_category').attr('disabled' , 'disabled');
                $('#select_city').attr('disabled' , 'disabled');
            }else{
                $('#select_category').removeAttr('disabled');
                $('#select_city').removeAttr('disabled');
            }
        });
        $('#search').keyup(function () {
            var $this = $(this);
            var searchIn = $('#search_in');
            if(searchIn.val() == 'store_id'){
                return;
            }
            var searchResult = $('#search-result-container');
            searchResult.css('display', 'block');
            var ul = searchResult.find('ul');
            if ($this.val().length >= 3) {
                $.ajax({
                    type: 'get',
                    url: '{{ route('auto.complete.search') }}',
                    data: {
                        'search': $(this).val(),
                    },
                    success: function (response) {
                        ul.html('');
                        if ($('#search').val().length >= 3) {
                            if (response.length > 0) {
                                ul.css('display', 'block');
                                for (var i = 0; i <= response.length; i++) {
                                    // ul.stop().animate({"opacity": "1"}, "slow");
                                    ul.append('<li><a href="{{ url()->to('/')  }}/product/' + response[i].id + '">' + response[i].name + ' </a></li>');
                                }
                            } else {
                                ul.append('<li>موردی یافت نشد!</li>');
                            }
                        }
                    }
                });
            } else {
                searchResult.css('display', 'none');
            }

        });

        // this will get the full URL at the address bar
        var url = window.location.href;

        // passes on every "a" tag
        $(".mainmenu nav ul li a").each(function () {
            // checks if its the same on the address bar
            if (url == (this.href)) {
                $(this).closest("li").addClass("active");
            }
        });

        $('.read-rest-of-test').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var quickDesc = $this.closest('.product-info').find('.quick-desc');
            if (quickDesc.hasClass('ellipsis-rest-of-text')) {
                quickDesc.removeClass('ellipsis-rest-of-text');
                $this.html('کوتاه سازی مطلب');
            } else {
                quickDesc.addClass('ellipsis-rest-of-text');
                $this.html('ادامه مطلب...');
            }
        });

        $('#account_sub').click(function (e) {
            e.stopPropagation();
            var $this = $(this);
            $this.closest('.cart_header').find('#account_submenu').slideToggle(100);
        });
        $(document).click(function () {
            var menu = $('#account_submenu').slideUp(100);
        });
    });
    //scripts for mega menu
    $(".guild-item").hover(function(){
        if(window.innerWidth > 992){
        var id = $(this).attr('id').split('-')[1]
        $('.guild-item').css('background-color' , '#EBECED')
        $('.guild-item').css('color' , '#FC2A23')
        $(this).css("background-color", "#FC2A23");
        $(this).css("color", "white");
        $('.mgmenu-left-section').hide();
        var height = $('#mgmenu-right-section').height();
        $("#guild-"+id+"-categories").css('height' ,  height.toString() + 'px');
        $("#guild-"+id+"-categories").show();
        }
        });
        $(".guild-item").click(function(){
        if(window.innerWidth <= 992){
        var id = $(this).attr('id').split('-')[1]
        $('.guild-item').css('background-color' , '#EBECED')
        $('.guild-item').css('color' , '#FC2A23')
        $(this).css("background-color", "#FC2A23");
        $(this).css("color", "white");

        }
        });
</script>

@yield('script')
<script>
    $('#searchInProvinces').keyup(function () {
        var $this = $(this);
        var keyword = $this.val();
        $('[data-province-name]').each(function () {
            var province = $(this);
            if (province.attr('data-province-name').indexOf(keyword) !== -1) {
                province.closest('div').css('display', 'block');
            } else {
                province.closest('div').css('display', 'none');
            }
        });
    });

    $('#cart-open-menu').click(function (e) {
        e.stopPropagation();
        var menu = $('.cart-widget-menu-container');
        menu.fadeToggle(400);
    });

    $(document).click(function (e) {
        var menu = $('.cart-widget-menu-container');
        menu.fadeOut(400);
    });

    $('.categories-menu a.navigation.right').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var ul = $('.categories-menu');
        var firstLi = $this.closest('.categories-menu').find('li.non-navigation').first();
        var right = firstLi.css('right').replace('px', '');
        if (right <= 0) {
            right = 0;
        } else {
            right -= 60;
        }
        $('.categories-menu ul li.non-navigation').css('right', right + 'px');
    });
    $('.categories-menu a.navigation.left').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var ul = $('.categories-menu');
        var firstLi = $this.closest('.categories-menu').find('li.non-navigation').first();
        var right = firstLi.css('right').replace('px', '');
        var ulWidth = ul.width();
        if (Math.abs(right) >= ulWidth) {
            right = ulWidth;
        } else {
            right -= 60;
        }
        $('.categories-menu ul li.non-navigation').css('right', right + 'px');
    });

    $(document).click(function () {
        $('.child-submenu').hide(400);
    });

    {{--@foreach(\App\Guild::all() as $guild)--}}
    {{--$('#guild-{{ $guild->id }}-menu').hover(function () {--}}
    {{--$('[data-show-for]').css('display', 'none');--}}
    {{--var show = $('[data-show-for="#guild-{{ $guild->id }}-menu"]');--}}
    {{--show.slideDown(200);--}}
    {{--});--}}
    {{--@endforeach--}}

    //    $(document).click(function () {
    //        $('[data-show-for]').slideUp(200);
    //    });

    $('.submenu-container').mouseenter(function () {
        var $this = $(this);
        var a = $this.prev('a');
        a.css('background-color', '#455A64');
    });

    $('.submenu-container').mouseleave(function () {
        var $this = $(this);
        var a = $this.prev('a');

        a.css('background-color', '#546E7A');
    });

    $('.categories-menu >  a').mouseenter(function () {
        var a = $(this);

        a.css('background-color', '#455A64');
    });

    $('.categories-menu >  a').mouseleave(function () {
        var a = $(this);

        a.css('background-color', '#546E7A');
    });

</script>
</body>
</html>