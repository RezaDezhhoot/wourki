@extends('frontend.master')
@section('style')
    <title>وورکی | رمز عبورم را فراموش کرده ام</title>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page-container">
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                            <div class="login-form-container">
                                @include('frontend.errors')
                                <form action="{{ route('forget.password') }}" method="post">
                                    {{ csrf_field() }}
                                    <h1 class="text-center">فراموشی رمز عبور</h1>
                                    <p class="forget-password-note">در صورتی که رمز عبور خود را فراموش کرده اید، تلفن همراه خود را در کادر زیر وارد نمایید.</p>
                                    <div class="form-group">
                                        <label for="mobile">تلفن همراه:</label>
                                        <input autocomplete="off" type="text" name="mobile" id="mobile" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-pink">ارسال کد فعال سازی حساب کاربری</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection