@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | تبلیغات</title>

    <style>
        .sweet-overlay {
            z-index: 100000000000;
        }

        .sweet-alert {
            z-index: 100000000000;
        }

        .submitted-ads-item {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 30px;
        }

        .submitted-ads-item h4 {
            color: #0a6aa1;
            font-size: 15px;
            margin-top: 30px;
        }

        .submitted-ads-item p {
            float: right;
            margin-left: 30px;
        }


        .submitted-ads-item a.stairs {
            color: #00AA00;
            font-weight: bold;
            margin-right: 30px;
        }

        .submitted-ads-item a.edit-ad {
            color: mediumvioletred;
            font-weight: bold;
            margin-right: 30px;
        }

        .submitted-ads-item a.delete-ad {
            color: red;
            font-weight: bold;
            margin-right: 30px;
        }

        .bottom-spacer {
            margin-bottom: 12px;
        }
    </style>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <?php use App\Store;
    $user = auth()
        ->guard('web')
        ->user();
    ?>
    <section class="container-fluid ads-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-audio-description"></i>
                                تبلیغات
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <ul class="nav nav-tabs">
                                                    <li role="presentation" class="active">
                                                        <a href="#submit_new_ad" data-toggle="tab">ثبت تبلیغ جدید</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#ads_list" data-toggle="tab">لیست تبلیغات
                                                            ثبت شده</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-content">
                                            <div id="submit_new_ad" class="tab-pane fade in active">
                                                @if ($helpText)
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                                                            <div class="alert alert-warning">
                                                                {!! nl2br($helpText) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (count($errors->all()) > 0)
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                                                            <div class="alert alert-danger text-center">
                                                                @foreach ($errors->all() as $error)
                                                                    {{ $error }} <br />
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                                                        <form action="{{ route('my_account.ads.save') }}"
                                                            class="form-horizontal" id="saveAdForm" method="post"
                                                            enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <div class="form-group">
                                                                <label for="ads_position"
                                                                    class="col-sm-2 control-label">جایگاه</label>
                                                                <div class="col-sm-10">
                                                                    <select name="position" id="ads_position"
                                                                        class="form-control">
                                                                        @foreach ($positions as $ps)
                                                                            <option data-price="{{ $ps->price }}"
                                                                                data-id="{{ $ps->id }} "
                                                                                value="{{ $ps->id }}">
                                                                                {{ $ps->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-2" for="discount_code">کد تخفیف</label>
                                                                <div class="col-sm-8" class="form-control-wrapper">
                                                                    <input type="text" name="discount_code"
                                                                        id="discount_code" placeholder="(اختیاری)"
                                                                        class="form-control">
                                                                    <input type="hidden" name="discount" id="discount" />
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <button class="btn btn-pink btn-sm"
                                                                        id="apply-discount-button">
                                                                        اعمال تخفیف
                                                                    </button>
                                                                </div>
                                                                <p class="text-danger error-container"></p>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-sm-10 col-sm-offset-2">
                                                                    <div class="alert alert-info">
                                                                        مبلغ قابل پرداخت:
                                                                        <span class="position-price"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="form-group"> --}}
                                                            {{-- <label for="ads_photo" class="col-sm-2 control-label">آپلود --}}
                                                            {{-- تصویر</label> --}}
                                                            {{-- <div class="col-sm-10"> --}}
                                                            {{-- <input type="file" name="ads_photo" id="ads_photo" --}}
                                                            {{-- class="form-control"> --}}
                                                            {{-- </div> --}}
                                                            {{-- </div> --}}
                                                            <div class="form-group">
                                                                <label for="description"
                                                                    class="col-sm-2 control-label">توضیحات</label>
                                                                <div class="col-sm-10">
                                                                    <textarea name="description" id="description" cols="30" rows="5" style="height:70px;" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="link_to" class="col-sm-2 control-label">لینک
                                                                    به:</label>
                                                                <div class="col-sm-10">
                                                                    <select name="link_to" id="link_to"
                                                                        class="form-control">
                                                                        <option value="store">فروشگاه</option>
                                                                        <option value="product">محصول</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group hidden" id="link_to_product">
                                                                <label for="product_name" class="col-sm-2 control-label">نام
                                                                    محصول / خدمت:</label>
                                                                <div class="col-sm-10">
                                                                    <select name="product_name" id="product_name"
                                                                        class="form-control">
                                                                        @foreach ($products as $product)
                                                                            <option value="{{ $product->id }}">
                                                                                {{ $product->name }}</option>
                                                                        @endforeach
                                                                        @foreach ($services as $service)
                                                                            <option value="{{ $service->id }}">
                                                                                {{ $service->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="link_to_store">
                                                                <label for="store_type" class="col-sm-2 control-label">نام
                                                                    فروشگاه:</label>
                                                                <div class="col-sm-10">
                                                                    <select name="store_type" id="store_type"
                                                                        class="form-control">
                                                                        @foreach (Store::where('user_id', $user->id)->get() as $s)
                                                                            <option value="{{ $s->store_type }}">
                                                                                {{ $s->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <p style="padding: 14px;"
                                                                data-wallet-stock="{{ $walletStock }}"
                                                                class="wallet-stock-is-enough-or-not col-sm-10 col-sm-offset-2">

                                                            </p>
                                                            <div class="form-group">
                                                                <div class="col-sm-10 col-sm-offset-2">
                                                                    <button class="btn btn-pink btn-sm"
                                                                        data-toggle="modal"
                                                                        data-target="#walletChargeModal">
                                                                        شارژ کیف پول
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-sm-10 col-sm-offset-2">
                                                                    <button id="submit_btn" name="action" value="wallet"
                                                                        type="submit" class="btn btn-pink btn-sm">
                                                                        پرداخت از طریق کیف پول
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-sm-10 col-sm-offset-2">
                                                                    <button id="submit_btn" name="action"
                                                                        value="gateway" type="submit"
                                                                        class="btn btn-pink btn-sm">
                                                                        پرداخت از طریق درگاه
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="ads_list" class="tab-pane fade in">
                                                <div class="row">
                                                    @foreach ($adsList as $ad)
                                                        <div class="col-xs-12">
                                                            <div id="edit_ad_{{ $ad->id }}"
                                                                class="modal fade extend-ad-price" tabindex="-1"
                                                                role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close"><span
                                                                                    aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            <h4 class="modal-title">ویرایش تبلیغ</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-xs-12 col-md-8 col-md-offset-2">
                                                                                    <form
                                                                                        action="{{ route('my_account.ads.update', $ad->id) }}"
                                                                                        class="form-horizontal"
                                                                                        id="editAdForm" method="post"
                                                                                        enctype="multipart/form-data">
                                                                                        {{ csrf_field() }}
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="ads_{{ $ad->id }}_description"
                                                                                                class="col-sm-2 control-label">توضیحات</label>
                                                                                            <div class="col-sm-10">
                                                                                                <textarea name="description" id="ads_{{ $ad->id }}_description" cols="30" rows="5"
                                                                                                    style="height:70px;" class="form-control ads-description">{{ $ad->description }}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="ads_{{ $ad->id }}_link_to"
                                                                                                class="col-sm-2 control-label">لینک
                                                                                                به:</label>
                                                                                            <div class="col-sm-10">
                                                                                                <select name="link_to"
                                                                                                    id="ads_{{ $ad->id }}_link_to"
                                                                                                    class="form-control ads-link-to">
                                                                                                    <option
                                                                                                        {{ $ad->link_type == 'store' ? 'selected' : '' }}
                                                                                                        value="store">
                                                                                                        فروشگاه
                                                                                                    </option>
                                                                                                    <option
                                                                                                        {{ $ad->link_type == 'product' ? 'selected' : '' }}
                                                                                                        value="product">
                                                                                                        محصول
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group hidden ads-link-to-product"
                                                                                            id="ads_{{ $ad->id }}link_to_product">
                                                                                            <label
                                                                                                for="ads_{{ $ad->id }}_product_name"
                                                                                                class="col-sm-2 control-label">نام
                                                                                                محصول / خدمت:</label>
                                                                                            <div class="col-sm-10">
                                                                                                <select name="product_name"
                                                                                                    id="ads_{{ $ad->id }}_product_name"
                                                                                                    class="form-control ads-product-link-to">
                                                                                                    @foreach ($products as $product)
                                                                                                        <option
                                                                                                            {{ $ad->product_id == $product->id ? 'selected' : '' }}
                                                                                                            value="{{ $product->id }}">
                                                                                                            {{ $product->name }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                    @foreach ($services as $service)
                                                                                                        <option
                                                                                                            value="{{ $service->id }}">
                                                                                                            {{ $service->name }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group ads-link-to-store"
                                                                                            id="ads_{{ $ad->id }}link_to_store">
                                                                                            <label
                                                                                                for="ads_{{ $ad->id }}_store_name"
                                                                                                class="col-sm-2 control-label">نام
                                                                                                فروشگاه:</label>
                                                                                            <div class="col-sm-10">
                                                                                                <select name="store_type"
                                                                                                    id="ads_{{ $ad->id }}_store_name"
                                                                                                    class="form-control ads-store-link-to">
                                                                                                    @foreach (Store::where('user_id', $user->id)->get() as $s)
                                                                                                        <option
                                                                                                            {{ $ad->store_id == $s->id ? 'selected' : '' }}
                                                                                                            value="{{ $s->store_type }}">
                                                                                                            {{ $s->name }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <div
                                                                                                class="col-sm-10 col-sm-offset-2">
                                                                                                <button
                                                                                                    id="ads_{{ $ad->id }}_submit_btn"
                                                                                                    type="submit"
                                                                                                    class="btn btn-pink btn-sm current-ad-submit-btn">ویرایش
                                                                                                    تبلیغ</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="extend_ad_{{ $ad->id }}"
                                                                class="modal fade extend-ad-price" tabindex="-1"
                                                                role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close"><span
                                                                                    aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            <h4 class="modal-title">تمدید تبلیغ</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-xs-12 col-md-10 col-md-offset-1">
                                                                                    <form
                                                                                        action="{{ route('extend.ad', $ad->id) }}"
                                                                                        class="form-horizontal"
                                                                                        id="saveAdForm" method="post"
                                                                                        enctype="multipart/form-data">
                                                                                        {{ csrf_field() }}
                                                                                        <input type="hidden"
                                                                                            name="position"
                                                                                            data-position-id="{{ $ad->position_id }}"
                                                                                            id="ads_{{ $ad->id }}_position"
                                                                                            class="ads-position"
                                                                                            value="{{ $ad->position_price }}">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                for="ads_{{ $ad->id }}_description"
                                                                                                class="col-sm-2 control-label">توضیحات</label>
                                                                                            <div class="col-sm-10">
                                                                                                <textarea name="description" id="ads_{{ $ad->id }}_description" cols="30" rows="5"
                                                                                                    style="height:70px;" class="form-control ads-description"></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        {{-- <div class="form-group">
                                                                                            <label for="ads_{{ $ad->id }}_link_to"
                                                                                                   class="col-sm-2 control-label">لینک
                                                                                                به:</label>
                                                                                            <div class="col-sm-10">
                                                                                                <select name="link_to"
                                                                                                        id="ads_{{ $ad->id }}_link_to"
                                                                                                        class="form-control ads-link-to">
                                                                                                    <option {{ $ad->link_type == 'store' ? 'selected' : '' }} value="store">
                                                                                                        فروشگاه
                                                                                                    </option>
                                                                                                    <option {{ $ad->link_type == 'product' ? 'selected' : '' }} value="product">
                                                                                                        محصول
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div> --}}

                                                                                        <div class="form-group">
                                                                                            <div
                                                                                                class="col-sm-10 col-sm-offset-2">
                                                                                                <div
                                                                                                    class="alert alert-info">
                                                                                                    مبلغ قابل پرداخت:
                                                                                                    <span
                                                                                                        class="ads-position-price"
                                                                                                        id="position-price-{{ $ad->id }}"></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        {{-- <div class="form-group hidden ads-link-to-product"
                                                                                             id="ads_{{ $ad->id }}link_to_product">
                                                                                            <label for="ads_{{ $ad->id }}_product_name"
                                                                                                   class="col-sm-2 control-label">نام
                                                                                                محصول / خدمت:</label>
                                                                                            <div class="col-sm-10">
                                                                                                <select name="product_name"
                                                                                                        id="ads_{{ $ad->id }}_product_name"
                                                                                                        class="form-control ads-product-link-to">
                                                                                                    @foreach ($products as $product)
                                                                                                        <option {{ $ad->product_id == $product->id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                                                                                    @endforeach
                                                                                                    @foreach ($services as $service)
                                                                                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group ads-link-to-store"
                                                                                             id="ads_{{ $ad->id }}link_to_store">
                                                                                            <label for="ads_{{ $ad->id }}_store_name"
                                                                                                   class="col-sm-2 control-label">نام
                                                                                                فروشگاه:</label>
                                                                                            <div class="col-sm-10">
                                                                                                <select name="store_type"
                                                                                                        id="ads_{{ $ad->id }}_store_name"
                                                                                                        class="form-control ads-store-link-to">
                                                                                                    @foreach (Store::where('user_id', $user->id)->get() as $s)
                                                                                                        <option {{ $ad->store_id == $s->id ? 'selected' : '' }} value="{{ $s->store_type }}">{{ $s->name }}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div> --}}
                                                                                        <div class="form-group">
                                                                                            <label class="col-sm-2"
                                                                                                for="discount_code_{{ $ad->id }}"
                                                                                                style="white-space: nowrap">کد
                                                                                                تخفیف</label>
                                                                                            <div class="col-sm-6"
                                                                                                class="form-control-wrapper">
                                                                                                <input type="text"
                                                                                                    name="discount_code"
                                                                                                    id="discount_code_{{ $ad->id }}"
                                                                                                    placeholder="(اختیاری)"
                                                                                                    class="form-control">
                                                                                                <input type="hidden"
                                                                                                    name="discount"
                                                                                                    id="discount_{{ $ad->id }}" />
                                                                                            </div>
                                                                                            <div class="col-sm-2">
                                                                                                <button
                                                                                                    class="btn btn-pink btn-sm apply-discount-button"
                                                                                                    data-id="{{ $ad->id }}"
                                                                                                    data-position="{{ $ad->position_id }}"
                                                                                                    data-price="{{ $ad->position->price }}">
                                                                                                    اعمال تخفیف
                                                                                                </button>
                                                                                            </div>
                                                                                            <p
                                                                                                class="text-danger error-container">
                                                                                            </p>
                                                                                        </div>
                                                                                        <p style="padding: 14px;"
                                                                                            data-current-ad-wallet-stock="{{ $walletStock }}"
                                                                                            class="current-ad-wallet-stock-is-enough-or-not col-sm-10">

                                                                                        </p>
                                                                                        <div class="form-group">
                                                                                            <div class="col-sm-10">
                                                                                                <button
                                                                                                    id="ads_{{ $ad->id }}_submit_btn"
                                                                                                    type="submit"
                                                                                                    class="btn btn-pink btn-sm current-ad-submit-btn">
                                                                                                    پرداخت به حساب و ثبت
                                                                                                    تبلیغ
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="delete_ad_{{ $ad->id }}"
                                                                class="modal fade extend-ad-price" tabindex="-1"
                                                                role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close"><span
                                                                                    aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            <h4 class="modal-title">حذف تبلیغ</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-xs-12 col-md-8 col-md-offset-2">
                                                                                    <form
                                                                                        action="{{ route('my_account.ads.delete', $ad->id) }}"
                                                                                        class="form-horizontal"
                                                                                        id="saveAdForm" method="post"
                                                                                        enctype="multipart/form-data">
                                                                                        <div
                                                                                            class="text-center bottom-spacer">
                                                                                            آیا از حذف تبلیغ اطمینان دارید؟
                                                                                        </div>
                                                                                        {{ csrf_field() }}
                                                                                        <div
                                                                                            class="form-group  text-center">
                                                                                            <button
                                                                                                id="ads_{{ $ad->id }}_submit_btn"
                                                                                                type="submit"
                                                                                                class="btn btn-pink btn-sm current-ad-submit-btn">
                                                                                                حذف تبلیغ
                                                                                            </button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="submitted-ads-item">
                                                                <img src="{{ url()->to('/image/ads') }}/{{ $ad->final_pic != null ? $ad->final_pic : $ad->pic }}"
                                                                    style="width:100%; max-width: 600px" alt="">
                                                                <h4>جایگاه:
                                                                    {{ $ad->position_name }}
                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#extend_ad_{{ $ad->id }}"
                                                                        class="stairs">ارتقاء</a>
                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#edit_ad_{{ $ad->id }}"
                                                                        class="edit-ad">ویرایش تبلیغ</a>
                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#delete_ad_{{ $ad->id }}"
                                                                        class="delete-ad">حذف تبلیغ</a>
                                                                </h4>

                                                                <p class="float-right">وضعیت تایید:
                                                                    <b class="text-success">
                                                                        @if ($ad->status == 'approved')
                                                                            <span class="text-success">تایید شده</span>
                                                                        @else
                                                                            <span class="text-danger">در انتظار
                                                                                تایید</span>
                                                                        @endif
                                                                    </b>
                                                                </p>
                                                                <p class="float-right">
                                                                    <b class="text-success">اعتبار تا تاریخ
                                                                        {{ \Morilog\Jalali\Jalalian::forge($ad->expire_date)->format('%d %B %Y') }}
                                                                    </b>
                                                                </p>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="walletChargeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">شارژ کیف پول</h4>
                </div>
                <form action="{{ route('charge.user.wallet') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="charge_amount">مبلغ شارژ(تومان):</label>
                            <input type="number" name="cost" id="charge_amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pink">پرداخت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var discount_used = false;
        var discount_price = 0;
        $('#apply-discount-button').click(function(e) {
            e.preventDefault();
            if (!discount_used) {
                var discount_code = $('#discount_code').val();

                $.ajax({
                    type: 'get',
                    url: '{{ url()->to('api/discount/validate') }}',
                    headers: {
                        Authorization: 'Bearer {{ Cookie::get('X_AJAX_TOKEN') }}'
                    },
                    data: {
                        type: 'ad',
                        id: $('#ads_position option:selected').data('id'),
                        code: discount_code,
                        price: $('#ads_position option:selected').data('price')
                    },
                    success: function(response) {
                        swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                        var $position = $('#ads_position option:selected').data('price');
                        if (response.data.type == 'percentage')
                            $('.position-price').html((parseInt($position) * response.data.percentage /
                                100).toString() + ' - ' + $position + ' تومان');
                        else
                            $('.position-price').html(response.data.percentage.toString() + ' - ' +
                                $position + ' تومان');
                        $('#discount').val(response.data.id);
                        var $positions = $('.ads-position');
                        $positions.each(function() {
                            var $this = $(this);
                            var price = $this.val();
                            var modal = $this.closest('.extend-ad-price');
                            var walletStock = modal.find(
                                '.current-ad-wallet-stock-is-enough-or-not');
                            var is_enough = walletStock.data('current-ad-wallet-stock') >
                                price - discount_price;
                            if (is_enough) {
                                walletStock.html(
                                    '<b class="text-success">موجودی کیف پول کافی</b>');
                                modal.find('.current-ad-submit-btn').removeAttr('disabled');
                            } else {
                                walletStock.html(
                                    '<b class="text-danger">موجودی کیف پول نا کافی</b>');
                                modal.find('.current-ad-submit-btn').attr('disabled',
                                    'disabled');
                            }
                        });
                        discount_used = true;
                    },
                    error: function(data) {
                        swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                    }
                });
            }
        });
        var discount_stairs_used = false;
        var discount_stairs_price = 0;
        $('.apply-discount-button').click(function(e) {
            e.preventDefault();
            var ad_id = $(this).data('id');
            var ad_position_id = $(this).data('position');
            var price = $(this).data('price');
            if (!discount_used) {
                var discount_code = $('#discount_code_' + ad_id).val();

                $.ajax({
                    type: 'get',
                    url: '{{ url()->to('api/discount/validate') }}',
                    headers: {
                        Authorization: 'Bearer {{ Cookie::get('X_AJAX_TOKEN') }}'
                    },
                    data: {
                        type: 'ad',
                        id: ad_position_id,
                        code: discount_code,
                        price
                    },
                    success: function(response) {
                        swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                        var $position = $('#ads_position option:selected').data('price');
                        if (response.data.type == 'percentage')
                            $('#position-price-' + ad_id).html((parseInt($position) * response.data
                                .percentage / 100).toString() + ' - ' + $position + ' تومان');
                        else
                            $('#position-price-' + ad_id).html(response.data.percentage.toString() +
                                ' - ' + $position + ' تومان');
                        $('#discount_' + ad_id).val(response.data.id);
                        var $positions = $('.ads-position');
                        $positions.each(function() {
                            var $this = $(this);
                            var price = $this.val();
                            var modal = $this.closest('.extend-ad-price');
                            var walletStock = modal.find(
                                '.current-ad-wallet-stock-is-enough-or-not');
                            var is_enough = walletStock.data('current-ad-wallet-stock') >
                                price - discount_stairs_price;
                            if (is_enough) {
                                walletStock.html(
                                    '<b class="text-success">موجودی کیف پول کافی</b>');
                                modal.find('.current-ad-submit-btn').removeAttr('disabled');
                            } else {
                                walletStock.html(
                                    '<b class="text-danger">موجودی کیف پول نا کافی</b>');
                                modal.find('.current-ad-submit-btn').attr('disabled',
                                    'disabled');
                            }
                        });
                        discount_stairs_used = true;
                    },
                    error: function(data) {
                        swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                    }
                });
            }
        });
        $(document).ready(function() {
            var is_enough = $('#ads_position option:selected').data('price') <= $('.wallet-stock-is-enough-or-not')
                .data('wallet-stock');
            if (is_enough) {
                $('.wallet-stock-is-enough-or-not').html('<b class="text-success">موجودی کیف پول کافی</b>');
                $('#submit_btn').removeAttr('disabled');
            } else {
                $('.wallet-stock-is-enough-or-not').html('<b class="text-danger">موجودی کیف پول نا کافی</b>');
                $('#submit_btn').attr('disabled', 'disabled');
            }
        });
        $('#ads_position , #payment_type').on('change', function() {
            var is_enough = $('#ads_position option:selected').data('price') <= $('.wallet-stock-is-enough-or-not')
                .data('wallet-stock');
            if (is_enough) {
                $('.wallet-stock-is-enough-or-not').html('<b class="text-success">موجودی کیف پول کافی</b>')
                $('#submit_btn').removeAttr('disabled');
            } else {
                $('.wallet-stock-is-enough-or-not').html('<b class="text-danger">موجودی کیف پول نا کافی</b>')
                $('#submit_btn').attr('disabled', 'disabled');
            }
        });
        $('#payment_type').change(function() {
            var value = $(this).val();
            if (value == 'online') {
                $('.wallet-stock-is-enough-or-not').css('display', 'none');
            } else {
                $('.wallet-stock-is-enough-or-not').css('display', 'block');
            }
        });

        $('#shaba-btn').click(function() {
            $('#shaba-form').css('display', 'block');
            $(this).css('display', 'none');
        });

        $('#link_to').on('ready change', function() {
            var $this = $(this);
            var link_to_product = $('#link_to_product');
            var link_to_store = $('#link_to_store');
            if ($this.val() == 'product') {
                link_to_product.removeClass('hidden').addClass('visible').css('display', 'none').fadeIn();
                link_to_store.fadeOut().removeClass('visible').addClass('hidden').css('display', 'block');

            } else {
                link_to_store.removeClass('hidden').addClass('visible').css('display', 'none').fadeIn();
                link_to_product.fadeOut().removeClass('visible').addClass('hidden').css('display', 'block');
            }
        });
        jQuery.validator.addMethod('link_to_should_be_store_or_product', function(value, element) {
            if (value != 'store' && value != 'product') {
                return false;
            }
            return true;
        }, 'این فیلد باید فروشگاه یا محصول باشد.');
        $('#saveAdForm').validate({
            rules: {
                position: {
                    required: true,
                    number: true
                },
                ads_photo: {
                    required: true,
                },
                description: {
                    required: true,
                    minlength: 5
                },
                link_to: {
                    link_to_should_be_store_or_product: true,
                },
                product_name: {
                    number: true,
                }
            },
            messages: {
                position: {
                    required: 'جایگاه الزامی است.',
                    number: 'جایگاه نامعتبر است.'
                },
                ads_photo: {
                    required: 'تصویر الزامی است.',
                },
                description: {
                    required: 'توضیحات الزامی است.',
                    minlength: 'توضیحات باید حداقل 5 کاراکتر باشد.'
                },
                product_name: {
                    number: 'نام محصول نامعتبر است.'
                }
            },
            errorClass: 'text-red'
        });

        $(document).ready(function() {
            var $position = $('#ads_position option:selected').data('price');
            $('.position-price').html($position + ' تومان');
        });
        $('#ads_position').change(function() {
            var $position = $('#ads_position option:selected').data('price');
            $('.position-price').html($position + ' تومان');
            $('#discount').val('');
            discount_used = false;
        });

        $(document).ready(function() {
            var link_to = $('.ads-link-to');
            link_to.each(function() {
                var $this = $(this);
                var modal = $this.closest('.extend-ad-price');
                if ($this.val() == 'product') {
                    modal.find('.ads-link-to-product').addClass('visible').removeClass('hidden').css(
                        'display', 'none').fadeIn();
                } else {
                    modal.find('.ads-link-to-product').addClass('hidden').css('display', 'none')
                        .removeClass('visible').fadeOut();

                }
            });

        });
        $('.ads-link-to').change(function() {
            var $this = $(this);
            var value = $this.val();
            var modal = $this.closest('.extend-ad-price');
            var link_to_product = modal.find('.ads-link-to-product');
            var link_to_store = modal.find('.ads-link-to-store');
            if (value == 'product') {
                link_to_product.addClass('visible').removeClass('hidden').css('display', 'none').fadeIn();
                link_to_store.addClass('visible').addClass('hidden').css('display', 'none').removeClass('visible')
                    .fadeOut();
            } else {
                link_to_product.addClass('visible').addClass('hidden').css('display', 'none').removeClass('visible')
                    .fadeOut();
                link_to_store.addClass('visible').removeClass('hidden').css('display', 'none').fadeIn();
            }
        });
        $(document).ready(function() {
            var $positions = $('.ads-position');
            $positions.each(function() {
                var $this = $(this);
                var price = $this.val();
                var modal = $this.closest('.extend-ad-price');
                modal.find('.ads-position-price').html(price + 'تومان');
            });
        });


        $(document).ready(function() {
            var $positions = $('.ads-position');
            $positions.each(function() {
                var $this = $(this);
                var price = $this.val();
                var modal = $this.closest('.extend-ad-price');
                var walletStock = modal.find('.current-ad-wallet-stock-is-enough-or-not');
                var is_enough = walletStock.data('current-ad-wallet-stock') > price - discount_price;
                if (is_enough) {
                    walletStock.html('<b class="text-success">موجودی کیف پول کافی</b>');
                    modal.find('.current-ad-submit-btn').removeAttr('disabled');
                } else {
                    walletStock.html('<b class="text-danger">موجودی کیف پول نا کافی</b>');
                    modal.find('.current-ad-submit-btn').attr('disabled', 'disabled');
                }
            });
        });
        $('#payment_type').change(function() {
            var value = $(this).val();
            if (value == 'online') {
                $('.wallet-stock-is-enough-or-not').css('display', 'none');
            } else {
                $('.wallet-stock-is-enough-or-not').css('display', 'block');
            }
        });

        $('#editAdForm select[name="position"]').change(function() {

        });
    </script>
@endsection
