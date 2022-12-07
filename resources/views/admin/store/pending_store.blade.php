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
                                @foreach($errors->all() as $error)
                                    <div class="alert alert-danger text-center">
                                        <ul class="list-unstyled">
                                            <li>{{ $error }}</li>
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                            <form role="form" action="{{ URL::current() }}" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store_name">نام فرشگاه</label>
                                            <input value="{{ request()->input('store_name') }}" class="form-control"
                                                   type="text" name="store_name" id="store_name"
                                                   placeholder="نام فروشگاه را وارد کنید...">
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
                                            <label for=".">وضعیت اشتراک</label>
                                            <select name="subscription" class="js-example-basic-single">
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
                                            <label for="user_full_name">نام کاربر:</label>
                                            <input type="text" name="user_full_name"
                                                   value="{{ request()->input('user_full_name') }}" id="user_full_name"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user_mobile">تلفن همراه کاربر:</label>
                                            <input type="text" name="user_mobile"
                                                   value="{{ request()->input('user_mobile') }}" id="user_mobile"
                                                   class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="send" class="btn btn-purple waves-effect waves-light">
                                            اعمال
                                        </button>
                                        <a href="{{ url()->current() }}"
                                           class="btn btn-default waves-effect waves-light">حذف فیلترها</a>
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
                                                        <th>نام فروشگاه</th>
                                                        <th>نوع فروشگاه</th>
                                                        <th>صنف</th>
                                                        <th>استان و شهر</th>
                                                        <th>آدرس</th>
                                                        <th>حداقل میزان خرید</th>
                                                        <th>تعداد محصولات</th>
                                                        <th>اختیارات</th>
                                                        <th>وضعیت نمایش</th>
                                                        <th>وضعیت تایید</th>
                                                        <th>نوع فعالیت</th>
                                                        <th>نوع پرداختی</th>
                                                        <th>موبایل</th>
                                                        <th>تلفن</th>
                                                        <th>تعداد بازدید</th>
                                                        <th>وضعیت اشتراک</th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($storeLists as $storeList)
                                                        <tr>
                                                            <th>{{ $id }}</th>
                                                            <th>
                                                                <a href="#" data-toggle="modal" data-target="#quick_show_store_{{ $storeList->id }}">{{ $storeList->first_name }} {{ $storeList->last_name }}</a></th>
                                                            <th>{{ $storeList->store_name }}</th>
                                                            <th>{{ $storeList->store_type == 'product' ? 'فروشگاه محصولات' : ($storeList->store_type == 'service' ? 'فروشگاه خدمات' : 'فروشگاه بازاریابی')}}</th>
                                                            <th>{{ $storeList->guild_name }}</th>
                                                            <th>{{ $storeList->province_name }}
                                                                - {{ $storeList->city_name }}</th>
                                                            <th>{{ $storeList->address }}</th>
                                                            <th>{{ $storeList->min_pay ? $storeList->min_pay . 'تومان' : 'ندارد'}} </th>
                                                            <th>0</th>
                                                            <th>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-success btn-xs dropdown-toggle"
                                                                            type="button"
                                                                            id="{{ $storeList->id }}_options"
                                                                            data-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="true">
                                                                        گزینه ها
                                                                        <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu"
                                                                        aria-labelledby="{{ $storeList->id }}_options">
                                                                        <li>
                                                                            <a href="{{ route('listOfProductSeller' , $storeList->user_name) }}">مشاهده
                                                                                فروشگاه</a></li>
                                                                        <li>
                                                                            <a href="{{ route('message.index' , ['user' => $storeList->user_id]) }}">پیام
                                                                                ها</a></li>
                                                                        <li>
                                                                            <a href="#" data-toggle="modal" data-target="#store-{{ $storeList->id }}-messages">مشاهده سریع پیام ها</a></li>

                                                                    </ul>
                                                                </div>
                                                                {{--<a href="{{ route('listOfProductSeller' , $storeList->user_name) }}">مشاهده فروشگاه</a>--}}
                                                            </th>
                                                            <th>
                                                                <div class="btn-group m-b-20">
                                                                    <div class="btn-group">
                                                                        @if(($storeList->visible == 1))
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> نمایش <span
                                                                                        class="caret"></span></button>
                                                                        @else
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> عدم نمایش
                                                                                <span
                                                                                        class="caret"></span></button>
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
                                                                                        class="caret"></span></button>
                                                                        @elseif($storeList->status == 'rejected')
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> رد شده <span
                                                                                        class="caret"></span></button>
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
                                                                                    کردن</a></li>
                                                                            <li>
                                                                                <a href="{{ route('pending.store' , $storeList->id) }}"
                                                                                   class="btn btn-info btn-block btn-xs">درانتظارتایید</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th>{{ $storeList->activity_type == 'province' ? 'دراستان' : 'درکشور' }}</th>
                                                            <th>
                                                                @if($storeList->pay_type == 'online')آنلاین
                                                                @elseif($storeList->pay_type == 'postal')پستی
                                                                @else آنلاین و پستی
                                                                @endif
                                                            </th>
                                                            <th>{{ $storeList->mobile }}</th>
                                                            <th>{{ $storeList->phone_number }}</th>
                                                            <th>{{ $storeList->total_hits }}</th>
                                                            <th>
                                                                @if($storeList->status_subscription == 1)
                                                                    <span class="text-success">دارای اشتراک</span>
                                                                @else
                                                                    <span class="text-danger">فاقد اشتراک</span>
                                                                @endif
                                                            </th>
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

        </div> <!-- content -->
        @include('admin.footer')
    </div>
    @foreach($storeLists as $storeList)
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
                                    <img width="100%" src="{{ url()->to('/image/store_photos') }}/{{ $photo->photo_name }}" class="img-thumbnail" alt="">
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
                                        <img src="{{ url()->to('/image/store_photos') }}/{{ $storeList->thumbnail_photo }}" class="img-thumbnail" alt="">
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
                    </div>
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
                        <form action="{{ route('admin.store.message' , ['user' => $storeList->user_id]) }}" method="post">
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
                            <textarea type="text" name="customMessage" id="customMessage" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pink">ثبت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            function getCity() {
                var province = $('#province');
                var city = $('#city');
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

            @if(request()->input('city'))
            getCity();
            @endif

            $('#province').change(function () {
                getCity();
            });
            $('#send').click(function (e) {
                if ($('#store_name').val() == '' && $('#province').val() == null && $('#city').val() == null && $('#guild').val() == null && $('#visibility').val() == null
                    && $('#subscription').val() == null && $('#pay_type').val() == null && $('#activity_type').val() == null && $('#user_full_name').valid() == null && $('#user_mobile').valid() == null) {
                    e.preventDefault();
                    swal("خطا", "فیلتری انتخاب نشده است.", "error")
                }
            });

        });
    </script>
@endsection
