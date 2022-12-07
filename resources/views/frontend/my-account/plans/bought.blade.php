@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | خرید پلن اشتراک</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid bought-plans-tab-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-certificate"></i>
                                پلن اشتراک
                            </div>
                            <div class="panel-body buy-plan-panel">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        @if($storeValidatePlan == true)
                                            <h4 class="text-center">
                                                کاربر گرامی، شما پلن اشتراک با
                                                <b>{{ $intervalDays }}</b>
                                                روز اعتبار دارید.
                                            </h4>
                                            <h4 class="text-center">
                                                کاربر گرامی، شما پلن اشتراک بازاریابی با
                                                <b>{{ $intervalMarketDays }}</b>
                                                روز اعتبار دارید.
                                            </h4>
                                            <a href="{{ route('user.plan.create.page') }}"
                                               class="btn btn-xs btn-pink">تمدید پلن</a>
                                        @else
                                            <h4 class="text-center">
                                                برای ثبت فروشگاه نیاز به خرید پلن اشتراک دارید.
                                            </h4>
                                            <a href="{{ route('user.plan.create.page') }}"
                                               class="btn btn-xs btn-pink">خرید پلن</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-certificate"></i>
                                پلن های خریداری شده
                            </div>
                            <div class="panel-body">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>عنوان پلن</th>
                                                <th>بازه زمانی</th>
                                                <th>مبلغ پرداختی</th>
                                                <th>تاریخ شروع پلن</th>
                                                <th>تاریخ پایان پلن</th>
                                                <th>شماره پیگیری پرداخت</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($userPlans)
                                                @foreach($userPlans as $plan)
                                                    <tr>
                                                        <td>{{ $plan->name }}</td>
                                                        <td>{{ $plan->month_interval }} ماه</td>
                                                        <td>{{ number_format($plan->price) }} تومان</td>
                                                        <td>{{ \Morilog\Jalali\Jalalian::forge($plan->from_date)->format('%d %B %Y') }}</td>
                                                        <td>{{ \Morilog\Jalali\Jalalian::forge($plan->to_date)->format('%d %B %Y') }}</td>
                                                        <td>{{ $plan->pay_id }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection