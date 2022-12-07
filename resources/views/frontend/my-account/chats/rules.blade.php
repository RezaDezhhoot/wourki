@extends('frontend.master')
@section('style')
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
    </style>
    <title>وورکی | حساب کاربری من | قوانین و مقررات گفت و گو ها</title>
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
                                <i class="fa fa-book"></i>قوانین و مقررات گفت و گو ها
                            </div>

                            <div class="panel-body">
                                @if($rules)
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="alert alert-warning">
                                                {!! nl2br($rules) !!}
                                            </div>
                                        </div>
                                    </div>
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