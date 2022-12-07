@extends('admin.master')
@section('styles')
    <style>
        table tbody th {
            font-size: 12px;
            font-weight: normal;
            color: #202020;
        }

        table thead th {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .dropdown-menu li a {
            border-radius: 0;
        }

        .list-unstyled li, textarea {
            font-size: 12px !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>افزودن کیف پول</b></h4><br>
                            @include('frontend.errors')
                            <form role="form" action="{{ route('wallet.store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="hidden" name="user" value="{{ $user->id }}">
                                            <label for="cost">مبلغ کیف پول افزایشی <span
                                                        style="color: red;">*</span></label>
                                            <input name="cost" value="{{ old('cost') }}" type="number"
                                                   class="form-control input-sm" id="cost" required
                                                   placeholder="مبلغ افزایشی کیف پول برای کاربر مورد نظر">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="submit" style="margin-top: 25px;"
                                                    class="btn btn-sm btn-facebook">ثبت
                                            </button>
                                            <a href="{{ route('showListOfUsers') }}"
                                               style="margin-top: 25px;" class="btn btn-sm btn-pinterest">بازگشت</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>تراکنش های کیف
                                            پول {{ $user->first_name . ' ' . $user->last_name }}</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        <table class="table table-striped m-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>کاربر</th>
                                                <th>مبلغ</th>
                                                <th>نوع</th>
                                                <th>تاریخ</th>
                                            </tr>
                                            </thead>

                                            <tbody id="sortable-list">
                                            @foreach($wallets as $index => $wallet)
                                                <tr>
                                                    <th scope="row">{{ ++$index }}</th>
                                                    <th>{{ $wallet->first_name . ' ' . $wallet->last_name }}</th>
                                                    <th>{{ $wallet->cost }}</th>
                                                    <th>
                                                        @if($wallet->wallet_type == 'input')
                                                            @if($wallet->tracking_code == null) ورودی (توسط مدیر)
                                                            @else ورودی
                                                            @endif
                                                        @elseif($wallet->wallet_type == 'output') خروجی
                                                        @elseif($wallet->wallet_type == 'reagent') معرفی کرده
                                                        @elseif($wallet->wallet_type == 'reagented') معرفی شده
                                                        @elseif($wallet->wallet_type == 'date_gift') هدیه برای خرید در
                                                        تاریخ مقرر
                                                        @elseif($wallet->wallet_type == 'buy_gift') هدیه برای خرید مبلغ
                                                        تعیین شده
                                                        @elseif($wallet->wallet_type == 'register_gift')هدیه ثبت نام
                                                        @elseif($wallet->wallet_type == 'register_gift')هدیه ثبت نام
                                                        @elseif($wallet->wallet_type == 'reagented_create_store')هدیه
                                                        ثبت
                                                        @elseif($wallet->wallet_type == 'reject_order')رد سفارش
                                                        @elseif($wallet->wallet_type == 'first_buy_gift')هدیه اولین خرید
                                                        @elseif($wallet->wallet_type == 'first_sell_gift')هدیه اولین فروش
                                                        @elseif($wallet->wallet_type == 'upgrade_product')ارتقا محصول/خدمت
                                                        @elseif($wallet->wallet_type == 'upgrade_store')ارتقا فروشگاه
                                                        @elseif($wallet->wallet_type == 'buy_ad')خرید تبلیغات
                                                        @elseif($wallet->wallet_type == 'buy_plan')خرید اشتراک
                                                        @else ساخت فروشگاه
                                                        @endif
                                                    </th>
                                                    <th>{{ \Morilog\Jalali\Jalalian::forge($wallet->wallet_created_date)->format('Y/m/d H:i:s') }}</th>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th style="background-color: #00BCD4;"> کیف پول : {{ $sumWallet }}</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {{ $wallets->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection