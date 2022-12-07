@extends('admin.master')
@section('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet"
    />
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
        .filepond--drop-label{
            background-color: white
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                @include('frontend.errors')
                <form action="{{ route('admin.store.message' , ['user' => $user->id]) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label for="message">متن پیام ارسالی برای کاربر {{ $user->first_name.' '.$user->last_name }}
                        
                        @foreach($user->stores as $s)
                            - <a style="font-size:16px;"
                                 href="{{ route('listOfProductSeller' , $s->user_name) }}">{{ $s->name }}</a>
                        @endforeach
                    </label>
                    <textarea name="message" class="form-control" id="message" cols="20" rows="5"
                              required></textarea><br>
                    <input class="filepond" 
                        data-allow-reorder="true"
                        data-max-file-size="3MB"
                        name="file" type="file" />
                    <button type="submit" class="btn btn-default">ارسال</button>
                    <a href="{{ URL::previous() }}" class="btn btn-dropbox">بازگشت</a>
                    <a  href="{{ route('admin.user.login' , $user->id) }}"
                        class="btn btn-facebook">
                            ورود به سیستم به عنوان کاربر
                        </a>
                
                </form>
                @foreach($messages as $message)
                    <div class="row comment-container">
                        <div class="col-xs-12 col-md-8 {{ $message->user_id == null ? '' : 'col-md-offset-4' }}">
                            @if($message->user_id == null)
                                <div class="pull-left col-xs-2 text-right avatar-container">{{--admin pic--}}
                                    <img src="{{ url()->to('/img/avatar.png') }}" width="40px" alt="">
                                </div>
                            @endif

                            <div class="pull-left col-xs-10 {{ $message->user_id == null ? '' : 'col-md-11' }} comment-section">
                                <div class="comment-inner">
                                    @if($message->user_id == null)
                                        <form action="{{ route('message.delete' , ['message' => $message->id]) }}"
                                              class="delForm{{ $message->id }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <i title="حذف پیام" data-toggle="tooltip" style="cursor: pointer;"
                                               onclick="$('.delForm{{ $message->id }}').submit()"
                                               class="fa fa-close pull-right text-danger"></i>
                                        </form>
                                    @endif
                                    <p class="title">
                                        {{ $message->user_id == null ? 'مدیر' : $message->user->first_name .' '.$message->user->last_name }}
                                        در
                                        تاریخ {{ \Morilog\Jalali\Jalalian::forge($message->created_at)->format('Y/m/d H:i') }}
                                        گفته است :
                                    </p>
                                    <p class="comment-body">{{ $message->message }}</p>
                                    @if($message->attached_file)
                                    <a href="{{route('admin.chats.image' , $message->id)}}" ><img style="border-radius: 0;width:100%;height:auto;max-width:500px" src="{{route('admin.chats.image' , $message->id)}}" /></a>
                                    @endif
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
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
    // Get a reference to the file input element
    var inputElement = document.querySelector('input[type="file"]');
    FilePond.registerPlugin(FilePondPluginImagePreview)
    // Create a FilePond instance
    var pond = FilePond.create(
        inputElement,
        {
            labelIdle : '<div style="display:flex;justify-content:center;align-items:center;cursor:pointer"><span style="font-size:12px;margin-right:10px">جهت درج تصویر لطفا فایل خود را به اینجا بکشید و یا کلیک کنید </span> <i class="fa fa-camera" style="font-size:20px"></i></div>',
            imagePreviewHeight: 170,
            stylePanelLayout: 'compact',
            storeAsFile:true,
            instantUpload:false,
            credits : null
        });
</script>
@endsection