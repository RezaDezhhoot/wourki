@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | مدیریت نظرات</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid comments-list-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        @if(!auth()->guard('web')->user()->store)
                            <div class="alert alert-warning text-center">
                                کاربر گرامی، برای مشاهده نظرات باید ابتدا فروشگاه خود را ثبت کنید.
                            </div>
                        @else
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-comment"></i>
                                    مدیریت نظرات
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        @if($comments)
                                            @foreach($comments as $comment)
                                                <div class="col-xs-12 parent">
                                                    <div class="comment-item even">
                                                        <div class="row">
                                                            <div class="col-xs-1" style="text-align:left;">
                                                                <img src="{{ url()->to('/img/avatar.png') }}"
                                                                     alt="avatar"
                                                                     class="img-circle" width="45px">
                                                            </div>
                                                            <div class="col-xs-10">
                                                                <div class="comment-text-container">
                                                                    <p class="title">
                                                                        <input type="hidden" class="commentId"
                                                                               value="{{ $comment->id }}">
                                                                        <span class="author">{{ $comment->userFirstName . ' ' . $comment->userLastName }} </span> {{ \Morilog\Jalali\Jalalian::forge($comment->created_at)->ago() }}
                                                                        برای
                                                                        <b><a href="">{{ $comment->product }}</a></b>
                                                                        گفته است :
                                                                        @if($comment->status == 'approved')
                                                                            <span class="label label-success approved-label span">تایید شده</span>
                                                                        @elseif($comment->status == 'rejected')
                                                                            <span class="label label-danger rejected-label span">رد شده</span>
                                                                        @else
                                                                            <span class="label label-warning pending-label span">در انتظار تایید</span>
                                                                        @endif
                                                                    </p>
                                                                    <p class="body">{{ $comment->comment }}</p>
                                                                    <div class="btn-group accept-and-reject-buttons"
                                                                         role="group">
                                                                        <a class="btn btn-success btn-sm approve-comment"><i
                                                                                    class="fas fa-check"></i> تایید</a>
                                                                        <a class="btn btn-danger btn-sm reject-comment">رد
                                                                            <i class="fas fa-times"></i> </a>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-md-10 col-md-offset-1">
                                                                            @foreach($comment->responses as $response)
                                                                                <div class="row child-comment-wrapper" style="margin-top:14px;">
                                                                                    <div class="col-xs-1"
                                                                                         style="text-align:left;">
                                                                                        <img src="{{ url()->to('/img/avatar.png') }}"
                                                                                             alt="avatar"
                                                                                             class="img-circle"
                                                                                             width="45px">
                                                                                    </div>
                                                                                    <div class="col-xs-10">
                                                                                        <div class="comment-text-container">
                                                                                            <p class="title">
                                                                                                <input type="hidden"
                                                                                                       class="commentId"
                                                                                                       value="{{ $response->id }}">
                                                                                                <span class="author">شما در پاسخ گفته اید:</span>
                                                                                            </p>
                                                                                            <p class="body">{{ $response->comment }}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>

                                                                    </div>
                                                                    <h4>پاسخ دادن به این نظر</h4>
                                                                    <form action="{{ route('user.comments.respond') }}"
                                                                          method="post">
                                                                        {{ csrf_field() }}
                                                                        <div class="form-control-wrapper">
                                                                            <input type="hidden"
                                                                                   name="parent_comment_id"
                                                                                   value="{{ $comment->id }}">
                                                                            <input type="hidden"
                                                                                   name="product_id"
                                                                                   value="{{ $comment->product_id }}">
                                                                            <textarea style="height:70px;"
                                                                                      name="response"
                                                                                      class="form-control"
                                                                                      id="response" cols="30"
                                                                                      rows="5"></textarea>
                                                                            <button type="submit" class="btn btn-pink">
                                                                                ارسال
                                                                            </button>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            $('.approve-comment').click(function () {
                var reject = $(this).closest('.parent').find('.span');
                var commentId = $(this).closest('.parent').find('.commentId').val();

                $.ajax({
                    type: 'post',
                    url: '{{ route('user.comments.make.approved') }}',
                    data: {
                        'comment': commentId,
                        '_token': '{{ csrf_token() }}',
                    },

                    success: function () {
                        reject.removeClass('rejected-label').addClass('approved-label').html('تایید شده');
                        swal('تبریک', 'عملیات با موفقیت انجام شد.', 'success');
                    }
                });
            });

            $('.reject-comment').click(function () {
                var approve = $(this).closest('.parent').find('.span');
                var commentId = $(this).closest('.parent').find('.commentId').val();

                $.ajax({
                    type: 'post',
                    url: '{{ route('user.comments.make.reject') }}',
                    data: {
                        'comment': commentId,
                        '_token': '{{ csrf_token() }}',
                    },

                    success: function () {
                        approve.removeClass('approved-label').addClass('rejected-label').html('رد شده');
                        swal('تبریک', 'عملیات با موفقیت انجام شد.', 'success');
                    }
                });
            });

        })
    </script>
@endsection