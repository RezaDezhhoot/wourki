@extends('frontend.master')
@section('style')
    @if(request()->filled('store'))
        @if(request()->input('store') == 'top-stores')
            <title>وورکی | فروشگاه های برتر</title>
        @else
            <title>وورکی | جستجو در فروشگاه ها</title>
        @endif
    @else
        <title>وورکی | جستجو در فروشگاه ها</title>
    @endif
@endsection
@section('content')
    <div class="slider_area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="slider-area">
                        <div id="ensign-nivoslider" class="slides" style="margin-top: 30px">
                            @if(isset($sliders))
                            @foreach($sliders as $slider)
                                <a style="width:100%"
                                   href="{{ $slider->link ? $slider->link : '' }}">
                                    <img class="img-responsive" src="{{ URL::to('/image/slider') }}/{{ $slider->pic }}"
                                         alt="{{ $slider->alt }}">
                                </a>
                            @endforeach
                            @endif
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
                        <a href="{{ route('mainPage') }}"><i class="fas fa-home"></i>خانه</a>
                        <span class="navigation-pipe"><i class="fas fa-angle-left"></i></span>
                        @if(Route::currentRouteName() == 'list.stores')
                            <a href="{{ route('list.stores') }}">فروشگاه های برتر</a>
                        @endif
                        @if(request()->search_in == 'product')
                            <a style="font-weight: bold;"
                               href="{{ route('products.list' , ['keyword' => '' , 'search_in' => 'product']) }}">لیست
                                محصولات</a>
                        @elseif(request()->search_in == 'store')
                            <a style="font-weight: bold;"
                               href="{{ route('products.list' , ['keyword' => '' , 'search_in' => 'store']) }}">لیست
                                فروشگاه ها</a>
                        @endif

                        @if(request()->has('guild'))
                            <span class="navigation-pipe"><i class="fas fa-angle-left"></i> صنف : </span>
                            <a style="color: red;">{{ \App\Guild::find(request()->guild)->name }}</a>
                        @endif
                        @if(request()->has('category'))
                            <span class="navigation-pipe"><i class="fas fa-angle-left"></i> دسته بندی : </span>
                            <a style="color: red;">{{ \App\Category::find(request()->category)->name }}</a>
                        @endif
                        @if(request()->has('city'))
                            <span class="navigation-pipe"><i class="fas fa-angle-left"></i> شهر : </span>
                            <a style="color: red;">{{ \App\City::find(request()->city)->name }}</a>
                        @endif
                        @if(request()->has('keyword'))
                            @if(request()->keyword != '')
                                <span class="navigation-pipe"><i class="fas fa-angle-left"></i></span>
                                <span style="color: #000;" class="navigation_page">متن جستجو : <a
                                            style="color: red;">{{ request()->keyword }}</a></span>
                            @endif
                        @endif

                        @if(request()->search == '' && request()->category == 'all')
                            <span class="navigation-pipe"><i class="fas fa-angle-left"></i></span>
                            <span style="color: #000;" class="navigation_page">جستجو در همه فروشگاه ها</span>
                        @endif

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="block" id="layered_block_left">
                        <p class="title_block">زمینه های فعالیت</p>

                        <div class="block_content" style="">

                            <div class="layered_filter" style="border: none;">
                                <div class="layered_subtitle_heading">
                                </div>
                                <ul class="layered_filter_ul">
                                    @foreach($guilds as $guild)
                                        <li>
                                            @if(Route::currentRouteName() == 'products.list')
                                                <a href="{{ route('products.list' , ['search_in' => 'store' , 'guild' => $guild->id]) }}">
                                                    <i class="fas fa-angle-left"></i> {{ $guild->name }}
                                                </a>
                                            @else
                                                <a href="{{ route('list.stores' , ['guild' => $guild->id]) }}">
                                                    <i class="fas fa-angle-left"></i> {{ $guild->name }}
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-sm-8 col-xs-12">
                    <div class="all-products">
                        <h1 class="page-heading product-listing">
                            <span class="cat-name">نتایج جستجو</span>
                            <span class="heading-counter"><span class="heading-counter"><span
                                            style="color: red;">{{ $stores->total() }}</span> فروشگاه یافت شد.</span></span>
                        </h1>
                        <div class="tab_container block_content">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="grid">
                                    <div class="shops_list_wrapper">
                                        <div class="row">
                                            @if(count($stores) > 0)
                                                @foreach($stores as $store)
                                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                                        <div class="today-submitted-store">
                                                            <a href="{{ route('show.store' , $store->user_name) }}">
                                                                <div class="cover-photo"
                                                                     @if($store->photo)
                                                                     style="background-image: url('{{ url()->to('/image/store_photos/') }}/{{ $store->photo }}')">
                                                                    @else
                                                                        style="background-image:
                                                                        url('{{ url()->to('/image/store.jpg') }}')">
                                                                    @endif
                                                                    @if($store->thumbnail_photo)
                                                                        <img src="{{ url()->to('/image/store_photos/') }}/{{ $store->thumbnail_photo }}"
                                                                             class="img-circle" alt="store thumbnail">
                                                                    @else
                                                                        <img src="{{ url()->to('/image/logo.png') }}"
                                                                             class="img-circle" alt="store thumbnail">
                                                                    @endif
                                                                </div>
                                                            </a>
                                                            <div class="store-info">
                                                                <h4 style="text-align: center;">
                                                                    <a href="{{ route('show.store' , $store->user_name) }}">{{ $store->name }}</a>
                                                                </h4>
                                                                @if($store->rate != null)
                                                                    <div style="margin:10px auto;"
                                                                         class="text-center rateyo"
                                                                         id="rateyo_store_{{ $store->id }}"></div>
                                                                @else
                                                                    <div style="margin:10px auto;"
                                                                         class="text-center null-rate"></div>
                                                                @endif
                                                                <p style="text-align: center;"
                                                                   class="slowgun">{{ $store->slogan }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="alert alert-danger text-center">موردی یافت نشد</div>
                                            @endif
                                        </div>
                                        {{ $stores->links() }}
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

@section('script')
    <script>
        $(function () {
            @foreach($stores as $store)
            $("#rateyo_store_{{ $store->id }}").rateYo({
                starWidth: "15px",
                readOnly: true,
                rating: '{{ $store->rate }}',
                ratedFill: '#FF9800'
            });
            @endforeach
            $(".null-rate").rateYo({
                starWidth: "15px",
                readOnly: true,
                rating: '0',
                ratedFill: '#FF9800'
            });
        });
    </script>
@endsection

