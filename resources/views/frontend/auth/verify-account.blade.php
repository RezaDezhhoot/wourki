@extends('frontend.master')
@section('style')
    <title>وورکی | تایید حساب کاربری</title>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page-container">
                    <div class="row">
                        <div class="col-xs-10 col-sm-6 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-3 col-lg-offset-4 col-md-offset-4">
                            <div class="login-form-container">
                                @include('frontend.errors')
                                <form action="{{ route('verify.mobile') }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('put') }}
                                    <h1 class="text-center">احراز هویت</h1>
                                    <p class="forget-password-note">لطفا کد ارسال شده به تلفن همراه خود را در کادر زیر وارد نمایید تا حساب کاربری شما فعال سازی شود</p>
                                    <div class="form-group">
                                        <label for="pin_code">پین کد ارسالی</label>
                                        <input autocomplete="off" type="text" name="pin_code" value="{{ old('pin_code') }}" id="pin_code" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-pink btn-xs vfy-btns">فعال سازی حساب کاربری</button>
                                    <br/>
                                    <button type="submit" class="btn btn-xs btn-pink btn-bordered vfy-btns">ارسال مجدد کد</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection