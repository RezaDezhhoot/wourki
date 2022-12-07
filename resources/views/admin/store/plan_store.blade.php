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

        .dropdown-menu li button {
            border-radius:0;
        }
        .list-unstyled li {font-size: 12px;}
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5><b> پلن های خریداری شده فروشگاه <span class="text-danger">{{ $store->name }}</span> </b></h5>
                                    <p class="text-muted font-13"></p>
                                    @if(count($planInfos) > 0)
                                        <div class="p-10">
                                            <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام پلن</th>
                                                        <th>تاریخ آغاز اشتراک</th>
                                                        <th>تاریخ پایان اشتراک</th>
                                                        <th>شماره پیگیری پرداخت</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                <?php $id = 1; ?>
                                                <tbody id="sortable-list">
                                                @foreach($planInfos as $planInfo)
                                                    <tr>
                                                        <th>{{ $id }}</th>
                                                        <th>{{ $planInfo->plan_name }}</th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($planInfo->from_date)->format('%d %B %Y') }}</th>
                                                        <th>{{\Morilog\Jalali\Jalalian::forge($planInfo->to_date)->format('%d %B %Y') }}</th>
                                                        <th>{{ $planInfo->pay_id }}</th>
                                                        <th>
                                                            <a href="{{ route('plans.subscription.delete' , $planInfo->subscription_id ) }}"
                                                               class="btn btn-danger btn-xs">حذف اشتراک</a>
                                                        </th>
                                                    </tr>
                                                    <?php $id++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            پلنی خریداری نشده است!
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5><b>ثبت پلن اشتراک جدید</b></h5>
                                    <p class="text-muted font-13"></p>
                                    @if(count($plans) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="{{ route('setPlanStore' , $slug->user_name) }}" method="post">
                                                {{ csrf_field() }}
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>#</th>
                                                        <th>نام پلن</th>
                                                        <th>بازه زمانی</th>
                                                        <th>قیمت خرید</th>
                                                        <th>توضیحات</th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($plans as $plan)
                                                        <tr>
                                                            <th><input type="radio" name="plan" class="plan" value="{{ $plan->id }}"></th>
                                                            <th>{{ $id }}</th>
                                                            <th>{{ $plan->plan_name }}</th>
                                                            <th>{{ $plan->month_inrterval }} ماه </th>
                                                            @if($plan->price == 0)
                                                                <th>رایگان</th>
                                                            @else
                                                                <th>{{ $plan->price }} تومان </th>
                                                            @endif
                                                            <th>{{ $plan->description }}</th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-6 col-md-offset-4">
                                                        <button id="submit" type="submit" disabled="disabled" class="btn btn-sm btn-youtube waves-effect waves-light">فعال سازی پلن برای این فروشگاه</button>
                                                        <a href="{{ route('listOfProductSeller' , $slug->user_name) }}" class="btn btn-sm btn-github waves-effect waves-light">بازگشت</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        @else
                                        <div class="alert alert-danger text-center">
                                            پلنی تا کنون ثبت نشده است!
                                        </div>
                                    @endif
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
@section('scripts')
    <script>
        $(document).ready(function () {
           $('.plan').click(function () {
               $('#submit').removeAttr('disabled');
           });
        });
    </script>
@endsection
