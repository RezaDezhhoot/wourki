@extends('frontend.master')

@section('style')
    <link rel="stylesheet" href="{{ URL::to('/css') }}/login.css">
    <title>وورکی | تایید تلفن همراه</title>
@endsection

@section('content')
    <div class="form">

        <h1>فرم کد احراز هویت</h1>
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
        <form action="{{ route('verifyMobile') }}" method="post">
            {{ csrf_field() }}
            <div class="field-wrap">
                {{--<label>کد احراز هویت<span class="req">*</span></label>--}}
                <input name="referral_code" type="text" placeholder="کد احراز هویت">
            </div>

            <button type="submit" style="float: none;" class="button button-block"/>ثبت</button>

        </form>

    </div> <!-- /form -->
@endsection

