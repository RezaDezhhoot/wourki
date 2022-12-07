@extends('admin.master')
@section('styles')
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        @if(count($comments) > 0)
                        <h2 class="page-header">نظرات</h2>
                        <section class="comment-list">
                            <!-- First Comment -->
                            @foreach($comments as $comment)
                                <article class="row">
                                    <div class="col-md-2 col-sm-2 hidden-xs">
                                        <figure class="thumbnail">
                                            <img class="img-responsive" src="{{ URL::to('/admin') }}/assets/images/doc_placeholder.png">
                                            <figcaption class="text-center">{{ $comment->user_first_name }}
                                                &nbsp;{{ $comment->user_last_name }}</figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-md-10 col-sm-10">
                                        <div class="panel panel-default arrow left">
                                            <div class="panel-body">
                                                <header class="text-left">
                                                    <div class="comment-user"><i class="fa fa-user"></i> ارسال شده توسط {{ $comment->user_first_name }}
                                                    </div>
                                                    <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> ارسال شده در تاریخ {{ jDate($comment->created_at)->format('%B %d، %Y') }}
                                                    </time>
                                                </header>
                                                <br>
                                                <div class="comment-post">
                                                    <p>
                                                        {{ $comment->comment }}
                                                    </p>
                                                </div>
                                                <p class="text-right">
                                                <p class="text-right">
                                                    <a href="{{ route('approvedComment' , $comment->id) }}" class="btn btn-success btn-sm">تایید
                                                        @php
                                                            if ($comment->status == 'approved')
                                                            echo '<i class="ti-check"></i>';
                                                        @endphp
                                                    </a>
                                                    <a href="{{ route('rejectComment' , $comment->id) }}" class="btn btn-warning btn-sm">رد کردن
                                                        @php
                                                            if ($comment->status == 'rejected')
                                                            echo '<i class="ti-check"></i>';
                                                        @endphp
                                                    </a>

                                                    <a href="{{ route('deleteComment' , $comment->id) }}" class="btn btn-danger btn-sm">حذف
                                                        @php
                                                            if ($comment->status == 'deleted')
                                                            echo '<i class="ti-check"></i>';
                                                        @endphp
                                                    </a>

                                                    <a href="{{ route('pendingComment' , $comment->id) }}" class="btn btn-default btn-sm"> معلق
                                                        @php
                                                            if ($comment->status == 'pending')
                                                            echo '<i class="ti-check"></i>';
                                                        @endphp
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </section>
                            @else
                            <h2 class="page-header">برای این محصول نظری ثبت نشده است.</h2>
                        @endif
                    </div>
                </div>

                @if(count($comments) > 0)
                    <div class="row">
                        <div class="col-xs-12">
                            <ul class="pagination pagination-split">
                                @if($comments->currentPage() != 1)
                                    <li>
                                        <a href="{{ $comments->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                @endif
                                @for($i =1 ; $i <= $comments->lastPage() ; $i++)
                                    <li class="{{ $i == $comments->currentPage() ? 'active' : '' }}">
                                        <a href="{{ $comments->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                @if($comments->currentPage() != $comments->lastPage())
                                    <li>
                                        <a href="{{ $comments->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>





@endsection

@section('scripts')
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"
            type="text/javascript"></script>

    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/countries.js"></script>
    <script type="text/javascript" src="{{ url()->to('/admin') }}/assets/pages/autocomplete.js"></script>

@endsection