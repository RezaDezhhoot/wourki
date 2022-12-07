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
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن فاکتور خرید محصولات</b></h4><br>
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
                                            <label for="province_buyer">استان خریدار</label>
                                            <select name="province_buyer" id="province_buyer"
                                                    class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($provinces as $province)
                                                    <option {{ request()->input('province_buyer') == $province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="city_buyer">شهر خریدار</label>
                                            <select name="city_buyer" id="city_buyer" class="js-example-basic-single">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dateRangePicker">انتخاب تاریخ</label>
                                            <input type="text" name="date_range" id="dateRangePicker"
                                                   value="{{ request()->input('date_range') }}"
                                                   class="form-control input-sm"
                                                   placeholder="رنج تاریخ را انتخاب کنید...">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="province_seller">استان فروشنده</label>
                                            <select name="province_seller" id="province_seller"
                                                    class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($provinces as $province)
                                                    <option {{ request()->input('province_seller') == $province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="city_seller">شهر فروشنده</label>
                                            <select name="city_seller" id="city_seller" class="js-example-basic-single">

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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store">نام فروشگاه</label>
                                            <select name="store" id="store" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($stores as $store)
                                                    <option {{ request()->input('store') == $store->id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product">نام محصول</label>
                                            <select name="product" id="product" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($products as $product)
                                                    <option {{ request()->input('product') == $product->id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="price_from">قیمت از</label>
                                            <input type="number" value="{{ request()->input('price_from') }}"
                                                   name="price_from" id="price_from" class="form-control input-sm"
                                                   placeholder="قیمت ابتدایی را وارد کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="price_to">قیمت تا</label>
                                            <input type="number" value="{{ request()->input('price_to') }}"
                                                   name="price_to" id="price_to" class="form-control input-sm"
                                                   placeholder="قیمت پایانی را وارد کنید...">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="send"
                                                class="btn input-sm btn-purple waves-effect waves-light">اعمال
                                        </button>
                                        <a href="{{ url()->current() }}"
                                           class="btn input-sm btn-default waves-effect waves-light">حذف فیلترها</a>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ request()->input('start_date_ts') }}"
                                       name="start_date_ts" id="start_date_ts">
                                <input type="hidden" value="{{ request()->input('end_date_ts') }}" name="end_date_ts"
                                       id="end_date_ts">
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
                                    <h4 class="m-t-0 header-title"><b>لیست سفارشات فروشگاه ها</b></h4>
                                    <p class="text-muted font-13"></p>
                                    @if(count($billsInfo) > 0)
                                        <div class="p-10">
                                            <form action="{{ route('bills.adminConfirmBill') }}" method="post">
                                                {{ csrf_field() }}
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام خریدار</th>
                                                        <th>نام فروشگاه</th>
                                                        <th>صنف</th>
                                                        <th>جمع کل خرید</th>
                                                        <th>نوع خرید</th>
                                                        <th>کد پیگیری خرید</th>
                                                        <th>تعداد اقلام</th>
                                                        <th>وضعیت خرید</th>
                                                        <th>استان و شهر</th>
                                                        <th>تاریخ و ساعت</th>
                                                        <th>
                                                            <span style="font-size: 20px;margin-right: 20%;"
                                                                  class="glyphicon glyphicon-eye-open"></span>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($billsInfo as $bill)
                                                        <tr>
                                                            <th>{{ $bill->id }}</th>
                                                            <th>{{ $bill->full_name}}</th>
                                                            <th>{{ $bill->store_name }}</th>
                                                            <th>{{ $bill->guild_name }}</th>
                                                            <th>{{ number_format($bill->billItemPrice) }} تومان</th>
                                                            <th>
                                                                @if($bill->pay_type == 'online')آنلاین
                                                                @elseif($bill->pay_type == 'postal')پستی
                                                                @else کیف پول
                                                                @endif
                                                            </th>
                                                            <th class="text-center">{{ $bill->pay_id == null ? '---' : $bill->pay_id }}</th>
                                                            <th>{{ count($bill->billItems) }}</th>
                                                            <th>
                                                                <div class="btn-group m-b-20">
                                                                    <div class="btn-group">
                                                                        @if($bill->status == 'delivered')
                                                                            <a class="btn btn-xs btn-success">تحویل داده
                                                                                شده</a>
                                                                        @elseif($bill->status == 'adminReject' || $bill->status == 'rejected')
                                                                            <a class="btn btn-xs btn-danger">رد شده</a>
                                                                        @elseif($bill->status == 'pending')
                                                                            <a class="btn btn-xs btn-default"> درانتظار
                                                                                تایید</a>
                                                                        @elseif($bill->status == 'paid_back')
                                                                            <a class="btn btn-xs btn-info">بازگشت
                                                                                مبلغ</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th>{{ $bill->province_name }} - {{ $bill->city_name }}</th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($bill->created_at)->format('%d %B %Y h:i') }}</th>
                                                            <th><a href="{{ route('billItem.show' , $bill->id) }}">مشاهده
                                                                    فاکتور</a></th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $billsInfo->links() }}
                                                <br>
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            سفارشی یافت نشد!
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
@endsection
@section('scripts')
    <script src="{{ url()->to('/admin/assets/js/moment.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/moment-jalaali.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/daterangepicker-fa-ex.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
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
                showDropdowns: true
            }).on('apply.daterangepicker', function (ev, picker) {
                night = picker.endDate.diff(picker.startDate, 'days');
                if (night > 0) {
                    $(this).val(picker.startDate.format(dateFormat) + ' - ' + picker.endDate.format(dateFormat));
                    $('#start_date_ts').val(picker.startDate.format('X'));
                    $('#end_date_ts').val(picker.endDate.format('X'));
                } else {
                    $(this).val('')
                }
            });


            $('.ga-datepicker').daterangepicker({
                clearLabel: 'Clear',
                // autoUpdateInput: !!(dateFrom && dateTo),
                //minDate: moment(),
                autoApply: true,
                opens: 'right',
                singleDatePicker: true,
                showDropdowns: true,
                language: 'en'
            }).on('apply.daterangepicker', function () {
                $('.tooltip').hide();
                $('.date-select').text($(this).val());
            });

            $('.jalali-datepicker').daterangepicker({
                clearLabel: 'Clear',
                autoApply: true,
                opens: 'left',
                singleDatePicker: true,
                showDropdowns: true,
                jalaali: true,
                language: 'fa'
            }).on('apply.daterangepicker', function () {
                $('.tooltip').hide();
                $('.date-select').text($(this).val());
            });

            $(document).on('mouseover', '.daterangepicker .calendar td', function () {
                var gagDate = $(this).attr('data-original-title');
                $('.date-hover').text('');
                $('.date-hover').text(gagDate);

                $('[data-toggle="tooltip"]').tooltip()
            });
        });

        $('#check-all').change(function () {
            var check = $(this).is(':checked');
            if (check == true)
                $('.bill-item').prop('checked', true);
            else
                $('.bill-item').prop('checked', false);
        });

        function getCity_buyer() {
            var province = $('#province_buyer').val();
            var city = $('#city_buyer');

            $.ajax({
                type: 'get',
                url: '{{ url()->to('/admin/province/') }}/' + province + '/city/ajax',
                data: {},

                success: function (response) {
                    var list = response;
                    city.html('<option disabled selected>::انتخاب کنید::</option>');
                    for (var i = 0; i < list.length; i++) {
                        city.append('<option value="' + list[i].id + '">' + list[i].name + '</option>');
                    }
                    @if(request()->has('city_buyer'))
                    city.val({{ request()->input('city_buyer') }});
                    @endif
                }
            });
        }

        function getCity_seller() {
            var province = $('#province_seller').val();
            var city = $('#city_seller');

            $.ajax({
                type: 'get',
                url: '{{ url()->to('/admin/province/') }}/' + province + '/city/ajax',
                data: {},

                success: function (response) {
                    var list = response;
                    city.html('<option selected disabled>::انتخاب کنید::</option>');
                    for (var i = 0; i < list.length; i++) {
                        city.append('<option value="' + list[i].id + '">' + list[i].name + '</option>')
                    }
                    @if(request()->has('city_seller'))
                    city.val({{ request()->input('city_seller') }});
                    @endif
                }
            });
        }

        $('#province_buyer').change(function () {
            getCity_buyer();
        });
        @if(request()->has('city_buyer'))
        getCity_buyer();
        @endif

        $('#province_seller').change(function () {
            getCity_seller();
        });
        @if(request()->has('city_seller'))
        getCity_seller();
        @endif

        $('.delivered-bill-status').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var status = $this.val();
            $.ajax({
                type: 'get',
                url: '{{ url()->to('/admin/store-lists/') }}/' + status + '/approved-store-status/ajax',
                data: {
                    id: 4,
                },

                success: function () {
                    $this.closest('th').find('[data-status-button]').removeClass().addClass('btn btn-xs btn-success dropdown-toggle waves-effect').html(' تحویل داده شده <span class="caret"></span>');
                }
            });
        });
        $('.reject-bill-status').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var status = $this.val();
            $.ajax({
                type: 'get',
                url: '{{ url()->to('/admin/store-lists/') }}/' + status + '/reject-store-status/ajax',
                data: {
                    id: 4,
                },

                success: function () {
                    $this.closest('th').find('[data-status-button]').removeClass().removeClass().addClass('btn btn-xs btn-danger dropdown-toggle waves-effect').html(' رد شده <span class="caret"></span>');
                }
            });
        });
        $('.pending-bill-status').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var status = $this.val();
            $.ajax({
                type: 'get',
                url: '{{ url()->to('/admin/store-lists/') }}/' + status + '/pending-store-status/ajax',
                data: {
                    id: 4,
                },

                success: function () {
                    $this.closest('th').find('[data-status-button]').removeClass().removeClass().addClass('btn btn-xs btn-info dropdown-toggle waves-effect').html(' درانتظارتایید <span class="caret"></span>');
                }
            });
        });
        $('.paid-back-bill-status').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var status = $this.val();
            $.ajax({
                type: 'get',
                url: '{{ route('makePaidBackBillStatus') }}',
                data: {
                    id: status,
                },

                success: function () {
                    $this.closest('th').find('[data-status-button]').removeClass().removeClass().addClass('btn btn-xs btn-default dropdown-toggle waves-effect').html('پرداخت شده <span class="caret"></span></button>')
                }
            });
        });

        $('#send').click(function (e) {
            if ($('#province_buyer').val() == null && $('#city_buyer').val() == null && $('#guild').val() == null && $('#status').val() == null
                && $('#province_seller').val() == null && $('#city_seller').val() == null && $('#dateRangePicker').val() == '' && $('#pay_type').val() == null
                && $('#store').val() == null && $('#product').val() == null && $('#price_from').val() == '' && $('#price_to').val() == '') {
                e.preventDefault();
                swal("خطا", "فیلتری انتخاب نشده است.", "error")
            }
        });

    </script>
@endsection
