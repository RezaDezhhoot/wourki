@extends('frontend.master')

@section('style')
    <link rel="stylesheet" href="{{ URL::to('/css') }}/login.css">
    <title>وورکی | بازنشانی رمز عبور</title>
@endsection

@section('content')
    <div class="form">

        <h1>رمز عبور جدید را وارد کنید</h1>
        @if(Session::has('error'))
            <div class="alert alert-danger">
                <span data-dismiss="alert">
                    {!!Session::get('error')!!}
                </span>
            </div>
        @endif


        @if($errors->has('referral_code'))
            <b class="text-danger">{{ $errors->first('referral_code') }}</b>
        @endif
        <form action="{{ route('resetPassword') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="mobile" value="{{ $mobile }}">
            <input type="hidden" name="token" value="{{ $token }}">

            @if($errors->has('password'))
                <b class="text-danger">{{ $errors->first('password') }}</b>
            @endif
            <div class="field-wrap">
                {{--<label>وارد کردن پسورد جدید<span class="req">*</span></label>--}}
                <input name="password" type="text" placeholder="وارد کردن پسورد جدید">
            </div>
            @if($errors->has('password_confirmation'))
                <b class="text-danger">{{ $errors->first('password_confirmation') }}</b>
            @endif
            <div class="field-wrap">
                {{--<label>وارد کردن دوباره پسورد<span class="req">*</span></label>--}}
                <input name="password_confirmation" type="text" placeholder="وارد کردن دوباره پسورد">
            </div>

            <button type="submit" style="float: none;" class="button button-block"/>ثبت</button>

        </form>

    </div> <!-- /form -->
@endsection

