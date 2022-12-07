@extends('frontend.master')
@section('style')
    <title>وورکی | ورود به حساب کاربری</title>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-page-container">
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                            <div class="login-form-container">
                                <ul class="login-register-tabs list-inline text-center">
                                    <li class="active">
                                        <a href="{{ route('show.login.form') }}">ورود</a>
                                    </li>
                                    <li>
                                        <a href="{{ request()->redirectTo ? route('show.register.form' , ['redirectTo' => request()->redirectTo]) : route('show.register.form') }}">ثبت نام</a>
                                    </li>
                                </ul>
                                @include('frontend.errors')
                                <form action="{{ route('do.login') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="mobile">تلفن همراه:</label>
                                        <input autocomplete="off" type="text" value="{{ old('mobile') }}" name="mobile" id="mobile" class="form-control">
                                    </div>
                                    @if(request()->redirectTo)
                                    <input type="hidden" name="redirectTo" value="{{ request()->redirectTo }}">
                                    @endif
                                    <div class="form-group">
                                        <label for="password">رمز عبور:</label>
                                        <input autocomplete="off" type="password" name="password" id="password" class="form-control"><br>
                                        <input type="checkbox" name="remember" id="remember">
                                        <label style="color: #838383;font-size: 13px;font-family: IRANSans;" for="remember">مرا به خاطر بسپار</label><br>
                                        <a href="{{ route('show.forget.password.form') }}" class="forget-password-link">رمز عبورم را فراموش کرده ام</a>
                                    </div>
                                    <button type="submit" class="btn btn-pink">ورود</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection