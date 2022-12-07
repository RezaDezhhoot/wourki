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
    <title>وورکی | حساب کاربری من | ارتباط با پشتیبانی</title>
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
                                <i class="fa fa-comment-alt"></i>پیام ها
                            </div>

                            <div class="panel-body">
                                @if($helpText)
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="alert alert-warning">
                                                {!! nl2br($helpText) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @include('frontend.errors')

                                <div class="row">
                                    <div class="col-xs-12">
                                        <form action="{{ route('user.store.message') }}" class="addAnswerForm"
                                              method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label for="add_answer">افزودن پیام</label>
                                                <textarea style="height:100px;resize: none;" name="message"
                                                          id="add_answer" cols="30" rows="5"
                                                          class="form-control" placeholder="پیام خود را وارد کنید..."
                                                          required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-pink">ارسال</button>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                @foreach($messages as $message)
                                    <div class="row">
                                        <div class="col-xs-12 col-md-8 {{ $message->user_id == null ? 'col-md-offset-4' : '' }}">
                                            <div class="comment-container row">
                                                @if($message->user_id != null)
                                                    <div class="col-xs-2">
                                                        <img width="40px" src="{{ url()->to('/img/avatar.png') }}"
                                                             alt="avatar">
                                                    </div>
                                                @endif
                                                <div class="col-xs-10">
                                                    <div class="comment-inner">
                                                        @if($message->user_id != null)
                                                            <form action="{{ route('message.delete' , ['message' => $message->id]) }}"
                                                                  class="delForm{{ $message->id }}" method="post">
                                                                {{ csrf_field() }}
                                                                {{ method_field('delete') }}
                                                                <i title="حذف پیام" data-toggle="tooltip"
                                                                   style="cursor: pointer;"
                                                                   onclick="$('.delForm{{ $message->id }}').submit()"
                                                                   class="fa fa-close pull-left text-danger"></i>
                                                            </form>
                                                        @endif
                                                        <p class="title">
                                                            {{ $message->user_id == null ? 'مدیر' : 'شما' }}
                                                            در
                                                            تاریخ {{ \Morilog\Jalali\Jalalian::forge($message->created_at)->format('Y/m/d H:i') }}
                                                            گفته {{ $message->user_id == null ? 'است' : 'اید' }} :
                                                        </p>
                                                        <p class="comment-body">{{ $message->message }}</p>
                                                    </div>
                                                </div>
                                                @if($message->user_id == null)
                                                    <div class="col-xs-2">
                                                        <img width="40px" src="{{ url()->to('/img/avatar.png') }}"
                                                             alt="avatar">
                                                    </div>
                                                @endif
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
    </section>
@endsection
@section('script')

@endsection