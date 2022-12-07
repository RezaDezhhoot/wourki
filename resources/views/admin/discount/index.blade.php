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
        .daterangepicker.dropdown-menu{
            z-index: 10000;
        }
    </style>
@endsection
@section('content')
{{$errors}}
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{route('discounts.admin.create') }}" method="post">
                            {{ csrf_field() }}
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="code" class="control-label">کد تخفیف</label>
                                            <input type="text" name="code" id="code"
                                                   placeholder="کد تخفیف را وارد کنید"
                                                   value="{{ request()->input('code') }}" class="form-control input-sm">
                                            @if($errors->has('code'))
                                                <b class="text-danger">{{ $errors->first('code') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name" class="control-label">موضوع تخفیف</label>
                                            <input type="text" name="name" id="name"
                                                   placeholder="موضوع تخفیف را وارد کنید"
                                                   value="{{ request()->input('code') }}" class="form-control input-sm">
                                            @if($errors->has('name'))
                                                <b class="text-danger">{{ $errors->first('name') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                    <div class="form-group">
                                            <label for="discountable_type">اعمال تخفیف بر روی :</label>
                                            <select name="discountable_type" id="discountable_type"
                                                    class="js-example-basic-single">
                                                <option value="all" selected>
                                                    همه
                                                </option>
                                                <option value="all-product">
                                                    همه محصولات
                                                </option>
                                                <option value="all-services">
                                                    همه خدمات
                                                </option>
                                                <option value="all-ads">
                                                    همه تبلیغات
                                                </option>
                                                <option value="all-plans">
                                                    همه اشتراک ها
                                                </option>
                                                <option value="all-upgrade">
                                                    همه ارتقا ها
                                                </option>
                                                <option value="guild">
                                                    صنف
                                                </option>
                                                <option value="category">
                                                    دسته بندی
                                                </option>
                                                <option value="product">
                                                    محصول
                                                </option>
                                                <option value="service">
                                                    خدمات
                                                </option>
                                                <option value="store">
                                                    فروشگاه
                                                </option>
                                                <option value="ad">
                                                    تبلیغ
                                                </option>
                                                <option value="plan">
                                                    اشتراک
                                                </option>
                                                <option value="upgrade">
                                                    ارتقا
                                                </option>
                                                <option value="all-sending">
                                                    همه ارسال ها
                                                </option>
                                                <option value="store-sending">
                                                    ارسال های فروشگاه
                                                </option>
                                                <option value="product-sending">
                                                    ارسال های محصول
                                                </option>
                                            </select>
                                            @if($errors->has('discountable_type'))
                                                <b class="text-danger">{{ $errors->first('discountable_type') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                    <div class="form-group">
                                            <label for="discountable_id">آیتم مورد تخفیف :</label>
                                            <select disabled name="discountable_id" id="discountable_id"
                                                    class="js-example-basic-single">
                                                    <option value="0" selected>همه</option>
                                            </select>
                                            @if($errors->has('discountable_id'))
                                                <b class="text-danger">{{ $errors->first('discountable_id') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                    <div class="form-group">
                                            <label for="discount_type">نوع تخفیف</label>
                                            <select name="type" id="discount_type"
                                                    class="js-example-basic-single">
                                                    <option value="percentage" selected>درصدی</option>
                                                    <option value="rial">ریالی</option>
                                            </select>
                                            @if($errors->has('type'))
                                                <b class="text-danger">{{ $errors->first('type') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="percentage">مقدار تخفیف</label>
                                            <input type="number" name="percentage" id="percentage"
                                                   class="form-control">
                                            @if($errors->has('percentage'))
                                                <b class="text-danger">{{ $errors->first('percentage') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="dateRangePicker">تاریخ تخفیف</label>
                                            <input type="text" name="daterangepicker" id="dateRangePicker"
                                                   class="form-control">
                                            @if($errors->has('start_date') || $errors->has('end_date'))
                                                <b class="text-danger">لطفا تاریخ را به درستی وارد نمایید</b>
                                            @endif
                                        </div>
                                        <input type="hidden" name="start_date" id="start_date">
                                        <input type="hidden" name="end_date" id="end_date">

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="min_price">حداقل قیمت اعمال تخفیف</label>
                                            <input type="number" name="min_price" id="min_price"
                                                   class="form-control">
                                            @if($errors->has('min_price'))
                                                <b class="text-danger">{{ $errors->first('min_price') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="max_price">حداکثر قیمت اعمال تخفیف</label>
                                            <input type="number" name="max_price" id="max_price"
                                                   class="form-control">
                                            @if($errors->has('max_price'))
                                                <b class="text-danger">{{ $errors->first('max_price') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description">توضیحات تخفیف</label>
                                        <textarea type="number" name="description" id="description"
                                                   class="form-control"></textarea>
                                            @if($errors->has('description'))
                                                <b class="text-danger">{{ $errors->first('description') }}</b>
                                            @endif
                                                   
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                    <input type="checkbox" name="send_message" id="send_message" />
                                    <label for="send_message">پیام تخفیف به همه کاربران ارسال شود</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-facebook">افزودن</button>
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

                                    <h4 class="m-t-0 header-title"><b>لیست کد تخفیف ها</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-10">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th>#</th>
                                                    <th>نام</th>
                                                    <th>کد تخفیف</th>
                                                    <th>تاریخ شروع</th>
                                                    <th>تاریخ پایان</th>
                                                    <th>توضیحات</th>
                                                    <th>نوع تخفیف</th>
                                                    <th>مقدار تخفیف</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>

                                                <?php $i = 1; ?>
                                                <tbody id="sortable-list">
                                                @foreach($discounts as $i => $discount)
                                                    <tr>
                                                        <th scope="row">{{ $i }}</th>
                                                        <th>
                                                            {{$discount->name}}
                                                        </th>
                                                        <th>
                                                            {{$discount->code}}
                                                        </th>
                                                        <th>
                                                        {{\Morilog\Jalali\Jalalian::forge($discount->start_date)->format('Y/m/d')}}
                                                        </th>
                                                        <th>
                                                            {{\Morilog\Jalali\Jalalian::forge($discount->end_date)->format('Y/m/d')}}
                                                        </th>
                                                        <th>{{ $discount->description }}</th>
                                                        <th>{{ $discount->type == "percentage" ? 'درصدی' : 'ریالی' }}</th>
                                                        <th>{{$discount->percentage}}</th>
                                                        <th>
                                                            <button class="btn btn-success" data-toggle="modal"
                                                                    data-target="#update_{{ $discount->id }}">
                                                                ویرایش
                                                            </button>
                                                            <button class="btn btn-danger" data-toggle="modal" data-target="#delete-{{$discount->id}}">
                                                                حذف
                                                            </button>
                                                        </th>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{ $discounts->links() }}
            </div>
        </div>
    </div>
    @foreach ($discounts as $discount )
        <div class="modal fade" id="update_{{ $discount->id }}" tabindex="-1" role="dialog"
             data-user-id="{{ $discount->id }}">
            <div class="modal-dialog" role="document">
                <form action="{{route('discounts.admin.update' , ['id' => $discount->id]) }}" method="post">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ویرایش کد تخفیف</h4>
                    </div>
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="code" class="control-label">کد تخفیف</label>
                                            <input type="text" name="code" id="code"
                                                   placeholder="کد تخفیف را وارد کنید"
                                                   value="{{ $discount->code }}" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name" class="control-label">موضوع تخفیف</label>
                                            <input type="text" name="name" id="name"
                                                   placeholder="موضوع تخفیف را وارد کنید"
                                                   value="{{ $discount->name }}" class="form-control input-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                    <div class="form-group">
                                            <label for="type-{{$discount->id}}">نوع تخفیف</label>
                                            <select name="type" id="type-{{$discount->id}}"
                                                    class="js-example-basic-single">
                                                    <option value="percentage" selected>درصدی</option>
                                                    <option value="rial">ریالی</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="percentage-{{$discount->id}}">مقدار تخفیف</label>
                                            <input type="number" name="percentage" id="percentage-{{$discount->id}}"
                                                   class="form-control" value="{{$discount->percentage}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="dateRangePicker{{$discount->id}}">تاریخ تخفیف</label>
                                            <input type="text" name="daterangepicker" id="dateRangePicker{{$discount->id}}"
                                                   class="form-control" value="{{\Morilog\Jalali\Jalalian::forge($discount->start_date)->format('Y/m/d')}} - {{\Morilog\Jalali\Jalalian::forge($discount->end_date)->format('Y/m/d')}}">
                                        </div>
                                        <input type="hidden" name="start_date" id="start_date_{{$discount->id}}" value="{{$discount->start_date}}">
                                        <input type="hidden" name="end_date" id="end_date_{{$discount->id}}" value="{{$discount->end_date}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="min_price-{{$discount->id}}">حداقل قیمت اعمال تخفیف</label>
                                            <input type="number" name="min_price" id="min_price-{{$discount->id}}"
                                                   class="form-control" value="{{$discount->min_price}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="max_price-{{$discount->id}}">حداکثر قیمت اعمال تخفیف</label>
                                            <input type="number" name="max_price" id="max_price-{{$discount->id}}"
                                                   class="form-control" value="{{$discount->max_price}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description-{{$discount->id}}">توضیحات تخفیف</label>
                                        <textarea type="number" name="description" id="description-{{$discount->id}}"
                                                   class="form-control">{{$discount->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-default">ریست کردن فرم</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="delete-{{ $discount->id }}" tabindex="-1" role="dialog"
             data-user-id="{{ $discount->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">حذف کد تخفیف</h4>
                    </div>
                        <div class="modal-body">
                            <p>آیا از حذف کد تخفیف اطمینان دارید؟</p>
                        </div>
                        <div class="modal-footer">
                        <form action="{{route('discounts.admin.delete' , ['id' => $discount->id]) }}" method="post">
                            {{ csrf_field() }}
                            <button type="reset" class="btn btn-default" data-dismiss="modal">برگرد</button>
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </form>
                        </div>
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
            $('#discountable_type').change(function(){
                if($(this).find(":selected").val() === 'all'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه</option>`);
                    return
                }
                if($(this).find(":selected").val() === 'all-product'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه محصولات</option>`);
                    return
                }
                if($(this).find(":selected").val() === 'all-services'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه خدمات</option>`);
                    return
                }
                if($(this).find(":selected").val() === 'all-ads'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه تبلیغات</option>`);
                    return
                }
                if($(this).find(":selected").val() === 'all-plans'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه اشتراک ها</option>`);
                    return
                }
                if($(this).find(":selected").val() === 'all-upgrade'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه ارتقا ها</option>`);
                    return
                }
                if($(this).find(":selected").val() === 'all-sending'){
                    $("#discountable_id").prop('disabled' , true);
                    $('#discountable_id').html(`<option value="0" selected>همه ارسال ها</option>`);
                    return
                }
                $("#discountable_id").prop('disabled' , false);
                $.ajax({
                type: 'get',
                url: '{{url()->to("api/discounts/discountables/get/")}}' + '/' + $(this).find(":selected").val(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                    $('#discountable_id').html(``);
                    var data = response.data
                    for(var i = 0;i<data.length;i++){
                        $('#discountable_id').append(`
                        <option value="${data[i].id}">${data[i].name}</option>
                        `);
                    }

                }
            });
            })
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

            @foreach($discounts as $discount)
            var $dateRanger{{$discount->id}} = $("#dateRangePicker{{$discount->id}}");
                $dateRanger{{$discount->id}}.daterangepicker({
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
                    $('#start_date_{{$discount->id}}').val(picker.startDate.format('YYYY/MM/DD'));
                    $('#end_date_{{$discount->id}}').val(picker.endDate.format('YYYY/MM/DD'));
                } else {
                    $(this).val('')
                }
            });
            @endforeach

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


    </script>
@endsection
