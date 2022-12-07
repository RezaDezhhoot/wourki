@extends('frontend.master')
@section('style')
    <title>وورکی | تماس با ما</title>
@endsection

@section('content')
    <!--Breadcrumb Start-->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="breadcrumb">
                        <a href="{{ route('mainPage') }}"><i class="fa fa-home"></i>خانه</a>
                        <span class="navigation-pipe"><i class="fa fa-angle-left"></i></span>
                        <span class="navigation_page">تماس با ما</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb-->
    <!--About Area Start-->
    <div class="home-hello-info">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="f-title text-center">
                        <h3 class="title text-uppercase">تماس با ما</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="about-page-cntent" style="text-align: right;">
                        <p style="font-size:16px;margin-bottom:20px;color:#000;">دوست عزیز، لطفاً در صورت وجود هرگونه
                            سوال یا ابهامی، دراپ یا وب سایت از طریق روش های زیر با وورکی تماس بگیرید.</p>
                        <h3 style="margin: 3px;">ایمیل پشتیبانی</h3>
                        <p>ایمیل پشتیبانی : wourki@yahoo.com</p>
                        <p>ایمیل مدیریت: info@wourki.com</p>
                        <h3 style="margin: 3px;">دفتر مرکزی</h3>
                        <p>اصفهان - خیابان پروین
                            <br>
                        </p><br><br>
                        @include('frontend.errors')
                        <h3 style="margin: 3px;">فرم تماس</h3>
                        <form action="{{ route('contact.us.mail') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email_in_contact_us_form">ایمیل شما:</label>
                                <input type="email" name="email" id="email_in_contact_us_form" class="form-control"
                                       placeholder="ایمیل خود را وارد کنید...">
                            </div>
                            <div class="form-group">
                                <label for="description_in_contact_us_form">پیام شما:</label>
                                <textarea name="message" id="description_in_contact_us_form" cols="30" rows="5"
                                          class="form-control" placeholder="متن پیام خود را وارد کنید..."
                                          required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">ثبت</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="img-element">
                        <a class="white-hover" href="#"><img style="width: 80%;float: left;" src="{{ url()->to('/img') }}/about/contactUsImage.png"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of ABout ARea-->
@endsection