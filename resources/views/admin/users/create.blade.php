@extends('admin.master')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form action="{{ route('registerUser') }}" method="post">
                                {{ csrf_field() }}
                                <legend class="text-center">ثبت کاربر جدید</legend>
                                @include('frontend.errors')
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label for="first_name">نام</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" required class="form-control input-sm"
                                                   value="{{ old('first_name') }}" name="first_name" id="first_name"
                                                   placeholder="نام را وارد کنید...">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label for="last_name">نام خانوادگی</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" required class="form-control input-sm"
                                                   value="{{ old('last_name') }}"
                                                   name="last_name" id="last_name"
                                                   placeholder="نام خانوادگی را وارد کنید...">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label for="email">ایمیل</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="email" required class="form-control input-sm" value="{{ old('email') }}"
                                                   name="email" id="email" placeholder="ایمیل را وارد کنید...">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label for="password">رمز عبور</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" required class="form-control input-sm"
                                                   value="{{ old('password') }}"
                                                   name="password" id="password"
                                                   placeholder="کلمه عبور را وارد کنید...">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label for="mobile">موبایل</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control input-sm" value="{{ old('mobile') }}"
                                                   name="mobile" id="mobile" placeholder="موبایل را وارد کنید...">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <label for="shaba_code">کدشبا</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" title="کد شبا مربوط به کاربران بازاریاب میباشد" data-toggle="tooltip" class="form-control input-sm" value="{{ old('shaba_code') }}"
                                                   name="shaba_code" id="shaba_code" placeholder="کد شبا را وارد کنید(اختیاری)">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-sm btn-facebook">ثبت</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection



@section('scripts')
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"
            type="text/javascript"></script>

    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    {{--<script type="text/javascript" src="{{ url()->to('/admin') }}/assets/pages/autocomplete.js"></script>--}}



@endsection