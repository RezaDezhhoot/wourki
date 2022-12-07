<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="فروشگاه اینترنتی وورکی فروش محصولات لوکس و تزئینی منزل، لوازم خانگی، کالای دیجیتال، صنایع دستی، فرهنگ و هنر، ورزش و تندرستی، سرگرمی، لوازم تحریر،تخفیف های شگفت انگیز، دکوراسیون داخلی و...">
    <meta name="author" content="Ali Kiani">

    <link rel="shortcut icon" href="{{ url()->to('/image/favicon.png') }}">

    <title>وورکی - ورود به پنل مدیریت</title>

    <link href="{{ url()->to('/admin') }}/assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url()->to('/admin') }}/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="{{ url()->to('/admin') }}/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="{{ url()->to('/admin') }}/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="{{ url()->to('/admin') }}/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="{{ url()->to('/admin') }}/assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="{{ url()->to('/admin') }}/assets/js/modernizr.min.js"></script>

</head>
<body>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
    <div class=" card-box">
        <div class="panel-heading">
            <h3 class="text-center"> ورود  به حساب <strong class="text-custom">وورکی</strong> </h3>
        </div>

        @include('frontend.errors')
        <div class="panel-body">
            <form class="form-horizontal m-t-20" action="{{ route('admin.login') }}" method="post">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="login" autocomplete="off" class="form-control" type="text" required placeholder="ایمیل یا شماره همراه">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="password" autocomplete="off" class="form-control" type="password" required placeholder="کلمه عبور">
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-primary">
                            <input name="remember" id="checkbox-signup" type="checkbox">
                            <label for="checkbox-signup">مرا به خاطر بسپار</label>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center m-t-40">
                    <div class="col-xs-12">
                        <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">ورود</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{ url()->to('/admin') }}/assets/js/jquery.min.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/bootstrap-rtl.min.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/detect.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/fastclick.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/jquery.slimscroll.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/jquery.blockUI.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/waves.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/wow.min.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/jquery.nicescroll.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/jquery.scrollTo.min.js"></script>


<script src="{{ url()->to('/admin') }}/assets/js/jquery.core.js"></script>
<script src="{{ url()->to('/admin') }}/assets/js/jquery.app.js"></script>

</body>
</html>