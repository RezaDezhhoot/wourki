<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ّOmid Davar">

    <link rel="shortcut icon" href="{{ URL::to('/image/favicon.png') }}">

    <title>فروشگاه | پنل مدیریت</title>
    <script src="{{ URL::to('admin') }}/assets/js/jquery.min.js"></script>
    <script src="{{ url()->to('/admin/assets/js/persian-date.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/persian-datepicker.min.js') }}"></script>
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ URL::to('/admin') }}/assets/plugins/morris/morris.css">
    <link href="{{ URL::to('/admin') }}/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet"
          type="text/css">
    <link href="{{ URL::to('/admin') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/core.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/pages.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/bootstrap-slider.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <script src="{{ URL::to('admin') }}/assets/js/cropper.min.js"></script>
    <link href="{{ URL::to('/admin') }}/assets/css/cropper.min.css" rel="stylesheet" type="text/css"/>

    {{--<script src="{{ URL::to('admin') }}/assets/js/croppie.min.js"></script>--}}
    {{--<link href="{{ URL::to('/admin') }}/assets/css/croppie.css" rel="stylesheet" type="text/css" />--}}

    {{--<script src="{{ URL::to('admin') }}/assets/js/jquery.Jcrop.min.js"></script>--}}
    {{--<link href="{{ URL::to('/admin') }}/assets/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />--}}

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="{{ URL::to('/admin') }}/assets/js/Sortable.min.js"></script>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="{{ URL::to('/admin') }}/assets/js/modernizr.min.js"></script>

    <script src="{{ URL::to('/admin') }}/assets/plugins/morris/morris.min.js"></script>
    <script src="{{ URL::to('/admin') }}/assets/plugins/raphael/raphael-min.js"></script>

    <script type="text/javascript" src="{{ URL::to('/admin/assets/js') }}/jquery.validate.min.js"></script>

    <link href="{{ URL::to('/admin') }}/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css"
          rel="stylesheet"/>
    <link href="{{ URL::to('/admin') }}/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/admin') }}/assets/plugins/multiselect/css/multi-select.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/admin') }}/assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"/>

    <script src="{{ URL::to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript"
            src="{{ URL::to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script src="{{ URL::to('/admin') }}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/persian-datepicker.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/persian-datepicker-blue.min.css') }}">
    @yield('styles')
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HbDPHxl75YCub0FIOxXroYRyRd_dm9U&language=fa&region=IR&libraries=places"></script> --}}
    <style>
        #sidebar-menu span {
            font-weight: bold !important;
        }

        .input-field-errors {
            color: #f44336;
        }

        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }

        a {
            font-size: 11px;
        }
    </style>
