<!doctype html>
<html class="no-js" lang="fa">
<head>
    <title>دانلود اپلیکیشن وورکی</title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta charset="utf-8">
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
            width: 370px;
            font-weight: 300;
            font-size: 15px;
        }

        body .section .mobile_number button {
            position: absolute;
            left: 0;
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
            width: 40%;
            position: fixed;
            top: 50%;
            right: 50%;
            margin-right: -305px;
            margin-top: -111px;
            padding: 44px;
        }

        body .user-not-found-container .user-not-found p {
            font-size: 30px;
            font-weight: bold;
            color: #424242;
        }
        .bazar-badge{
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="section">
                <h1 class="text-right">
                دانلود اپلیکیشن وورکی
                </h1>
                <p class="text-right">کسب و کار وورکی محیطی زیبا با کاربردی آسان را برای خرید و فروش محصولات نو و با
                    کیفیت کاربران عزیز فراهم نموده است.
                    با وورکی به راحتی فروشگاهتو بساز محصولات نو ،با کیفیت ، تصاویری زیبا در دسته بندی هایی که داخل سایت
                    معرفی شده در فروشگاه قرار بده (و البته تبلیغات شما به عهده وورکی می باشد) و درآمد کسب کن .</p>
                <a href="https://cafebazaar.ir/app/com.idea_bonyan.Injas_Kala/?l=fa">
                    <img class="bazar-badge" src="{{ url()->to('/image/cafebazaar-light.png') }}" alt="">
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="image-container">
                <img src="{{ url()->to('/image/mobile-image.png') }}" alt="" class="img-responsive">
            </div>
        </div>
    </div>
</div>
</body>
</html>