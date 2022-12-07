@extends('frontend.master')

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

                        <p class="account-info"><u>{{ $user->first_name }} {{ $user->last_name }}</u> عزیز به حساب
                            کاربری خود خوش آمدید. در اینجا شما می توانید تمام اطلاعات و سفارشات شخصی خود را مدیریت کنید.
                        </p>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#order">
                                            <i class="fa fa-list-ol"></i><span>جزئیات اقلام محصول</span>
                                        </a>
                                    </h4>
                                </div>
                                <br>
                                <div id="order" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div id="orders-history">
                                        <!--Cart Main Area Start-->
                                        <div class="cart-main-area area-padding">
                                            {{--<div class="container">--}}


                                            <div class="row invoice-head-wrapper">
                                                <div class="col-xs-12">
                                                    <span class="invoice-date-in-detail-page"> تاریخ : {{ jdate($bill->created_at)->format('%B %d، %Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row invoice-head-wrapper" id="customer-information">
                                                <div class="col-xs-12">
                                                    <h4 class="text-center">مشخصات خریدار</h4>
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                             <tbody>
                                                             <tr>
                                                                 <td>نام و نام خانوادگی</td>
                                                                 <td class="invoice-information-value">
                                                                     {{ $bill->user_first_name }} {{ $bill->user_last_name }}
                                                                 </td>
                                                                 <td>کد پستی</td>
                                                                 <td class="invoice-information-value">{{ $bill->postal_code }}</td>
                                                             </tr>
                                                             <tr>
                                                                 <td>وضعیت تحویل</td>
                                                                 <td class="invoice-information-value">
                                                                     @if($bill->status == 'bought') <p class="text-secondary bold">در انتظار تایید</p> @endif
                                                                     @if($bill->status == 'shipping') <p class="text-info bold">درحال ارسال</p> @endif
                                                                     @if($bill->status == 'delivered') <p class="text-success bold">تحویل داده شده</p> @endif
                                                                     @if($bill->status == 'returned') <p class="text-warning bold">بازگشت داده شده</p> @endif
                                                                     @if($bill->status == 'rejected') <p class="text-danger bold">رد شده</p> @endif
                                                                 </td>
                                                                 <td>نحوه پرداخت</td>
                                                                 <td class="invoice-information-value">
                                                                     @if ($bill->pay_type === 'online')آنلاین@endif
                                                                     @if ($bill->pay_type === 'venal')پستی@endif
                                                                 </td>
                                                             </tr>
                                                             <tr>
                                                                 <td>استان</td>
                                                                 <td class="invoice-information-value">{{ $bill->province_name }}</td>
                                                                 <td>شهر</td>
                                                                 <td class="invoice-information-value"{{ $bill->city_name }}></td>
                                                             </tr>
                                                             <tr>
                                                                 <td>کد پیگیری خرید</td>
                                                                 <td class="invoice-information-value">{{ $bill->pay_referral_code }}</td>
                                                                 <td>آدرس</td>
                                                                 <td class="invoice-information-value">{{ $bill->address }}</td>
                                                             </tr>
                                                             <tr>
                                                                 <td colspan="1">توضیحات</td>
                                                                 <td colspan="3" style="color: #4b6a88;">{{ $bill->description }}</td>
                                                             </tr>
                                                             </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row invoice-head-wrapper" id="invoice-items-table-wrapper">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <h4 class="m-t-0 header-title text-center"><b>لیست اقلام</b></h4>
                                                    <div class="cart-table table-responsive">
                                                        <table class="table-responsive">
                                                            <thead style="background-color: #f5f5f5;">
                                                            <tr>
                                                                <th class="p-image">ردیف</th>
                                                                <th class="p-name">تصویر محصول</th>
                                                                <th class="p-name">نام محصول</th>
                                                                <th class="p-edit">قیمت</th>
                                                                <th class="p-amount">تعداد</th>
                                                                <th class="p-quantity">تخفیف</th>
                                                                <th class="p-name">قیمت نهایی</th>
                                                                <th class="p-name">تاریخ</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $i = 1; ?>
                                                            @foreach($billItems as $billItem)
                                                                <tr>
                                                                    <td class="p-amount">{{ $i }}</td>
                                                                    <td class="p-image" style="padding: 0;">
                                                                        <a><img style="max-width: 50%;"
                                                                                alt="{{ $billItem->photo }}"
                                                                                src="{{ $billItem->photo }}"
                                                                                class="floatright"></a>
                                                                    </td>
                                                                    <td class="p-name">{{ $billItem->product_name }}</td>
                                                                    <td class="p-name">{{ number_format($billItem->price) }}</td>
                                                                    <td class="p-name">{{ $billItem->quantity }}</td>
                                                                    <td class="p-amount">{{ $billItem->discount }}</td>
                                                                    <td class="p-amount">{{ number_format($billItem->total_price) }}</td>
                                                                    <td class="p-total">{{ $billItem->created_at }}</td>
                                                                </tr>
                                                                <?php $i++; ?>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--</div>--}}
                                        </div>
                                        <!--End of Cart Main Area-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="button-back">
                        <a href="#"
                           class="read-button floatright"><span>برگشت به سفارشات</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Account Area-->

@endsection

