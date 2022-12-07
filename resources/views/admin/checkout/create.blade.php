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
                            <h4 class="m-t-0 header-title"><b>فرم تسویه حساب با فروشگاه ها</b></h4><br>
                            <form role="form" action="{{ route('checkout.store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">انتخاب تاریخ</label>
                                            <input type="text" name="date" autocomplete="off" value="{{ old('date') }}" id="date" class="datepicker form-control input-sm" placeholder="تاریخ را انتخاب کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="price">مبلغ</label>
                                            <input type="number" name="price" value="{{ old('price') }}" id="price" class="form-control input-sm" placeholder="مبلغ تسویه را وارد کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pay_id">شماره پیگیری</label>
                                            <input type="text" name="pay_id" value="{{ old('pay_id') }}" id="pay_id" class="form-control input-sm" placeholder="شماره پیگیری را وارد کنید...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="send" type="submit" class="btn input-sm btn-purple waves-effect waves-light">ثبت</button>
                                    </div>
                                </div>
                            </form>
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
