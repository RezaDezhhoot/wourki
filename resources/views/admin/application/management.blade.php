@extends('admin.master')
@section('styles')
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>آپلود اپلیکیشن</b></h4><br>
                            @if($errors->any())
                                <div class="alert alert-danger text-center">
                                    <ul class="list-unstyled">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form role="form" action="{{ route('admin.application.upload') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="app_version">ورژن (اختیاری)</label>
                                            <input name="app_version" value="{{ old('app_version') }}" type="text"
                                                   class="form-control input-sm" id="app_version"
                                                   placeholder="ورژن اپلیکیشن را وارد نمایید">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="file">فایل اپلیکیشن </label>
                                            <input name="file" type="file"
                                                   class="form-control input-sm" id="file">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button name="" value="product" type="submit" style="margin-top: 25px;"
                                                    class="btn btn-block btn-sm btn-purple waves-effect waves-light">ثبت
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>

        </div> <!-- content -->
        @include('admin.footer')
    </div>
    
@endsection