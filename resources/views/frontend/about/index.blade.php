@extends('frontend.master')
@section('style')
    <title>وورکی | درباره ما</title>
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
                        <span class="navigation_page">درباره ما</span>
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
                        <h3 class="title text-uppercase">درباره ما</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="about-page-cntent">
                        <h3>درباره وورکی</h3>
                        <p>
                            کسب و کار وورکی محیطی زیبا با کاربردی آسان را برای خرید و فروش محصولات نو و با کیفیت شما
                            دوستان عزیز فراهم نموده است .
                        </p>
                        <p>
                            با وورکی به راحتی فروشگاهتو بساز محصولات نو ،با کیفیت ، تصاویری زیبا در دسته بندی هایی که
                            داخل سایت معرفی شده در فروشگاه قرار بده ( و البته تبلیغات شما به عهده وورکی می باشد ) و
                            درآمد کسب کن .
                        </p>
                        <p>
                            گروه وورکی کار خود را از سال 1390 به صورت سنتی و آنلاین شروع کرده و اکنون پس از گذشت 7 سال
                            تجربه بستری کاملا آنلاین و حرفه ای آماده شده که فروشنده و خریدار بدون واسطه و به سادگی هرچه
                            تمام بتوانند با هم درارتباط بوده و محصولات خود را معرفی کنند
                        </p>
                        <p>
                            ما باور داریم که هر فردی می تواند کسب و کاری پر سود و همیشگی در زمینه ای که تخصص دارد داشته
                            باشد و از آن کسب و کار درآمد کسب عالی بدست آورد .
                        </p>
                        <p>
                            با توجه به مشکلاتی که بعضی از مردم در زمینه کسب و کار و اجاره کردن مکانی مناسب برای ایجاد
                            شغل و رساندن آن به درآمد عالی دارند، به این نتیجه رسیدیم که بستری را فراهم کنیم که همه افراد
                            جامعه در هر شغلی فارغ از مرد یا زن بودن، دانشجو یا کارمند بودن و...
                        </p>
                        <p>
                            بتوانند یک کسب و کار مادام العمر پر سود برای خود داشته باشند .
                        </p>
                        <h3 style="margin-top:20px;">این شد که وورکی به وجود آمد</h3>
                        <p>
                            و ماه ها تحقیق و بررسی انجام دادیم تا بتوانیم بستری کاملا آنلاین و حرفه ای و البته با کارایی
                            آسان برای شما دوستان فراهم کنیم .به لطف خداوند مهربان
                        </p>
                        <p style="margin-top:20px;">
                            <b>صاحب امتیاز: </b>
                            <span>روح اله موذنی</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="img-element">
                        <a class="white-hover" href="#"><img alt="" src="{{ url()->to('/img') }}/about/ab.png"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of ABout ARea-->
@endsection