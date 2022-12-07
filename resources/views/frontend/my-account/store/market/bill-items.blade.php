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
                        @if(!auth()->guard('web')->user()->market)
                            <div class="alert alert-warning text-center">
                                کاربر گرامی، برای مشاهده فاکتور های فروش خود باید ابتدا فروشگاه بازاریابی خود را ثبت کنید.
                            </div>
                        @else
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-file-invoice"></i>
                                    فاکتورهای بازاریابی
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نام خریدار</th>
                                                <th>نام محصول</th>
                                                <th>قیمت محصول</th>
                                                <th>هزینه بازاریابی</th>
                                                <th class="text-center">وضعیت</th>
                                                <th>تاریخ ثبت فاکتور</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1; ?>
                                            @if(count($billItems) > 0)
                                                @foreach($billItems as $billItem)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $billItem->bill->user->first_name }} {{ $billItem->bill->user->last_name }}</td>
                                                        <td>
                                                            {{ $billItem->product_name }}
                                                        </td>
                                                        <td>{{ number_format($billItem->price) }} تومان</td>
                                                        <td>{{ number_format($billItem->commission_price) }} تومان</td>
                                                        <td style="text-align: center;">
                                                            @if($billItem->bill->confirmed == 0)
                                                                <span class="label btn-default label-default">درانتظار تایید مدیریت</span>
                                                            @elseif($billItem->bill->confirmed == 2)
                                                                <span class="label label-danger "> رد شده توسط وورکی</span>
                                                            @else
                                                                @if($billItem->bill->status == 'pending')
                                                                    <span class="label btn-default label-default">درانتظار تایید</span>
                                                                @elseif($billItem->bill->status == 'delivered')
                                                                    <span class="label label-success label-success">تحویل داده شده</span>
                                                                @elseif($billItem->bill->status == 'rejected')
                                                                    <span class="label label-danger label-danger">رد شده</span>
                                                                @elseif($billItem->bill->status == 'paid_back')
                                                                    <span class="label label-info label-info">هزینه بازگشت داده شده</span>
                                                                @else
                                                                    <span class="label label-primary label-primary">تایید شده</span>
                                                                @endif
                                                            @endif

                                                        </td>
                                                        <td>{{ \Morilog\Jalali\Jalalian::forge($billItem->bill->created_at)->format('%d %B %Y') }}
                                                            ساعت {{ \Morilog\Jalali\Jalalian::forge($billItem->bill->created_at)->format('H:i') }}</td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                {{$billItems->links()}}
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
