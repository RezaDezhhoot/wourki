@extends('admin.master')
@section('styles')
    <style>
        h5{font-weight: bold !important;margin: 0;}
        .product_seller span , .plan span , .stores span {font-size: 12px !important;}
        .store a {color: green;}
        .product_seller a {color: #ff0000;}
        .plan a {color: #2A0569;}
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
                                <div class="col-md-4">
                                    <div class="stores">
                                        <h5>فروشگاه ها</h5>
                                        <hr>
                                        <span>تعداد فروشگاه های فعال:</span>
                                        <span> {{ $activeStoresCount }} </span>
                                        <a href="{{ route('listOfStores') }}">مشاهده</a><br>
                                        <span>تعداد کل فروشگاه های درانتظار تایید:</span>
                                        <span style="color: red;font-weight: bold;"> {{ $pendingStoresCount }} </span>
                                        <a href="{{ route('listOfPendingStores') }}">مشاهده</a><br>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="product_seller">
                                        <h5>محصولات فروشگاه ها</h5>
                                        <hr>
                                        <span>تعداد کل محصولات ثبت شده:</span>
                                        <span> {{ $allProductsCount }} </span>
                                        <br/>
                                        <a href="{{ route('allProductSellerList') }}">مشاهده</a><br>
                                        <span>تعداد کل محصولات پنهان:</span>
                                        <span> {{ $hiddenProductsCount }} </span><br/>
                                        <a href="{{ route('allProductSellerList' , ['visibility' => '0']) }}">مشاهده</a><br>
                                        <span>تعداد کل محصولات رد شده:</span>
                                        <span> {{ $rejectedProductsCount }} </span><br/>
                                        <a href="{{ route('allProductSellerList' , ['status' => 'rejected']) }}">مشاهده</a><br>
                                        <span>مبلغ کل فروش این ماه:</span>
                                        <span style="color: #ff0000;"> {{ $billProductOfThisMonthPrice }} تومان </span><br>
                                        <span>مبلغ کل فروش:</span>
                                        <span style="color: #ff0000;"> {{ $totalBillPrice }} <span>تومان</span> </span><br>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="plan">
                                        <h5>پلن ها</h5>
                                        <hr>
                                        <span>تعداد کل پلن های ثبت شده:</span>
                                        <span> {{ $plansCount }} </span>
                                        <a href="{{ route('listOfPlans') }}">مشاهده</a><br>
                                        <span>مبلغ کل فروش این ماه:</span>
                                        <span style="color: #2A0569;"> {{ number_format($billPlanOfThisMonthPrice) }} تومان </span><br>
                                        <span>مبلغ کل فروش:</span>
                                        <span style="color: #2A0569;"> {{ number_format($totalPlanPrice) }} تومان </span><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-box">
                            {!! $billChart->container() !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card-box">
                            {!! $planChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
    {!!  $billChart->script()  !!}
    {!!  $planChart->script()  !!}
@endsection

