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
                                    <h4 class="m-t-0 header-title">تبلیغات کاربر
                                        <b class="text-success">{{ $user->first_name }} {{ $user->last_name }}</b>
                                    </h4>
                                    <div class="clearfix"></div>
                                    @if(count($list) > 0 )
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>نوع لینک</th>
                                                    <th>مصحول</th>
                                                    <th>فروشگاه</th>
                                                    <th>توضیحات</th>
                                                    <th>وضعیت پرداخت</th>
                                                    <th>وضعیت تایید</th>
                                                    <th>نام کاربر</th>
                                                    <th>نحوه پرداخت</th>
                                                    <th>وضعیت</th>
                                                    <th></th>
                                                    <th>پرداخت ها</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($list as $ad)
                                                    <tr>
                                                        <td>
                                                            {{ $ad->link_type == 'store' ? 'فروشگاه' : 'محصول' }}
                                                        </td>
                                                        <td>
                                                            @if($ad->product)
                                                                <a href="{{ route('showSingleProduct' , [
                                                                    $ad->product->store->id,
                                                                    $ad->product->id
                                                                ]) }}">{{ $ad->product->name }}</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($ad->store)
                                                                <a href="{{ route('listOfProductSeller' , $ad->store->user_name) }}">{{ $ad->store->name }}</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $ad->description }}</td>
                                                        <td>
                                                            @if($ad->pay_status == 'paid')
                                                                <span class="text-success">پرداخت شده</span>
                                                            @else
                                                                <span class="text-danger">پرداخت نشده</span>
                                                        @endif
                                                        <td>
                                                            @if($ad->status == 'pending')
                                                                <b class="text-warning">در انتظار تایید</b>
                                                            @elseif($ad->status == 'approved')
                                                                <b class="text-success">تایید شده</b>
                                                            @elseif($ad->status == 'rejected')
                                                                <b class="text-danger">رد شده</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($ad->user)
                                                                {{ $ad->user->first_name }}
                                                                {{ $ad->user->last_name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $ad->payment_type == 'wallet' ? 'کیف پول' : 'آنلاین'}}
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    وضعیت<span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="{{ route('ads.status.change' , [
                                                                        'ads' => $ad->id,
                                                                        'status' => 'pending'
                                                                    ]) }}">در انتظار تایید</a></li>
                                                                    <li><a href="{{ route('ads.status.change' , [
                                                                        'ads' => $ad->id,
                                                                        'status' => 'approved'
                                                                    ]) }}">تایید شده</a></li>
                                                                    <li><a href="{{ route('ads.status.change' , [
                                                                        'ads' => $ad->id,
                                                                        'status' => 'rejected'
                                                                    ]) }}">رد شده</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#change_ads_{{ $ad->id }}_position"
                                                               class="btn btn-xs btn-success">تغییر جایگاه</a>
                                                        </td>
                                                        <td>
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#payments_list_of_ad_{{ $ad->id }}"
                                                               class="btn btn-success btn-xs">تاریخچه پرداخت ها</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('ads.show' , $ad->id) }}"
                                                               class="btn btn-success btn-xs">به روز رسانی تصویر</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('delete.ads' , $ad->id) }}" class="btn btn-danger btn-xs">حذف</a>
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
        @foreach($list as $item)
            <div class="modal fade" id="change_ads_{{ $item->id }}_position" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                تغییر جایگاه
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ads.position.change' , $item->id) }}" method="POST">
                                {{csrf_field()}}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <select name="position" id="ads_{{ $item->id }}_position_to_change"
                                            class="form-control">
                                        @foreach($positions as $ps)
                                            <option {{ $item->ads_position_id == $ps->id ? 'selected' : '' }} value="{{ $ps->id }}">{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">ویرایش</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="payments_list_of_ad_{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                تاریخچه پرداخت ها
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>مبلغ</th>
                                        <th>روش پرداخت</th>
                                        <th>شماره پیگیری</th>
                                        <th>شماره ارجاع</th>
                                        <th>تاریخ پرداخت</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->price }}</td>
                                            <td>{{ $payment->payment_type == 'wallet' ? 'کیف پول' : 'آنلاین' }}</td>
                                            <td>{{ $payment->tracking_code }}</td>
                                            <td>{{ $payment->ref_id }}</td>
                                            <td>{{ \Morilog\Jalali\Jalalian::forge($payment->pay_date)->format('%d %B %y') }}</td>
                                            <td>{{ $payment->initial_pay == 'initial' ? 'پرداخت اولیه'  : 'نردبانی'}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">ذخیره</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('scripts')

@endsection
