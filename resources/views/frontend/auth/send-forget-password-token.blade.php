@extends('frontend.master')

@section('style')
    <link rel="stylesheet" href="{{ URL::to('/css') }}/login.css">
@endsection

@section('content')
    <div class="form">
        @if(count($errors->all()) > 0 )
            <div class="alert alert-danger text-center">
                @foreach($errors->all() as $error)
                    {{ $error }} <br/>
                @endforeach
            </div>
        @endif
        <h1>فرم کد احراز هویت</h1>
        @if(Session::has('error'))
            <div class="alert alert-danger">
                <span data-dismiss="alert">
                    {!!Session::get('error')!!}
                </span>
            </div>
        @endif


        @if($errors->has('token'))
            <b class="text-danger">{{ $errors->first('token') }}</b>
        @endif
        <form action="{{ route('reset.password.form.show') }}" method="get">
            {{ csrf_field() }}
            <input type="hidden" name="mobile" value="{{ request()->input('mobile') }}">
            <div class="field-wrap">
                {{--<label>کد فراموشی رمز عبور<span class="req">*</span></label>--}}
                <input name="token" type="text" placeholder="کد فراموشی رمز عبور">
            </div>

            <button type="submit" style="float: none;" class="button button-block"/>ثبت</button>

        </form>

    </div> <!-- /form -->
@endsection

