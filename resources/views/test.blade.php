@extends('admin.master')
@section('head')
    <link rel="stylesheet" href="{{ url()->to('/webservice/assets/css') }}/persian-datepicker.min.css">
    <link href="{{url()->to('/webservice')}}/assets/plugins/select2/css/select2.min.css" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                @if(@session("msg"))
                    <div class="alert alert-success text-center">{{@session('msg')}}</div>
                @elseif(@session('error'))
                    <div class="alert alert-danger text-center">{{@session('error')}}</div>
                @endif
                @if($errors->any())
                    <ul class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                @endif
                <h4 class="m-t-0 header-title">در این فرم میتوانید برای هر تاریخ غذاهای مورد نطر خود را وارد کنید. </h4>
                <br/>
                <h6>برای هر تاریخ تنها یک آیتم را برای نمایش در صفحه ی اول انتخاب کنید .</h6>
                <div class="row">
                    <form method="post" action="{{action("FoodDatesController@store")}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row fdrows form-group">
                            <div class="col-md-2">
                                <b>تاریخ</b>
                                <input type="text" class="date form-control" name="date[]">
                            </div>
                            <div class="col-md-2">
                                <b>نام غذا</b>
                                <select name="food_id[]" class="foods form-controll" required
                                        data-parsley-error-message="*الزامی"></select>
                            </div>
                            <div class="col-md-2">
                                <b>وعده غذایی</b>
                                <select name="meal_id[]" class="meals form-controll" required
                                        data-parsley-error-message="*الزامی">
                                </select>
                            </div>

                            <div class="col-md-1">
                                <b style="font-size:10px;">محدودیت سفارش </b>
                                <input type="text" class="form-control" name="limit[]">
                            </div>
                            <div class="col-md-3">
                                <b>توضیح</b>
                                <input type="text" name="description[]" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <b> صفحه اول </b>
                                <select name="show_default[]" class="form-control">
                                    <option value="0"> خیر</option>
                                    <option value="1"> بله</option>
                                </select>
                            </div>
                            <div class="col-md-1 add">
                                <a href="#" class="addrow"><i class="fa fa-plus"></i> </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m-t-20">
                                <button type="submit" class="btn btn-default waves-effect waves-light">ثبت</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{url()->to('/webservice')}}/assets/js/persian-date.min.js"></script>
    <script src="{{url()->to('/webservice')}}/assets/js/persian-datepicker.min.js"></script>
    <script src="{{url()->to('/webservice')}}/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="{{url()->to('/webservice')}}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <script type="text/javascript" src="{{url()->to('/webservice')}}/assets/plugins/parsleyjs/parsley.min.js"></script>
    <script>
        function addDate(e) {
            e.pDatepicker({
                observer: true,
                format: 'YYYY/MM/DD',
                persianDigit: false,
                minDate: new persianDate(),
                autoClose: true
            });
            return;
        }
        function addNewMealField(element){
            element.select2({
                placeholder: 'وعده غذایی را وارد کنید..',
                ajax: {
                    url: '{{ route('searchInMeals') }}',
                    dataType: 'json',
                    delay: 250,
                    params: {
                        contentType: 'application/ajax ; charset=utf-8'
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        function addSelect2(element) {
            element.select2({
                placeholder: 'نام غذا را وارد کنید ...',
                ajax: {
                    url: '{{url('admin/searchfoods')}}',
                    dataType: 'json',
                    delay: 250,
                    params: {
                        contentType: 'application/ajax ; charset=utf-8'
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        $('.addrow').on("click", function () {
            newRow = '<div class="row fdrows form-group">' +
                '<div class="col-md-2">' +
                '<b>تاریخ</b>' +
                '<input type="text" class="date form-control" name="date[]" >' +
                '</div>' +
                '<div class="col-md-2">' +
                '<b>نام غذا</b>' +
                '<select name="food_id[]" class="foods form-controll" required data-parsley-error-message="*الزامی"></select>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<b>وعده غذایی</b>' +
                '<select name="meal_id[]" class="meals form-controll" required data-parsley-error-message="*الزامی" ></select>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<b style="font-size:10px;">محدودیت سفارش</b>' +
                '<input type="text" class="form-control"  name="limit[]" placeholder="محدودیت سفارش">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<b>توضیح</b>' +
                '<input type="text" name="description[]" class="form-control" placeholder="توضیح">' +
                '</div>' +
                '<div class="col-md-1">' +
                '<b>صفحه اول</b>' +
                '<select name="show_default[]" class="form-control">' +
                '<option value="0"> خیر</option>' +
                '<option value="1"> بله</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-1 rem">' +
                '<a href="#" class="remrow text-danger"><i class="fa fa-minus"></i> </a>' +
                '</div>' +
                '</div>';
            $('.fdrows').last().after(newRow);
            addDate($('.fdrows').last().find('.date'));
            addSelect2($('.fdrows').last().find('.foods'));
            addNewMealField($('.fdrows').last().find('.meals'));
        });
        $(document).on("click", ".remrow", function () {
            thisRow = $(this).closest('.fdrows');
            thisRow.remove();
        });
        $(document).ready(function () {
            addDate($('.date'));
            addSelect2($('.foods'));
            addNewMealField($('.meals'));
        });
        $(document).ready(function () {
            $('form').parsley(
            );
        });
    </script>
@endsection