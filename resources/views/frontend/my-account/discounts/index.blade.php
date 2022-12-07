@extends('frontend.master')
@section('style')
    <style>
        .toast-close{
            float : right !important;
        }
        .comment-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .comment-container img {
            border-radius: 50%;
        }

        .comment-container .title {
            font-weight: bold;
            color: #fc2a23;
            margin-bottom: 10px;
        }

        .comment-container .comment-body {
            text-align: justify;
        }

        .comment-inner {
            background-color: #eee;
            border-radius: 5px;
            padding: 10px 20px;
            box-shadow: 3px 3px 4px #aaa;
        }
        .discount-box{
            margin-top : 20px;
            background-color: white;
            width : 100%;
            height : 100px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .right-part{
            width: 30%;
            background-color: #ddd;
            height : 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
        }
        .left-part{
            width: 70%;
            height : 100%;
            border-right: 3px #aaa dashed;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;

        }
    </style>
    <title>وورکی | حساب کاربری من | کد های تخفیف</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid my-account-tabs-content ">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-percent"></i>کد های تخفیف
                            </div>
                            <div class="panel-body" style="background-color: #f5f5f5">
                                    <div class="row">
                                        @foreach ($discounts as $discount)
                                        <div class="col-md-4 d-flex justify-content-center align-items-center discount-container" data-code="{{$discount->code}}" style="cursor: pointer">
                                            <div class="discount-box">
                                                <div class="right-part">
                                                    <b style="font-size : 13px;color:red">

                                                        {{$discount->type == "percentage" ? $discount->percentage . ' درصد' : str($discount->percentage) . ' تومان'}}
                                                    </b>
                                                    <b style="font-size : 12px;">
                                                        تخفیف
                                                    </b>
                                                    <b style="font-size : 15px;">
                                                        {{$discount->name}}
                                                    </b>
                                                </div>
                                                <div class="left-part">
                                                    <div class="w-100" >
                                                        کد تخفیف : <span style="color : green">{{$discount->code}}</span>
                                                    </div>
                                                    <div class="w-100" style="color : darkorange">
                                                        اعتبار تا تاریخ :  {{\Morilog\Jalali\Jalalian::forge($discount->end_date)->format('%d %B %Y')}}
                                                    </div>
                                                    <div class="w-100" >
                                                        <b>{{'حداقل میزان خرید : '.$discount->min_price.' تومان'}}</b>
                                                    </div>
                                                    <div class="w-100" >
                                                        <b>{{$discount->max_price ? 'حداکثر میزان خرید : '.$discount->max_price.' تومان' : 'بدون سقف خرید'}}</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
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
        $('.discount-container').click(function(){
            navigator.clipboard.writeText($(this).data('code'));
            Toastify({
                text: "کد تخفیف کپی شد !",
                duration: 3000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "#FC2A23",
                    direction : 'rtl'
                },
                onClick: function(){} // Callback after click
            }).showToast();
        });
    </script>
@endsection