@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/datepicker-theme.css') }}">
    <style>
        table tbody tr th {
            font-size: 16px !important;
            font-weight: normal !important;
            color: #202020 !important;

        }

        table thead tr th {
            font-size: 13px !important;
            font-weight: bold !important;
            color: #000 !important;
        }

        .charge-wallet {
            position: fixed;
            width: 100%;
            bottom: 0;
            right: 0;
            z-index: 999;
            background-color: #36404A;
            padding: 10px;
        }

        .charge-wallet form label span {
            color: #fff;
        }
    </style>
@endsection
@section('content')
<?php use App\Store; ?>
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ route('charge.all.user.wallet') }}" method="post">
                            {{ csrf_field() }}
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="price" class="control-label">شارژ کیف پول برای همه کاربران</label>
                                        <input type="number" name="price" id="price"
                                               placeholder="مبلغ شارژ را وارد کنید"
                                               class="form-control input-sm" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <button style="margin-top: 25px;" type="submit" class="btn btn-sm btn-facebook">
                                            اعمال شارژ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ url()->current() }}" method="get">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="name" class="control-label">نام و نام خانوادگی</label>
                                            <input type="text" name="name" id="name"
                                                   placeholder="نام یا نام خانوادگی را وارد کنید"
                                                   value="{{ request()->input('name') }}" class="form-control input-sm">
                                            @if($errors->has('name'))
                                                <b class="text-danger">{{ $errors->first('name') }}</b>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">موبایل</label>
                                            <input type="text" name="mobile" class="form-control input-sm"
                                                   value="{{ request()->input('mobile') }}"
                                                   placeholder="شماره موبایل را وارد کنید">
                                            @if($errors->has('mobile'))
                                                <b class="text-danger">{{ $errors->first('mobile') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">ایمیل</label><br>
                                            <input type="email" name="email" value="{{ request()->input('email') }}"
                                                   id="from" class="form-control input-sm"
                                                   placeholder="ایمیل را وارد کنید">
                                            @if($errors->has('email'))
                                                <b class="text-danger">{{ $errors->first('email') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="dateRangePicker">تاریخ ثبت نام</label>
                                            <input type="text" name="daterangepicker" id="dateRangePicker"
                                                   class="form-control">
                                        </div>
                                        <input type="hidden" name="start_date_ts" id="start_date_ts">
                                        <input type="hidden" name="end_date_ts" id="end_date_ts">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-facebook">اعمال فیلتر</button>
                                        <a href="{{ url()->current() }}" class="btn btn-sm btn-linkedin">حذف فیلترها</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <a href="#" data-toggle="modal"
                                               data-target="#users_export_excel"
                                               class="btn btn-pinterest btn-sm">خروجی اکسل تلفن همراه همه کاربران</a>
                                        </div>
                                    </div>
                                    @if(count($errors->all()) > 0)
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="alert alert-danger text-center">
                                                    @foreach($errors->all() as $error)
                                                        {{ $error }} <br/>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(session('success_msg'))
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="alert alert-success text-center">
                                                    {{ session('success_msg') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <h4 class="m-t-0 header-title"><b>لیست کاربران</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-10">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th></th>
                                                    <th>#</th>
                                                    <th>نام</th>
                                                    <th>ارسال پیام</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>موبایل</th>
                                                    <th>ایمیل</th>
                                                    <th>دارای فروشگاه</th>
                                                    <th>موقعیت فروشگاه</th>
                                                    <th>تاریخ ثبت نام</th>
                                                    <th>کاربر معرف</th>
                                                    <th>موجودی کیف پول</th>
                                                </tr>
                                                </thead>

                                                <?php $i = 1; ?>
                                                <tbody id="sortable-list">
                                                @foreach($users as $user)
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" name="user_id[]"
                                                                   data-user-id-to-charge-wallet id=""
                                                                   value="{{ $user->id }}">
                                                        </th>
                                                        <th scope="row">{{ $i }}</th>
                                                        <th>
                                                            <a href="{{route('admin.user.get' , ['user_id' => $user->id])}}" data-toggle="modal"
                                                               style="font-size:16px;"
                                                               >
                                                                {{ $user->first_name }}&nbsp;{{ $user->last_name }}
                                                            </a>
                                                        </th>
                                                        <th>
                                                            <button class="btn btn-success" data-toggle="modal"
                                                                    data-target="#send_quick_message_for_user_{{ $user->id }}">
                                                                ارسال پیام
                                                            </button>
                                                            {{--<div class="dropdown">
                                                                <button class="btn btn-success dropdown-toggle"
                                                                        type="button"
                                                                        id="send_message_to_user_{{ $user->id }}_dropdown"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="true">
                                                                    گزینه ها
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu"
                                                                    aria-labelledby="send_message_to_user_{{ $user->id }}_dropdown">
                                                                    <li>
                                                                        <a href="{{ route('message.index' , $user->id) }}">ارسال
                                                                            پیام</a>
                                                                    </li>
                                                                    <li><a href="#" data-toggle="modal"
                                                                           data-target="#send_quick_message_for_user_{{ $user->id }}">ارسال
                                                                            پیام سریع</a>
                                                                    </li>
                                                                </ul>
                                                            </div>--}}
                                                        </th>
                                                        <th>
                                                            @if($user->become_marketer == 1 && $user->isMarketer == 0)
                                                                <span class="badge badge-success">درخواست بازاریاب شدن</span>
                                                            @endif
                                                        </th>
                                                        <th>
                                                            @if($user->unread_msg > 0 )
                                                                <a href="{{ route('message.index' , $user->id) }}">
                                                                    <span class="badge badge-success">{{ $user->unread_msg }}</span>
                                                                    پیام جدید
                                                                </a>
                                                            @endif
                                                        </th>
                                                        <th>{{ $user->mobile }}</th>
                                                        <th>{{ $user->email == null ? 'ثبت نشده' : $user->email }}</th>
                                                        <th>
                                                            @if($user->store_user_name)
                                                                <a href="{{ route('listOfProductSeller' , $user->store_user_name) }}">{{ '@' . $user->store_user_name }}</a>
                                                            @else
                                                                خیر
                                                            @endif
                                                        </th>
                                                        <th>
                                                            @if($user->province_name && $user->city_name)
                                                                {{ $user->province_name }} - {{ $user->city_name }}
                                                            @endif
                                                        </th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($user->created_at)->format('Y/m/d H:i:s') }}</th>
                                                        <th>
                                                            {{ $user->referrer_first_name }} {{ $user->referrer_last_name }}
                                                        </th>
                                                        <td>{{ $user->total_credit }} تومان</td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div class="charge-wallet" style="display:none;">
                                                <form action="{{ route('wallet.batch_charge') }}" class="form-inline"
                                                      method="POST">
                                                    <div class="form-group">
                                                        {{ csrf_field() }}
                                                        <label for="price_of_charge">
                                                            <span style="margin-left:40px;">شارژ کیف پول</span>
                                                            <span>مبلغ شارژ (تومان):
                                                            </span>
                                                        </label>
                                                        <input type="number" class="form-control-static"
                                                               name="charge_value" id="price_of_charge">
                                                    </div>
                                                    <button type="submit" class="btn btn-success">شارژ</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{ $users->links() }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="users_export_excel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">خروجی اکسل تلفن همراه کاربران</h4>
                </div>
                <form action="{{ route('export.all.users.mobile') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="export_excel_from_index">دریافت خروجی از ردیف:</label>
                            <input type="number" name="from_index"
                                   min="0"
                                   max="{{ $users_num - 1 }}"
                                   placeholder="عددی بین 0 تا {{ $users_num - 1 }} وارد کنید..."
                                   id="export_excel_from_index" class="form-control">
                            <p class="text-muted">تعداد کل کاربران:
                                <b class="text-success">{{ $users_num }}</b>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="limit_in_export_excel">تعداد ردیف ها:</label>
                            <input type="number"
                                   disabled
                                   value="{{ $default_excel_export_rows_limit }}"
                                   id="limit_in_export_excel" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">دریافت خروجی</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach($users as $user)

        <div class="modal fade" id="send_quick_message_for_user_{{ $user->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ارسال پیام سریع</h4>
                    </div>
                    <form action="{{ route('admin.send_quick_message_to_user') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <label for="quick_msg_for_user_{{ $user->id }}">متن پیام:</label>
                                <textarea name="message" class="form-control" id="quick_msg_for_user_{{ $user->id }}"
                                          cols="30" rows="5" placeholder="متن پیام را وارد کنید..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-default">ریست کردن فرم</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="quick_edit_user_{{ $user->id }}" tabindex="-1" role="dialog"
             data-user-id="{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ویرایش سریع اطلاعات کاربر</h4>
                    </div>
                    <form action="{{ route('admin.user_info.quick_update' , $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_first_name">نام:</label>
                                <input type="text" name="first_name" id="user_{{ $user->id }}_first_name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_last_name">نام خانوادگی:</label>
                                <input type="text" name="last_name" id="user_{{ $user->id }}_last_name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_password">رمز عبور:</label>
                                <input type="password" name="password" id="user_{{ $user->id }}_password"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_shaba_code">شماره شبا:</label>
                                <input type="text" name="shaba_code" id="user_{{ $user->id }}_shaba_code"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_email">ایمیل:</label>
                                <input type="email" name="email" id="user_{{ $user->id }}_email" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-default">ریست کردن فرم</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


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

        $('[data-user-id-to-charge-wallet]').change(function () {
            var $this = $(this);
            var user_id = $this.val();
            var div = $('.charge-wallet');
            if ($this.is(':checked')) {
                div.find('form').append('<input type="hidden" name="user_id[]" data-user-id="' + user_id + '" value="' + user_id + '">')
            } else {
                div.find('form input[data-user-id="' + user_id + '"]').remove();
            }
            var user_ids = div.find('form input[data-user-id]');
            if (user_ids.length == 0) {
                div.css('display', 'none');
            } else {
                div.css('display', 'block');
            }
        });

        $('[data-quick-edit-user-button]').click(function () {
            var $this = $(this);
            var modal = $($this.attr('data-target'));
            $.ajax({
                type: 'GET',
                url: '{{ url()->to('/admin/users/') }}/' + modal.data('user-id') + '/info',
                success: function (response) {
                    modal.find('#user_' + modal.data('user-id') + '_first_name').val(response.first_name);
                    modal.find('#user_' + modal.data('user-id') + '_last_name').val(response.last_name);
                    modal.find('#user_' + modal.data('user-id') + '_shaba_code').val(response.shaba_code);
                    modal.find('#user_' + modal.data('user-id') + '_email').val(response.email);
                }
            })
        });
    </script>
@endsection
