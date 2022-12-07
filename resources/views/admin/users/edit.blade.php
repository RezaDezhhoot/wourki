@extends('admin.master')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form action="{{ route('updateUser' , $users->id) }}" method="post">
                                {{ csrf_field() }}
                                <legend class="text-center">ویرایش کاربر</legend>

                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="first_name">نام</label>
                                            <input type="text" class="form-control input-sm" value="{{ $users->first_name }}"
                                                   name="first_name" id="first_name">
                                            @if($errors->has('first_name'))
                                                <b class="text-danger">{{ $errors->first('first_name') }}</b>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="last_name">نام خانوادگی</label>
                                            <input type="text" class="form-control input-sm" value="{{ $users->last_name }}"
                                                   name="last_name" id="last_name">
                                            @if($errors->has('last_name'))
                                                <b class="text-danger">{{ $errors->first('last_name') }}</b>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="shaba_code">کد شبا</label>
                                            <input type="text" class="form-control input-sm" value="{{ $users->shaba_code }}"
                                                   name="shaba_code" id="shaba_code" placeholder="{{ $users->shaba_code == null ? 'کد شبا ثبت نشده' : '' }}">
                                            @if($errors->has('shaba_code'))
                                                <b class="text-danger">{{ $errors->first('shaba_code') }}</b>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-sm btn-facebook">ثبت</button>
                                                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-sm btn-pinterest">انصراف</a>
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