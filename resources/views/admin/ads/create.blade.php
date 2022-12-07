@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/datepicker-theme.css') }}">
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
            height: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
            font-size: 12px;
            text-align: right;
            color: #888 !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>ویرایش آگهی</h4>
                                    <p class="text-danger">فیلد های ستاره دار الزامی هستند.</p>
                                    <form action="{{ route('admin.ads.store') }}"
                                          method="post"
                                          enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="position">جایگاه:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="position" id="position" class="form-control">
                                                @foreach($positions as $p)
                                                    <option data-price="{{ $p->price }}"
                                                            value="{{ $p->id }}">{{ $p->name }} - ({{ $p->price }})
                                                        تومان
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('position'))
                                                <b class="text-danger">{{ $errors->first('position') }}</b>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="pic">تصویر:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" name="pic" id="pic" class="form-control">
                                            @if($errors->has('pic'))
                                                <b class="text-danger">{{ $errors->first('pic') }}</b>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="user_name_or_mobile">نام کاربر / شماره مویایل:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="user" id="user_name_or_mobile"></select>
                                            @if($errors->has('user'))
                                                <b class="text-danger">{{ $errors->first('user') }}</b>
                                            @endif
                                        </div>
                                        <div class="form-group" id="link_to_container">
                                            <label for="link_to">لینک شود به :
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="link_to" id="link_to" class="form-control">
                                                <option value="store">فروشگاه</option>
                                                <option value="product">محصول</option>
                                            </select>
                                            @if($errors->has('link_to'))
                                                <b class="text-danger">{{ $errors->first('link_to') }}</b>
                                            @endif
                                        </div>
                                        <div class="form-group" id="product_name_container">
                                            <label for="product_name">نام محصول:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="product_id" id="product_name" class="form-control">

                                            </select>
                                            @if($errors->has('product_id'))
                                                <b class="text-danger">{{ $errors->first('product_id') }}</b>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="product_price">قیمت:</label>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <input type="number" name="price" id="product_price"
                                                               class="form-control">
                                                        @if($errors->has('price'))
                                                            <b class="text-danger">{{ $errors->first('price') }}</b>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="default_price" style="margin-top: 8px;">
                                                            <input type="checkbox" name="default_price"
                                                                   id="default_price">
                                                            قیمت پیش فرض
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pay_from_user_wallet">
                                                <input type="checkbox" name="pay_from_user_wallet"
                                                       id="pay_from_user_wallet">
                                                پرداخت از کیف پول کاربر
                                            </label>
                                            <b class="text-danger wallet-stock-is-enough hidden">موجودی کیف پول کافی
                                                نیست</b>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6">
                                                    <label for="expire_date">تاریخ انقضاء</label>
                                                    <input type="text" name="expire_date" id="expire_date"
                                                           class="form-control">
                                                    <input type="hidden" name="expire_date_ts" id="expire_date_ts">
                                                    @if($errors->has('expire_date'))
                                                        <b class="text-danger">{{ $errors->first('expire_date') }}</b>
                                                    @endif
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <label for="expire_date_based_on_default_setting"
                                                           style="margin-top: 33px;">
                                                        <input type="checkbox"
                                                               name="expire_date_based_on_default_setting"
                                                               id="expire_date_based_on_default_setting">
                                                        براساس تنظیمات پیش فرض
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">وضعیت:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="pending">در انتظار تایید</option>
                                                <option value="approved">تایید شده</option>
                                                <option value="rejected">رد شده</option>
                                            </select>
                                            @if($errors->has('status'))
                                                <b class="text-danger">{{ $errors->first('status') }}</b>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="description">توضیحات:
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="description" id="description" cols="30"
                                                      rows="5" class="form-control"></textarea>
                                            @if($errors->has('description'))
                                                <b class="text-danger">{{ $errors->first('description') }}</b>
                                            @endif
                                        </div>
                                        <button class="btn btn-success" type="submit">ثبت</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection

