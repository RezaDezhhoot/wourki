@extends('frontend.master')
@section('style')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/datepicker-theme.css') }}">
    <style>
.sweet-overlay {
  z-index: 100000000000;
}
.sweet-alert {
  z-index: 100000000000;
}
.daterangepicker{
    z-index: 100000000 !important;
}
        .daterangepicker td.in-range {
            background: #ffc4c2;
            color: #fc2a23;
        }
        .daterangepicker td.start-date {
            background: #ffc4c2;
            color: #fc2a23;
        }
        .daterangepicker .input-mini {
            border: 1px solid #fc2a23;
            border-radius: 2px;
        }
        .daterangepicker .input-mini:focus {
            border: 1px solid #fc2a23;
            border-radius: 2px;
            outline: none;
        }
        .daterangepicker .input-mini.active {
            border: 1px solid #fc2a23;
            border-radius: 2px;
        }
        .daterangepicker .input-mini.active:focus {
            border: 1px solid #fc2a23;
            border-radius: 2px;
            outline: none;
        }
        
        .daterangepicker td.available:hover, .daterangepicker td.active:hover {
        color: #ffc4c2;
        }
        .conversation{
          font-weight: 300;
          color: white;
          background-color: #FC494C;
          border-radius: 40px;
          padding: 8px 32px;
          display: inline;
          float: right;
        }
        .conversation:hover{
          color: white;
        }
    </style>
    <title>وورکی | حساب کاربری من | لیست مصحولات</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid products-list-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        @if(!$userStore)
                            @if(!$is_service)
                            <div class="alert alert-warning text-center">کاربر گرامی برای نمایش محصولات فروشگاه ابتدا باید فروشگاه
                                خود را ثبت کنید.
                            </div>
                            @else
                            <div class="alert alert-warning text-center">کاربر گرامی برای نمایش خدمات فروشگاه ابتدا باید فروشگاه
                                خود را ثبت کنید.
                            </div>
                            @endif
                        @else
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-cart-arrow-down"></i>
                                    @if(!$is_service)
                                    لیست محصولات
                                    @else
                                    لیست خدمات
                                    @endif
                                </div>
                                <div class="panel-body">
                                    <ul class="nav nav-tabs">
                                        <li role="presentation" class="active">
                                            @if(!$is_service)
                                            <a href="#showcase-tab" data-toggle="tab">محصولات ویترین</a>
                                            @else
                                            <a href="#showcase-tab" data-toggle="tab">خدمات ویترین</a>
                                            @endif
                                        </li>
                                        <li role="presentation">
                                            @if(!$is_service)
                                            <a href="#hidden-product-tab" data-toggle="tab">محصولات مخفی شده</a>
                                            @else
                                            <a href="#hidden-product-tab" data-toggle="tab">خدمات مخفی شده</a>
                                            @endif
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="showcase-tab" class="tab-pane fade in active">
                                            <div class="row">
                                                @if(count($visibleUserProducts) > 0)
                                                    @foreach($visibleUserProducts as $product)
                                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                                            <div class="product-item-wrapper">
                                                                <a class="menu">
                                                                    <i class="fas fa-ellipsis-h"></i>
                                                                </a>
                                                                <ul class="list-unstyled ul-menu" style="display:none;">
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a href="{{ route('user.product.edit' , $product->id) }}">ویرایش
                                                                            محصول</a></li>
                                                                        @else
                                                                        <li>
                                                                        <a href="{{ route('user.service.edit' , $product->id) }}">ویرایش
                                                                            خدمت</a></li>
                                                                        @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#discount-modal-{{$product->id}}" href="">کد های تخفیف
                                                                            محصول</a></li>
                                                                        @else
                                                                        <li>
                                                                        <a data-toggle="modal" data-target="#discount-modal-{{$product->id}}" href="">کد های تخفیف
                                                                            خدمت</a></li>
                                                                        @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-modal-{{$product->id}}" href="">ارتقا
                                                                            محصول</a></li>
                                                                    @else
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-modal-{{$product->id}}" href="">ارتقا
                                                                            خدمت</a></li>
                                                                    @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-history-modal-{{$product->id}}" href="">تاریخچه ارتقا محصول
                                                                            </a></li>
                                                                    @else
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-history-modal-{{$product->id}}" href="">تاریخجه ارتقا خدمت
                                                                            </a></li>
                                                                    @endif
                                                                        @if(!$is_service)
                                                                        <li>
                                                                        <a href="{{ route('user.product.photo' , $product->id) }}">تصاویر
                                                                            محصول</a></li>
                                                                            @else
                                                                            <li>
                                                                           <a href="{{ route('user.service.photo' , $product->id) }}">تصاویر
                                                                            خدمت</a></li>
                                                                            @endif
                                                                    {{-- @if(!$is_service)
                                                                    <li>
                                                                        <a href="{{ route('user.product.attributes' , $product->id) }}">ویژگی
                                                                            های محصول</a></li>
                                                                        @else
                                                                        <li>
                                                                            <a href="{{ route('user.service.attributes' , $product->id) }}">ویژگی
                                                                            های خدمت</a></li>
                                                                        @endif --}}
                                                                        @if(!$is_service)
                                                                        <li>
                                                                        <a href="{{ route('user.product.delete' , ['product' => $product->id]) }}">حذف
                                                                            محصول</a></li>
                                                                        @else
                                                                        <li>
                                                                        <a href="{{ route('user.service.delete' , ['product' => $product->id]) }}">حذف
                                                                            خدمت</a></li>
                                                                        @endif
                                                                        @if(!$is_service)
                                                                        <li>
                                                                            <a data-toggle="modal" data-target="#marketers-modal-{{$product->id}}" href="">لیست بازایاب های محصول</a>
                                                                        </li>
                                                                        @else
                                                                        <li>
                                                                            <a data-toggle="modal" data-target="#marketers-modal-{{$product->id}}" href="">لیست بازاریاب های خدمت</a>
                                                                        </li>
                                                                        @endif
                                                                </ul>
                                                                @if($product->status == 'approved')
                                                                    <span class="label label-success">تایید شده</span>
                                                                @elseif($product->status == 'pending')
                                                                    <span class="label label-warning">در انتظار تایید</span>
                                                                @else
                                                                    <span class="label label-danger">رد شده</span>
                                                                @endif

                                                                @if($product->photo != null)
                                                                    <img src="{{ url()->to('/image/product_seller_photo/') }}/{{ $product->photo->file_name }}"
                                                                         alt="" class="img-thumbnail">
                                                                @else
                                                                    <img src="{{ url()->to('/image/logo.png') }}" alt=""
                                                                         class="img-thumbnail">
                                                                @endif

                                                                <div class="product-details">
                                                                    <p class="product-name">
                                                                        <a href="">{{ $product->name }}</a>
                                                                    </p>
                                                                    <p class="old-price">قیمت:
                                                                        @if($product->discount != 0)
                                                                            <del class="text-danger">{{ number_format($product->price) }}
                                                                                تومان
                                                                            </del>
                                                                        @endif
                                                                        <b class="new-price">
                                                                            {{ number_format($product->price - (($product->price * $product->discount) / 100)) }}
                                                                            تومان
                                                                        </b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div id="hidden-product-tab" class="tab-pane fade">
                                            <div class="row">
                                                @if(count($hiddenUserProducts) > 0 )
                                                    @foreach($hiddenUserProducts as $product)
                                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                                            <div class="product-item-wrapper">
                                                                <a class="menu">
                                                                    <i class="fas fa-ellipsis-h"></i>
                                                                </a>
                                                                <ul class="list-unstyled ul-menu" style="display:none;">
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a href="{{ route('user.product.edit' , $product->id) }}">ویرایش
                                                                            محصول</a></li>
                                                                    @else
                                                                    <li>
                                                                        <a href="{{ route('user.service.edit' , $product->id) }}">ویرایش
                                                                            خدمت</a></li>
                                                                    @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#discount-modal-{{$product->id}}" href="">کد های تخفیف
                                                                            محصول</a></li>
                                                                        @else
                                                                        <li>
                                                                        <a data-toggle="modal" data-target="#discount-modal-{{$product->id}}" href="">کد های تخفیف
                                                                            خدمت</a></li>
                                                                        @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-modal-{{$product->id}}" href="">ارتقا
                                                                            محصول</a></li>
                                                                    @else
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-modal-{{$product->id}}" href="">ارتقا
                                                                            خدمت</a></li>
                                                                    @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-history-modal-{{$product->id}}" href="">تاریخچه ارتقا محصول
                                                                            </a></li>
                                                                    @else
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-history-modal-{{$product->id}}" href="">تاریخجه ارتقا خدمت
                                                                            </a></li>
                                                                    @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a href="{{ route('user.product.photo' , $product->id) }}">تصاویر
                                                                            محصول</a></li>
                                                                    @else
                                                                    <li>
                                                                        <a href="{{ route('user.service.photo' , $product->id) }}">تصاویر
                                                                            خدمت</a></li>
                                                                    @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a href="{{ route('user.product.delete' , ['product' => $product->id]) }}">حذف
                                                                            محصول</a>
                                                                    </li>
                                                                    @else
                                                                    <li>
                                                                        <a href="{{ route('user.service.delete' , ['product' => $product->id]) }}">حذف
                                                                            خدمت</a>
                                                                    </li>
                                                                    @endif
                                                                    @if(!$is_service)
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#marketers-modal-{{$product->id}}" href="">لیست بازایاب های محصول</a>
                                                                    </li>
                                                                    @else
                                                                    <li>
                                                                        <a data-toggle="modal" data-target="#marketers-modal-{{$product->id}}" href="">لیست بازاریاب های خدمت</a>
                                                                    </li>
                                                                    @endif
                                                                </ul>

                                                                @if($product->status == 'approved')
                                                                    <span class="label label-success">تایید شده</span>
                                                                @elseif($product->status == 'pending')
                                                                    <span class="label label-warning">در انتظار تایید</span>
                                                                @else
                                                                    <span class="label label-danger">رد شده</span>
                                                                @endif

                                                                @if($product->photo != null)
                                                                    <img src="{{ url()->to('/image/product_seller_photo/') }}/{{ $product->photo->file_name }}"
                                                                         alt="" class="img-thumbnail">
                                                                @else
                                                                    <img src="{{ url()->to('/image/logo.png') }}" alt=""
                                                                         class="img-thumbnail">
                                                                @endif

                                                                <div class="product-details">
                                                                    <p class="product-name">
                                                                        <a href="">{{ $product->name }}</a>
                                                                    </p>
                                                                    <p class="old-price">قیمت:
                                                                        @if($product->discount != 0)
                                                                            <del class="text-danger">{{ number_format($product->price) }}
                                                                                تومان
                                                                            </del>
                                                                        @endif
                                                                        <b class="new-price">
                                                                            {{ number_format($product->price - (($product->price * $product->discount) / 100)) }}
                                                                            تومان
                                                                        </b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(count($allUserProducts) > 0)
    @foreach($allUserProducts as $item)
                <div class="modal fade" id="marketers-modal-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">لیست بازاریاب های محصول / خدمت
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                            @php
                                $markets = $item->markets()->paginate(20)
                            @endphp
                            @if(count($markets) > 0)
                             <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>نام فروشگاه</th>
                                        <th>نام کاربر</th>
                                        <th>شماره تماس</th>
                                        <th>میزان پورسانت</th>
                                        <th>شروع گفت و گو</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($markets as $market)
                                    <tr>
                                        <td>{{ $market->name}}</td>
                                        <td>{{ $market->user->first_name . ' ' . $market->user->last_name}}</td>
                                        <td>{{ $market->user->mobile}}</td>
                                        <td>{{ optional(App\MarketCommission::where('category_id' , $item->category->id)->first())->amount}}%</td>
                                        <td><form action="{{route('chats.create')}}" method="POST">
                                                {{ csrf_field() }}
                                            <button type="submit" style="cursor: pointer;"
                                               class="btn conversation"><i class="fa fa-paper-plane"></i></button>
                                            <input hidden name="type" value="store" />
                                            <input hidden name="id" value="{{$market->id}}" />
                                        </form></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $markets->links() }}
                        @else
                            <p class="danger">این محصول تا به حال بازاریابی نشده است</p>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
            @php
             $upgrades = $item->upgrades()->where('upgrades.status' , 'approved')->whereNull('upgrades.from_marketer')->orderByDesc('upgrades.updated_at')->paginate(20);   
            @endphp
            <div class="modal fade" id="upgrade-history-modal-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">تاریخچه ارتقا محصول / خدمت
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
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
                            <p class="danger">این محصول تا به حال ارتقا داده نشده است</p>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
        <div class="modal fade" id="upgrade-modal-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('upgrades.product.create') }}" method="POST"
                                  class="form-horizontal" enctype="multipart/form-data"> 
                                {{ csrf_field() }}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ارتقا محصول / خدمت
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                                    <div>                                                         
                                    <label for="position_id">انتخاب جایگاه:</label>
                                    <select name="position_id" id="position_id_{{$item->id}}" 
                                            class="form-control">
                                        @php
                                            $index = 0;
                                            $cond = true;
                                        @endphp
                                        @foreach($positions as $i => $position)
                                            @if(str_contains($position->position, $is_service ? "service" : "product"))
                                            <option data-id={{$position->id}} data-price="{{$position->price}}" {{$cond ? "selected" : ""}}  value="{{ $position->id }}">{{ $position->name }}</option>
                                            @php
                                                if($cond)
                                                $index = $i;

                                                $cond = false;
                                            @endphp
                                            @endif
                                        @endforeach
                                    </select>
                                    <div style="margin-top:20px">
                                    <label for="discount_code">کد تخفیف : </label>
                                    <div class="row" >
                                    <div class="col-sm-8" class="form-control-wrapper">
                                        <input type="text" name="discount_code" id="discount_code_{{$item->id}}" placeholder="(اختیاری)"
                                            class="form-control">
                                        <input type="hidden" name="discount" id="discount" />
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-pink apply-discount-button" data-id={{$item->id}}>
                                            اعمال تخفیف
                                        </button>
                                    </div>
                                    </div>
                                    </div>
                                    <p style="margin-top : 20px" id="position_price_{{$item->id}}" class="alert alert-info">مبلغ قابل پرداخت : {{optional($positions[$index])->price}} تومان</p> 
                                    <input hidden type="number" name="product_seller_id"  value="{{$item->id}}" />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="پرداخت آنلاین" />
                            <input type="submit" name="wallet" value="پرداخت کیف پول" class="btn btn-success" />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            @php
             $discounts = $item->getDiscountsPaginated(auth()->guard('web')->user()->id);   
            @endphp
            <div class="modal fade" id="discount-modal-{{$item->id}}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">کد های تخفیف محصول / خدمت
                                <b class="text-primary">{{ $item->name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                            @if(count($discounts) > 0)
                             <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>موضوع تخفیف</th>
                                        <th>کد تخفیف</th>
                                        <th>تاریخ شروع</th>
                                        <th>تاریخ پایان</th>
                                        <th>نوع تخفیف</th>
                                        <th>میزان تخفیف</th>
                                        <th>توضیحات</th>
                                        <th>حذف</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($discounts as $discount)
                                    <tr id="discount-{{$discount->id}}">
                                        <td>{{ $discount->name}}</td>
                                        <td>{{ $discount->code}}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($discount->start_date)->format('%d %B %Y')}}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($discount->end_date)->format('%d %B %Y') }}</td>
                                        <td>{{ $discount->type == "percentage" ? "درصدی" : "ریالی" }}</td>
                                        <td>{{ $discount->percentage }}</td>
                                        <td>{{ $discount->description }}</td>
                                        <td><button class="btn" onclick="deleteDiscount({{$discount->id}})" style="background-color: red;color:white"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $discounts->links() }}
                        @else
                            <p class="text-danger" style="font-size : 12px;margin-top:10px;">برای این محصول / خدمت تخفیفی ثبت نشده است</p>
                        @endif
                        <form id="discount" action="{{ route('discounts.user.product.create') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input hidden type="number" value="{{$item->id}}" name="product_id" />
                            <div class="panel panel-default" style="margin-top : 20px">
                                <div class="panel-heading">
                                    ایجاد کد تخفیف جدید
                                </div>
                                <div class="panel-body buy-plan-panel">
                                <div style="padding:20px">
                                <div class="form-group">                                                         
                                <div class="row gx-4">
                                    <div class="col-sm-4" style="padding : 0 30px">
                                        <div class="form-group">
                                            <label for="code" class="control-label">کد تخفیف</label>
                                            <input type="text" name="code" id="code"
                                                   placeholder="کد تخفیف را وارد کنید"
                                                   value="{{ request()->input('code') }}" class="form-control input-sm">
                                            @if($errors->has('code'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('code') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                        <div class="form-group">
                                            <label for="name" class="control-label">موضوع تخفیف</label>
                                            <input type="text" name="name" id="name"
                                                   placeholder="موضوع تخفیف را وارد کنید"
                                                   value="{{ request()->input('code') }}" class="form-control input-sm">
                                            @if($errors->has('name'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('name') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                    <div class="form-group">
                                            <label for="discount_type">نوع تخفیف</label>
                                            <select name="type" id="discount_type"
                                                    class="form-control">
                                                    <option value="percentage" selected>درصدی</option>
                                                    <option value="rial">ریالی</option>
                                            </select>
                                            @if($errors->has('type'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('type') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                        <div class="form-group">
                                            <label for="percentage">مقدار تخفیف</label>
                                            <input type="number" name="amount" id="percentage"
                                                   class="form-control">
                                            @if($errors->has('amount'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('amount') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                        <div class="form-group">
                                            <label for="dateRangePicker{{$item->id}}">تاریخ تخفیف</label>
                                            <input type="text" name="daterangepicker" id="dateRangePicker{{$item->id}}"
                                                   class="form-control">
                                            @if($errors->has('start_date') || $errors->has('end_date'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">لطفا تاریخ را به درستی وارد نمایید</b>
                                            @endif
                                        </div>
                                        <input type="hidden" name="start_date" id="start_date_{{$item->id}}">
                                        <input type="hidden" name="end_date" id="end_date_{{$item->id}}">

                                    </div>
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                    <div class="form-group">
                                            <label for="discount_for">اعمال تخفیف برای</label>
                                            <select name="discount_for" id="discount_for"
                                                    class="form-control">
                                                    <option value="sending">هزینه ارسال</option>
                                                    <option value="self" selected>محصولات یا خدمات فروشگاه</option>
                                            </select>
                                            @if($errors->has('discount_for'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('discount_for') }}</b>
                                            @endif
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group" style="padding: 0 30px ">
                                        <label for="description">توضیحات تخفیف</label>
                                        <textarea type="number" name="description" id="description"
                                                   class="form-control"></textarea>
                                            @if($errors->has('description'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('description') }}</b>
                                            @endif
                                                   
                                    </div>
                                </div>
                                </div>
                                </div>
                                    <div>
                                        <input type="submit" class="btn btn-success" value="ایجاد تخفیف" />
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
    @endif
   
@endsection

@section('script')
    <script src="{{ url()->to('/admin/assets/js/moment.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/moment-jalaali.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/daterangepicker-fa-ex.js') }}"></script>
    <script>
        function deleteDiscount(id){
                $.ajax({
                    url: "{{ url()->to('api/user/discounts/delete') }}" + '/' + id.toString(),
                    type: 'delete',
                    headers : {
                        Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                    },
                    success: function (response) {
                    swal('موفقیت آمیز', 'با موفقیت حذف شد', 'success');
                    document.getElementById('discount-' + id.toString()).remove();
                    }
                })
            }
            var night;
            var isRtl = true;
            var dateFormat = isRtl ? 'jYYYY/jMM/jDD' : 'YYYY/MM/DD';
            var dateFrom = false ? moment("") : undefined;
            var dateTo = false ? moment("") : undefined;
        @foreach($allUserProducts as $item)
        var $dateRanger = $("#dateRangePicker{{$item->id}}");
        $dateRanger.daterangepicker({
                clearLabel: 'Clear',
                autoUpdateInput: !!(dateFrom && dateTo),
                autoApply: true,
                opens: isRtl ? 'left' : 'right',
                locale: {
                    separator: ' - ',
                    format: dateFormat
                },
                startDate: dateFrom,
                endDate: dateTo,
                jalaali: isRtl,
            }).on('apply.daterangepicker', function (ev, picker) {
                night = picker.endDate.diff(picker.startDate, 'days');
                if (night > 0) {
                    $(this).val(picker.startDate.format(dateFormat) + ' - ' + picker.endDate.format(dateFormat));
                    $('#start_date_{{$item->id}}').val(picker.startDate.format('YYYY/MM/DD'));
                    $('#end_date_{{$item->id}}').val(picker.endDate.format('YYYY/MM/DD'));
                } else {
                    $(this).val('')
                }
            }).on('showCalendar.daterangepicker' , function() {
                // changing icons in daterangepicker
                var el = $('.drp-angle-right');
                if(el.length){
                el.removeClass('drp-angle-right');
                el.addClass('fa');
                el.addClass('fa-arrow-left');
                var el = $('.drp-angle-left');
                el.removeClass('drp-angle-left');
                el.addClass('fa');
                el.addClass('fa-arrow-right');
                }
            });
            $('#position_id_{{$item->id}}').on('change' , function(){
            $('#position_price_{{$item->id}}').html(`مبلغ قابل پرداخت : ${$('#position_id_{{$item->id}} option:selected').data('price')} تومان`)
        });
        var discount_used = false;
        var discount_price = 0;
        $('.apply-discount-button').click(function(e){
            e.preventDefault();
            if(!discount_used){
            var discount_code = $('#discount_code_' + $(this).data('id')).val();
            
                $.ajax({
                type: 'get',
                url: '{{url()->to("api/discount/validate")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {
                    type : 'upgrade',
                    id : $('#position_id_{{$item->id}} option:selected').data('id'),
                    code : discount_code,
                },
                success: function (response) {
                    swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                    var $position = $('#position_id option:selected').data('price');
                    if(response.data.type == 'percentage')
                    $('#position_price_{{$item->id}}').html('مبلغ قابل پرداخت : ' +(parseInt($position) * response.data.percentage / 100).toString() + ' - ' + $position + ' تومان');
                    else
                    $('#position_price_{{$item->id}}').html('مبلغ قابل پرداخت : ' + response.data.percentage.toString() + ' - ' + $position + ' تومان');
                    $('#discount').val(response.data.id);
                    discount_used = true;
                },
                error: function (data){
                    swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                }
            });
            }
        });
        @endforeach
            setInterval(() => {
                // changing icons in daterangepicker
                var el = $('.drp-angle-right');
                if(el.length){
                el.removeClass('drp-angle-right');
                el.addClass('fa');
                el.addClass('fa-arrow-left');
                var el = $('.drp-angle-left');
                el.removeClass('drp-angle-left');
                el.addClass('fa');
                el.addClass('fa-arrow-right');
                }
            }, 500);
        
        $(window).click(function () {
            $('.products-list-page .product-item-wrapper .ul-menu ').hide(300);
        });
        $('.products-list-page .product-item-wrapper .menu ').click(function (e) {
            e.stopPropagation();
            var $this = $(this);
            var ul = $this.closest('.product-item-wrapper').find('.ul-menu');
            ul.toggle(300);
        });
    </script>
@endsection