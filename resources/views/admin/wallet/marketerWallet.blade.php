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
                            <h4 class="m-t-0 header-title"><b>فیلتر</b></h4><br>
                            @include('frontend.errors')
                            <form role="form" action="{{ url()->current() }}" method="get">
                                <div class="row">
                                    <label style="float: right;" for="checkout">نوع تسویه </label>
                                    <div class="col-md-3">
                                        <select name="checkout" id="checkout" class="form-control input-sm">
                                            <option {{ request()->checkout == 'notCheckout' ? 'selected' : '' }} value="notCheckout">
                                                تسویه نشده
                                            </option>
                                            <option {{ request()->checkout == 'both' ? 'selected' : '' }} value="both">
                                                هردور
                                            </option>
                                            <option {{ request()->checkout == 'isCheckout' ? 'selected' : '' }} value="isCheckout">
                                                تسویه شده
                                            </option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-pinterest">فیلتر</button>
                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-facebook">حذف فیلتر</a>
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

                                    <h4 class="m-t-0 header-title"><b>لیست کاربران معرفی شده <span
                                                    class="text-danger">{{ $user->first_name . ' ' . $user->last_name }}</span></b>
                                    </h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-10">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th>#</th>
                                                    <th>کاربر معرفی شده</th>
                                                    <th>مبلغ</th>
                                                    <th>جهت</th>
                                                    <th>تاریخ</th>
                                                    <th>تسویه</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                @foreach($wallets as $index => $wallet)
                                                    <tr>
                                                        <th scope="row">{{ ++$index }}</th>
                                                        <th>{{ $wallet->reagent_user }}</th>
                                                        <th>{{ number_format($wallet->reagent_user_fee) }} تومان</th>
                                                        <th>
                                                            @if($wallet->type == 'reagent')معرفی کاربر
                                                            @else ساخت فروشگاه
                                                            @endif
                                                        </th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($wallet->created_at)->format('Y/m/d') }}</th>
                                                        <th>
                                                            @if($wallet->checkout == 1)
                                                                <span style="border-radius: 0;"
                                                                      class="btn btn-xs btn-success">تسویه شده</span>
                                                            @else
                                                                <span style="border-radius: 0;float: right;"
                                                                      class="btn btn-xs btn-pinterest">تسویه نشده</span>
                                                                <form style="float: right;"
                                                                      action="{{ route('delete.reagent.code', ['reagent' => $wallet->id]) }}"
                                                                      method="post">
                                                                    {{ csrf_field() }}
                                                                    {{ method_field('delete') }}
                                                                    <button style="border-radius: 0;" type="submit"
                                                                            class="btn btn-xs btn-default">حذف
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <form action="{{ route('checkout.marketer') }}" method="post">
                                                {{ csrf_field() }}
                                                <table>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th style="background-color: #00BCD4;"><span
                                                                    style="font-weight: bold;">مبلغ تسویه : {{ number_format($sumWallet) }} تومان </span>
                                                        </th>

                                                        <th style="padding: 0;">
                                                            <input type="hidden" name="user" value="{{ $user->id }}">
                                                            <button type="submit" style="border-radius: 0;"
                                                                    class="btn btn-block btn-linkedin">تسویه کل مبلغ و
                                                                ثبت
                                                                سند
                                                            </button>
                                                        </th>
                                                        <th style="padding: 0;">
                                                            <input style="width: 100%;height: 34px;" type="text"
                                                                   name="tracking_code" required
                                                                   placeholder="کد رهگیری را وارد کنید...">
                                                        </th>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{ $wallets->links() }}

            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection
