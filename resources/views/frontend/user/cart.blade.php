@extends('frontend.master')

@section('content')

    <!--Breadcrumb Start-->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div class="breadcrumb">
                        <a href="{{ route('mainPage') }}"><i class="fa fa-home"></i>خانه</a>
                        <span class="navigation-pipe"><i class="fa fa-angle-left"></i></span>
                        <span class="navigation_page">سبد خرید</span>
                        @auth
                        <div style="float: left;color: #000;font-weight: bold;" class="col-lg-7"><u style="color: red;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</u> عزیز به سبد خرید خود خوش آمدید</div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb-->
    <!--Cart Main Area Start-->
    <div class="cart-main-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1>سبد خرید</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form method="POST" action="{{ route('payment.type.detect') }}" id="cartForm">

                        {{ csrf_field() }}
                        <div class="cart-table table-responsive">
                            <table>
                                @if((isset($cartProducts) && count($cartProducts) > 0) || (isset($tmpCartProducts) && count($tmpCartProducts) > 0))
                                <thead>
                                <tr>
                                    <th class="p-image">تصویر محصول</th>
                                    <th class="p-name">نام محصول</th>
                                    <th class="p-edit">تنظیمات</th>
                                    <th class="p-amount">قیمت واحد</th>
                                    <th class="p-amount">تخفیف</th>
                                    <th class="p-quantity">تعداد</th>
                                    <th class="p-total">جمع جزء</th>
                                </tr>
                                </thead>
                                <tbody>

                                    @guest('web')
                                        @foreach($tmpCartProducts as $cartProduct)
                                            <tr>
                                                <td class="p-image">
                                                    <a><img style="max-width: 50%;" alt="{{ $cartProduct->first_photo }}"
                                                            src="{{ $cartProduct->first_photo }}" class="floatright"></a>
                                                </td>
                                                <td class="p-name"><a>{{ $cartProduct->name }}</a></td>
                                                <td class="edit">
                                                    <a href="{{ route('deleteCartSession' , $cartProduct->id ) }}">
                                                        <button type="button"
                                                                onclick="return confirm('آیا مطمئن هستید؟', true , false)"
                                                                class="btn btn-danger btn-xs">حذف
                                                        </button>
                                                    </a>
                                                </td>
                                                <td class="p-amount">{{ number_format($cartProduct->total_price) }} تومان</td>
                                                <td class="p-amount">{{ number_format($cartProduct->discount) }}درصد</td>
                                                <td class="p-quantity">
                                                    <a href="{{ route('cartPlusBySession' , ['cart' => $cartProduct->id]) }}"><i
                                                                style="color: darkgreen;" class="fa fa-plus"></i></a>
                                                    {{ $cartProduct->cart_quantity }}
                                                    <a href="{{ route('cartMinesBySession' , ['cart' => $cartProduct->id]) }}"><i
                                                                style="color: darkred;" class="fa fa-minus"></i></a>
                                                </td>
                                                <td class="p-name">{{  number_format($cartProduct->total_price * $cartProduct->cart_quantity) }} تومان</td>
                                                {{--<td class="p-action"><a href="#"><i class="fa fa-times"></i></a></td>--}}
                                            </tr>
                                        @endforeach
                                    @endguest

                                    @auth
                                    @foreach($cartProducts as $cartProduct)
                                        <tr>
                                            <td class="p-image">
                                                <a><img style="max-width: 50%;" alt="{{ $cartProduct->first_photo }}"
                                                        src="{{ $cartProduct->first_photo }}" class="floatright"></a>
                                            </td>
                                            <td class="p-name"><a>{{ $cartProduct->product_name }}</a></td>
                                            <td class="edit">
                                                <a href="{{ route('deleteCart' , $cartProduct->product_id) }}">
                                                    <button type="button"
                                                            onclick="return confirm('آیا مطمئن هستید؟', true , false)"
                                                            class="btn btn-danger btn-xs">حذف
                                                    </button>
                                                </a>
                                            </td>
                                            <td class="p-amount">{{ number_format($cartProduct->product_price) }} تومان</td>
                                            <td class="p-amount">{{ number_format($cartProduct->product_discount) }}درصد
                                            </td>
                                            <td class="p-quantity">
                                                <a href="{{ route('increaseCart' , ['increaseCart' => $cartProduct->id]) }}"><i
                                                            style="color: darkgreen;" class="fa fa-plus"></i></a>
                                                {{ $cartProduct->quantity }}
                                                <a href="{{ route('decreaseCart' , ['decreaseCart' => $cartProduct->id]) }}"><i
                                                            style="color: darkred;" class="fa fa-minus"></i></a>
                                            </td>
                                            <td class="p-name">{{ number_format($cartProduct->sum_of_price) }} تومان</td>
                                            {{--<td class="p-action"><a href="#"><i class="fa fa-times"></i></a></td>--}}
                                        </tr>
                                    @endforeach
                                    @endauth

                                    @else
                                        <div class="alert alert-danger"> سبد خرید خالی میباشد!</div>
                                </tbody>
                                @endif
                            </table>
                            <div class="all-cart-buttons">
                                <div>
                                    <a href="{{ route('mainPage') }}">
                                        <button class="btn btn-success" type="button" style="border-radius: 0;float: left;height: 40px;">  صفحه اصلی <i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                                    </a>
                                    @if((isset($cartProducts) && count($cartProducts) > 0) || (isset($tmpCartProducts) && count($tmpCartProducts) > 0))
                                        <a style="float: right;" onclick="return confirm('آیا مطمئن هستید؟', true , false)"
                                           href="{{ route('deleteAllCart') }}">
                                            <button class="button clear-cart" type="button"><span>پاکسازی سبد خرید</span></button>
                                        </a>
                                        @guest('web')
                                        <a href="{{ route('showLoginForm') }}" style="float: right;">
                                            <button class="button" type="button"><span style="background: #D32F2F;">ادامه فرایند خرید</span></button>
                                        </a>
                                        @endguest
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if((isset($cartProducts) && count($cartProducts) > 0))
                            @if(auth()->guard('web')->check())
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-title">
                                        <h1>ثبت مشخصات</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-sm-3">
                                    <label for="province" class="control-label">انتخاب استان </label>
                                    <select name="province" id="province" class="js-data-example-ajax form-control">
                                        <option value="all" disabled selected="selected">...</option>
                                        @foreach($provinces as $province)
                                            <option {{ $province->id == request()->input('province') ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <script>
                                    $('#province').change(function () {
                                        var value = $(this).val();

                                        $.ajax({
                                            type: 'get',
                                            url: '{{ url()->to('/admin/provinces') }}/' + value + '/city',
                                            data: {},
                                            success: function (response) {
                                                var list = response;
                                                var overloads = $('#city');
                                                if (list.length > 0) {
                                                    overloads.html('<option>...</option>');
                                                    for (var i = 0; i < list.length; i++) {
                                                        overloads.append('<option value="' + list[i].id + '">' + list[i].name + '</option>')
                                                    }

                                                }
                                            }
                                        })
                                    });

                                    $(document).ready(function () {
                                                @if(request()->has('province') && request()->input('province') != 'all')
                                        var value = {{ request()->input('province') }};

                                        $.ajax({
                                            type: 'get',
                                            url: '{{ url()->to('/admin/provinces') }}/' + value + '/city',
                                            data: {},
                                            success: function (response) {
                                                var list = response;
                                                var overloads = $('#city');
                                                if (list.length > 0) {
                                                    overloads.html('<option disabled selected>...</option>');
                                                    for (var i = 0; i < list.length; i++) {
                                                        overloads.append('<option  value="' + list[i].id + '">' + list[i].name + '</option>')
                                                    }
                                                    @if(request()->has('city'))
                                                    overloads.val({{ request()->input('city') }});
                                                    @endif
                                                }
                                            }
                                        })
                                        @endif
                                    });
                                </script>

                                <div class="col-sm-3">
                                    <label for="city" class="control-label">انتخاب شهر </label>
                                    <select name="city" id="city" class="js-data-example-ajax form-control">
                                    </select>
                                </div>


                                <div class="col-sm-3">
                                    <label for="postal_code">کدپستی</label>
                                    <input class="form-control" type="text" id="postal_code"
                                           value="{{ old('postal_code') }}" name="postal_code" maxlength="10"
                                           placeholder="کدپستی را وارد کنید">
                                    @if($errors->has('postal_code'))
                                        <p class="text-danger">{{ $errors->first('postal_code') }}</p>
                                    @endif
                                </div>

                                <div class="col-sm-3">
                                    <label for="postal_code">نوع پرداخت</label>
                                    <select id="pay_type" name="pay_type" class="js-data-example-ajax form-control">
                                        <option disabled="disabled" selected="selected">...</option>
                                        <option {{ request()->input('pay_type') == 'online' ? 'selected' : '' }} value="online">
                                            آنلاین
                                        </option>
                                        <option {{ request()->input('pay_type') == 'venal' ? 'selected' : '' }} value="venal">
                                            پستی
                                        </option>
                                    </select>
                                </div>


                                <div class="col-md-6 col-xs-12">
                                    <label for="address">آدرس</label>
                                    <textarea class="form-control" id="address" value="{{ old('address') }}" cols="30"
                                              rows="10" name="address" placeholder="آدرس را وارد کنید"></textarea>
                                    @if($errors->has('address'))
                                        <p class="text-danger">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <label for="description">توضیحات</label>
                                    <textarea class="form-control" id="description" value="{{ old('description') }}"
                                              cols="30" rows="10" name="description"
                                              placeholder="توضیحات محصول را وارد کنید"></textarea>
                                    @if($errors->has('description'))
                                        <p class="text-danger">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">

                                </div>
                                <div class="col-md-4">

                                </div>
                                <div class="col-md-4">
                                    <div class="amount-totals">
                                        {{--<p class="total">جمع جزء <span>156.870 تومان</span></p>--}}
                                        @if(auth()->guard('web')->check())
                                            <p class="total">جمع کل <span>{{ number_format($total_price) }} تومان</span></p>
                                        @else
                                            <p class="total">جمع کل <span>{{ number_format($sumOfTempCartProductPrice) }} تومان</span></p>
                                        @endif
                                        <button id="online" class="button" type="submit"><span style="background: #579E59;">اقدام به پرداخت</span>
                                        </button>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End of Cart Main Area-->

@endsection

@section('script')
    <script src="{{ url()->to('/js/jquery.validate.min.js') }}"></script>

    <script>
        $('#cartForm').validate({
            rules: {
                province: {
                    required: true,
                    number: true
                },
                city: {
                    required: true,
                    number: true
                },
                postal_code: {
                    required: true,
                    number: true
                },
                address: {
                    required: true
                },
                pay_type: {
                    required: true
                }
            },
            messages: {
                province: {
                    required: 'انتخاب استان الزامی است.',
                    number: 'استان انتخاب شده نامعتبر است.'
                },
                city: {
                    required: 'انتخاب شهر الزامی است.',
                    number: 'شهر انتخاب شده نامعتبر است.'
                },
                postal_code: {
                    required: 'وارد کردن کد پستی الزامی است.',
                    number: 'کد پستی وارد شده نامعتبر است.'
                },
                address: {
                    required: 'وارد کردن آدرس الزامی است'
                },
                pay_type: {
                    required: 'وارد کردن نوع پرداخت الزامی است'
                },

            },
            errorClass: 'text-danger'
        });

        $('#pay_type').change(function () {
            var val = $(this).val();
            var button = $('#online span');
            if (val == 'venal') {
                button.html('ثبت سفارش');
            } else {
                button.html('اقدام به پرداخت');
            }
        });

    </script>
@endsection