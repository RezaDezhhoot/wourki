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
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن</b></h4><br>
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
                                            <label for="store_name">انتخاب فروشگاه</label>
                                            <select name="store_name" id="store_name" class="js-example-basic-single">
                                                <option value="all" disabled selected>::همه فروشگاه ها::</option>
                                                @foreach($stores as $store)
                                                    <option {{ request()->input('id') == $store->id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_name">انتخاب محصول</label>
                                            <select name="product_name" id="product_name" class="js-example-basic-single">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dateRangePicker">انتخاب تاریخ</label>
                                            <input type="text" name="date_range" id="dateRangePicker" value="{{ request()->input('date_range') }}" class="form-control input-sm" placeholder="رنج تاریخ را انتخاب کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">وضعیت تایید نظر</label>
                                            <select name="status" id="status" class="js-example-basic-single">
                                                <option value="all" disabled selected>::همه::</option>
                                                <option {{ request()->input('status') == 'approved' ? 'selected' : '' }} value="approved">تایید شده</option>
                                                <option {{ request()->input('status') == 'rejected' ? 'selected' : '' }} value="rejected">رد شده</option>
                                                <option {{ request()->input('status') == 'pending' ? 'selected' : '' }} value="pending">در انتظار تایید</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ request()->input('start_date_ts') }}" name="start_date_ts" id="start_date_ts">
                                <input type="hidden" value="{{ request()->input('end_date_ts') }}" name="end_date_ts" id="end_date_ts">
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="send" class="btn btn-purple waves-effect waves-light">اعمال</button>
                                        <a href="{{ url()->current() }}" class="btn btn-default waves-effect waves-light">نمایش اطلاعات</a>
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
                                    <h4 class="m-t-0 header-title"><b>لیست نظرات</b></h4>
                                    <p class="text-muted font-13"></p>
                                    @if(count($comments) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="">
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام کاربر</th>
                                                        <th>نام فروشگاه</th>
                                                        <th>نام محصول / خدمت</th>
                                                        <th>متن نظر</th>
                                                        <th>وضعیت تایید نظر</th>
                                                        <th>تاریخ</th>
                                                        <th>ساعت</th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($comments as $comment)
                                                        <tr>
                                                            <th>{{ $id }}</th>
                                                            <th>{{ $comment->full_name }}</th>
                                                            <th>{{ $comment->store_name }}</th>
                                                            <th>{{ $comment->product_name }}</th>
                                                            <th>{{ $comment->comment_text }}</th>
                                                            <th>
                                                                <div class="btn-group m-b-20">
                                                                    <div class="btn-group">
                                                                        @if($comment->status == 'approved')
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                    data-status-button
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> تایید شده
                                                                                <span
                                                                                        class="caret"></span></button>
                                                                        @elseif($comment->status == 'rejected')
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-warning dropdown-toggle waves-effect"
                                                                                    data-status-button
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> رد شده <span
                                                                                        class="caret"></span></button>
                                                                        @elseif($comment->status == 'pending')
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-info dropdown-toggle waves-effect"
                                                                                    data-status-button
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"> درانتظار
                                                                                تایید
                                                                                <span class="caret"></span></button>
                                                                        @endif
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <button class="btn btn-success btn-block btn-xs"
                                                                                        id="approved-store-status"
                                                                                        value="{{ $comment->comment_id }}">
                                                                                    تاییده شده
                                                                                </button>
                                                                            </li>
                                                                            <li>
                                                                                <button class="btn btn-warning btn-block btn-xs"
                                                                                        id="reject-store-status"
                                                                                        value="{{ $comment->comment_id }}">رد
                                                                                    کردن
                                                                                </button>
                                                                            </li>
                                                                            <li>
                                                                                <button class="btn btn-info btn-block btn-xs"
                                                                                        id="pending-store-status"
                                                                                        value="{{ $comment->comment_id }}">
                                                                                    درانتظارتایید
                                                                                </button>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($comment->created_at)->format('%d %B %Y') }}</th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($comment->created_at)->format('H:i') }}</th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $comments->links() }}
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            تاکنون نظری ثبت نشده است!
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

            $('#store_name').change(function () {
               var $this = $(this).val();
               var $product = $('#product_name');
               $.ajax({
                  type: 'get',
                  url: '{{ route('getListOfProductSellerByAjax') }}',
                  data: {
                    id: $this,
                  },

                   success: function (response) {
                       var $list = response;
                       $product.html('<option disabled selected>::همه محصولات::</option>');
                       for (var i = 0 ; i < $list.length ; i++){
                           $product.append('<option value=" ' + $list[i].id + ' ">' + $list[i].name + '</option>');
                       }
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
                        id: 3,
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
                    type : 'GET',
                    url : '{{ url()->to('/admin/store-lists') }}/' + $this + '/reject-store-status/ajax',
                    data : {
                        id: 3,
                    },

                    success : function () {
                        $('[data-status-button]').removeClass().addClass('btn btn-xs btn-warning dropdown-toggle waves-effect').html('رد شده <span class="caret"></span>');
                    }
                });
            });

            $('#pending-store-status').click(function (e) {
                e.preventDefault();
                var $this = $(this).val();
                $.ajax({
                    type : 'GET',
                    url : '{{ url()->to('/admin/store-lists') }}/' + $this + '/pending-store-status/ajax',
                    data : {
                        id: 3,
                    },

                    success : function () {
                        $('[data-status-button]').removeClass().addClass('btn btn-xs btn-info dropdown-toggle waves-effect').html('در انتظار تایید <span class="caret"></span>');
                    }
                });
            });
            $('#send').click(function (e) {
                if ( $('#store_name').val() == null && $('#product_name').val() == null && $('#dateRangePicker').val() == '' && $('#status').val() == null) {
                    e.preventDefault();
                    swal ( "خطا" ,  "فیلتری انتخاب نشده است." ,  "error" )
                }
            });

        });
    </script>
@endsection
