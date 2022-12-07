@extends('frontend.master')
@section('style')
    <title>وورکی | بازنشانی رمز عبور</title>
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
                                <form action="{{ route('auth.user') }}" method="post">
                                    {{ csrf_field() }}
                                    <h1 class="text-center">بازنشانی رمز عبور</h1>
                                    <p class="forget-password-note">در کادرهای زیر پین کدهای ارسالی به تلفن همراه خود و
                                        رمز های عبور انتخابی خود را وارد کنید تا رمز عبور شما بازنشانی شود.</p>
                                    <br>
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="form-group">
                                        <label for="pin_code">پین کد ارسالی</label>
                                        <input autocomplete="off" required type="text" name="pin_code" id="pin_code"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">رمز عبور جدید</label>
                                        <input autocomplete="off" required type="password" name="password" id="password"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">تکرار رمز عبور جدید</label>
                                        <input autocomplete="off" required type="password" name="password_confirmation"
                                               id="password_confirmation" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-pink">بازنشانی رمز عبور</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection