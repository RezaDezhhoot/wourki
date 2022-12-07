@extends('admin.master')
@section('content')

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="card-box">
                    <h1 style="font-size:14px;font-weight: bold;" class="text-pink">تغییر رمز عبور</h1>
                    <form action="{{ route('changeAdminPassword') }}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="password">رمز عبور:</label>
                                    @if($errors->has('password'))
                                        <b class="text-danger">{{ $errors->first('password') }}</b>
                                    @endif
                                    <input type="text" name="password" value="{{ old('password') }}" id="password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary">ثبت</button>
                                <a href="{{ route('adminDashboard') }}">
                                    <button type="button" class="btn btn-default">بازگشت</button>
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection