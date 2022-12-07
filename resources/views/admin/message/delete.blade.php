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

        .dropdown-menu li a {
            border-radius: 0;
        }

        .list-unstyled li, textarea {
            font-size: 12px !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ route('admin.messages.batchDelete') }}" method="post">
                        {{ csrf_field() }}
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="dateRangePicker">تاریخ ارسال پیام</label>
                                            <input type="text" name="daterangepicker" id="dateRangePicker"
                                                   class="form-control">
                                            @if($errors->has('start_date') || $errors->has('end_date'))
                                                <b class="text-danger">لطفا تاریخ را به درستی وارد نمایید</b>
                                            @endif
                                        </div>
                                        <input type="hidden" name="start_date" id="start_date">
                                        <input type="hidden" name="end_date" id="end_date">

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="message">قسمتی از متن پیام ارسال شده</label>
                                            <input type="text" name="message" id="message" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-pinterest">حذف پیام های ادمین</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                    $('#start_date').val(picker.startDate.format('YYYY/MM/DD'));
                    $('#end_date').val(picker.endDate.format('YYYY/MM/DD'));
                } else {
                    $(this).val('')
                }
            });
</script>
@endsection