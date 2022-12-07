@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | فاکتورهای فروش</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid sales-list-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        @if(!auth()->guard('web')->user()->store)
                            <div class="alert alert-warning text-center">
                                کاربر گرامی، برای مشاهده فاکتور های فروش خود باید ابتدا فروشگاه خود را ثبت کنید.
                            </div>
                        @else
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-file-invoice"></i>
                                    فاکتورهای فروش
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نام خریدار</th>
                                                <th>آدرس خریدار</th>
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
                                            @if(count($bills) > 0)
                                                @foreach($bills as $bill)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $bill->first_name }} {{ $bill->last_name }}</td>
                                                        <td>
                                                            {{ $bill->address }}
                                                        </td>
                                                        <td>{{ number_format($bill->total_price) }} تومان</td>
                                                        <td>
                                                            @if($bill->pay_type == 'online')<span
                                                                    class="label label-success">آنلاین</span>
                                                            @elseif($bill->pay_type == 'postal')<span
                                                                    class="label label-info">پستی</span>
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
                                                                    <span class="label btn-default label-default">درانتظار تایید</span>
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
                                                            @if($bill->confirmed == 1)
                                                                @if($bill->status == 'pending')
                                                                    {{--<a href="{{ route('user.sales.invoice.make.delivered' , $bill->id) }}"
                                                                       data-toggle="tooltip" title="کالا را تحویل گرفتم">
                                                                        <i class="fa fa-truck"></i>
                                                                    </a>--}}
                                                                    <a href="{{ route('user.sales.invoice.delete' , $bill->id) }}"
                                                                       data-toggle="tooltip" title="لغو سفارش">
                                                                        <i class="fa fa-times"></i>
                                                                    </a>
                                                                @elseif($bill->status == 'delivered')
                                                                    <a data-toggle="tooltip" title="تحویل داده شده">
                                                                        <i style="color: green;"
                                                                           class="fa fa-check"></i>
                                                                    </a>
                                                                @elseif($bill->status == 'rejected')
                                                                    <a data-toggle="tooltip" title="سفارش لغو شده">
                                                                        <i style="color: red;"
                                                                           class="fa fa-frown-o"></i>
                                                                    </a>
                                                                @endif
                                                                <a href="{{ route('user.purchase.sales.bill.item' , $bill->id) }}"
                                                                   data-toggle="tooltip" title="مشاهده جزئیات سفارش">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
