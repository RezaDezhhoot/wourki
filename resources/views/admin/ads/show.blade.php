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
        <div class="content">
            <div class="container">
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>ویرایش آگهی</h4>
                                    @if(count($errors->all()) > 0)
                                        <div class="alert alert-danger text-center">
                                            @foreach($errors->all() as $error)
                                                {{ $error }} <br/>
                                            @endforeach
                                        </div>
                                    @endif
                                    <form action="{{ route('ads.update' , $ads->id) }}" method="post"
                                          enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="ads_position">جایگاه</label>
                                            <select name="position" id="ads_position" class="form-control">
                                                @foreach($positions as $p)
                                                    <option {{ $ads->position->id == $p->id ? 'selected' : '' }} value="{{ $p->id }}">{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <h5>تصویر آپلود شده توسط کاربر:</h5>
                                        <img src="{{ url()->to('/image/ads/') . '/' . $ads->pic }}"
                                             style="width:50%;display:block;margin:20px auto;" alt="">

                                        <h5>تصویر آپلود شده توسط مدیر:</h5>

                                        <div class="form-group">
                                            <input type="file" name="final_pic" class="form-control">
                                        </div>
                                        @if($ads->final_pic)
                                            <img src="{{ url()->to('/image/ads') . '/' . $ads->final_pic }}"
                                                 style="width:50%;display:block;margin:20px auto;"
                                                 alt="">
                                        @endif
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">ثبت</button>
                                        </div>
                                    </form>
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