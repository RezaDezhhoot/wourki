@extends('admin.master')
@section('styles')
    <style>
        table tbody th {
            font-size: 12px;
            font-weight: normal;
            color: #202020;
        }

        table thead th {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .dropdown-menu li button {
            border-radius: 0;
        }

        .list-unstyled li {
            font-size: 12px;
        }

        .select2-container .select2-selection--single {
            height: 28px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px !important;
        }

        .comment-container img {
            border-radius: 50%;
        }

        .comment-container .avatar-container {
            padding-top: 15px;
        }

        .comment-section .comment-inner {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 3px 3px 4px #aaa;
            margin: 10px;
        }

        .comment-container .title {
            font-weight: bold;
            color: #fc2a23;
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن</b></h4><br>
                            <div class="row">
                                @if($errors->any())
                                    <div class="alert alert-danger text-center">
                                        <ul class="list-unstyled">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <form role="form" action="{{ URL::current() }}" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store_name">نام فروشگاه</label>
                                            <input value="{{ request()->input('store_name') }}"
                                                   class="form-control input-sm" type="text" name="store_name"
                                                   id="store_name" placeholder="نام فروشگاه را وارد کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="activity_type">نوع فعالیت</label>
                                            <select name="activity_type" id="activity_type"
                                                    class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                <option {{ request()->input('activity_type') == 'country' ? 'selected' : '' }} value="country">
                                                    در کشور
                                                </option>
                                                <option {{ request()->input('activity_type') == 'province' ? 'selected' : '' }} value="province">
                                                    در استان
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pay_type">نوع پرداختی</label>
                                            <select name="pay_type" id="pay_type" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                <option {{ request()->input('pay_type') == 'online' ? 'selected' : '' }} value="online">
                                                    آنلاین
                                                </option>
                                                <option {{ request()->input('pay_type') == 'postal' ? 'selected' : '' }} value="postal">
                                                    پستی
                                                </option>
                                                <option {{ request()->input('pay_type') == 'both' ? 'selected' : '' }} value="both">
                                                    هردو
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="guild">صنف</label>
                                            <select name="guild" id="guild" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($guilds as $guild)
                                                    <option {{ request()->input('guild') == $guild->id ? 'selected' : '' }} value="{{ $guild->id }}">{{ $guild->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="province">استان</label>
                                            <select name="province" id="province" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($provinces as $province)
                                                    <option {{ request()->input('province') == $province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="city">شهر</label>
                                            <select name="city" id="city" class="js-example-basic-single">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="visibility">وضعیت نمایش</label>
                                            <select name="visibility" id="visibility" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                <option {{ request()->input('visibility') == '1' ? 'selected' : '' }} value="1">
                                                    نمایش
                                                </option>
                                                <option {{ request()->input('visibility') == '0' ? 'selected' : '' }} value="0">
                                                    عدم نمایش
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">وضعیت تایید</label>
                                            <select name="status" id="status" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                <option {{ request()->input('status') == 'approved' ? 'selected' : '' }} value="approved">
                                                    تاییده شده
                                                </option>
                                                <option {{ request()->input('status') == 'rejected' ? 'selected' : '' }} value="rejected">
                                                    رد شده
                                                </option>
                                                <option {{ request()->input('status') == 'pending' ? 'selected' : '' }} value="pending">
                                                    درانتظارتایید
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="subscription">وضعیت اشتراک</label>
                                            <select name="subscription" id="subscription"
                                                    class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                <option {{ request()->input('subscription') == '1' ? 'selected' : '' }} value="1">
                                                    دارای اشتراک
                                                </option>
                                                <option {{ request()->input('subscription') == '0' ? 'selected' : '' }} value="0">
                                                    فاقد اشتراک
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name_of_user">نام کاربر</label>
                                            <input type="text" name="name_of_user"
                                                   value="{{ request()->input('name_of_user') }}" id="name_of_user"
                                                   class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user_mobile">تلفن همراه کاربر</label>
                                            <input type="text" name="user_mobile"
                                                   value="{{ request()->input('user_mobile') }}" id="user_mobile"
                                                   class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store_user_name">نام کاربری فروشگاه</label>
                                            <input type="text" name="store_user_name"
                                                   value="{{ request()->input('store_user_name') }}"
                                                   id="store_user_name"
                                                   class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store_type">نوع فروشگاه</label>
                                            <select name="store_type" id="store_type" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                <option {{ request()->input('store_type') == 'product' ? 'selected' : '' }} value="product">
                                                    محصولات
                                                </option>
                                                <option {{ request()->input('store_type') == 'service' ? 'selected' : '' }} value="service">
                                                    خدمات
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="margin-top: 25px;">
                                            <label for="storeProductCount">نمایش فروشگاه های بدون محصول</label>
                                            <input type="checkbox" name="storeProductCount"
                                                   id="storeProductCount" {{ request()->has('storeProductCount') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="send"
                                                class="btn btn-purple btn-sm waves-effect waves-light">اعمال
                                        </button>
                                        <a href="{{ url()->current() }}"
                                           class="btn btn-default btn-sm waves-effect waves-light">حذف فیلترها</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <a href="#"
                                               data-toggle="modal"
                                               data-target="#stores_export_excel"
                                               class="btn btn-pinterest btn-sm">خروجی اکسل تلفن همراه همه فروشندگان</a>
                                        </div>
                                    </div>
                                    <h4 class="m-t-0 header-title"><b>لیست فروشگاه ها</b></h4>
                                    <p class="text-muted font-13"></p>
                                    @if(count($storeLists) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="">
                                                <div class="table-responsive">
                                                    <table class="table table-striped m-0">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>نام فروشنده</th>
                                                            <th>آپشن ها</th>
                                                            <th>نام کاربری</th>
                                                            <th>معرف</th>
                                                            <th>نام فروشگاه</th>
                                                            <th> نوع فروشگاه </th>
                                                            <th>آدرس</th>
                                                            <th>حداقل میزان خرید</th>
                                                            <th>تعداد محصولات</th>
                                                            <th>وضعیت اشتراک</th>
                                                            <th>وضعیت نمایش</th>
                                                            <th>وضعیت تایید</th>
                                                            <th>ارتقا</th>
                                                            <th>نوع پرداختی</th>
                                                            <th>تاریخ و ساعت ثبت</th>
                                                        </tr>
                                                        </thead>
                                                        <?php $id = 1; ?>
                                                        <tbody id="sortable-list">
                                                        @foreach($storeLists as $storeList)
                                                            <tr>
                                                                <th>{{ $id }}</th>
                                                                <th>
                                                                    <a href="#" data-toggle="modal"
                                                                       data-target="#quick_show_store_{{ $storeList->id }}">{{ $storeList->first_name }} {{ $storeList->last_name }}</a>
                                                                </th>
                                                                <td>
                                                                    <a href="#"
                                                                       data-toggle="modal"
                                                                       data-target="#user_{{ $storeList->id }}_option_buttons"
                                                                       class="btn btn-danger btn-sm">مشاهده</a>
                                                                </td>
                                                                <td>{{ $storeList->user_name }}</td>
                                                                <th>
                                                                    {{ $storeList->referrer_full_name }}
                                                                </th>
                                                                <th>{{ $storeList->store_name }}</th>
                                                                <th>{{ $storeList->store_type == 'product' ? 'فروشگاه محصولات' : ($storeList->store_type == 'service' ? 'فروشگاه خدمات' : 'فروشگاه بازاریابی')}}</th>
                                                                <th>{{ $storeList->address }}</th>
                                                                <th>{{ $storeList->min_pay ? number_format($storeList->min_pay) . 'تومان' : 'ندارد'}} </th>
                                                                <th style="text-align: center;">{{ $storeList->productsCount }}</th>
                                                                <th>
                                                                    @if($storeList->status_subscription == 1)
                                                                        <b class="text-success">دارای اشتراک</b>
                                                                    @else
                                                                        <b class="text-danger">فاقد اشتراک</b>
                                                                    @endif
                                                                </th>
                                                                <th>
                                                                    <div class="btn-group m-b-20">
                                                                        <div class="btn-group">
                                                                            @if(($storeList->visible == 1))
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> نمایش
                                                                                    <span
                                                                                            class="caret"></span>
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> عدم نمایش
                                                                                    <span
                                                                                            class="caret"></span>
                                                                                </button>
                                                                            @endif
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a href="{{ route('show.store.admin.panel' , $storeList->id) }}"
                                                                                       class="btn btn-block btn-xs btn-success">نمایش
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="{{ route('hide.store' , $storeList->id) }}"
                                                                                       class="btn btn-block btn-xs btn-danger">عدم
                                                                                        نمایش
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <th>
                                                                    <div class="btn-group m-b-20">
                                                                        <div class="btn-group">
                                                                            @if($storeList->status == 'approved')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> تایید شده
                                                                                    <span
                                                                                            class="caret"></span>
                                                                                </button>
                                                                            @elseif($storeList->status == 'rejected')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> رد شده
                                                                                    <span
                                                                                            class="caret"></span>
                                                                                </button>
                                                                            @elseif($storeList->status == 'pending')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-info dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> درانتظار
                                                                                    تایید
                                                                                    <span class="caret"></span></button>
                                                                            @endif
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a href="{{ route('approved.store' , $storeList->id) }}"
                                                                                       class="btn btn-success btn-block btn-xs">تاییده
                                                                                        شده</a></li>
                                                                                <li>
                                                                                <a onclick="document.getElementById('rejectForm').action = '{{ route('rejected.store' , $storeList->id) }}'" data-toggle="modal" data-target="#rejectModal"
                                                                                   class="btn btn-danger btn-block btn-xs">رد
                                                                                    کردن</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="{{ route('pending.store' , $storeList->id) }}"
                                                                                       class="btn btn-info btn-block btn-xs">درانتظارتایید</a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <th>
                                                                <button
                                                                        class="btn btn-xs btn-warning waves-effect"
                                                                        data-toggle="modal"
                                                                        type="button"
                                                                        data-target="#upgrade_modal_{{$storeList->id}}"> ارتقا</button>
                                                                </th>
                                                                <th>
                                                                    @if($storeList->pay_type == 'online')آنلاین
                                                                    @elseif($storeList->pay_type == 'postal')پستی
                                                                    @elseآنلاین و پستی
                                                                    @endif
                                                                </th>
                                                                {{--<th>
                                                                    <a href="{{ route('listOfProductSeller' , $storeList->user_name) }}">مشاهده
                                                                        فروشگاه</a>
                                                                    <a class="text-danger"
                                                                       href="{{ route('message.index' , ['user' => $storeList->user_id]) }}">پیام
                                                                        ها</a>
                                                                </th>--}}
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($storeList->created_at)->format('Y/m/d H:i:s') }}</th>
                                                            </tr>
                                                            <?php $id++; ?>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{ $storeLists->links() }}
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            فروشگاهی یافت نشد!
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

            <div class="modal fade"  role="dialog" id="rejectModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">رد کردن فروشگاه</h4>
                </div>
                <form id="rejectForm" action="{{ route('rejected.product' , -1)}}" method="get">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="customMessage">دلیل رد کردن فروشگاه را بنویسید</label>
                            <textarea type="text" name="customMessage" id="customMessage" class="form-control" required ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pink">ثبت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            <div class="modal fade" id="stores_export_excel" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">خروجی اکسل تلفن همراه فروشندگان</h4>
                        </div>
                        <form action="{{ route('export.all.stores.mobile') }}" method="GET">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="export_excel_from_index">دریافت خروجی از ردیف:</label>
                                    <input type="number" name="from_index"
                                           min="0"
                                           max="{{ $numOfStores - 1 }}"
                                           placeholder="عددی بین 0 تا {{ $numOfStores - 1 }} وارد کنید..."
                                           id="export_excel_from_index" class="form-control">
                                    <p class="text-muted">تعداد کل فروشندگان:
                                        <b class="text-success">{{ $numOfStores }}</b>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="limit_in_export_excel">تعداد ردیف ها:</label>
                                    <input type="number"
                                           disabled
                                           value="{{ $defaultExcelExportRowsLimit }}"
                                           id="limit_in_export_excel" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">دریافت خروجی</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- content -->
        @include('admin.footer')
    </div>

    @foreach($storeLists as $storeList)
            <div class="modal fade"  role="dialog" id="upgrade_modal_{{$storeList->id}}">
        <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('upgrades.admin.store.create') }}" method="POST"
                                  class="form-inline" style="margin:20px 0;">
                                {{ csrf_field() }}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ارتقا فروشگاه
                                <b class="text-primary">{{ $storeList->name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                            @php
                                $upgrades = $storeList->upgrades()->where('upgrades.status' , 'approved')->orderByDesc('upgrades.updated_at')->paginate(20);
                            @endphp
                            <h4>تاریخچه ارتقا</h4>
                            @if(count($upgrades) > 0)
                             <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>جایگاه ارتقا</th>
                                        <th>روش پرداخت</th>
                                        <th>مبلغ پرداختی</th>
                                        <th>تاریخ  و ساعت ارتقا</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($upgrades as $upgrade)
                                    <tr>
                                        <td>{{ $upgrade->position->name}}</td>
                                        <td>{{ $upgrade->pay_type == "admin" ? "توسط مدیریت وورکی" : ($upgrade->pay_type == "wallet" ? "کیف پول" : ($upgrade->pay_type == "online" ? "آنلاین" : "پرداخت درون برنامه ای"))}}</td>
                                        <td>{{ $upgrade->price}}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($upgrade->updated_at)->format('H:i:s %d %B %Y') }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $upgrades->links() }}
                        @else
                            <p class="text-danger">این فروشگاه  تا به حال ارتقا داده نشده است</p>
                        @endif
                        <h4>ارتقا سریع</h4>
                                <div class="form-group">
                                    <label for="store_{{ $storeList->id }}_position">انتخاب جایگاه:</label>
                                    <select name="position_id" id="product_{{ $storeList->id }}"
                                            class="form-control">
                                        @foreach($positions as $position)
                                            @if(str_contains($position->position, 'store'))
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <input hidden type="number" name="store_id" value="{{$storeList->id}}" />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="wallet" class="btn btn-success">ثبت از طریق کیف پول</button>
                            <button type="submit" class="btn btn-success">ثبت رایگان</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                        </form>
                    </div>
        </div>
    </div>
        <div class="modal fade" id="user_{{ $storeList->id }}_option_buttons" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>نام فروشنده</th>
                                            <th>نام فروشگاه</th>
                                            <th>صنف</th>
                                            <th>استان / شهر</th>
                                            <th>تعداد محصولات</th>
                                            <th>وضعیت نمایش</th>
                                            <th>لوگو</th>
                                            <th>وضعیت تایید</th>
                                            <th>نوع فعالیت</th>
                                            <th>موبایل</th>
                                            <th>تلفن</th>
                                            <th>تعداد بازدید</th>
                                            <th>وضعیت اشتراک</th>
                                            <th>تاریخ و ساعت ثبت</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $storeList->first_name }} {{ $storeList->last_name }}</td>
                                            <td>{{ $storeList->store_name }}</td>
                                            <td>{{ $storeList->guild_name }}</td>
                                            <td>{{ $storeList->province_name }} - {{ $storeList->city_name }}</td>
                                            <td>{{ $storeList->productsCount }}</td>
                                            <td>
                                                @if($storeList->visible == 1)
                                                    <span class="text-success">نمایان</span>
                                                @else
                                                    <span class="text-danger">پنهان</span>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ url()->to('/image/store_photos') . '/' . $storeList->thumbnail_photo }}"
                                                     width="50px" alt="">
                                            </td>
                                            <td>
                                                @if($storeList->status == 'approved')
                                                    <span class="text-success">تایید شده</span>
                                                @elseif($storeList->status == 'pending')
                                                    <span class="text-warning">در انتظار تایید</span>
                                                @elseif($storeList->status == 'rejected')
                                                    <span class="text-danger">رد شده</span>
                                                @else
                                                    <span class="text-custom">حذف شده</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($storeList->activity_type == 'country')
                                                    در کشور
                                                @else
                                                    در استان
                                                @endif
                                            </td>
                                            <td>{{ $storeList->mobile }}</td>
                                            <td>{{ $storeList->phone_number }}</td>
                                            <td>{{ $storeList->total_hits }}</td>
                                            <td>
                                                @if($storeList->has_subscription == 1)
                                                    <span class="text-success">دارای اشتراک</span>
                                                @else
                                                    <span class="text-danger">فاقد اشتراک</span>
                                                @endif
                                            </td>
                                            <td>{{ \Morilog\Jalali\Jalalian::forge($storeList->created_at)->format('Y/m/d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('message.index' , ['user' => $storeList->user_id]) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">مشاهده
                                    پیام ها</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('listOfProductSeller' , $storeList->user_name) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">مشاهده
                                    فروشگاه</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.store.edit' , $storeList->id) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">ویرایش
                                    فروشگاه</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('bills.index' , [
                                    'store' => $storeList->id
                                ]) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">فاکتور
                                    های فروش</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('showListOfUsers' , [
                                    'user_id' => $storeList->user_id
                                ]) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">مشخصات
                                    فروشنده</a>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="quick_show_store_{{ $storeList->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">مشاهده سریع فروشگاه</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($storeList->photos as $photo)
                                <div class="col-sm-4">
                                    <img width="100%"
                                         src="{{ url()->to('/image/store_photos') }}/{{ $photo->photo_name }}"
                                         class="img-thumbnail" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>نام فروشگاه</td>
                                    <td>{{ $storeList->store_name }}</td>
                                    <td>صنف</td>
                                    <td>{{ $storeList->guild_name }}</td>
                                </tr>
                                <tr>
                                    <td>کاربر ثبت کننده</td>
                                    <td>{{ $storeList->first_name }} {{ $storeList->last_name }}</td>
                                    <td>شماره تماس فروشگاه</td>
                                    <td>{{ $storeList->phone_number }}</td>
                                </tr>
                                <tr>
                                    <td>شماره تماس ثبت کننده فروشگاه</td>
                                    <td>{{ $storeList->mobile }}</td>
                                    <td>تاریخ ثبت</td>
                                    <td>{{ \Morilog\Jalali\Jalalian::forge($storeList->created_at)->format('Y/m/d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td>حوزه فعالیت</td>
                                    <td>
                                        @if($storeList->activity_type == 'country')
                                            در کشور
                                        @else
                                            در استان
                                        @endif
                                    </td>
                                    <td>نوع پرداختی</td>
                                    <td>
                                        @if($storeList->pay_type == 'online')
                                            آنلاین
                                        @elseif($storeList->pay_type == 'postal')
                                            پستی
                                        @else
                                            هر دو
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>وضعیت فعالیت</td>
                                    <td>
                                        @if($storeList->status == 'approved')
                                            تایید شده
                                        @elseif($storeList->status == 'rejected')
                                            رد شده
                                        @elseif($storeList->status == 'pending')
                                            در انتظار تایید
                                        @else
                                            حذف شده
                                        @endif
                                    </td>
                                    <td>وضعیت نمایش</td>
                                    <td>
                                        {{ $storeList->visible == 1 ? 'نمایش' : 'عدم نمایش' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>وضعیت اشتراک</td>
                                    <td>
                                        @if($storeList->status_subscription == 1)
                                            دارای اشتراک
                                        @else
                                            فاقد اشتراک
                                        @endif
                                        <a href="{{ route('planStore'  ,$storeList->user_name) }}" class="text-success">تمدید
                                            / ست کردن پلن</a>
                                    </td>
                                    <td>شعار فروشگاه</td>
                                    <td>{{ $storeList->slogan }}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>لوگوی فروشگاه</td>
                                    <td>
                                        <img src="{{ url()->to('/image/store_photos') }}/{{ $storeList->thumbnail_photo }}"
                                             class="img-thumbnail" alt="">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="text-left">لیست محصولات</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>نام محصول</th>
                                    <th>قیمت</th>
                                    <th>درصد تخفیف</th>
                                    <th>قیمت تمام شده</th>
                                    <th>موجودی انبار</th>
                                    <th>محصول ویژه</th>
                                    <th>وضعیت نمایش</th>
                                    <th>وضعیت تایید</th>
                                    <th>اختیارات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($storeList->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->price }} تومان</td>
                                        <td>{{ $product->discount }}%</td>
                                        <td>
                                            {{ $product->price - ($product->discount / 100 * $product->price) }}
                                            تومان
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-{{ $product->is_vip ? 'success' : 'danger' }} btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    {{ $product->is_vip ? 'بله' : 'خیر' }}
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ route('set.vip.product' , $product->id) }}">بله</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('unset.vip.product' , $product->id) }}">خیر</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-{{ $product->visible == 1 ? 'success' : 'danger' }} btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    {{ $product->visible == 1 ? 'نمایش' : 'عدم نمایش' }}
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ route('show.product' , $product->id) }}">نمایش</a>
                                                    </li>
                                                    <li><a href="{{ route('hide.product' , $product->id) }}">عدم
                                                            نمایش</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-{{ $product->status == 'approved' ? 'success' : ($product->status == 'rejected' ? 'danger' : 'warning') }} btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    {{ $product->status == 'approved' ? 'تایید شده' : ($product->status == 'rejected' ? 'رد شده' : 'در انتظار تایید') }}
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ route('pending.product' , $product->id) }}">در
                                                            انتظار تایید</a></li>
                                                    <li><a href="{{ route('approved.product' , $product->id) }}">تایید
                                                            شده</a></li>
                                                    <li><a href="{{ route('rejected.product' , $product->id) }}">رد
                                                            شده</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    گزینه ها
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('showSingleProduct' , [$storeList->id , $product->id]) }}">مشاهده</a>
                                                    </li>
                                                    <li><a href="{{ route('admin.product.edit.page' , $product->id) }}">ویرایش</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('product.seller.attribute.index' , [$storeList->id , $product->id]) }}">ویژگی
                                                            ها</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        </img>
                    </div>
                </div>
            </div>
        </div>
        <div id="thumbnail-photo-of-store-{{ $storeList->id }}-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">لوگوی فروشگاه</h4>
                    </div>
                    <form action="{{ route('updatePlan') }}" method="post">
                        <div class="modal-body">
                            <img src="{{ url()->to('/image/store_photos') }}/{{ $storeList->thumbnail_photo }}"
                                 class="img-thumbnail" alt="">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="store-{{ $storeList->id }}-messages" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">پیام ها</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.store.message' , ['user' => $storeList->user_id]) }}"
                              method="post">
                            {{ csrf_field() }}
                            <label for="message">متن پیام</label>
                            <textarea name="message" class="form-control" id="message" cols="20" rows="5"
                                      required></textarea><br>
                            <button type="submit" class="btn btn-default">ارسال</button>
                            <a href="{{ URL::previous() }}" class="btn btn-dropbox">بازگشت</a>
                        </form>
                        @foreach($storeList->messages as $message)
                            <div class="row comment-container">
                                <div class="col-xs-12 col-md-8 {{ $message->user_id == null ? '' : 'col-md-offset-4' }}">
                                    @if($message->user_id == null)
                                        <div class="pull-left col-xs-2 text-right avatar-container">{{--admin pic--}}
                                            <img src="{{ url()->to('/img/avatar.png') }}" width="40px" alt="">
                                        </div>
                                    @endif

                                    <div class="pull-left col-xs-10 {{ $message->user_id == null ? '' : 'col-md-11' }} comment-section">
                                        <div class="comment-inner">
                                            @if($message->user_id == null)
                                                <form action="{{ route('message.delete' , ['message' => $message->id]) }}"
                                                      class="delForm{{ $message->id }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <i title="حذف پیام" data-toggle="tooltip" style="cursor: pointer;"
                                                       onclick="$('.delForm{{ $message->id }}').submit()"
                                                       class="fa fa-close pull-right text-danger"></i>
                                                </form>
                                            @endif
                                            <p class="title">
                                                {{ $message->user_id == null ? 'مدیر' : $message->user->first_name .' '.$message->user->last_name }}
                                                در
                                                تاریخ {{ \Morilog\Jalali\Jalalian::forge($message->created_at)->format('Y/m/d H:i') }}
                                                گفته است :
                                            </p>
                                            <p class="comment-body">{{ $message->message }}</p>
                                        </div>
                                    </div>

                                    @if($message->user_id != null)
                                        <div class="pull-left col-xs-1 text-right avatar-container">{{--user pic--}}
                                            <img src="{{ url()->to('/img/avatar.png') }}" width="40px" alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            function getCity() {
                var province = $('#province');
                var city = $('#city');

                if (province) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ url()->to('/admin/province') }}/' + province.val() + '/city/ajax',
                        data: {},

                        success: function (response) {
                            var list = response;
                            city.html('<option disabled selected>::انتخاب کنید::</option>');
                            for (var i = 0; i < list.length; i++) {
                                city.append('<option value="' + list[i].id + '">' + list[i].name + '</option>');
                            }
                            @if(request()->has('city'))
                            city.val({{ request()->input('city') }});
                            @endif
                        }
                    });
                }
            }

            @if(request()->has('city'))
            getCity();
            @endif

            $('#province').change(function () {
                getCity();
            });

            // $('#send').click(function (e) {
            //     if ( $('#store_name').val() == '' && $('#province').val() == null && $('#storeProductCount').attr('checked', false) && $('#city').val() == null && $('#guild').val() == null && $('#visibility').val() == null
            //         && $('#status').val() == null && $('#subscription').val() == null && $('#pay_type').val() == null && $('#activity_type').val() == null ) {
            //         e.preventDefault();
            //         swal ( "خطا" ,  "فیلتری انتخاب نشده است." ,  "error" )
            //     }
            // });

        });
    </script>
@endsection
