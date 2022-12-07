@extends('frontend.master')

@section('style')
    <style>
        .owl-theme .owl-controls .owl-buttons div {
            top: -40px !important;
        }
    </style>
@endsection

@section('content')
    <!--Breadcrumb Start-->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="breadcrumb">
                        <a href="{{ route('mainPage') }}"><i class="fa fa-home"></i>خانه</a>
                        <span class="navigation-pipe"><i class="fa fa-angle-left"></i></span>
                        <span class="navigation_page">حساب</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb-->
    <!-- Account Area start -->
    <div class="account-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="account-link-list">
                        <div class="page-title">
                            <h1>حساب من</h1>
                        </div>

                        <p class="account-info"><u style="font-weight: bold;">{{ $user->first_name }} {{ $user->last_name }}</u> عزیز به حساب کاربری خود خوش آمدید، در اینجا شما می توانید تمام اطلاعات و سفارشات شخصی خود را مدیریت کنید.</p>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#order">
                                            <i class="fa fa-list-ol"></i><span>تاریخ سفارش و جزئیات</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="order" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div id="orders-history">
                                        <!--Cart Main Area Start-->
                                        <div class="cart-main-area area-padding">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="page-title">
                                                            <h1>سفارش ها</h1>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        @if(isset($bills) && count($bills) > 0)
                                                        <div class="cart-table table-responsive">
                                                            <table>
                                                                <thead>
                                                                <tr>
                                                                    <th class="p-image">ردیف</th>
                                                                    <th class="p-name">آدرس</th>
                                                                    <th class="p-edit">وضعیت سفارش</th>
                                                                    <th class="p-edit">کد پستی</th>
                                                                    <th class="p-amount">نوع سفارش</th>
                                                                    <th class="p-quantity">تاریخ</th>
                                                                    <th class="p-quantity">کدرهگیری</th>
                                                                    <th class="p-name">اقلام</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $i = 1 ?>
                                                                @foreach($bills as $bill)
                                                                    <tr>
                                                                        <td class="p-amount">{{ $i }}</td>
                                                                        <td class="p-name">{{ $bill->address }}</td>
                                                                        <td class="p-name">
                                                                            @if($bill->status == 'bought')
                                                                                <button class="btn btn-default btn-xs">در انتظار تایید</button>
                                                                                @elseif($bill->status == 'rejected')
                                                                                <button class="btn btn-danger btn-xs">رد شده</button>
                                                                                @elseif($bill->status == 'delivered')
                                                                                <button class="btn btn-success btn-xs">تحویل داده شده</button>
                                                                                @elseif($bill->status == 'shipping')
                                                                                <button class="btn btn-info btn-xs">در حال ارسال</button>
                                                                                @else
                                                                                <button class="btn btn-warning btn-xs">بازگشت داده شده</button>
                                                                            @endif
                                                                        </td>
                                                                        <td class="p-name">{{ $bill->postal_code }}</td>
                                                                        <td class="p-amount">{{ $bill->pay_type == 'online' ? 'آنلاین' : 'پستی' }}</td>
                                                                        <td class="p-total">{{ jdate($bill->created_at)->format('date') }}</td>
                                                                        <td class="p-total">{{ $bill->pay_referral_code  }}</td>
                                                                        <td class="p-amount">
                                                                            <a href="{{ route('showBillItemsUser' , $bill->id) }}"><button id="add-new-address" class="btn btn-success btn-xs">مشاهده اقلام</button></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $i+=1; ?>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                            <div class="alert alert-info">تاکنون سفارشی ثبت نکرده اید.</div>
                                                        @endif
                                                    </div>

                                                    @if(count($bills) > 0)
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <ul class="pagination pagination-split">
                                                                    @if($bills->currentPage() != 1)
                                                                        <li>
                                                                            <a href="{{ $bills->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
                                                                        </li>
                                                                    @endif
                                                                    @for($i =1 ; $i <= $bills->lastPage() ; $i++)
                                                                        <li class="{{ $i == $bills->currentPage() ? 'active' : '' }}">
                                                                            <a href="{{ $bills->url($i) }}">{{ $i }}</a>
                                                                        </li>
                                                                    @endfor
                                                                    @if($bills->currentPage() != $bills->lastPage())
                                                                        <li>
                                                                            <a href="{{ $bills->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <!--End of Cart Main Area-->
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#information">
                                            <i class="fa fa-user"></i><span>اطلاعات شخصی من</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="information" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="personal-info col-lg-6 col-md-8 col-sm-10">
                                                <p class="panel-title">ویرایش اطلاعات کاربری. </p>
                                                <div id="account-info">
                                                    <div class="row">
                                                        <form action="{{ route('updateUserInUserPanel') }}" method="post">
                                                            {{ csrf_field() }}

                                                            <div class="form-group required">
                                                                <label class="col-md-12 col-sm-12 control-label">نام</label>
                                                                @if($errors->has('first_name'))
                                                                    <p style="margin-right: 15px;" class="text-danger">{{ $errors->first('first_name') }}</p>
                                                                @endif
                                                                <div class="col-md-12 col-sm-12">
                                                                    <input type="text" class="form-control" id="input-payment-fname" placeholder="نام" value="{{ $user->first_name }}" name="first_name">
                                                                </div>
                                                            </div>

                                                            <div class="form-group required">
                                                                <label class="col-md-12 col-sm-12 control-label">نام خانوادگی</label>
                                                                @if($errors->has('last_name'))
                                                                    <p style="margin-right: 15px;" class="text-danger">{{ $errors->first('last_name') }}</p>
                                                                @endif
                                                                <div class="col-md-12 col-sm-12">
                                                                    <input type="text" class="form-control" id="input-payment-lastname" placeholder="نام خانوادگی" value="{{ $user->last_name }}" name="last_name">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12" style="margin-top: 20px;">
                                                                <div class="button-back">
                                                                    <button class="btn btn-success" type="submit">ویرایش</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFive">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#wishlist">
                                            <i class="fa fa-heart"></i><span>لیست علاقه مندی های من</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="wishlist" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="section_title">
                                                    <h3><span class="angle"><i class="fa fa-star"></i></span>علاقه مندی ها</h3>
                                                    <span class="more"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="wishlist-container">
                                                    <p class="row">
                                                        @if(isset($fav_products) && count($fav_products) > 0)
                                                        <div class="featured_products without_tab" style="margin-top: 30px;">
                                                            @foreach($fav_products as $product)
                                                                <div class="col-xs-12">
                                                                    <div class="single_product" style="height: 335px;">
                                                                        <div class="product_image">
                                                                            <a class="product_img_link">
                                                                                <img src="{{ $product->first_photo }}"
                                                                                     alt="">
                                                                            </a>
                                                                            {{--@if($mostSell->discount)
                                                                                <a class="new-box"><span>{{ $mostSell->discount }}% تخفیف</span></a>
                                                                            @endif--}}
                                                                            <a style="cursor: pointer;" class="quick-view modal-view" data-toggle="modal"
                                                                               data-target="#{{ $product->id }}">مشاهده سریع<i class="fa fa-eye"></i></a>
                                                                        </div>
                                                                        <div class="product_content">
                                                                            <a href="{{ route('singlePage' , $product->slug) }}"
                                                                               class="product-name" title="{{ $product->name }}">{{ str_limit($product->name , 23) }}</a>
                                                                            <div class="product-name" style="color: #FF7C49;">
                                                                                @if($product->discount)
                                                                                    {{ $product->discount }}% تخفیف
                                                                                @endif
                                                                            </div>
                                                                            <div class="price_box">
                                                                                @if($product->discount != 0)
                                                                                    <span class="old-price product-price"> {{ number_format($product->price) }}تومان </span>
                                                                                @endif
                                                                                <span style="font-size: 13px;" class="price">{{ number_format($product->total_price) }} تومان </span>
                                                                            </div>
                                                                            <div class="button_content" @if($product->discount == 0) style="padding-top: 31px;" @else style="padding-top: 2px;"  @endif>
                                                                                <a href="{{ route('addToUserCart' , $product->id) }}" class="cart_button">افزودن به سبد خرید</a>
                                                                                <a title="حذف از علاقه مندی ها" href="{{ route('removeToFav' , $product->id) }}" class="heart"><i class="fa fa-remove"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @else
                                                        <p>لیست علاقه مندی های شما خالی میباشد.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($fav_products as $product)
                            <div id="quickview-wrapper">
                                <!-- Modal -->
                                <div class="modal fade productModal" id="{{ $product->id }}" tabindex="-1" role="dialog">
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
                                                            <img src="{{ $product->first_photo }}" alt="">
                                                        </div>
                                                    </div><!-- .product-images -->

                                                    <div class="product-info">
                                                        <h1>{{ $product->name }}</h1>
                                                        <div class="price-box">
                                                            <p class="price"><span class="special-price"><span class="amount">{{ number_format($product->total_price) }}
                                                                        تومان</span></span></p>
                                                        </div>
                                                        <div class="quick-add-to-cart">
                                                            <form action="{{ route('addToUserCartByPost') }}" method="post" class="cart">
                                                                {{ csrf_field() }}
                                                                <div class="numbers-row">
                                                                    <input type="number" name="quantity" id="french-hens" value="1">
                                                                </div>
                                                                <input type="hidden" value="{{ $product->id }}" name="id">
                                                                <button class="single_add_to_cart_button" type="submit">افزودن به سبد خرید
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="quick-desc">
                                                            {!! $product->description !!}
                                                        </div>
                                                        <div class="social-sharing">
                                                            <div class="widget widget_socialsharing_widget">
                                                                <h3 class="widget-title-modal">به اشتراک گذاری این محصول</h3>
                                                                <div class="footer_area" style="border-top: 0">
                                                                    <div id="social_block" style="margin: 0;overflow: visible;">
                                                                        <a target="_blank" title="تلگرام" onclick="void(location.href='https://telegram.me/share/url?url=http://injaskala.ir/product/{{ $product->slug }}');" class="twitter"><i class="fa fa-send" aria-hidden="true"></i></a>
                                                                        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//injaskala.ir/product/{{ $product->slug }}" class="facebook social-icon" title="فیس بوک"><i style="font-size: 24px;margin-top: 4px;" class="fa fa-facebook"></i></a>
                                                                        <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=http%3A//injaskala.ir/{{ $product->slug }}/&title=injaskala&summary=&source=" style="background-color: #0077B5;" title="لینکدین"><i class="fa fa-linkedin" style="font-size:25px;color: #fff;"></i></a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Account Area-->

@endsection