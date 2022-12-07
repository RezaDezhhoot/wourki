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
    </style>
    <title>وورکی | حساب کاربری من | معرفی برنامه</title>
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
                                <i class="fa fa-share-alt"></i>معرفی برنامه
                            </div>
                            <div class="panel-body">
                            <div class="row" style="margin-top: 20px;margin-bottom:20px;">
                                <div class="col-lg-4" style="display:flex;align-items:center;height:31.33px">
                                    <span>وورکی را معرفی و کسب درآمد کنید</span>
                                </div>
                            <div class="col-lg-4">
                            <a href="whatsapp://send?text=https://wourki.com %0a{{$whatsapp_text}}"
                                    class="btn-pink"><span>معرفی از طریق واتس اپ</span> <i class="fab fa-whatsapp fa-lg" style="margin-right:10px" aria-hidden="true"></i>
                            </a>
                            </div>
                            <div class="col-lg-4">
                            <a href="tg://msg_url?url=https://wourki.com&text={{$telegram_text}}"
                                    class="btn-pink"><span>معرفی از طریق تلگرام</span> <i class="fab fa-telegram fa-lg" style="margin-right:10px" aria-hidden="true"></i>
                            </a>
                            </div>
                                
                            </div>
                            <h4>کاربران معرفی شده</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>کاربر معرفی شده</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lists as $list)
                                    <tr>
                                        <td>{{ $list->first_name .  ' ' .  $list->last_name }}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($list->created_at)->format('%d %B %Y') }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $lists->links() }}
                            <h4 style="margin-top:50px">فروشگاه های معرفی شده</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>نام فروشگاه</th>
                                        <th>شناسه فروشگاه</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($stores as $store)
                                    <tr>
                                        <td>{{ $store->name }}</td>
                                        <td><a href="{{route('show.store' , ['slug' => $store->user_name])}}">{{'@'.$store->user_name}}</a></td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($store->created_at)->format('%d %B %Y') }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $lists->links() }}
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