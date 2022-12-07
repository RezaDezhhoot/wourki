@extends('admin.master')
@section('styles')
    <style>
        .comment-container img {
            border-radius: 50%;
        }

        .comment-container .avatar-container {
            padding-top: 15px;
        }

        .comment-section .comment-inner {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 3px 3px 4px #aaa;
            margin: 10px;
        }

        .comment-container .title {
            font-weight: bold;
            color: #fc2a23;
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                @foreach($messages as $message)
                    <div class="row comment-container">
                        <div class="col-xs-12 col-md-8 {{ $message->is_sent ? '' : 'col-md-offset-4' }}">
                            @if($message->user_id == null)
                                <div class="pull-left col-xs-2 text-right avatar-container">{{--admin pic--}}
                                    <img src="{{ $chat->sender->thumbnail_photo }}" width="40px" alt="">
                                </div>
                            @endif

                            <div class="pull-left col-xs-10 {{ $message->is_sent ? '' : 'col-md-11' }} comment-section">
                                <div class="comment-inner">
                                        <form action="{{ route('admin.chats.messages.delete' , ['message_id' => $message->id]) }}"
                                              class="delForm{{ $message->id }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <i title="حذف پیام" data-toggle="tooltip" style="cursor: pointer;"
                                               onclick="$('.delForm{{ $message->id }}').submit()"
                                               class="fa fa-close pull-right text-danger"></i>
                                        </form>
                                    <p class="title">
                                        {{ $message->is_sent ? $chat->receiver->first_name. ' ' .$chat->receiver->last_name  : $chat->sender->first_name .' '.$chat->sender->last_name }}
                                        در
                                        تاریخ {{ $message->persian_datetime }}
                                        گفته است :
                                    </p>
                                    <p class="comment-body">{{ $message->content }}</p>
                                </div>
                            </div>

                            @if($message->user_id != null)
                                <div class="pull-left col-xs-1 text-right avatar-container">{{--user pic--}}
                                    <img src="{{ url()->to('/img/avatar.png') }}" width="40px" alt="">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @include('admin.footer')
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.comment-body').each(function(i , el){
            transformHyperlink(el);
        })
    })

</script>
@endsection