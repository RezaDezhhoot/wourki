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
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن</b></h4><br>
                            <form role="form" action="{{ route('showListOfPlanSubscription') }}" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="plan">انتخاب پلن</label>
                                            <select name="plan" id="plan" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب همه::</option>
                                                @foreach($plans as $plan)
                                                    <option {{ request()->input('plan') == $plan->id ? 'selected' : '' }} value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dateRangePicker">انتخاب تاریخ</label>
                                            <input type="text" name="date_range"
                                                   value="{{ request()->input('date_range') }}" id="dateRangePicker"
                                                   class="form-control input-sm"
                                                   placeholder="رنج تاریخ را انتخاب کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="store">انتخاب فروشگاه</label>
                                            <select name="store" id="store" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب همه::</option>
                                                @foreach($stores as $store)
                                                    <option {{ request()->input('store') == $store->id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ request()->input('start_date_ts') }}"
                                       name="start_date_ts"
                                       id="start_date_ts">
                                <input type="hidden" value="{{ request()->input('end_date_ts') }}" name="end_date_ts"
                                       id="end_date_ts">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="send" type="submit"
                                                class="btn input-sm btn-purple waves-effect waves-light">اعمال
                                        </button>
                                        <a href="{{ url()->current() }}"
                                           class="btn input-sm btn-default waves-effect waves-light">حذف فیلترها</a>
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
                                    <h4 class="m-t-0 header-title"><b>لیست اسناد خرید پلن ها</b></h4>
                                    <p class="text-muted font-13"></p>
                                    @if(count($planBills) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="{{ route('submitDocumentOfPlan') }}"
                                                  method="post">
                                                {{ csrf_field() }}
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="check-all" title="انتخاب همه">
                                                        </th>
                                                        <th>ردیف</th>
                                                        <th>نام پلن</th>
                                                        <th>نام فروشگاه</th>
                                                        <th>نام کاربر</th>
                                                        <th>تلفن همراه</th>
                                                        <th>تاریخ اعتبار</th>
                                                        <th>نحوه پرداخت</th>
                                                        <th>کد پرداخت</th>
                                                        <th>تاریخ خرید</th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($planBills as $planBill)
                                                        <tr>
                                                            <th><input type="checkbox" class="check-item"
                                                                       name="planId[]" value="{{ $planBill->id }}"></th>
                                                            <th>{{ $id }}</th>
                                                            <th>{{ $planBill->plan_name }}</th>
                                                            <th>{{ $planBill->name }}</th>
                                                            <th>
                                                                {{ $planBill->first_name }} {{ $planBill->last_name }}
                                                            </th>
                                                            <th>{{ $planBill->mobile }}</th>
                                                            <th>
                                                                از {{ \Morilog\Jalali\Jalalian::forge($planBill->from_date)->format('Y/m/d') }}
                                                                تا {{ \Morilog\Jalali\Jalalian::forge($planBill->to_date)->format('Y/m/d') }} </th>
                                                            <th>
                                                                @if($planBill->pay_id)
                                                                    نقدی
                                                                @else
                                                                    @if($planBill->bazar_in_app_purchase == 1)
                                                                        پرداخت درون برنامه ای کافه بازار
                                                                    @else
                                                                        کیف پول
                                                                    @endif
                                                                @endif
                                                            </th>
                                                            <th>{{ $planBill->pay_id }}</th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($planBill->created_at)->format('%d %B %Y H:i') }}</th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $planBills->links() }}
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <button type="submit"
                                                                class="btn input-sm btn-purple waves-effect waves-light">
                                                            ثبت اسناد
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            سندی یافت نشد!
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

            $('#check-all').change(function () {
                var check = $(this).is(':checked');
                if (check == true)
                    $('.check-item').prop('checked', true);
                else
                    $('.check-item').prop('checked', false);
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
            $('#send').click(function (e) {
                if ($('#plan').val() == null && $('#dateRangePicker').val() == '' && $('#store').val() == null) {
                    e.preventDefault();
                    swal("خطا", "فیلتری انتخاب نشده است.", "error")
                }
            });
        });
    </script>
@endsection