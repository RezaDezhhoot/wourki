@extends('frontend.master')
@section('style')
    <title>وورکی | ورود - ثبت نام</title>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ URL::to('/css') }}/login.css">
@endsection

@section('content')
    <div class="form">

        <ul class="tab-group">
            <li class="tab"><a href="#signup">ثبت نام</a></li>
            <li class="tab active"><a href="#login">ورود</a></li>
        </ul>

        <div class="tab-content">
            <div id="signup" style="display: none;">
                <h1>فرم ثبت نام</h1>
                @if($errors->has('first_name'))
                    <b class="text-danger">{{ $errors->first('first_name') }}</b><br>
                @endif
                @if($errors->has('last_name'))
                    <b class="text-danger">{{ $errors->first('last_name') }}</b>
                @endif
                <form action="{{ route('userRegister') }}" method="post">
                    {{ csrf_field() }}

                    <div class="top-row">

                        <div class="field-wrap">
                            {{--<label>نام<span>*</span></label>--}}
                            <input type="text" value="{{ old('first_name') }}" name="first_name" placeholder="نام *">
                        </div>

                        <div class="field-wrap">
                            {{--<label>نام خانوادگی<span>*</span></label>--}}
                            <input name="last_name" value="{{ old('last_name') }}" type="text" placeholder="نام خانوادگی *">
                        </div>
                    </div>

                    @if($errors->has('email'))
                        <b class="text-danger">{{ $errors->first('email') }}</b>
                    @endif
                    <div class="field-wrap">
                        {{--<label>آدرس ایمیل<span>*</span></label>--}}
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="ایمیل (اختیاری)">
                    </div>

                    @if($errors->has('mobile'))
                        <b class="text-danger">{{ $errors->first('mobile') }}</b>
                    @endif
                    <div class="field-wrap">
                        {{--<label>موبایل<span>*</span></label>--}}
                        <input name="mobile" value="{{ old('mobile') }}" type="text" placeholder="موبایل *">
                    </div>

                    @if($errors->has('password'))
                        <b class="text-danger">{{ $errors->first('password') }}</b>
                    @endif
                    <div class="field-wrap">
                        {{--<label>پسورد<span>*</span></label>--}}
                        <input name="password" type="password" placeholder="پسورد *">
                    </div>

                    <button style="float: none;" type="submit" class="button button-block"/>ثبت نام</button>

                </form>

            </div>

            <div id="login" style="display: block;">
                <h1>فرم ورود</h1>
                @if(Session::has('msg'))
                    <div class="alert alert-info">
                        <span data-dismiss="alert">
                            {!!Session::get('msg')!!}
                        </span>
                    </div>
                @endif
                <form action="{{ route('userLogin') }}" method="post" autocomplete="off">

                    {{ csrf_field() }}
                    <div class="field-wrap">
                        {{--<label>ایمیل یا شماره موبایل<span>*</span></label>--}}
                        <input name="login" value="{{ old('login') }}" type="text" autocomplete="off" required placeholder="ایمیل یا شماره موبایل">
                    </div>

                    <div class="field-wrap">
                        {{--<label>پسورد<span>*</span></label>--}}
                        <input name="password" type="password" required autocomplete="new-password" placeholder="پسورد">
                    </div>
                    <input type="hidden" name="route" value="{{ request()->route }}">

                    <p style="margin-bottom: 15px;" class="forgot"><a href="" data-toggle="modal" data-target="#update-category-modal">رمز خود را فراموش کرده ام</a></p>

                    <button type="submit" style="float: none;" class="button button-block"/>ورود</button>


                </form>

            </div>

        </div><!-- tab-content -->

    </div> <!-- /form -->

    <div id="update-category-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">شماره موبایل</h4>
                </div>
                <form action="{{ route('sendResetLinkEmail') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input name="mobile" value="" type="text" class="form-control" id="category-name-field" placeholder="شماره موبایل خود را وارد کنید">
                                    @if($errors->has('mobile'))
                                        <b class="text-danger">{{ $errors->first('mobile') }}</b>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info waves-effect waves-light">ارسال</button>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function(){
            $(':input').live('focus',function(){
                $(this).attr('autocomplete', 'off');
            });
            $('form').attr('autocomplete','off');
        });
    </script>
@endsection
