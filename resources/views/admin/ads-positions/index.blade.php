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
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>جایگاه های تبلیغات</b></h4>
                                    <div class="clearfix"></div>
                                    @if(count($list) > 0 )
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>شناسه</th>
                                                    <th>موقعیت</th>
                                                    <th>در انتظار تایید ها</th>
                                                    <th>تایید شده ها</th>
                                                    <th>رد شده ها</th>
                                                    <th></th>
                                                    <th>قیمت (تومان)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($list as $item)
                                                    <tr>
                                                        <td>
                                                            {{$item->id}}
                                                        </td>
                                                        <td>
                                                            {{ $item->name }}
                                                        </td>
                                                        <td>
                                                            <b class="text-warning">{{ $item->pending_num }}</b>
                                                        </td>
                                                        <td>
                                                            <b class="text-success">{{ $item->approved_num }}</b>
                                                        </td>
                                                        <td>
                                                            <b class="text-danger">{{ $item->rejected_num }}</b>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('ads_list_management' , $item->id) }}"
                                                               class="btn btn-success">آگهی ها
                                                                @if($item->count > 0 )
                                                                    <span class="label label-danger">{{ $item->count }}</span>
                                                                @endif
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('position_price.update' , $item->id) }}"
                                                                  method="POST">
                                                                {{ csrf_field() }}
                                                                {{ method_field('PUT') }}
                                                                <input type="number" name="price"
                                                                       value="{{ $item->price }}"
                                                                       id="position_{{ $item->id }}_price"
                                                                       class="form-control">
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">موردی یافت نشد!</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
        <div class="modal fade" id="add_new_ad" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ثبت تبلیغ جدید
                            <small class="text-muted">عرض و ارتفاع تمام عکس ها باید یکی باشد.</small>
                        </h4>
                    </div>
                    <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data"
                          class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="position_of_new_ad">جایگاه:</label>
                                <div class="col-sm-9">
                                    <select name="position" id="position_of_new_ad" class="form-control">
                                        <option value="under_last_stores">
                                            زیر قسمت آخرین فروشگاه های ثبت شده
                                        </option>
                                        <option value="under_best_stores">
                                            زیر قسمت فروشگاه های برتر
                                        </option>
                                        <option value="under_wourki_offer">
                                            زیر قسمت پیشنهاد وورکی
                                        </option>
                                        <option value="under_wourki_discount">
                                            زیر قسمت حراجی های وورکی
                                        </option>
                                        <option value="under_latest_products">
                                            زیر قسمت جدید ترین محصولات
                                        </option>
                                        <option value="under_most_viewed_products">
                                            زیر قسمت پر بازدید ترین محصولات
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="file_of_new_ad" class="col-sm-3 control-label">تصویر تبلیغ:</label>
                                <div class="col-sm-9">
                                    <input type="file" name="file" id="file_of_new_ad" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">ذخیره</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
