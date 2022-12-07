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

        .seller-info span, .seller-info a, .seller-info b {
            font-size: 11px;
        }

        .store-info span, .store-info b, .store-info a {
            font-size: 11px;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <div class="row">
                                @if($storeType == "product")
                                <h4 class="m-t-0 header-title"><b>فیلتر کردن محصولات</b></h4><br>
                                @else
                                <h4 class="m-t-0 header-title"><b>فیلتر کردن خدمات</b></h4><br>
                                @endif
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="key_word">کلمه کلیدی</label>
                                                <input type="text" value="{{ request()->input('key_word') }}"
                                                       name="key_word" id="key_word" class="form-control input-sm"
                                                       placeholder="قسمتی از نام یا توضیحات را وارد کنید...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price_from">قیمت از</label>
                                                <input type="number" value="{{ request()->input('price_from') }}"
                                                       name="price_from" id="price_from" class="form-control input-sm"
                                                       placeholder="قیمت ابتدایی را وارد کنید...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price_to">قیمت تا</label>
                                                <input type="number" value="{{ request()->input('price_to') }}"
                                                       name="price_to" id="price_to" class="form-control input-sm"
                                                       placeholder="قیمت پایانی را وارد کنید...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="user_full_name">نام کاربر:</label>
                                                <input type="text" name="user_full_name"
                                                       value="{{ request()->input('user_full_name') }}"
                                                       id="user_full_name" class="form-control" placeholder="نام کاربر">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="user_mobile">تلفن همراه کاربر:</label>
                                                <input type="text" name="user_mobile"
                                                       value="{{ request()->input('user_mobile') }}" id="user_mobile"
                                                       class="form-control" placeholder="تلفن همراه کاربر">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="store">فروشگاه</label>
                                                <select name="store" id="store" class="form-control input-sm">
                                                    <option value="all" disabled selected>::همه::</option>
                                                    @foreach($stores as $store)
                                                        <option {{ request()->input('store') == $store->id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status">وضعیت تایید</label>
                                                <select name="status" id="status" class="form-control input-sm">
                                                    <option value="all" disabled selected>::همه::</option>
                                                    <option {{ request()->input('status') == 'approved' ? 'selected' : '' }} value="approved">
                                                        تایید شده
                                                    </option>
                                                    <option {{ request()->input('status') == 'rejected' ? 'selected' : '' }} value="rejected">
                                                        رد شده
                                                    </option>
                                                    <option {{ request()->input('status') == 'pending' ? 'selected' : '' }} value="pending">
                                                        در انتظار تایید
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="visibility">وضعیت نمایش</label>
                                                <select name="visibility" id="visibility" class="form-control input-sm">
                                                    <option value="all" disabled selected>::همه::</option>
                                                    <option {{ request()->input('visibility') == '1' ? 'selected' : '' }} value="1">
                                                        نمایش
                                                    </option>
                                                    <option {{ request()->input('visibility') == '0' ? 'selected' : '' }} value="0">
                                                        عدم نمایش
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="radio radio-primary col-sm-3">
                                                    <input type="radio" name="exists" id="exists"
                                                           value="1" {{ request()->input('exists') == '1' ? 'checked' : '' }}>
                                                    <label for="exists">موجود</label>
                                                </div>
                                                <div class="radio radio-danger col-sm-5" style="margin-top: 10px;">
                                                    <input type="radio" name="exists" id="exists"
                                                           value="0" {{ request()->input('exists') == '0' ? 'checked' : '' }}>
                                                    <label for="exists">ناموجود</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info col-sm-5" style="margin-top: 10px;">
                                                    <input type="checkbox" name="vip_products" id="vip_products"
                                                            {{ request()->has('vip_products') ? 'checked' : '' }}>
                                                    @if($storeType == 'product')
                                                    <label for="vip_products">محصولات ویژه</label>
                                                    @else
                                                    <label for="vip_products">خدمات ویژه</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" id="send" class="btn btn-xs btn-facebook">اعمال
                                            </button>
                                            <a href="{{ url()->current() }}" class="btn btn-xs btn-default">حذف
                                                فیلترها</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if($storeType == 'product')
                                    <h4 class="m-t-0 header-title"><b>لیست محصولات</b></h4>
                                    @else
                                    <h4 class="m-t-0 header-title"><b>لیست خدمات</b></h4>
                                    @endif
                                    <p class="text-muted font-13"></p>
                                    <br>

                                    @if(count($allProduct) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="{{ url()->current() }}">
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        @if($storeType == 'product')
                                                        <th>نام محصول</th>
                                                        @else
                                                        <th>نام خدمت</th>
                                                        @endif
                                                        <th>نام فروشگاه</th>
                                                        <th>توضیحات</th>
                                                        <th>تاریخ و ساعت ثبت</th>
                                                        <th>دسته بندی</th>
                                                        <th>قیمت</th>
                                                        <th>تخفیف</th>
                                                        <th>تعداد</th>
                                                        <th>محصول ویژه</th>
                                                        <th>وضعیت نمایش</th>
                                                        <th>گزینه ها</th>
                                                        <th>وضعیت تایید</th>
                                                        <th>
                                                            <span style="font-size: 20px;margin-right: 33%;"
                                                                  class="glyphicon glyphicon-eye-open"></span>
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($allProduct as $item)
                                                        <tr>
                                                            <th>{{ $id }}</th>
                                                            <th>{{ $item->product_name }}</th>
                                                            <th>{{ str_replace('-',' ',$item->store_name) }}</th>
                                                            <th>{{ $item->description }}</th>
                                                            <th>
                                                                {{ \Morilog\Jalali\Jalalian::forge($item->created_at)->format('Y/m/d H:i:s') }}
                                                            </th>
                                                            <th>{{ $item->category_name }}</th>
                                                            <th>{{ number_format($item->price) }} تومان</th>
                                                            <th>%{{ $item->discount  }}</th>
                                                            <th>{{ $item->quantity }}</th>
                                                            <th>
                                                                <div class="btn-group m-b-20">
                                                                    <div class="btn-group">
                                                                        @if(($item->is_vip == 1))
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> بله
                                                                                <span
                                                                                        class="caret"></span>
                                                                            </button>
                                                                        @else
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false">خیر
                                                                                <span
                                                                                        class="caret"></span>
                                                                            </button>
                                                                        @endif
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a href="{{ route('set.vip.product' , $item->id) }}"
                                                                                   class="btn btn-block btn-xs btn-success">بله</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="{{ route('unset.vip.product' , $item->id) }}"
                                                                                   class="btn btn-block btn-xs btn-danger">خیر</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                @if($item->status == 'deleted')
                                                                    <span class="text-danger"
                                                                          style="font-weight: bold;">عدم نمایش</span>
                                                                @else
                                                                    <div class="btn-group m-b-20">
                                                                        <div class="btn-group">
                                                                            @if(($item->visible == 1))
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
                                                                                    <a href="{{ route('show.product' , $item->id) }}"
                                                                                       class="btn btn-block btn-xs btn-success">نمایش</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="{{ route('hide.product' , $item->id) }}"
                                                                                       class="btn btn-block btn-xs btn-danger">عدم
                                                                                        نمایش</a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <div class="btn-group">
                                                                    <button class="btn btn-success btn-xs dropdown-toggle"
                                                                            type="button"
                                                                            data-toggle="dropdown">
                                                                        گزینه ها
                                                                        <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            {{--<a href="#" data-toggle="modal"
                                                                               data-target="#quick_view_product_{{ $item->id }}">مشاهده
                                                                                سریع محصول</a>--}}
                                                                            <a href="{{ route('showSingleProduct' , [$item->store_name , $item->id]) }}">مشاهده
                                                                                </a>

                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ route('admin.product.edit.page' , ['product' => $item->id]) }}">ویرایش
                                                                                </a>
                                                                        {{--<a href="#" data-toggle="modal"
                                                                           data-target="#quick_edit_product_{{ $item->id }}">ویرایش
                                                                            سریع محصول</a></li>--}}
                                                                        <li>
                                                                            {{--<a href="#" data-toggle="modal"
                                                                               data-target="#quick_view_product_{{ $item->id }}_attributes">مشاهده
                                                                                سریع ویژگی ها</a>--}}
                                                                            <a href="{{ route('product.seller.attribute.index' , [$item->store_name , $item->id]) }}">ویژگی
                                                                            ها
                                                                                </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                @if($item->status == 'deleted')
                                                                    <span class="text-danger"
                                                                          style="font-weight: bold;">حذف شده توسط فروشنده</span>
                                                                @else
                                                                    <div class="btn-group m-b-20">
                                                                        <div class="btn-group">
                                                                            @if($item->status == 'approved')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> تایید شده
                                                                                    <span
                                                                                            class="caret"></span>
                                                                                </button>
                                                                            @elseif($item->status == 'rejected')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> رد شده
                                                                                    <span
                                                                                            class="caret"></span>
                                                                                </button>
                                                                            @elseif($item->status == 'pending')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-info dropdown-toggle waves-effect"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"> درانتظار
                                                                                    تایید
                                                                                    <span class="caret"></span></button>
                                                                            @endif
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a href="{{ route('approved.product' , $item->id) }}"
                                                                                       class="btn btn-success btn-block btn-xs">تاییده
                                                                                        شده</a></li>
                                                                                <li>
                                                                                    <a href="{{ route('rejected.product' , $item->id) }}"
                                                                                       class="btn btn-danger btn-block btn-xs">رد
                                                                                        کردن</a></li>
                                                                                <li>
                                                                                    <a href="{{ route('pending.product' , $item->id) }}"
                                                                                       class="btn btn-info btn-block btn-xs">درانتظارتایید</a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <a style="font-weight: bold;" class="text-danger"
                                                                   data-toggle="modal"
                                                                   data-target="#quick_view_product_{{ $item->id }}"
                                                                   href="#">مشاهده سریع</a>&nbsp;
                                                                <a style="font-weight: bold;" class="text-success"
                                                                   href="#" data-toggle="modal"
                                                                   data-target="#quick_edit_product_{{ $item->id }}">ویرایش
                                                                    سریع</a>&nbsp;
                                                                <a style="font-weight: bold;" class="text-primary"
                                                                   data-toggle="modal"
                                                                   data-target="#quick_view_product_{{ $item->id }}_attributes"
                                                                   href="#">مشاهده سریع ویژگی
                                                                    ها</a>
                                                                <a style="font-weight: bold;" class="text-warning"
                                                                   data-toggle="modal"
                                                                   data-target="#quick_upgrade_{{$item->id}}"
                                                                   href="#">ارتقا
                                                                </a>
                                                            </th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $allProduct->links() }}
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            محصولی یافت نشد!
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.footer')

        @foreach($allProduct as $item)
            <div class="modal fade" id="quick_view_product_{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">مشاهده سریع </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach($item->photos as $photo)
                                    <div class="col-xs-12 col-md-6">
                                        <img src="{{ url()->to('/image/product_seller_photo') }}/{{ $photo->file_name }}" style="width:100%;" alt="">
                                    </div>
                                @endforeach
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        @if($storeType == 'product')
                                        <td>نام محصول </td>
                                        @else
                                        <td>نام خدمت </td>
                                        @endif
                                        <td>{{ $item->product_name }}</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>توضیحات</td>
                                        <td>{{ $item->description }}</td>
                                    </tr>
                                    <tr>
                                        <td>دسته بندی</td>
                                        <td>{{ $item->category_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>صنف</td>
                                        <td>{{ $item->guild_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>نام فروشگاه</td>
                                        <td>{{ $item->store_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>وضعیت نمایش</td>
                                        <td>
                                            @if($item->visible == 1)
                                                نمایان
                                            @else
                                                پنهان
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>وضعیت تایید</td>
                                        <td>
                                            @if($item->status == 'approved')
                                                <b class="text-success">تایید شده</b>
                                            @elseif($item->status == 'rejected')
                                                <b class="text-danger">رد شده</b>
                                            @elseif($item->status == 'pending')
                                                <b class="text-warning">در انتظار تایید</b>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>تعداد تصاویر</td>
                                        <td>{{ $item->photos()->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td>تعداد نظرات ثبت شده</td>
                                        <td>{{ $item->comments()->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td>قیمت خالص</td>
                                        <td>{{ $item->price }}</td>
                                    </tr>
                                    <tr>
                                        <td>درصد تخفیف</td>
                                        <td>{{ $item->discount }}</td>
                                    </tr>
                                    <tr>
                                        <td>قیمت تمام شده</td>
                                        <td>{{ $item->price - ($item->discount / 100 * $item->price) }}</td>
                                    </tr>
                                    <tr>
                                        <td>هزینه حمل به تهران</td>
                                        <td>{{ $item->shipping_price_to_tehran }} تومان</td>
                                    </tr>
                                    <tr>
                                        <td>هزینه حمل به شهرستان ها</td>
                                        <td>{{ $item->shipping_price_to_other_towns }} تومان</td>
                                    </tr>
                                    <tr>
                                        <td>زمان تحویل در تهران</td>
                                        <td>{{ $item->deliver_time_in_tehran }} روز</td>
                                    </tr>
                                    <tr>
                                        <td>زمان تحویل در شهرستان ها</td>
                                        <td>{{ $item->deliver_time_in_other_towns }} رمز</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="quick_edit_product_{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ویرایش سریع</h4>
                        </div>
                        <form action="{{ route('admin.product.update' , $item->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            @if($storeType == 'product')
                                            <label for="product_{{ $item->id }}_name">نام محصول:</label>
                                            @else
                                            <label for="product_{{ $item->id }}_name">نام خدمت:</label>
                                            @endif
                                            <input type="text" name="name" id="product_{{ $item->id }}_name"
                                                   value="{{ $item->product_name }}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="product_{{ $item->id }}_price">قیمت (تومان):</label>
                                            <input type="number" name="price" id="product_{{ $item->id }}_price"
                                                   value="{{ $item->price }}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    @if($storeType == 'product')
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="product_{{ $item->id }}_quantity">تعداد موجودی محصول:</label>
                                            <input type="number" name="quantity" id="product_{{ $item->id }}_quantity"
                                                   value="{{ $item->quantity }}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="product_{{ $item->id }}_discount_percentage">درصد تخفیف:</label>
                                            <input type="number" name="discount"
                                                   value="{{ $item->discount }}"
                                                   id="product_{{ $item->id }}_discount_percentage"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="product_{{ $item->id }}_category">دسته بندی:</label>
                                            <select name="category" class="form-control"
                                                    id="product_{{ $item->id }}_category">
                                                @foreach($productCategories as $category)
                                                    <option {{ $category->id == $item->category_id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="product_{{ $item->id }}_description">توضیحات:</label>
                                            <textarea name="description" id="product_{{ $item->id }}_description"
                                                      cols="30" rows="5"
                                                      class="form-control">{{ $item->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">ویرایش</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="quick_upgrade_{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('upgrades.admin.product.create') }}" method="POST"
                                  class="form-inline" style="margin:20px 0;">
                                {{ csrf_field() }}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ارتقا محصول / خدمت
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                            @php
                                $upgrades = $item->upgrades()->where('upgrades.status' , 'approved')->orderByDesc('upgrades.updated_at')->paginate(20);
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
                            <p class="text-danger">این محصول/خدمت  تا به حال ارتقا داده نشده است</p>
                        @endif
                            <h4>ارتقا سریع</h4>
                                <div class="form-group">
                                    <label for="product_{{ $item->id }}_position">انتخاب جایگاه:</label>
                                    <select name="position_id" id="product_{{ $item->id }}"
                                            class="form-control">
                                        @foreach($positions as $position)
                                            @if(str_contains($position->position, optional($item->store)->store_type))
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <input hidden type="number" name="product_seller_id" value="{{$item->id}}" />
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
            <div class="modal fade" id="quick_view_product_{{ $item->id }}_attributes" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">مشاهده سریع ویژگی ها
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <h5>ثبت ویژگی جدید</h5>
                            <form action="{{ route('product.seller.attribute.create') }}" method="POST"
                                  class="form-inline" style="margin:20px 0;">
                                {{ csrf_field() }}
                                <input type="hidden" name="product_seller_id" value="{{ $item->id }}">
                                <div class="form-group">
                                    <label for="product_{{ $item->id }}_attribute_name">نام ویژگی:</label>
                                    <select name="attribute_id" id="product_{{ $item->id }}_attribute_name"
                                            class="form-control">
                                        @foreach($attributes as $attr_)
                                            <option value="{{ $attr_->id }}">{{ $attr_->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="product_{{ $item->id }}_attribute_specifications">مشخصات ویژگی:</label>
                                    <input type="text" name="title"
                                           id="product_{{ $item->id }}_attribute_specifications" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="product_{{ $item->id }}_extra_price">قیمت افزایشی:</label>
                                    <input type="number" name="extra_price" id="product_{{ $item->id }}_extra_price"
                                           class="form-control">
                                </div>
                                <button class="btn btn-success">ثبت</button>
                            </form>
                            <h5>ویژگی های موجود</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نام ویژگی</th>
                                        <th>مشخصات ویژگی</th>
                                        <th>قیمت افزایشی</th>
                                        <th>اختیارات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->attributes as $attr)
                                        <tr>
                                            <td>{{ $attr->id }}</td>
                                            <td>{{ $attr->attribute_type }}</td>
                                            <td>{{ $attr->title }}</td>
                                            <td>{{ $attr->extra_price }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="#" data-toggle="modal"
                                                       data-target="#quick_edit_product_{{ $attr->id }}_attributes"
                                                       class="btn btn-linkedin">ویرایش</a>
                                                    <a href="#" data-remove-attribute="{{ $attr->id }}"
                                                       class="btn btn-linkedin">حذف</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($item->attributes as $attr)
                <div class="modal fade" id="quick_edit_product_{{ $attr->id }}_attributes" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">ویرایش سریع ویژگی
                                    <b class="text-primary">{{ $item->product_name }}</b>
                                </h4>
                            </div>
                            <form action="{{ route('product.seller.attribute.update' , $attr->id) }}"
                                  class="form-horizontal" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="attribute_{{ $attr->id }}_name">نام ویژگی:</label>
                                        <select name="attribute" id="attribute_{{ $attr->id }}_name"
                                                class="form-control">
                                            @foreach($attributes as $attr_)
                                                <option {{ $attr_->id == $attr->attribute_id ? 'selected' : '' }} value="{{ $attr_->id }}">{{ $attr_->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="attribute_{{ $attr->id }}_specifications">مشخصات ویژگی:</label>
                                        <input type="text" name="title"
                                               value="{{ $attr->title }}"
                                               id="attribute_{{ $attr->id }}_specifications" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="attribute_{{ $attr->id }}_extra_price">قیمت افزایشی:</label>
                                        <input type="number" name="extra_price"
                                               value="{{ $attr->extra_price }}"
                                               id="attribute_{{ $attr->id }}_extra_price" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">ویرایش</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

    </div>
@endsection
@section('scripts')
    <script>

        $('[data-remove-attribute]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var id = $this.data('remove-attribute');
            $.ajax({
                type: 'GET',
                url: '{{ url()->to('/admin/product-seller/attributes/') }}/' + id + '/destroy',
                success: function (response) {
                    if (response.status == 200) {
                        $this.closest('tr').remove();
                    }
                }
            });
        });
        $(document).ready(function () {

            $('#show_product').click(function (e) {
                e.preventDefault();
                var $this = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ url()->to('/admin/store-lists') }}/' + $this + '/show-store/ajax',
                    data: {
                        id: 2,
                    },

                    success: function () {
                        $('[data-visibility-button]').removeClass('btn-danger').addClass('btn-success')
                            .html('نمایش <span class="caret"></span>');
                    }
                });
            });

            $('#hide_product').click(function (e) {
                e.preventDefault();
                var $this = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ url()->to('/admin/store-lists') }}/' + $this + '/hide-store/ajax',
                    data: {
                        id: 2,
                    },

                    success: function () {
                        $('[data-visibility-button]').removeClass('btn-success').addClass('btn-danger').html('عدم نمایش <span class="caret"></span>');
                    }
                });
            });

            $('#approved-store-status').click(function (e) {
                e.preventDefault();
                var $this = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ url()->to('/admin/store-lists') }}/' + $this + '/approved-store-status/ajax',
                    data: {
                        id: 2,
                    },

                    success: function () {
                        $('[data-status-button]').removeClass().addClass('btn btn-xs btn-success dropdown-toggle waves-effect').html('تاییده شده <span class="caret"></span>');
                    }
                });
            });

            $('#reject-store-status').click(function (e) {
                e.preventDefault();
                var $this = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ url()->to('/admin/store-lists') }}/' + $this + '/reject-store-status/ajax',
                    data: {
                        id: 2,
                    },

                    success: function () {
                        $('[data-status-button]').removeClass().addClass('btn btn-xs btn-warning dropdown-toggle waves-effect').html('رد شده <span class="caret"></span>');
                    }
                });
            });

            $('#pending-store-status').click(function (e) {
                e.preventDefault();
                var $this = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ url()->to('/admin/store-lists') }}/' + $this + '/pending-store-status/ajax',
                    data: {
                        id: 2,
                    },

                    success: function () {
                        $('[data-status-button]').removeClass().addClass('btn btn-xs btn-info dropdown-toggle waves-effect').html('در انتظار تایید <span class="caret"></span>');
                    }
                });
            });

            $('#send').click(function (e) {
                if ($('#key_word').val() == '' && $('#price_from').val() == '' && $('#price_to').val() == '' && $('#store').val() == null && $('#visibility').val() == null
                    && $('#status').val() == null && $('#exists:checked').val() == undefined && $('#user_full_name').val() == null && $('#user_mobile').val() == null) {
                    e.preventDefault();
                    swal("خطا", "فیلتری انتخاب نشده است.", "error")
                }
            });

        });
    </script>
@endsection
