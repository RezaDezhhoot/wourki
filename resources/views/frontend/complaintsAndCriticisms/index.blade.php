@extends('frontend.master')


@section('content')
    <!--Breadcrumb Start-->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="breadcrumb">
                        <a href="{{ route('mainPage') }}"><i class="fa fa-home"></i>خانه</a>
                        <span class="navigation-pipe"><i class="fa fa-angle-left"></i></span>
                        <span class="navigation_page">ثبت شکایات و انتقادات</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb-->
    <!--Contact Us Area Start-->
    <div class="contact-us-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-heading">خدمات مشتری - ثبت شکایات و انتقادات</h1>
                </div>
                <div class="col-md-12">
                    <form class="contact-form-box" action="{{ route('complaints.and.criticisms.mail') }}" method="post">
                        {{ csrf_field() }}
                        <fieldset>
                            <h3 class="page-subheading">ارسال یک پیام</h3>
                            @include('frontend.errors')
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <p class="form-group">
                                        <label>آدرس ایمیل</label>
                                        <input type="email" name="email" id="email" class="form-control grey" required placeholder="آدرس ایمیل خود را وارد نمایید">
                                    </p>
                                </div>
                                <div class="col-xs-12 col-md-9">
                                    <div class="form-group">
                                        <label for="message">متن پیام</label>
                                        <textarea name="message" id="message" class="form-control" required placeholder="متن پیام را وارد نمایید..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-default">ارسال</button>
                                {{--<button id="submitMessage" name="submitMessage" type="submit"><span>ارسال<i class="fa fa-chevron-left left"></i></span></button>--}}
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End of Contact Us Area-->
@endsection