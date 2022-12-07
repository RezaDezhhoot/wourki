@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | فاکتورهای خرید</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid purchase-list-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-file-invoice"></i>
                                فاکتورهای خرید
                            </div>
                            <div class="panel-body">
                                <div class="alert alert-danger pink-alert text-center">
                                    هشدار ! خریدار گرامی ، شما 2 روز مهلت دارید کالای تحویل گرفته شده خود را تایید نمایید در غیر اینصورت مبلغ واریزی شما به حساب فروشنده واریز می گردد
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نام فروشگاه</th>
                                            <th>آدرس فروشگاه</th>
                                            <th>مبلغ فاکتور</th>
                                            <th>نحوه پرداخت</th>
                                            <th>شماره پیگیری پرداخت</th>
                                            <th class="text-center">وضعیت</th>
                                            <th>تاریخ ثبت فاکتور</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($bills as $bill)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $bill->store }}</td>
                                                <td>
                                                    استان
                                                    {{ $bill->province_name }} -
                                                    شهر
                                                    {{ $bill->city_name }} -
                                                    {{ $bill->store_address }}</td>
                                                <td>{{ number_format($bill->total_price) }} تومان</td>
                                                <td>
                                                    @if($bill->pay_type == 'online')<span class="label label-success">آنلاین</span>
                                                    @elseif($bill->pay_type == 'postal')<span class="label label-info">حضوری</span>
                                                    @else<span class="label label-warning">کیف پول</span>
                                                    @endif

                                                </td>
                                                <td>{{ $bill->pay_id != null ? $bill->pay_id : '----' }}</td>
                                                <td style="text-align: center;">
                                                    @if($bill->confirmed == 0)
                                                        <span class="label btn-default label-default">درانتظار تایید مدیریت</span>
                                                    @elseif($bill->confirmed == 2)
                                                        <span class="label label-danger "> رد شده توسط وورکی</span>
                                                    @else
                                                        @if($bill->status == 'pending')
                                                            <span class="label btn-default label-default">درانتظار تایید شما</span>
                                                        @elseif($bill->status == 'delivered')
                                                            <span class="label label-success label-success">تحویل داده شده</span>
                                                        @elseif($bill->status == 'rejected')
                                                            <span class="label label-danger label-danger">رد شده</span>
                                                        @elseif($bill->status == 'paid_back')
                                                            <span class="label label-info label-info">هزینه بازگشت داده شده</span>
                                                        @else
                                                            <span class="label label-primary label-primary">تایید شده</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ \Morilog\Jalali\Jalalian::forge($bill->created_at)->format('%d %B %Y') }}
                                                    ساعت {{ \Morilog\Jalali\Jalalian::forge($bill->created_at)->format('H:i') }}</td>
                                                <td class="option-buttons">
                                                    @if($bill->status != 'rejected' && $bill->status != 'delivered' && $bill->confirmed == 1)
                                                        <a href="{{ route('user.purchase.invoice.make.delivered' , $bill->id) }}"
                                                           data-toggle="tooltip" title="کالا را تحویل گرفتم">
                                                            <i class="fas fa-truck"></i>
                                                        </a>
                                                        <a href="{{ route('user.purchase.invoice.delete' , $bill->id) }}"
                                                           data-toggle="tooltip" title="لغو سفارش">
                                                            <i class="fas fa-times reject-bill"></i>
                                                        </a>
                                                    @elseif($bill->status == 'delivered' and $bill->confirmed == 1)
                                                        <a data-toggle="tooltip" title="سفارش تحویل داده شده">
                                                            <i style="color: green;"
                                                               class="fa fa-smile-o reject-bill"></i>
                                                        </a>
                                                    @elseif($bill->status == 'rejected' or $bill->confirmed == 2)
                                                        <a data-toggle="tooltip" title="سفارش لغو شده">
                                                            <i style="color: red;"
                                                               class="fa fa-frown-o reject-bill"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('user.purchase.invoice.bill.item' , $bill->id) }}"
                                                       data-toggle="tooltip" title="مشاهده جزئیات سفارش">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{$bills->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            // $('.reject-bill').click(function (e) {
            //     e.preventDefault();
            //     swal("Are you sure you want to do this?", 'salam');
            // });

        })
    </script>
@endsection
