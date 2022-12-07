@extends('frontend.master')
@section('style')
    <title>وورکی | ثبت نام</title>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page-container">
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                            <div class="login-form-container">
                                <ul class="login-register-tabs list-inline text-center">
                                    <li>
                                        <a href="{{ route('show.login.form') }}">ورود</a>
                                    </li>
                                    <li class="active">
                                        <a href="{{ route('show.register.form') }}">ثبت نام</a>
                                    </li>

                                </ul>
                                @include('frontend.errors')
                                <form action="{{ route('do.register') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="first_name">نام:</label>
                                        <input autocomplete="off" {{ old('first_name') }} type="text" name="first_name" id="first_name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">نام خانوادگی:</label>
                                        <input autocomplete="off" {{ old('last_name') }} type="text" name="last_name" id="last_name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">تلفن همراه:</label>
                                        <input autocomplete="off" {{ old('mobile') }} type="tel" name="mobile" id="mobile" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">ایمیل(اختیاری):</label>
                                        <input autocomplete="off" {{ old('email') }} type="email" name="email" id="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="reagent_code">کد معرف(اختیاری):</label>
                                        <input autocomplete="off" {{ old('reagent_code') }} type="text" name="reagent_code" id="reagent_code" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">رمز عبور:</label>
                                        <input autocomplete="off" type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">تکرار رمز عبور:</label>
                                        <input autocomplete="off" type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-pink">ثبت نام</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection