<html class="no-js" lang="fa">
<head>
    <title>دانلود اپلیکیشن وورکی</title>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ URL::to('/image/favicon.png') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="{{ URL::to('/js') }}/jquery-1.9.1.min.js"></script>
    <link rel="stylesheet" href="{{ URL::to('/css') }}/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="{{ URL::to('/fontawesome/css') }}/all.min.css">
    <link rel="stylesheet" href="{{ URL::to('/css') }}/fontiran.css">
    <style>
        body {
            background: linear-gradient(-45deg, #253698 0%, #FD3F70 100%);
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            font-family: IRANSans;
        }

        body .section {
            padding: 20px;
        }

        body .section h1 {
            color: #fff;
            font-size: 26px;
            margin-top: 172px;
            line-height: 57px;
        }

        body .section p {
            color: #fff;
            font-size: 14px;
            margin-top: 20px;
            line-height: 30px;
            font-weight: 300;
            margin-bottom: 30px;
        }

        body .section .mobile_number {
            position: relative;
        }

        body .section .mobile_number input {
            padding: 9px 38px;
            height: 49px;
            border-top-right-radius: 65px;
            border-bottom-right-radius: 65px;
            width: 70%;
            font-weight: 300;
            font-size: 15px;
        }

        body .section .mobile_number button {
            position: absolute;
            left: 0;
            width: 25%;
            top: 0;
            padding: 15px 51px;
            margin-left: 118px;
            border-top-left-radius: 65px;
            border-bottom-left-radius: 65px;
            border: none;
            background-image: linear-gradient(74deg, #ff0613, #8b00af);
            color: #fff;
        }

        body .section .mobile_number input:focus {
            box-shadow: none;
            outline: none;
            border-color: transparent;
        }

        body .image-container img {
            margin-top: 63px;
        }

        body .user-not-found-container {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(68, 68, 68, 0.7);
            z-index: 9999;
        }

        body .user-not-found-container .user-not-found {
            background-color: #d1d1d1;
            border-radius: 5px;
            width: 283px;
            position: fixed;
            top: 50%;
            right: 50%;
            margin-right: -143px;
            margin-top: -111px;
            padding: 44px;
        }

        body .user-not-found-container .user-not-found p {
            font-size: 30px;
            font-weight: bold;
            color: #424242;
        }

        form .error-text {
            margin-right: 26px;
            font-weight: 300 !important;
        }

        @media screen and (max-width: 580px) {
            body .section .mobile_number input {
                display: block;
                max-width: 100%;
            }

            body .section .mobile_number button {
                display: block;
                max-width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @if(session('mobile_number_nof_found'))
            <div class="user-not-found-container">
                <div class="user-not-found">
                    <p class="text-center">{{ session('mobile_number_nof_found') }}</p>
                </div>
            </div>
        @endif
        @if(isset($mobile_number_nof_found))
            <div class="user-not-found-container">
                <div class="user-not-found">
                    <p class="text-center">{{ $mobile_number_nof_found }}</p>
                </div>
            </div>
        @endif

        <div class="col-xs-12 col-md-6">
            <div class="section">
                <h1 class="text-right">دوست شما،
                    @if(isset($mobile_number_nof_found) && $mobile_number_nof_found)
                        ****
                    @else
                        @php
                            $referrer = \App\User::where('mobile' , request()->session()->get('referrer_mobile_number'))->first();
                        @endphp
                        {{ $referrer->first_name }} {{ $referrer->last_name }}
                    @endif
                    شما را به دانلود اپلیکیشن وورکی دعوت کرده است.</h1>
                <p class="text-right">کسب و کار وورکی محیطی زیبا با کاربردی آسان را برای خرید و فروش محصولات نو و با
                    کیفیت کاربران عزیز فراهم نموده است.
                    با وورکی به راحتی فروشگاهتو بساز محصولات نو ،با کیفیت ، تصاویری زیبا در دسته بندی هایی که داخل سایت
                    معرفی شده در فروشگاه قرار بده (و البته تبلیغات شما به عهده وورکی می باشد) و درآمد کسب کن .</p>
                <form id="mobileNumberForm" action="{{ route('web.save_score') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mobile_number">
                        <input autocomplete="off" type="tel" maxlength="11" name="mobile" id="" class="form-control"
                               placeholder="شماره موبایل شما...">
                        <button type="submit">ثبت</button>
                        @if(count($errors->all()) > 0)
                            <p class="red-text error-text">{{ $errors->first('mobile') }}</p>
                        @endif
                        <p class="red-text error-text"></p>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="image-container">
                <img src="{{ url()->to('/image/mobile-image.png') }}" alt="" class="img-responsive">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ url()->to('/js/jquery.validate.min.js') }}"></script>
<script>
    jQuery.validator.addMethod('length11', function (value, element) {
        if (value.length != 11) {
            return false;
        }
        return true;
    }, 'تلفن همراه باید دقیقا 11 رقم باشد.');
    $('#mobileNumberForm').validate({
        rules: {
            mobile: {
                required: true,
                length11: true
            }
        },
        messages: {
            mobile: {
                required: 'تلفن همراه الزامی است.'
            }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.next('button').next('.error-text'));
        }
    });
</script>
</body>
</html>