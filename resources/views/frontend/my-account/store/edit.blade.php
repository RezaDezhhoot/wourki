@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | ویرایش فروشگاه</title>
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/datepicker-theme.css') }}">
    <style>
        .sweet-overlay {
        z-index: 100000000000;
        }
        .sweet-alert {
        z-index: 100000000000;
        }
        .btn-pink{
            border: 1px solid #fc2a23;
            color: #fff;
            background-color: #fc2a23;
            padding:10px 20px;
            transition: 500ms;
        }
        .btn-pink:hover{
            color : #fff;
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
        </style>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid save-store-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-certificate"></i>
                                    پلن اشتراک
                                </div>
                                <div class="panel-body buy-plan-panel">
                                    @include('frontend.errors')
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            @if($storeValidatePlan == true)
                                                <h4 class="text-center">
                                                    کاربر گرامی، شما پلن اشتراک با
                                                    <b>{{ $intervalDays }}</b>
                                                    روز اعتبار دارید.
                                                </h4>
                                                <a href="{{ route('user.plan.create.page') }}"
                                                   class="btn btn-xs btn-pink">تمدید پلن</a>
                                            @else
                                                <h4 class="text-center">
                                                    کاربر گرامی، شما فروشگاه خود را ثبت کرده اید، اما برای فعالیت در
                                                    سایت نیاز به خرید پلن دارید.
                                                </h4>
                                                <a href="{{ route('user.plan.create.page') }}"
                                                   class="btn btn-xs btn-pink">خرید پلن</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="upgrade" action="{{ route('upgrades.store.create') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <i class="fa fa-arrow-up"></i>
                                    ارتقا فروشگاه
                                </div>
                                <div class="panel-body buy-plan-panel">
                                <div style="padding:20px">
                                <div class="form-group">                                                         
                                    <label for="position_id">انتخاب جایگاه:</label>
                                    <select name="position_id" id="position_id" 
                                            class="form-control">
                                        @php
                                            $index = 0;
                                            $cond = true;
                                        @endphp
                                        @foreach($positions as $i => $position)
                                            @if(str_contains($position->position, 'store'))
                                            <option data-id={{$position->id}} data-price="{{$position->price}}" {{$cond ? "selected" : ""}}  value="{{ $position->id }}">{{ $position->name }}</option>
                                            @php
                                                if($cond)
                                                $index = $i;
                                                $cond = false;
                                            @endphp
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="row" style="margin-top:20px;margin-left:10px">
                                    <label class="col-sm-2" for="discount_code">کد تخفیف</label>
                                    <div class="col-sm-8" class="form-control-wrapper">
                                        <input type="text" name="discount_code" id="discount_code" placeholder="(اختیاری)"
                                            class="form-control">
                                        <input type="hidden" name="discount" id="discount" />
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-pink btn-sm" id="apply-discount-button">
                                            اعمال تخفیف
                                        </button>
                                    </div>
                                    </div>
                                    <p style="margin-top : 20px" id="position_price" class="alert alert-info">مبلغ قابل پرداخت : {{optional($positions[$index])->price}} تومان</p> 
                                    <input hidden type="number" name="store_id"  value="{{$store->id}}" />
                                </div>
                        </div>
                                    <div>
                                        <input type="submit" class="btn btn-success" value="پرداخت آنلاین" />
                                        <input type="submit" name="wallet" value="پرداخت کیف پول" class="btn btn-success" />
                                        <input type="button" class="btn btn-success" data-target="#upgrade-history-modal" data-toggle="modal" value="تاریخچه ارتقا">
                                    </div>
                                </div>
                            </div>
                            </form>
                            <form id="discount" action="{{ route('discounts.user.store.create') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input hidden type="number" value="{{$store->id}}" name="store_id" />
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <i class="fa fa-percent"></i>
                                    کد های تخفیف فروشگاه
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
                                            <label for="min_price">حداقل قیمت اعمال تخفیف</label>
                                            <input type="number" name="min_price" id="min_price"
                                                   class="form-control">
                                            @if($errors->has('min_price'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('min_price') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                        <div class="form-group">
                                            <label for="max_price">حداکثر قیمت اعمال تخفیف</label>
                                            <input type="number" name="max_price" id="max_price"
                                                   class="form-control">
                                            @if($errors->has('max_price'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">{{ $errors->first('max_price') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                        <div class="form-group">
                                            <label for="dateRangePicker">تاریخ تخفیف</label>
                                            <input type="text" name="daterangepicker" id="dateRangePicker"
                                                   class="form-control">
                                            @if($errors->has('start_date') || $errors->has('end_date'))
                                                <b class="text-danger" style="font-size : 12px;margin-top:10px;">لطفا تاریخ را به درستی وارد نمایید</b>
                                            @endif
                                        </div>
                                        <input type="hidden" name="start_date" id="start_date">
                                        <input type="hidden" name="end_date" id="end_date">

                                    </div>
                                    <div class="col-sm-4" style="padding: 0 30px ">
                                    <div class="form-group">
                                            <label for="discount_for">اعمال تخفیف برای</label>
                                            <select name="discount_for" id="discount_for"
                                                    class="form-control">
                                                    <option value="sending" >هزینه ارسال</option>
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
                                        <input type="button" class="btn btn-success" data-target="#discounts-modal" data-toggle="modal" value="تخفیف های فروشگاه">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('update.store.page') }}" method="post" class="form-horizontal"
                          id="editStoreForm"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-store"></i>
                                    مشخصات عمومی فروشگاه
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               title="کاملا واضح است! نام فروشگاه برای معرفی فروشگاه شما استفاده می شود."
                                                               for="storeName" class="col-sm-4 control-label">نام
                                                            فروشگاه</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="store_name" id="storeName"
                                                                   class="form-control" value="{{ $store->name }}">
                                                            <p class="text-danger error-container"></p>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="slogan" data-toggle="tooltip"
                                                               title="متنی است یک خطی که برای معرفی فروشگاه شما به دیگران استفاده می شود و حداکثر می تواند 30 کاراکتر باشد."
                                                               class="col-sm-4 control-label">شعار فروشگاه</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="slogan" id="slogan"
                                                                   class="form-control" value="{{ $store->slogan }}">
                                                            <p class="text-danger error-container"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               title="همان صنفی است که شما می خواهید در آن فعالیت کنید. زمینه فعالیت پس از ثبت فروشگاه غیر قابل تغییر خواهد بود."
                                                               for="guild" class="col-sm-4 control-label">زمینه
                                                            فعالیت</label>
                                                        <div class="col-sm-8">
                                                            <select disabled name="guild" id="guild"
                                                                    class="form-control">
                                                                <option disabled selected>::انتخاب کنید::</option>
                                                                @foreach($guilds as $guild)
                                                                    <option {{ $store->guild_id == $guild->id ? 'selected' : '' }} value="{{ $guild->id }}">{{ $guild->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger error-container"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               title="نام کاربری به عنوان شناسه فروشگاه شما شناخته می شود."
                                                               for="username" class="col-sm-4 control-label">نام
                                                            کاربری</label>
                                                        <div class="col-sm-8 username-container">
                                                            <input type="text" name="username" id="username" disabled
                                                                   class="form-control" value="{{ $store->user_name }}">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <p class="text-danger error-container"></p>

                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               title="متن کوتاهی بنویسید تا مخاطبینتان متوجه شوند فروشگاه شما چیست، چه کار می کند و چه محصولاتی عرضه می کند."
                                                               for="about_store" class="control-label col-sm-2">درباره
                                                            فروشگاه</label>
                                                        <div class="col-sm-10">
                                                    <textarea name="about" id="about_store" cols="30"
                                                              rows="5"
                                                              class="form-control">{{ $store->about }}</textarea>
                                                            <p class="text-danger error-container"></p>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               title="این گزینه به شما امکان می دهد که فروشگاه را به طور موقت از دید مشتریان خود مخفی کنید. این گزینه برای زمانی مفید است که می خواهید به طور موقت از مشتریان تان سفارش نگیرید."
                                                               for="visible">نمایش فروشگاه به مشتریان</label>
                                                        <input type="checkbox" name="visible" id="visible"
                                                               class="switchery" {{ $store->visible == 1 ? 'checked' : '' }}>
                                                        <p class="text-danger error-container"></p>

                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               title="با قراردادن مقدار این گزینه روی استان محل فعالیت خودتان، فقط مشتریانی به شما سفارش می دهند که آدرس آن ها درون محدوده استان فروشگاه شما باشد. در صورتی که مقدار این گزینه روی کل کشور قرار بگیرد، شما می توانید از کل کشور سفارش بگیرید."
                                                               for="activity_type" class="control-label col-sm-4">محدوده
                                                            فعالیت شما:</label>
                                                        <div class="col-sm-8">
                                                            <select name="activity_type" id="activity_type"
                                                                    class="form-control">
                                                                <option {{ $store->activity_type == 'province' ? 'selected' : '' }} value="province">
                                                                    استان محل فعالیت خودتان
                                                                </option>
                                                                <option {{ $store->activity_type == 'country' ? 'selected' : '' }} value="country">
                                                                    کل کشور
                                                                </option>
                                                            </select>
                                                            <p class="text-danger error-container"></p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-dollar-sign"></i>
                                    مالی و حسابداری
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label data-toggle="tooltip"
                                                       title="این فیلد حداقل مبلغ سفارش خریداران را مشخص می کند. خریداران نمی توانند فاکتورهایی کمتر از این مبلغ برای  شما ارسال کنند."
                                                       for="min_pay" class="col-sm-5 control-label">حداقل مبلغ خرید از
                                                    فروشگاه (تومان):

                                                </label>
                                                <div class="col-sm-7">
                                                    <input type="number" min="1000" class="form-control" id="min_pay"
                                                           name="min_pay" value="{{ $store->min_pay }}">
                                                    <p class="text-danger error-container"></p>

                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label data-toggle="tooltip"
                                                       title="این فیلد مشخص می کند کاربران به چه روش هایی می توانند پرداخت خود را انجام دهند."
                                                       class="control-label col-sm-4">نحوه پرداخت</label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="payment_type"
                                                               id="payment_type_online"
                                                               value="online" {{ $store->pay_type == 'online' ? 'checked' : '' }}>
                                                        آنلاین
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="payment_type"
                                                               id="payment_type_postal"
                                                               value="postal" {{ $store->pay_type == 'postal' ? 'checked' : '' }}>
                                                        پستی
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="payment_type"
                                                               id="payment_type_both"
                                                               value="both" {{ $store->pay_type == 'both' ? 'checked' : '' }}>
                                                        هر دو
                                                    </label>
                                                </div>
                                            </div>
                                        </div>--}}

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default communication-ways">
                                <div class="panel-heading">
                                    <i class="fas fa-user-circle"></i>
                                    راه های ارتباطی با مشتریان
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-10">
                                            <div class="form-group">
                                                <label for="telephone_number" class="control-label col-sm-2">تلفن
                                                    تماس</label>
                                                <div class="col-sm-10">
                                                    <input type="tel" name="telephone_number" id="telephone_number"
                                                           class="form-control" value="{{ $store->phone_number }}">
                                                    <p class="text-danger error-container"></p>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <div class="form-group btn-wrapper text-center">
                                                <label data-toggle="tooltip"
                                                       title="این گزینه مشخص می کند که شماره تماس به مشتریان نمایش داده بشود یا خیر."
                                                       for="show_telephone_number" data-toggle-checkbox
                                                       class="checkbox-inline btn btn-pink {{ $store->phone_number_visibility == 'show' ? '' : 'btn-bordered' }} hover-without-style btn-xs">
                                                    <input type="checkbox" name="show_telephone_number"
                                                           id="show_telephone_number" {{ $store->phone_number_visibility == 'show' ? 'checked' : '' }}>
                                                    <span>نمایش تلفن تماس</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-10">
                                            <div class="form-group">
                                                <label data-toggle="tooltip"
                                                       title="کاملا واضح است! آدرس محل فعالیت فروشگاه!" for="address"
                                                       class="control-label col-sm-2">انتخاب آدرس</label>
                                                <div class="col-sm-10">
                                                    <select name="address" id="address" class="form-control">
                                                        <option disabled selected>::انتخاب کنید::</option>
                                                        @foreach($addresses as $address)
                                                            <option {{ $store->address_id == $address->id ? 'selected' : '' }} value="{{ $address->id }}">{{ $address->address }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger error-container"></p>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <div class="form-group select-address text-center">
                                                <a data-toggle="modal" data-target="#addAddressModal"
                                                   class="btn btn-pink btn-sm"><i class="fas fa-plus-circle"></i>&nbsp;
                                                    افزودن آدرس</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 text-center submit-btn-wrapper">
                                            <button name="store_type" value="product" type="submit" class="btn btn-pink btn-sm btn-bordered">
                                                @if($store)ویرایش فروشگاه
                                                @else ثبت فروشگاه
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                </div>
            </div>
        </div>
    </section>
             @php
             $upgrades = $store->upgrades()->where('upgrades.status' , 'approved')->orderByDesc('upgrades.updated_at')->paginate(20);   
            @endphp
            <div class="modal fade" id="upgrade-history-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">تاریخچه ارتقا فروشگاه
                                <b class="text-primary">{{ $store->name }}</b>
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
                            <p class="text-danger" style="font-size : 12px;margin-top:10px;">این فروشگاه  تا به حال ارتقا داده نشده است</p>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="discounts-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">تخفیف های فروشگاه
                                <b class="text-primary">{{ $store->name }}</b>
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
                            <p class="text-danger" style="font-size : 12px;margin-top:10px;">برای این فروشگاه تخفیفی ثبت نشده است</p>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="">
                    <div class="modal-body">
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <i class="fas fa-map-marker-alt"></i>
                                افزودن آدرس جدید
                            </div>
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="province">استان</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-map-pin"></i>
                                                <select name="province" class="form-control" id="province">
                                                    <option disabled selected>::انتخاب کنید::</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="modal_city">شهر</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-map-pin"></i>
                                                <select name="modal_city" class="form-control" id="modal_city">

                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="modal_address">آدرس:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <input type="text" name="modal_address" id="modal_address"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="modal_postal_code">کد پستی:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-envelope-open"></i>
                                                <input type="text" name="modal_postal_code" id="modal_postal_code"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="modal_phone_number">تلفن تماس:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-phone"></i>
                                                <input type="text" name="modal_phone_number" id="modal_phone_number"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="modal_type">نوع محل:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-building"></i>
                                                <select name="modal_type" class="form-control" id="modal_type">
                                                    <option value="home">خانه</option>
                                                    <option value="store">مغازه</option>
                                                    <option value="warehouse">انبار</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div>
                                            <input type="text" placeholder="نام مکان را جستجو کنید.." id="pac-input"
                                                   style="opacity:0.6;width: 420px;font-family: IRANSans;"
                                                   class="form-control">
                                        </div>
                                        <div id="map-canvas" style="width:100%;height:350px;">

                                        </div>

                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="createAddress" class="btn btn-pink pull-right btn-sm">ذخیره</button>
                        <button type="button" class="btn btn-gray pull-right btn-sm" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#addAddressModal').on('shown.bs.modal', function () {
            window.resizeBy(1, 1);
        });
    </script>
    <script src="{{ url()->to('/admin/assets/js/moment.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/moment-jalaali.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/daterangepicker-fa-ex.js') }}"></script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HbDPHxl75YCub0FIOxXroYRyRd_dm9U&language=fa&region=IR&libraries=places"></script> --}}
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
            var $dateRanger = $("#dateRangePicker");
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
                    $('#start_date').val(picker.startDate.format('YYYY/MM/DD'));
                    $('#end_date').val(picker.endDate.format('YYYY/MM/DD'));
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
        $('#position_id').on('change' , function(){
            $('#position_price').html(`مبلغ قابل پرداخت : ${$('#position_id option:selected').data('price')} تومان`)
        });
        var discount_used = false;
        var discount_price = 0;
        $('#apply-discount-button').click(function(e){
            e.preventDefault();
            if(!discount_used){
            var discount_code = $('#discount_code').val();
            
                $.ajax({
                type: 'get',
                url: '{{url()->to("api/discount/validate")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {
                    type : 'upgrade',
                    id : $('#position_id option:selected').data('id'),
                    code : discount_code,
                },
                success: function (response) {
                    swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                    var $position = $('#position_id option:selected').data('price');
                    if(response.data.type == 'percentage')
                    $('#position_price').html('مبلغ قابل پرداخت : ' +(parseInt($position) * response.data.percentage / 100).toString() + ' - ' + $position + ' تومان');
                    else
                    $('#position_price').html('مبلغ قابل پرداخت : ' + response.data.percentage.toString() + ' - ' + $position + ' تومان');
                    $('#discount').val(response.data.id);
                    discount_used = true;
                },
                error: function (data){
                    swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                }
            });
            }
        });
        $(document).ready(function () {

            $('#username').keyup(function () {
                var username = $(this);
                username.removeAttr("style");
                $.ajax({
                    url: '{{ route('stores.check.username') }}',
                    type: 'get',
                    data: {
                        'username': username.val(),
                    },
                    success: function () {
                        username.css('color', 'red');
                        username.css('font-weight', 'bold');
                    }

                });
            });

            $('#createAddress').click(function (e) {
                e.preventDefault();
                var _token = $('#_token').val();
                $.ajax({
                    url: '{{ route('user.address.createByAjax') }}',
                    type: 'post',
                    data: {
                        'city_id': $('#modal_city').val(),
                        'address': $('#modal_address').val(),
                        'postal_code': $('#modal_postal_code').val(),
                        'phone_number': $('#modal_phone_number').val(),
                        'type': $('#modal_type').val(),
                        'latitude': $('#latitude').val(),
                        'longitude': $('#longitude').val(),
                        '_token': _token,
                    },
                    success: function () {
                        $('#addAddressModal').modal('hide');
                        swal("ثبت موفقیت.", "ثبت آدرس با موفقیت انجام شد.", "success");

                        var address = $('#address');
                        $.ajax({
                            type: 'get',
                            url: '{{ route('get.user.address.by.ajax') }}',
                            data: {},

                            success: function (response) {
                                address.append('<option value=" ' + response.id + ' ">' + response.address + '</option>');
                            }
                        })
                    }
                });

            });

            $('#province').change(function () {
                var province = $(this);
                var city = $('#modal_city');


            });

        });

        $(document).on('keypress', '#pac-input', function (e) {
            if (e.keyCode == 13) {
                return false;
            }
        });

        var center = {
            lat: 32.646911,
            lng: 51.667878
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: center,
            zoom: 13
        });
        var removeLocationButton = document.getElementById('remove-location');
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });
        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
        var marker;

        function addMarker(location) {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }

        function setMapOnAll(map) {
            marker.setMap(map);
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function showMarkers() {
            setMapOnAll(map);
        }

        function deleteMarkers() {
            clearMarkers();
            marker = null;
        }

        function initMap() {
            map.addListener('click', function (e) {
                if (marker != undefined) {
                    clearMarkers();
                }
                marker = new google.maps.Marker({
                    position: e.latLng,
                    map: map
                });
                setMapOnAll(map);

                document.getElementById('latitude').value = e.latLng.lat();
                document.getElementById('longitude').value = e.latLng.lng();
            });
            removeLocationButton.addEventListener('click', function () {
                if (marker != undefined) {
                    clearMarkers();
                }
                document.getElementById('latitude').value = "";
                document.getElementById('longitude').value = "";
            });
        }

        window.onload = initMap;

        jQuery.validator.addMethod('CustomUrl', function (value, element) {
            if (!value) {
                return true;
            }
            var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
            var regex = new RegExp(expression);
            if (value.match(regex)) {
                return true;
            }
            return false;
        }, 'url نامعتبر است.');
        $('#editStoreForm').validate({
            rules: {
                store_name: {
                    required: true,
                    maxlength: 200
                },
                slogan: {
                    required: true,
                    maxlength: 300
                },
                guild: {
                    required: true,
                    number: true
                },
                about: {
                    required: true,
                    maxlength: 500
                },
                min_pay: {
                    required: true,
                    min: 1000
                },
                telephone_number: {
                    required: true,
                    digits: true
                },
                telegram_address: {
                    CustomUrl: true
                },
                instagram_address: {
                    CustomUrl: true
                },
                address: {
                    required: true,
                    number: true
                }
            },
            messages: {
                store_name: {
                    required: 'نام فروشگاه الزامی است.',
                    maxlength: 'نام فروشگاه طولانی تر از حد مجاز است.'
                },
                slogan: {
                    required: 'شعار فروشگاه الزامی است.',
                    maxlength: 'شعار فروشگاه طولانی تز از حد مجاز است.'
                },
                guild: {
                    required: 'زمینه فعالیت الزامی است.',
                    number: 'زمینه فعالیت نامعتبر است.'
                },
                about: {
                    required: 'درباره فروشگاه الزامی است.',
                    maxlength: 'درباره فروشگاه طولانی تر از حد مجاز است.'
                },
                min_pay: {
                    required: 'حداقل مبلغ خرید از فروشگاه الزامی است.',
                    min: 'حداقل مبلغ خرید از فروشگاه باید 1000 تومان باشد.'
                },
                telephone_number: {
                    required: 'تلفن تماس الزامی است.',
                    digits: 'تلفن تماس باید متشکل از ارقام باشد.'
                },
                address: {
                    required: 'انتخاب آدرس الزامی است.',
                    number: 'آدرس نامعتبر است.'
                }
            },
            errorPlacement: function (error, element) {
                var placeholder = element.closest('.form-group').find('.text-danger.error-container');
                placeholder.html(error.text());
            }

        })
    </script>
@endsection