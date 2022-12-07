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
            font-size: 15px;
            line-height:40px;
        }

        .store-info span, .store-info b, .store-info a {
            font-weight: bold;
        }

        .product_seller_view_modal table tbody tr td.title {
            color: #424242;
        }

        .product_seller_view_modal table tbody tr td.value {
            color: #ff5a5f;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box" style="height: 440px;">
                            <div class="row">
                                @if(count($storePhotos) > 0)
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                        <!-- Indicators -->
                                        <ol class="carousel-indicators">
                                            @if(count($storePhotos) > 0)
                                                @foreach($storePhotos as $photo)
                                                    <li data-target="#myCarousel"
                                                        data-slide-to="{{ url()->to('/image/store_photos/') }}/{{ $photo->id }}"
                                                        class="{{ $photo->id == 1 ? 'active' : '' }}"></li>
                                                @endforeach
                                            @endif
                                        </ol>

                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner">
                                            @if(count($storePhotos) > 0)
                                                @foreach($storePhotos as $photo)
                                                    <div class="item {{ $photo->id == $storePhotos[0]->id ? 'active' : '' }}">
                                                        <img style="height: 300px;width: 100%;"
                                                             src="{{ url()->to('/image/store_photos/') }}/{{ $photo->photo_name }}"
                                                             alt="store-photo">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- Left and right controls -->
                                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                            <span class="sr-only">قبلی</span>
                                        </a>
                                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                            <span class="sr-only">بعدی</span>
                                        </a>
                                    </div>
                                @else
                                    <div>
                                        <p style="text-align: center;font-weight: bold;" class="text-danger">عکسی برای
                                            این فروشگاه ثبت نشده است.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-box">
                            <div class="row">
                                {!! $billStore->container() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-4 store-info">
                                    <span>نام فروشگاه :</span>
                                    <span style="color:#777">{{ $storeInfo->store_name }}</span><br>
                                    <span>صنف : </span>
                                    <span style="color:#777">{{ $storeInfo->guild_name }}</span><br>
                                    <span>کاربر ثبت کننده : </span>
                                    <span style="color:#777">{{ $storeInfo->first_name }} {{ $storeInfo->last_name }}</span><br>
                                    <span>شماره تماس فروشگاه : </span>
                                    <span style="color:#777">{{ $storeInfo->phone_number }}</span><br>
                                    <span>شماره تماس ثبت کننده فروشگاه : </span>
                                    <span style="color:#777">{{ $storeInfo->user_mobile }}</span><br>
                                    <span>تاریخ ثبت :</span>
                                    <span style="color:#777">{{ \Morilog\Jalali\Jalalian::forge($storeInfo->created_at)->format('%d %B %Y H:i:s') }}</span><br>
                                    <span style="color: #fe6500">نوع فعالیت:</span>
                                    <span style="color:#777;">
                                        {{ $storeInfo->activity_type == 'country' ? 'درکشور' : 'دراستان' }}
                                    </span>
                                    <br>
                                    <span style="color: #fe6500">تاریخ و زمان ثبت:</span>
                                    <span style="color: #fe6500">نوع پرداختی:</span>
                                    <span style="color:#777;">
                                        @if($storeInfo->pay_type == 'online')آنلاین
                                        @elseif($storeInfo->pay_type == 'postal')پستی
                                        @elseآنلاین و پستی
                                        @endif
                                    </span>
                                    <br>
                                    <span style="color: #fe6500">وضعیت فعالیت : </span>
                                    <span style="color:#777;font-weight: bold;">
                                        @if($storeInfo->store_status == 'approved')
                                            <span class="text-success">تایید شده</span>
                                        @elseif($storeInfo->store_status == 'rejected')
                                            <span class="text-danger">رد شده</span>
                                        @elseif($storeInfo->store_status == 'pending')
                                            <span class="text-info">در انتظار تایید</span>
                                        @endif
                                    </span><br>
                                    <span style="color: #fe6500">وضعیت نمایش:</span>
                                    <span style="color:#777;font-weight: bold;">
                                        @if($storeInfo->store_visible == 1)
                                            <span class="text-success">نمایان</span>
                                        @elseif($storeInfo->store_visible == 0)
                                            <span class="text-danger">عدم نمایش</span>
                                        @endif
                                    </span>
                                    <br>
                                    <span style="color: #fe6500">وضعیت اشتراک:</span>
                                    <b class="text-success">
                                        @if(isset($intervalDays))
                                            @if($intervalDays != 0)
                                                <span style="font-size: 10px;" class="text-success">دارای اشتراک ( معتبر تا {{ $intervalDays }} روز آینده )</span>
                                            @else
                                                <span class="text-danger">فاقد اشتراک</span>
                                            @endif
                                        @else
                                            <span class="text-danger">پلنی خریداری نشده است.</span>
                                        @endif
                                    </b>&nbsp;
                                    <a href="{{ route('planStore' , $storeInfo->user_name) }}"><b
                                                style="color:#FF5722;font-size: 10px;">تمدید / ست کردن پلن
                                            اشتراک</b></a>
                                    <br>
                                    <span>شعار فروشگاه:</span>
                                    <span style="color:#777;">{{ $storeInfo->slogan }}</span>
                                    @if($storeInfo->shaba_code != null)
                                        <br>
                                        <span style="font-weight: bold;">شماره شبا:</span>
                                        <input style="cursor: text;" class="btn btn-xs btn-facebook btn-block"
                                               type="text" readonly value="{{ $storeInfo->shaba_code }}">
                                    @endif

                                    <br><br>
                                    <a href="{{ route('editStore' , $storeInfo->user_name) }}"
                                       class="btn btn-xs btn-success">ویرایش فروشگاه</a>
                                    <a href="{{ route('editPhoto' , $storeInfo->user_name) }}"
                                       class="btn btn-xs btn-danger">ویرایش تصاویر / افزودن تصویر</a>
                                    <a href="{{ route('listOfStores') }}" class="btn btn-xs btn-info">بازگشت به لیست
                                        فروشگاه ها</a>
                                    <a href="{{ route('message.index' , $storeInfo->user_id) }}"
                                       class="btn btn-gray btn-xs">ارسال پیام</a>
                                </div>
                                <div class="col-md-8"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <img width="200px"
                             src="{{ url()->to('/image/store_photos/') }}/{{ $storeInfo->thumbnail_photo }}"
                             class="img-thumbnail pull-left" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>لیست محصولات</b></h4>
                                    <p class="text-muted font-13"></p>

                                    @if(count($products_seller) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="">
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام محصول</th>
                                                        <th>قیمت</th>
                                                        <th>درصد تخفیف</th>
                                                        <th>قیمت تمام شده</th>
                                                        <th>موجودی انبار</th>
                                                        <th>محصول ویژه</th>
                                                        <th>وضعیت نمایش</th>
                                                        <th>وضعیت تایید</th>
                                                        <th>دسترسی</th>
                                                        <th>دسترسی سریع</th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($productSellerQuery as $item)
                                                        <tr>
                                                            <th>{{ $id }}</th>
                                                            <th>{{ $item->name }}</th>
                                                            <th>{{ number_format($item->price) }} تومان</th>
                                                            <th>%{{ $item->discount  }}</th>
                                                            <th>{{ number_format($item->price - (($item->price * $item->discount) / 100)) }}
                                                                تومان
                                                            </th>
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
                                                                                       class="btn btn-block btn-xs btn-success">نمایش
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="{{ route('hide.product' , $item->id) }}"
                                                                                       class="btn btn-block btn-xs btn-danger">عدم
                                                                                        نمایش
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if($item->status == 'deleted')
                                                                    <span class="text-danger"
                                                                          style="font-weight: bold;">حذف شده توسط کاربر</span>
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
                                                                <div class="btn-group">
                                                                    <button type="button"
                                                                            class="btn btn-default btn-xs dropdown-toggle"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                        اختیارات <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a href="{{ route('showSingleProduct' , ['store' => $slug , 'product' => $item->id]) }}">مشاهده</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ route('admin.product.edit.page' , ['product' => $item->id]) }}">ویرایش</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ route('product.seller.attribute.index' , [$slug , $item->id]) }}">ویژگی
                                                                                ها</a></li>
                                                                    </ul>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="btn-group">
                                                                    <button type="button"
                                                                            class="btn btn-default btn-xs dropdown-toggle"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                        اختیارات <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="#" data-toggle="modal"
                                                                               data-target="#product_{{ $item->id }}_view_modal">مشاهده
                                                                                سریع</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" data-toggle="modal"
                                                                               data-target="#product_{{ $item->id }}_edit_modal">ویرایش
                                                                                سریع</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" data-toggle="modal"
                                                                               data-target="#product_{{ $item->id }}_view_attribute_modal">مشاهده
                                                                                سریع ویژگی ها</a></li>
                                                                    </ul>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $products_seller->links() }}
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
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>

    @foreach($productSellerQuery as $item)
        <div class="modal fade product_seller_view_modal" tabindex="-1" role="dialog"
             id="product_{{ $item->id }}_view_modal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">مشاهده محصول: {{ $item->name }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="title">نام محصول</td>
                                    <td class="value">{{ $item->name }}</td>
                                    <td class="title">توضیحات</td>
                                    <td class="value">{{ $item->description }}</td>
                                </tr>
                                <tr>
                                    <td class="title">دسته محصول</td>
                                    <td class="value">{{ $item->category_name }}</td>
                                    <td class="title">صنف</td>
                                    <td class="value">{{ $item->guild_name }}</td>
                                </tr>
                                <tr>
                                    <td class="title">موجودی انبار</td>
                                    <td class="value">{{ $item->quantity }}</td>
                                    <td class="title">نام فروشگاه</td>
                                    <td class="value">
                                        {{ $item->store_name }}
                                        <a href="" class="text-primary"> مشاهده فروشگاه</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">کل مبلغ فروش محصول</td>
                                    <td class="value">{{ $item->total_sales }}</td>
                                    <td class="title">وضعیت نمایش</td>
                                    <td class="value">
                                        @if($item->visible == 1)
                                            <b class="text-success">نمایان</b>
                                        @else
                                            <span class="text-danger">پنهان</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">وضعیت تایید</td>
                                    <td class="value">
                                        @if($item->status == 'approved')
                                            <span class="text-success">تایید شده</span>
                                        @elseif($item->status == 'pending')
                                            <span class="text-warning">در انتظار تایید</span>
                                        @else
                                            <span class="text-danger">رد شده</span>
                                        @endif
                                    </td>
                                    <td class="title">تعداد تصاویر</td>
                                    <td class="value">
                                        {{ $item->count_of_photos }}
                                        <a href="{{ route('editProductPhotos' , [ $item->store_user_name , $item->id]) }}"
                                           class="text-primary">ویرایش تصاویر</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">تعداد نظرات ثبت شده</td>
                                    <td class="value">
                                        {{ $item->comments_count }}
                                        <a href="{{ route('productComment' , $item->id) }}" class="text-primary">مشاهده
                                            نظرات</a>
                                    </td>
                                    <td class="title">قیمت خالص</td>
                                    <td class="value">{{ $item->price }}</td>
                                </tr>
                                <tr>
                                    <td class="title">درصد تخفیف</td>
                                    <td class="value">{{ $item->discount }}</td>
                                    <td class="title">قیمت تمام شده</td>
                                    <td class="value">{{ $item->price - ($item->discount / 100 * $item->price) }}</td>
                                </tr>
                                <tr>
                                    <td class="title">تعداد بازدید</td>
                                    <td class="value">{{ $item->hint }}</td>
                                    <td class="title">محصول ویژه؟</td>
                                    <td class="value">{{ $item->is_vip == 1 ? 'بله' : 'خیر' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade product_seller_view_modal" tabindex="-1" role="dialog"
             id="product_{{ $item->id }}_edit_modal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ویرایش سریع محصول:
                            <small class="text-success">{{ $item->name }}</small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.product.update' , $item->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_name">نام محصول:</label>
                                        <input type="text" name="name" id="product_{{ $item->id }}_name"
                                               class="form-control input-sm" value="{{ $item->name }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_price">نام محصول:</label>
                                        <input type="number" name="price" id="product_{{ $item->id }}_price"
                                               class="form-control input-sm" value="{{ $item->price }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_discount">درصد تخفیف:</label>
                                        <input type="number" name="discount" id="product_{{ $item->id }}_discount"
                                               class="form-control input-sm" value="{{ $item->discount }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_quantity">تعداد موجودی انبار:</label>
                                        <input type="number" name="quantity" id="product_{{ $item->id }}_quantity"
                                               class="form-control input-sm" value="{{ $item->quantity }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_category">دسته بندی:</label>
                                        <select name="category" id="product_{{ $item->id }}_category"
                                                class="form-control input-sm">
                                            @foreach($productSellerCategories as $cat)
                                                <option {{ $item->category_id == $cat->id ? 'selected' : '' }} value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="description">توضیحات:</label>
                                        <textarea name="description" id="description" cols="30" rows="10"
                                                  class="form-control input-sm">{{ $item->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-default">ویرایش</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade product_seller_view_attribute_modal" tabindex="-1" role="dialog"
             id="product_{{ $item->id }}_view_attribute_modal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">افزودن ویژگی جدید به محصول:
                            <small class="text-success">{{ $item->name }}</small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('product.seller.attribute.create') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="product_seller_id" value="{{ $item->id }}">
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_attribute_name">نام ویژگی <span
                                                    class="text-danger">*</span></label>
                                        <select name="attribute_id" id="product_{{ $item->id }}_attribute_name"
                                                class="form-control input-sm">
                                            @foreach($attributesName as $attrName)
                                                <option value="{{ $attrName->id }}">{{ $attrName->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_attribute_specification">مشخصات
                                            ویژگی <span class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                               required
                                               id="product_{{ $item->id }}_attribute_specification"
                                               class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <div class="form-group">
                                        <label for="product_{{ $item->id }}_extra_price">قیمت افزایشی <span
                                                    class="text-danger">*</span></label>
                                        <input type="number" name="extra_price" id="product_{{ $item->id }}_extra_price"
                                               required
                                               class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <button style="margin-top: 25px;" type="submit"
                                            class="btn btn-linkedin btn-block btn-sm">ثبت
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <h4 style="font-size:18px;">لیست ویژگی های محصول</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>نام ویژگی</th>
                                        <th>مشخصات ویژگی</th>
                                        <th>قیمت افزایشی</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->attributes as $attr)
                                        <tr>
                                            <td>{{ $attr->type }}</td>
                                            <td>{{ $attr->title }}</td>
                                            <td>{{ $attr->extra_price }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('scripts')
    <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
    {!!  $billStore->script()  !!}

    <script>
        $(document).ready(function () {

            $('.carousel').carousel({
                interval: 2000,
                ride: true,
                pause: "hover",
            });

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

        });
    </script>
@endsection
