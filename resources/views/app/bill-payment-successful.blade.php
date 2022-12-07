<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>پرداخت موفقیت آمیز</title>
    <link rel="stylesheet" href="{{ url()->to('/frontend/assets/css/fontiran.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body{
            direction:rtl;
            font-family: IRANSans !important;
        }
        h1{
            padding:10px;
            margin-top:10px;
            text-align:center;
            font-size:20px;
            color:#4CAF50;
            font-weight:bold;
        }
        #price{
            background-color:#F57C00;
            color:#fff;
        }
        #price h2{
            padding: 20px;
            margin-top: 12px;
        }
        #trackingCode{
            background-color:#127FC2;
            color:#fff;
            padding:10px 20px;
            font-size:14px;
        }
        #chargeHistoryTable tr td , #chargeHistoryTable tr th{
            text-align:center;
            font-size:14px;
        }
        #returnToApp{
            padding:10px 20px;
        }
        #returnToApp a{
            background-color: #FF9800;
            border-color: #FF9800;
        }
        #returnToApp a:hover , #returnToApp a:focus , #returnToApp a:active{
            background-color: #F57C00;
            border-color: #F57C00;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1>پرداخت موفقیت آمیز</h1>
        </div>
    </div>
    <div class="row" id="price">
        <div class="col">
            <div class="text-center">
                <h2>{{ round(session('price')) }} ریال</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-stripped" id="chargeHistoryTable">
                    <tr>
                        <td>شماره پیگیری</td>
                        <td>{{ session('gateway_tracking_code') }}</td>
                    </tr>
                    <tr>
                        <td>تاریخ پرداخت</td>
                        <td>{{ session('cart_payment_date') }} </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row" id="returnToApp">
        <div class="col">
            <div class="text-center">
                <a href="htttp://back_to_injastkala_app" class="btn btn-primary">بازگشت به اپلیکیشن</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>