@section('scripts')
    <script>
        $('#expire_date_based_on_default_setting').change(function () {
            var $this = $(this);
            var expireDate = $('#expire_date');
            if ($this.is(':checked')) {
                expireDate.attr('disabled', 'disabled');
            } else {
                expireDate.removeAttr('disabled');
            }
        });
        $('#expire_date').pDatepicker({
            persianDigit: false,
            format: 'LL',
            altField: '#expire_date_ts',
            altFieldFormatter: function (unixDate) {
                return unixDate;
            }
        });

        $('#user_name_or_mobile').select2({
            placeholder: 'بخشی از نام کاربری یا تلفن همراه',
            ajax: {
                url: '{{ route('users.search_my_mobile_and_name.via_ajax') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.first_name + ' ' + item.last_name + ' - ' + item.mobile + ' - فروشگاه ' + item.store_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: false
            }
        });
        $(document).ready(function () {
            var product = $('#product_name_container');
            var link_to = $('#link_to');
            if (link_to.val() == 'product') {
                product.addClass('visible').removeClass('hidden');
            } else {
                product.addClass('hidden').removeClass('visible');
            }


            var is_enough = $('.wallet-stock-is-enough');
            is_enough.addClass('hidden').removeClass('visible');

            var link_to_container = $('#link_to_container');
            var user = $('#user_name_or_mobile');
            if (user.val() != '') {
                link_to_container.find('select').removeAttr('disabled');
            } else {
                link_to_container.find('select').attr('disabled', 'disabled');
            }
        });

        $('#position').change(function () {
            var position = $(this);
            var user = $('#user_name_or_mobile');
            var is_enough = $('.wallet-stock-is-enough');
            var wallet_checkbox = $('#pay_from_user_wallet');
            if (!user.val()) {
                is_enough.addClass('hidden').removeClass('visible');
            } else {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('users.wallet.stock.ajax') }}',
                    data: {
                        user_id: user.val()
                    },
                    success: function (response) {
                        var positionPrice = position.find('option:selected').data('price');
                        if (response.wallet_stock >= positionPrice) {
                            is_enough.removeClass('text-danger').addClass('text-success').html('موجودی کیف پول کافی').removeClass('hidden').addClass('visible');
                            wallet_checkbox.removeAttr('disabled');
                        } else {
                            is_enough.removeClass('text-success').addClass('text-danger').html('موجودی کیف پول ناکافی').removeClass('hidden').addClass('visible');
                            wallet_checkbox.attr('disabled', 'disabled');
                            wallet_checkbox.prop('checked', false);
                        }
                    }
                });
            }
        });

        $('#user_name_or_mobile').change(function () {
            var $this = $(this);
            var link_to_container = $('#link_to_container');
            var product_container = $('#product_name_container');
            var position = $('#position');
            var is_enough = $('.wallet-stock-is-enough');
            var wallet_checkbox = $('#pay_from_user_wallet');
            if ($this.val() != '') {
                link_to_container.find('select').removeAttr('disabled');
                link_to_container.find('select').val('store');
                product_container.addClass('hidden').removeClass('visible');

                var position_price = position.find('option:selected').data('price');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('users.wallet.stock.ajax') }}',
                    data: {
                        user_id: $this.val()
                    },
                    success: function (response) {
                        var walletStock = response.wallet_stock;
                        if (walletStock >= position_price) {
                            is_enough.removeClass('text-danger').addClass('text-success').html('موجودی کیف پول کافی').removeClass('hidden').addClass('visible');
                            wallet_checkbox.removeAttr('disabled');
                        } else {
                            is_enough.removeClass('text-success').addClass('text-danger').html('موجودی کیف پول ناکافی').removeClass('hidden').addClass('visible');
                            wallet_checkbox.attr('disabled', 'disabled');
                        }
                    }
                });


            } else {
                link_to_container.find('select').attr('disabled', 'disabled');
                product_container.addClass('visible').removeClass('hidden');
                is_enough.addClass('hidden').removeClass('visible');
            }
        });
        $('#default_price').change(function () {
            var $this = $(this);
            var product_price = $('#product_price');
            if ($this.is(':checked')) {
                product_price.attr('disabled', 'disabled');
            } else {
                product_price.removeAttr('disabled');
            }
        });
        $('#link_to').change(function () {
            var $this = $(this);
            var product = $('#product_name_container');
            var user = $('#user_name_or_mobile');
            var productSelect = $('#product_name');
            if ($this.val() == 'product') {
                product.addClass('visible').removeClass('hidden');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('users.products.search.ajax') }}',
                    data: {
                        user_id: user.val()
                    },
                    success: function (response) {
                        var list = response.list;
                        productSelect.html('');
                        for (var i = 0; i < list.length; i++) {
                            productSelect.append('<option value="' + list[i].id + '">' + list[i].name + '</option>');
                        }
                    }
                });

            } else if ($this.val() == 'store') {
                product.addClass('hidden').removeClass('visible');
                productSelect.html('');
            }
        });
    </script>
@endsection