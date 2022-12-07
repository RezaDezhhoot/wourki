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
            text-align: right;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
            font-size: 12px;
            text-align: right;
            color: #888 !important;
            text-align: right;
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
                                @include('frontend.errors')
                            </div>

                            <h4 class="m-t-0 header-title"><b>فیلتر کردن</b></h4><br>
                            <form role="form" id="document_type_form" action="{{ url()->current() }}" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="document_type">انتخاب اسناد</label>
                                            <select name="document_type" id="document_type"
                                                    class="js-example-basic-single">
                                                <option selected disabled>::انتخاب کنید::</option>
                                                <option value="plan" {{ request()->input('document_type') && request()->input('document_type') == 'plan' ? 'selected' : '' }}>
                                                    اسناد مربوط به پلن ها
                                                </option>
                                                <option value="checkout" {{ request()->input('document_type') && request()->input('document_type') == 'checkout' ? 'selected' : '' }}>
                                                    اسناد مربوط به تسوبه حساب ها
                                                </option>
                                                <option value="bill" {{ request()->input('document_type') && request()->input('document_type') == 'bill' ? 'selected' : '' }}>
                                                    اسناد مربوط به فروشگاه ها
                                                </option>
                                                <option value="wallet" {{ request()->input('document_type') && request()->input('document_type') == 'wallet' ? 'selected' : '' }}>
                                                    اسناد مربوط به کیف پول
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                @if(request()->input('document_type') == 'checkout')
                                    <div class="checkout-doc-table">
                                        <div class="row">
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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateRangePicker">تاریخ تسویه</label>
                                                    <input type="text" autocomplete="off" name="checkout_date_range"
                                                           id="dateRangePicker1"
                                                           value="{{ request()->input('checkout_date_range') }}"
                                                           class="form-control input-sm"
                                                           placeholder="رنج تاریخ را انتخاب کنید...">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateRangePicker">تاریخ ثبت سند</label>
                                                    <input autocomplete="off" type="text" name="doc_date_range"
                                                           id="dateRangePicker"
                                                           value="{{ request()->input('doc_date_range') }}"
                                                           class="form-control input-sm"
                                                           placeholder="رنج تاریخ را انتخاب کنید...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif (request()->input('document_type') == 'bill' || request()->input('document_type') == 'marketer')
                                    <div class="bill-doc-table">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    @if(request()->input('document_type') == 'bill')
                                                        <label for="dateRangePicker">تاریخ خرید</label>
                                                    @else
                                                        <label for="dateRangePicker">تاریخ تسویه</label>
                                                    @endif
                                                    <input type="text" autocomplete="off" name="checkout_date_range"
                                                           id="dateRangePicker1"
                                                           value="{{ request()->input('checkout_date_range') }}"
                                                           class="form-control input-sm"
                                                           placeholder="رنج تاریخ را انتخاب کنید...">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateRangePicker">تاریخ ثبت سند</label>
                                                    <input type="text" autocomplete="off" name="doc_date_range"
                                                           id="dateRangePicker"
                                                           value="{{ request()->input('doc_date_range') }}"
                                                           class="form-control input-sm"
                                                           placeholder="رنج تاریخ را انتخاب کنید...">
                                                </div>
                                            </div>
                                            @if(request()->input('document_type') == 'bill')
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pay_type">نوع پرداختی</label>
                                                    <select name="pay_type" id="pay_type"
                                                            class="js-example-basic-single">
                                                        <option value="all" disabled selected>::انتخاب کنید::</option>
                                                        <option {{ request()->input('pay_type') == 'online' ? 'selected' : '' }} value="online">
                                                            آنلاین
                                                        </option>
                                                        <option {{ request()->input('pay_type') == 'postal' ? 'selected' : '' }} value="postal">
                                                            پستی
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @if(request()->input('document_type') == 'bill')
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="bill_store">انتخاب فروشگاه</label>
                                                    <select name="bill_store" id="bill_store" class="select2-store">
                                                        <option value="all" disabled selected>::انتخاب کنید::</option>
                                                        @foreach($stores as $store)
                                                            <option {{ request()->input('bill_store') == $store->id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}
                                                                @if($store->marked_as_submitted_accounting_documents)- تسویه حساب نشده@endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('export_excel_file.credit_stores') }}" style="display: inline-block;margin-top: 24px;" class="btn btn-white">دانلود فایل اکسل فروشگاه های طلبکار</a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @elseif (request()->input('document_type') == 'plan' || request()->input('document_type') == 'wallet')
                                    <div class="plan-doc-table">
                                        <div class="row">
                                            @if(request()->input('document_type') == 'plan')
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="plan">انتخاب پلن</label>
                                                        <select name="plan" id="plan" class="js-example-basic-single">
                                                            <option value="all" disabled selected>::انتخاب کنید::</option>
                                                            @foreach($plans as $plan)
                                                                <option {{ request()->input('plan') == $plan->id ? 'selected' : '' }} value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="user">انتخاب کاربر</label>
                                                        <select name="user" id="user" class="js-example-basic-single">
                                                            <option value="all" disabled selected>::انتخاب کنید::</option>
                                                            @foreach($users as $user)
                                                                <option {{ request()->input('user') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->first_name .' '.$user->last_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    @if(request()->input('document_type') == 'plan')
                                                        <label for="dateRangePicker">تاریخ خرید</label>
                                                    @else
                                                        <label for="dateRangePicker">تاریخ شارژ کیف پول</label>
                                                    @endif
                                                        <input type="text" name="checkout_date_range" id="dateRangePicker1"
                                                           value="{{ request()->input('checkout_date_range') }}"
                                                           class="form-control input-sm"
                                                           placeholder="رنج تاریخ را انتخاب کنید...">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateRangePicker">تاریخ ثبت سند</label>
                                                    <input type="text" name="doc_date_range" id="dateRangePicker"
                                                           value="{{ request()->input('doc_date_range') }}"
                                                           class="form-control input-sm"
                                                           placeholder="رنج تاریخ را انتخاب کنید...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                                <input type="hidden" value="{{ request()->input('start_date_doc') }}"
                                       name="start_date_doc" id="start_date_doc">
                                <input type="hidden" value="{{ request()->input('end_date_doc') }}" name="end_date_doc"
                                       id="end_date_doc">
                                <input type="hidden" value="{{ request()->input('start_date_checkout') }}"
                                       name="start_date_checkout" id="start_date_checkout">
                                <input type="hidden" value="{{ request()->input('end_date_checkout') }}"
                                       name="end_date_checkout" id="end_date_checkout">
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
                                    @if(request()->input('document_type') == 'checkout')
                                        <h4 class="m-t-0 header-title"><b> لیست اسناد حسابداری <span
                                            class="text-success">{{ request()->input('document_type') && request()->input('document_type') == 'checkout' ? 'مربوط به تسوبه حساب ها' : '' }}</span></b>
                                        </h4>
                                        <p class="text-muted font-13"></p>
                                        <div class="checkout-doc-table">
                                            @if(count($lists) > 0)
                                                <div class="p-10">
                                                    <table class="table table-striped m-0">
                                                        <thead>
                                                        <tr>
                                                            <th>شماره سند</th>
                                                            <th>نام خریدار</th>
                                                            <th>نام فروشگاه</th>
                                                            <th>توضیحات</th>
                                                            <th style="width: 100px;">بدهکار</th>
                                                            <th>بستانکار</th>
                                                            <th style="width: 100px;">تاریخ تسویه</th>
                                                            <th>تاریخ ثبت سند</th>
                                                            <th>شماره پیگیری</th>
                                                        </tr>
                                                        </thead>
                                                        <?php $id = 1; ?>
                                                        <tbody id="sortable-list">
                                                        @foreach($lists as $document)
                                                            <tr>
                                                                <th>{{ $id }}</th>
                                                                <th>{{ $document->first_name }} {{ $document->last_name }}</th>
                                                                <th>{{ $document->store_name }}</th>
                                                                <th>{{ $document->description }}</th>
                                                                <th>
                                                                    <a class="btn btn-xs btn-default">{{ number_format($document->balance) }}</a>
                                                                    تومان
                                                                </th>
                                                                <th>0</th>
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($document->checkouts_created_at)->format('%d %B %Y') }}</th>
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($document->accounting_documents_created_at)->format('%d %B %Y') }}</th>
                                                                <th>{{ $document->pay_id }}</th>
                                                            </tr>
                                                            <?php $id++; ?>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    {{ $lists->links() }}
                                                </div>
                                            @else
                                                <div class="alert alert-danger text-center">
                                                    سندی یافت نشد!
                                                </div>
                                            @endif
                                        </div>
                                    @elseif (request()->input('document_type') == 'bill' || request()->input('document_type') == 'marketer')
                                        @if(request()->input('document_type') == 'bill')
                                            <h4 class="m-t-0 header-title"><b> لیست اسناد حسابداری فروشگاه <span class="text-danger">{{ request()->storeName ? $storeName : '' }}</span></b></h4>
                                        @else
                                            <h4 class="m-t-0 header-title"><b> لیست اسناد بازاریاب </b></h4>
                                        @endif
                                            <p class="text-muted font-13"></p>
                                        <div class="bill-doc-table">
                                            @if(count($lists) > 0)
                                                <div class="p-10">
                                                    <table class="table table-striped m-0">
                                                        <thead>
                                                        <tr>
                                                            <th>شماره سند</th>
                                                            <th>شرح</th>
                                                            <th style="width: 110px;">بدهکار</th>
                                                            <th style="width: 110px;">بستانکار</th>
                                                            <th style="width: 110px;">نوع</th>
                                                            <th>کد خرید</th>
                                                            <th>مبلغ تسویه حساب</th>
                                                            <th>تاریخ ثبت سند</th>
                                                        </tr>
                                                        </thead>
                                                        <?php $id = 1; ?>
                                                        <tbody id="sortable-list">
                                                        @foreach($lists as $document)
                                                            <tr>
                                                                <th>{{ $id }}</th>
                                                                <th>{{ $document->description }}</th>
                                                                @if($document->type == 'bill')
                                                                    <th style="background-color: #dff0d8;font-weight: bold;">{{ number_format($document->balance) }}
                                                                        تومان
                                                                    </th>
                                                                @else
                                                                    <th style="text-align: center;">-</th>
                                                                @endif

                                                                @if($document->type == 'checkout' || $document->type == 'marketer')
                                                                    <th style="background-color: #f2dede;font-weight: bold;">{{ number_format($document->balance) }}
                                                                        تومان
                                                                    </th>
                                                                @else
                                                                    <th style="text-align: center;">-</th>
                                                                @endif

                                                                @if($document->type == 'bill')
                                                                    <th>خرید صورتحساب</th>
                                                                @else
                                                                    <th>تسویه فروشگاه</th>
                                                                @endif

                                                                @if(($document->type == 'bill' && $document->type == 'online') || $document->type == 'marketer')
                                                                    <th style="background-color: #dff0d8;font-weight: bold;">{{ $document->pay_id }}</th>
                                                                @else
                                                                    <th style="text-align: center;">پستی</th>
                                                                @endif

                                                                @if($document->type == 'checkout' || $document->type == 'marketer')
                                                                    <th>{{ number_format($document->balance) }}
                                                                        تومان
                                                                    </th>
                                                                @else
                                                                    <th style="text-align: center;">-</th>
                                                                @endif
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($document->created_at)->format('Y/m/d') }}</th>
                                                            </tr>
                                                            <?php $id++; ?>
                                                        @endforeach
                                                        </tbody>
                                                        @if(request()->input('document_type') == 'bill')
                                                            <table class="table table-striped m-0">
                                                                <tbody id="sortable-list">
                                                                <tr>
                                                                    <th></th>
                                                                    <th></th>
                                                                <th style="font-weight: bold;text-align: left;">
                                                                    باقیمانده :
                                                                </th>
                                                                <th style="font-weight: bold;background-color: rgba(189,243,255,0.93);">{{ number_format($totalBalance) }}</th>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        @endif
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-danger text-center">
                                                    سندی یافت نشد!
                                                </div>
                                            @endif
                                        </div>
                                    @elseif (request()->input('document_type') == 'plan' || request()->input('document_type') == 'wallet')
                                        <h4 class="m-t-0 header-title"><b> لیست اسناد حسابداری <span
                                            class="text-success">{{ request()->input('document_type') && request()->input('document_type') == 'plan' ? 'مربوط به خرید پلن ها' : '' }}</span></b>
                                        </h4>
                                        <p class="text-muted font-13"></p>
                                        <div class="plan-doc-table">
                                            @if(count($lists) > 0)
                                                <div class="p-10">
                                                    <table class="table table-striped m-0">
                                                        <thead>
                                                        <tr>
                                                            <th>شماره سند</th>
                                                            @if(request()->input('document_type') == 'plan')
                                                                <th>نام خریدار</th>
                                                                <th>نام فروشگاه</th>
                                                                <th>نام پلن</th>
                                                            @else
                                                                <th>نام کاربر</th>
                                                                <th>مبلغ شارژ</th>
                                                            @endif
                                                            <th>توضیحات</th>
                                                            <th>بدهکار</th>
                                                            <th style="width: 100px;">بستانکار</th>
                                                            <th style="width: 100px;">تاریخ خرید</th>
                                                            <th>تاریخ ثبت سند</th>
                                                            <th>شماره پیگیری</th>
                                                        </tr>
                                                        </thead>
                                                        <?php $id = 1; ?>
                                                        <tbody id="sortable-list">
                                                        @foreach($lists as $document)
                                                            <tr>
                                                                <th>{{ $id }}</th>
                                                                <th>{{ $document->first_name }} {{ $document->last_name }}</th>
                                                                @if(request()->input('document_type') == 'plan')
                                                                    <th>{{ $document->store_name }}</th>
                                                                    <th>{{ $document->planName }} {{ $document->planMonth }}
                                                                        ماهه
                                                                    </th>
                                                                @else
                                                                    <th>{{ $document->planPrice }}</th>
                                                                @endif
                                                                <th>{{ $document->description }}</th>
                                                                <th>0</th>
                                                                <th>
                                                                    <a class="btn btn-xs btn-default">{{ number_format($document->planPrice) }}</a>
                                                                    تومان
                                                                </th>
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($document->plan_created_at)->format('%d %B %Y') }}</th>
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($document->accounting_documents_created_at)->format('%d %B %Y') }}</th>
                                                                <th>{{ $document->pay_id }}</th>
                                                            </tr>
                                                            <?php $id++; ?>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    {{ $lists->links() }}
                                                </div>
                                            @else
                                                <div class="alert alert-danger text-center">
                                                    سندی یافت نشد!
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <h4 class="m-t-0 header-title"><b> لیست اسناد حسابداری </b></h4>
                                        <p class="text-muted font-13"></p>
                                        <div class="checkout-doc-table">
                                            @if(count($lists) > 0)
                                                <div class="p-10">
                                                    <table class="table table-striped m-0">
                                                        <thead>
                                                        <tr>
                                                            <th>شماره سند</th>
                                                            <th>شرح</th>
                                                            <th>بستانکار</th>
                                                            <th>بدهکار</th>
                                                            <th>نوع</th>
                                                            <th>مبلغ تسویه حساب</th>
                                                            <th>تاریخ ثبت</th>
                                                        </tr>
                                                        </thead>
                                                        <?php $id = 1; ?>
                                                        <tbody id="sortable-list">
                                                        @foreach($lists as $document)
                                                            <tr>
                                                                <th>{{ $id }}</th>
                                                                <th>{{ $document->description }}</th>

                                                                @if($document->type == 'bill' || $document->type == 'marketer')
                                                                    <th style="background-color: #dff0d8;font-weight: bold;">{{ number_format($document->balance) }}
                                                                        تومان
                                                                    </th>
                                                                @else
                                                                    <th style="text-align: center;">-</th>
                                                                @endif

                                                                @if($document->type == 'checkout' || $document->type == 'wallet' || $document->type == 'plan')
                                                                    <th style="background-color: #f2dede;font-weight: bold;">{{ number_format($document->balance) }}
                                                                        تومان
                                                                    </th>
                                                                @else
                                                                    <th style="text-align: center;">-</th>
                                                                @endif
                                                                @if($document->type == 'bill')
                                                                    <th>خرید صورتحساب</th>
                                                                @elseif($document->type == 'plan')
                                                                    <th>خرید پلن</th>
                                                                @elseif($document->type == 'checkout')
                                                                    <th>تسویه صورتحساب فروشگاه</th>
                                                                @else
                                                                    <th>کیف پول</th>
                                                                @endif

                                                                @if($document->type == 'checkout')
                                                                    <th>{{ number_format($document->balance) }}تومان
                                                                    </th>
                                                                @else
                                                                    <th style="text-align: center;">-</th>
                                                                @endif
                                                                <th>{{ \Morilog\Jalali\Jalalian::forge($document->created_at)->format('Y/m/d') }}</th>
                                                            </tr>
                                                            <?php $id++; ?>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    {{ $lists->links() }}
                                                </div>
                                            @else
                                                <div class="alert alert-danger text-center">
                                                    سندی یافت نشد!
                                                </div>
                                            @endif
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
                    $('#start_date_doc').val(picker.startDate.format('X'));
                    $('#end_date_doc').val(picker.endDate.format('X'));
                } else {
                    $(this).val('')
                }
            });

            var $dateRanger1 = $("#dateRangePicker1");

            $dateRanger1.daterangepicker({
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
                    $('#start_date_checkout').val(picker.startDate.format('X'));
                    $('#end_date_checkout').val(picker.endDate.format('X'));
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

        $(document).ready(function () {

            $('#document_type').change(function () {
                $('#document_type_form').submit();
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

        });

        $('.select2-store').select2();
    </script>
@endsection