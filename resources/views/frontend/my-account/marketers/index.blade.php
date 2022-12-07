@extends('frontend.master')
@section('style')
<link rel="stylesheet" href="{{ URL::to('/fontawesome/css') }}/all.min.css">
    <style>
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
        .btn-pink{
            border: 1px solid #fc2a23;
            color: #fff;
            background-color: #fc2a23;
            margin-left: 10px;
            margin-right:10px;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            display:inline-flex;
            align-items: center;
            justify-content: center;
            width: 200px
        }
        .btn-pink:hover{
            color : white;
        }
        .btn-pink:focus{
            color:white
        }
        @media screen and (max-width: 1199px) {
            .btn-pink{
                margin-top : 10px
            }
        }
        .conversation{
          color: white !important;
          background-color: #FC494C !important;
          border-radius: 40px !important; 
          padding: 8px 32px !important;
          display: inline !important;
          margin-top: 0 !important;
        }
        .conversation:hover{
          color: white !important;
        }
    </style>
    <title>وورکی | حساب کاربری من | بازاریاب ها</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid my-account-tabs-content ">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-share-alt"></i>لیست بازاریاب ها
                            </div>
                            <div class="panel-body">
                            <h4>بازاریاب های محصولات و خدمات شما</h4>
                            @if(count($markets) > 0)
                             <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>نام فروشگاه</th>
                                        <th>نام کاربر</th>
                                        <th>محصول / خدمت</th>
                                        <th>شماره تماس</th>
                                        <th>میزان پورسانت</th>
                                        <th>شروع گفت و گو</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($markets as $market)
                                    <tr>
                                        <td>{{ $market->market->name}}</td>
                                        <td>{{ $market->market->user->first_name . ' ' . $market->market->user->last_name}}</td>
                                        <td>{{ $market->product->name}}</td>
                                        <td>{{ $market->market->user->mobile}}</td>
                                        <td>{{ optional(App\MarketCommission::where('category_id' , $market->product->category->id)->first())->amount}}%</td>
                                        <td><form action="{{route('chats.create')}}" method="POST">
                                                {{ csrf_field() }}
                                            <button type="submit" style="cursor: pointer;"
                                               class="btn conversation"><i class="fa fa-paper-plane"></i></button>
                                            <input hidden name="type" value="store" />
                                            <input hidden name="id" value="{{$market->market->id}}" />
                                        </form></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $markets->links() }}
                        @else
                            <p class="danger">شما هیچ محصول بازاریابی شده ای ندارید</p>
                        @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')

@endsection