</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href="" class="logo"><i style="font-size:12px;" class="icon-magnet icon-c-logo">فروشگاه</i></a>
                <!-- Image Logo here -->
                <!--<a href="index.html" class="logo">-->
                <!--<i class="icon-c-logo"> <img src="assets/images/logo_sm.png" height="42"/> </i>-->
                <!--<span><img src="assets/images/logo_light.png" height="20"/></span>-->
                <!--</a>-->
            </div>
        </div>

        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="">
                    <div class="pull-left">
                        <button class="button-menu-mobile open-left waves-effect waves-light">
                            <i class="md md-menu"></i>
                        </button>
                        <span class="clearfix"></span>
                    </div>

                    <ul class="nav navbar-nav hidden-xs">
                        <li class="dropdown">
                            <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">حساب کاربری<span
                                        class="caret"></span></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right pull-right">
                        <li class="hidden-xs">
                            <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i
                                        class="icon-size-fullscreen"></i></a>
                        </li>
                        <li class="dropdown top-menu-item-xs">
                            <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown"
                               aria-expanded="true"><img src="{{ URL::to('/admin/assets/images') }}/doc_placeholder.png"
                                                         alt="user-img" class="img-circle"> </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('showChangePasswordForm') }}"><i
                                                class="ti-settings m-r-10 text-custom"></i> تغییر رمز عبور</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('adminLogout') }}"><i class="ti-power-off m-r-10 text-danger"></i>
                                        خروج</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <!-- Top Bar End -->

    <!-- ========== Left Sidebar Start ========== -->

    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">
            <!--- Divider -->
            <div id="sidebar-menu">
                <ul>

                    <li class="text-muted menu-title">سایدبار وورکی</li>

                    <li class="has_sub">
                        <a href="{{ route('adminDashboard') }}" class="waves-effect"><i class="ti-home"></i> <span> داشبورد </span></a>
                    </li>
                    <li class="has_sub">
                        <a class="waves-effect"><i class="fa fa-bar-chart"></i> <span> آمار کلی سایت </span>
                        <span class="menu-arrow"> </a>
                            <ul class="list-unstyled">
                            <li class="has_sub">
                                <a href="{{ route('transactions.index') }}" class="waves-effect">
                                    <span> تمامی تراکنش ها </span></a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('transactions.product.upgrade') }}" class="waves-effect">
                                    <span> تراکنش های ارتقا محصول / خدمت </span></a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('transactions.store.upgrade') }}" class="waves-effect">
                                    <span> تراکنش های ارتقا فروشگاه </span></a>
                            </li>
                            <li class="has_sub">
                            <a href="{{ route('transactions.orders') }}" class="waves-effect">
                                <span>سفارشات</span></a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('transactions.ads') }}" class="waves-effect">
                                    <span> تراکنش های تبلیغات </span></a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('transactions.commisions') }}" class="waves-effect">
                                    <span> پورسانت ها </span></a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('discounts.used') }}" class="waves-effect">
                                    <span> تخفیف های استفاده شده </span></a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('list.of.reagent.code.user') }}" class="waves-effect">
                             <span> لیست کاربران معرف </span></a>
                    </li>
                            <li>
                                <a href="{{ route('showListOfPlanSubscription') }}"> پلن ها
                                    <span style="border-radius: 50%;" class="btn btn-xs btn-facebook">{{
                                    \App\PlanSubscription::whereRaw('(
                                        seller_plan_subscription_details.id NOT IN (select plan_id from accounting_documents)
                                    )')
                                    ->count()
                                     }}</span>
                                </a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('wallet.index') }}" class="waves-effect"> کیف پول
                                    <span style="border-radius: 50%;"
                                          class="btn btn-xs btn-facebook">{{ $total_wallet  }}</span>
                                </a>
                            </li>
                            </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-paint-bucket"></i> <span>تعاریف پایه</span>
                            <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('showAllProvinces') }}">شهر ها و استان ها</a></li>
                            <li><a href="{{ route('listOfPlans') }}">پلن ها</a></li>
                            <li><a href="{{ route('attributeList') }}">ویژگی ها</a></li>
                            <li><a href="{{ route('guildList') }}">صنف های محصولات</a></li>
                            <li><a href="{{ route('guildServiceList') }}">صنف های خدمات</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-layout-slider"></i> <span> اسلایدر </span>
                            <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('createSlider') }}">ایجاد اسلایدر</a></li>
                            <li><a href="{{ route('viewSlider') }}">مشاهده اسلایدر</a></li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i>
                            <span> کاربران </span> <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('createUserPage') }}">ثبت کاربر جدید</a></li>
                            <li><a href="{{ route('deleteUserPage') }}" style="color:red">حذف کاربر</a></li>
                            <li><a href="{{ route('showListOfUsers') }}">لیست کاربران
                                    <span style="border-radius: 50%;"
                                          class="btn btn-xs btn-facebook">{{ \App\User::where('banned' , 0)->count() }}</span>
                                    <span style="border-radius: 50%;" class="btn btn-xs btn-pinterest">{{ \App\User::where('become_marketer' , 1)
                                    ->whereNotIn('id', \App\Marketer::select('user_id')->get()->toArray())
                                    ->count() }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('showBannedListOfUsers') }}">
                                    کاربران مسدود شده
                                    <span style="border-radius: 50%;"
                                          class="btn btn-xs btn-facebook">{{ \App\User::where('banned' , 1)->count() }}</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-bookmark-alt"></i> <span>تسویه حساب  </span>
                            <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            {{--                            <li><a href="{{ route('checkout.create') }}"> تسویه حساب جدید</a></li>--}}
                            <li><a href="{{ route('checkoutrequest.index') }}">لیست درخواست ها</a></li>
                        </ul>
                    </li>
                     <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-bookmark-alt"></i> <span>سفارشات فروشگاه ها </span>
                            <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            {{--                            <li><a href="{{ route('checkout.create') }}"> تسویه حساب جدید</a></li>--}}
                            <li><a href="{{ route('bills.adminConfirmIndex',['confirmed'=>1]) }}"> تایید شده
                                    <span style="border-radius: 50%;" class="btn btn-xs btn-facebook">{{
                                    \App\Bill::where('confirmed' , 1)->where('status','pending')
                                    ->whereRaw('(
                                        bill.id NOT IN (select bill_id from accounting_documents)
                                    )')
                                    ->count()
                                    }}</span></a></li>
                            <li><a href="{{ route('bills.adminConfirmIndex',['confirmed'=>2]) }}"> رد شده</a></li>
                            <li><a href="{{ route('bills.adminConfirmIndex',['confirmed'=>0]) }}">در انتظار بررسی
                                    <span style="border-radius: 50%;" class="btn btn-xs btn-facebook">{{
                                    \App\Bill::where('confirmed' , 0)
                                    ->whereRaw('(
                                        bill.id NOT IN (select bill_id from accounting_documents)
                                    )')
                                    ->count()
                                    }}</span></a></li>

                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-bookmark-alt"></i> <span> اسناد حسابداری </span>
                            <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('bills.index') }}"> فروشگاه ها
                                    <span style="border-radius: 50%;" class="btn btn-xs btn-facebook">{{
                                    \App\Bill::where('status' , 'delivered')
                                    ->where('pay_type' , 'online')
                                    ->whereRaw('(
                                        bill.id NOT IN (select bill_id from accounting_documents)
                                    )')
                                    ->count()
                                    }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('accountsDocument.index') }}" class="waves-effect"><i class="ti-save"></i>
                            <span> اسناد ثبت شده</span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ $submittedAccountingDocumentsCount }}
                            </span>
                        </a>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-shopping-cart"></i> <span> فروشگاه ها <span
                                        class="menu-arrow"></span></span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\Store::where('status' , 'pending')->count() }}</span>
                            <span class="menu-arrow">
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('listOfStores') }}" class="waves-effect"><span> همه فروشگاه ها </span></a>
                            </li>
                            <li>
                                <a href="{{ route('listOfPendingStores') }}" class="waves-effect"><span> فروشگاه های در انتظار تایید
                                <span style="border-radius: 50%;"
                                      class="btn btn-xs btn-pinterest">{{ \App\Store::where('status' , 'pending')->count() }}</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('allProductSellerList' , ['store_type' => 'product']) }}" class="waves-effect"><i class="ti-heart"></i>
                            <span> لیست تمام محصولات </span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\ProductSeller::where('product_seller.status' , 'pending')->join('store' , 'product_seller.store_id' , '=' , 'store.id')->where('store_type' , 'product')->count() }}</span>
                        </a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('allServiceSellerList' , ['store_type' => 'service']) }}" class="waves-effect"><i class="ti-heart"></i>
                            <span> لیست تمام خدمات </span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\ProductSeller::where('product_seller.status' , 'pending')->join('store' , 'product_seller.store_id' , '=' , 'store.id')->where('store_type' , 'service')->count() }}</span>
                        </a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('productSellerComments') }}" class="waves-effect"><i class="ti-comment"></i>
                            <span style="font-size: 10px;"> نظرات </span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\ProductSellerComment::where('status' , 'pending')->count() }}</span></a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('report.index') }}" class="waves-effect"><i class="ti-face-sad"></i> <span> گزارش تخلف فروشگاه ها </span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\Report::where('visible' , 0)->count() }}</span></a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('admin.chats.report.index') }}" class="waves-effect"><i class="ti-face-sad"></i> <span> گزارش تخلف گفت و گو ها </span>
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\ReportChat::where('seen' , 0)->count() }}</span></a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('user.list') }}" class="waves-effect"><i class="ti-email"></i>پشتیبانی
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ $newSupportTicketCount }}</span>
                        </a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('upgrades.admin.index') }}" class="waves-effect"><i class="fa fa-level-up"></i>ارتقا ها
                            <span style="border-radius: 50%;"
                                  class="btn btn-xs btn-facebook">{{ \App\Upgrade::query()->count() }}</span>
                        </a>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-shopping-cart"></i> <span> تبلیغات <span
                                        class="menu-arrow"></span></span>
                            @if($countOfPedningAds > 0 )
                                <span style="border-radius: 50%;"
                                      class="btn btn-xs btn-facebook">{{ $countOfPedningAds  }}</span> <span
                                        class="menu-arrow">
                            </span>
                            @endif
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('ads_management') }}" class="waves-effect"><span> لیست تبلیغات </span></a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ads.create') }}"
                                   class="waves-effect"><span> ثبت تبلیغ جدید</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-mobile"></i>
                            <span> اعلان </span> <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li class="has_sub">
                                <a href="{{ route('send.notification.form') }}"
                                   class="waves-effect"><span> ارسال اعلان </span></a>
                                <a href="{{ route('notification.index') }}"
                                   class="waves-effect"><span> لیست اعلانات </span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="{{ url()->to('/setting') }}" class="waves-effect"><i class="ti-settings"></i> <span> تنظیمات</span></a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('exciting.design.page') }}" class="waves-effect"><i class="ti-anchor"></i>
                            <span> طرح های تشویقی </span></a>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('discounts.admin.page') }}" class="waves-effect"><i class="ti-ticket"></i>
                            <span> کد های تخفیف </span></a>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i>
                            <span> بازاریابی </span> <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li class="has_sub">
                                <a href="{{ route('admin.commissions') }}"
                                   class="waves-effect"><span> پورسانت ها </span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-mobile"></i>
                            <span> اپلیکیشن </span> <span class="menu-arrow"></span> </a>
                        <ul class="list-unstyled">
                            <li class="has_sub">
                                <a href="{{ route('admin.application') }}"
                                   class="waves-effect"><span> مدیریت اپلیکیشن </span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="{{ route('adminLogout') }}" class="waves-effect"><i class="ti-power-off"></i> <span> خروج </span></a>
                    </li>

                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- Left Sidebar End -->


    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
@yield('content')
<!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->

    <!-- /Right-bar -->

</div>
<!-- END wrapper -->


<script>
    var resizefunc = [];
</script>
<script src="{{ URL::to('admin') }}/assets/js/bootstrap-rtl.min.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/detect.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/fastclick.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/jquery.slimscroll.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/jquery.blockUI.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/waves.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/wow.min.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/jquery.nicescroll.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/jquery.scrollTo.min.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/select2.full.min.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/bootstrap-slider.min.js"></script>
<!-- jQuery  -->
<script src="{{ URL::to('admin') }}/assets/plugins/moment/moment.js"></script>


<script src="{{ URL::to('admin') }}/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>

<!-- Todojs  -->
<script src="{{ URL::to('admin') }}/assets/pages/jquery.todo.js"></script>

<!-- chatjs  -->
<script src="{{ URL::to('admin') }}/assets/pages/jquery.chat.js"></script>

<script src="{{ URL::to('admin') }}/assets/plugins/peity/jquery.peity.min.js"></script>
@include('swal.swal')

<script src="{{ URL::to('admin') }}/assets/js/jquery.core.js"></script>
<script src="{{ URL::to('admin') }}/assets/js/jquery.app.js"></script>

<script src="{{ URL::to('admin') }}/assets/js/main.js"></script>
<script>
    function transformHyperlink(el){
        var test = $(el);
        var txt = test.html();
        var pattern = /((?:http|ftp|https):\/\/[\w\-_]+(?:\.[\w\-_]+)+(?:[\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?)/gi;
        test.html(txt.replace(pattern, '<a href="$1">$1</a>'));
    }
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
</script>
@yield('scripts')
</body>
</html>