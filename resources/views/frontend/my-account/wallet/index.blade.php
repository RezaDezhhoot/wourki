@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | کیف پول</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid products-list-page">
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fas fa-wallet"></i>
                        کیف پول
                    </div>
                    <div class="panel-body">
                        @if($helpText)
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="alert alert-warning">
                                        {!! nl2br($helpText) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="charge-wallet-button">
                            <button type="button" data-toggle="modal" data-target="#walletChargeModal"
                                    class="btn btn-pink">شارژ کیف پول
                            </button>
                        </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>نوع شارژ</th>
                                        <th>تاریخ</th>
                                        <th>مبلغ</th>
                                        <th>توضیحات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lists as $list)
                                    <tr>
                                        <td>
                                            @if($list->wallet_type == 'input')
                                                @if($list->tracking_code == null) شارژ (توسط مدیر)
                                                @else شارژ
                                                @endif
                                            @elseif($list->wallet_type == 'output') خروجی(استفاده شده)
                                            @elseif($list->wallet_type == 'reagent') معرف کاربر
                                            @elseif($list->wallet_type == 'reagented') معرفی شده
                                            @elseif($list->wallet_type == 'date_gift') هدیه برای خرید در تاریخ تعیین شده
                                            @elseif($list->wallet_type == 'buy_gift') هدیه برای خرید مبلغ تعیین شده
                                            @elseif($list->wallet_type == 'register_gift') هدیه ثبت نام
                                            @elseif($list->wallet_type == 'reagented_create_store') معرفی کاربری که
                                            فروشگاه ساخته است
                                            @elseif($list->wallet_type == 'reject_order')رد سفارش
                                            @elseif($list->wallet_type == 'first_buy_gift')هدیه اولین خرید
                                            @elseif($list->wallet_type == 'first_sell_gift')هدیه اولین فروش
                                            @elseif($list->wallet_type == 'upgrade_product')ارتقا محصول/خدمت
                                            @elseif($list->wallet_type == 'upgrade_store')ارتقا فروشگاه
                                            @elseif($list->wallet_type == 'buy_ad')خرید تبلیغات
                                            @elseif($list->wallet_type == 'buy_plan')خرید اشتراک
                                            @else ساخت فروشگاه
                                            @endif
                                        </td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($list->created_at)->format('%d %B %Y') }}</td>
                                        <td>{{ $list->cost }} تومان</td>
                                        <td>{{ $list->comment }} </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td style="text-align: left;font-weight: bold;" colspan="4">جمع کل:</td>
                                        <td style="background-color: #00AA00;color: #fff;font-weight: bold;">{{ $sumPrice }}
                                            تومان
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                        </div>
                        {{ $lists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" tabindex="-1" role="dialog" id="walletChargeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">شارژ کیف پول</h4>
                </div>
                <form action="{{ route('charge.user.wallet') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="charge_amount">مبلغ شارژ(تومان):</label>
                            <input type="number" name="cost" id="charge_amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pink">پرداخت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
