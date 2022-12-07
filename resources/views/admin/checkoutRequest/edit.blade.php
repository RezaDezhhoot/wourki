@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/bootstrap-datepicker.min.css') }}">
    <style>
        .select2-container .select2-selection--single {
            height: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
            font-size: 12px;
            text-align: right;
            color: #888 !important;
        }

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
                <div class="row">
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
                                <h4 class="m-t-0 header-title"><b>فرم تسویه حساب </b></h4><br>
                                <form role="form"
                                      action="{{ route('checkoutrequest.update',[$checkoutRequests->id]) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{method_field('PATCH')}}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">انتخاب تاریخ</label>
                                                <input type="text" name="date" autocomplete="off"
                                                       value="{{!is_null($checkoutRequests->checkout) ? \Morilog\Jalali\Jalalian::forge($checkoutRequests->checkout->created_at)->format('Y/m/d H:i:s') : old('date') }}"
                                                       id="date" class="datepicker form-control input-sm"
                                                       placeholder="تاریخ را انتخاب کنید...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="price">مبلغ</label>
                                                <input type="number" name="price"
                                                       value="{{!is_null($checkoutRequests->checkout) ? $checkoutRequests->checkout->price :old('price') }}"
                                                       id="price" class="form-control input-sm"
                                                       placeholder="مبلغ تسویه را وارد کنید...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pay_id">شماره پیگیری</label>
                                                <input type="text" name="pay_id"
                                                       value="{{!is_null($checkoutRequests->checkout) ? $checkoutRequests->checkout->pay_id : old('pay_id') }}"
                                                       id="pay_id" class="form-control input-sm"
                                                       placeholder="شماره پیگیری را وارد کنید...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <button id="send" type="submit"
                                                    class="btn input-sm btn-purple waves-effect waves-light">ثبت
                                            </button>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="{{route('checkoutrequest.destroy',['checkoutRequests'=>$checkoutRequests->id,'reject'=>1])}}"
                                               class="btn input-sm btn-danger waves-effect waves-light" role="button">رد
                                                تسویه
                                                حساب</a>
                                        </div>
                                        @if(!is_null($checkoutRequests->checkout))
                                            <div class="col-md-1">
                                                <a href="{{route('checkoutrequest.destroy',['checkoutRequests'=>$checkoutRequests->id])}}"
                                                   class="btn input-sm btn-danger waves-effect waves-light"
                                                   role="button">حذف تسویه
                                                    حساب</a>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>تراکنش های کیف
                                            پول {{ $checkoutRequests->user->first_name . ' ' . $checkoutRequests->user->last_name }}</b>
                                    </h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        <table class="table table-striped m-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>کاربر</th>
                                                <th>مبلغ</th>
                                                <th>نوع</th>
                                                <th>تاریخ</th>
                                            </tr>
                                            </thead>

                                            <tbody id="sortable-list">
                                            @foreach($checkoutRequests->user->wallet as $index => $wallet)
                                                <tr>
                                                    <th scope="row">{{ ++$index }}</th>
                                                    <th>{{ $checkoutRequests->user->first_name . ' ' . $checkoutRequests->user->last_name }}</th>
                                                    <th>{{ $wallet->cost }}</th>
                                                    <th>
                                                        ورودی
                                                    </th>
                                                    <th>{{ \Morilog\Jalali\Jalalian::forge($wallet->created_at)->format('Y/m/d H:i:s') }}</th>
                                                </tr>
                                                @foreach($wallet->reducedFrom as $index2=>$reducedFrom)
                                                    <tr>
                                                        <th scope="row">{{ ++$index2 }}</th>
                                                        <th>{{ $checkoutRequests->user->first_name . ' ' . $checkoutRequests->user->last_name }}</th>
                                                        <th>{{ $reducedFrom->pivot->Amount }}</th>
                                                        <th>
                                                            خروجی
                                                        </th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($reducedFrom->pivot->created_at)->format('Y/m/d H:i:s') }}</th>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                            <tr>
                                                <th> بستانکار : {{ $positiveTotal }}</th>
                                                <th> بدهکار : {{ $negativeTotal }}</th>
                                                <th style="background-color: #00BCD4;"> قابل پرداخت
                                                    : {{ $positiveTotal - $negativeTotal }}</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
    <script src="{{ url()->to('/admin/assets/js/bootstrap-datepicker.fa.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".datepicker").datepicker();
        });
    </script>
@endsection