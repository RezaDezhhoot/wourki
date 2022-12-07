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

        .dropdown-menu li button {
            border-radius: 0;
        }

        .list-unstyled li {
            font-size: 12px;
        }

        .select2-container .select2-selection--single {
            height: 28px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px !important;
        }

        .comment-container img {
            border-radius: 50%;
        }

        .comment-container .avatar-container {
            padding-top: 15px;
        }

        .comment-section .comment-inner {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 3px 3px 4px #aaa;
            margin: 10px;
        }

        .comment-container .title {
            font-weight: bold;
            color: #fc2a23;
            margin-bottom: 10px;
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
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن</b></h4><br>
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
                            <form role="form" action="{{ URL::current() }}" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tracking_code">کد پیگیری</label>
                                            <input value="{{ request()->input('tracking_code') }}"
                                                   class="form-control input-sm" type="text" name="tracking_code"
                                                   id="tracking_code">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                            <label for="user_id">کاربر :</label>
                                            <select name="user_id" id="user_id"
                                                    class="js-example-basic-single">
                                                    <option value="0" {{(app('request')->user_id == 0 || !app('request')->user_id) ? 'selected' : ''}}>همه</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{$user->id}}" {{$user->id == app('request')->user_id ? 'selected' : ''}}>{{$user->first_name . ' ' . $user->last_name . ' ' . $user->mobile}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                            <label for="store_id">فروشگاه :</label>
                                            <select name="store_id" id="store_id"
                                                    class="js-example-basic-single">
                                                    <option value="0" {{(app('request')->store_id == 0 || !app('request')->store_id) ? 'selected' : ''}}>همه</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{$store->id}}" {{$store->id == app('request')->store_id ? 'selected' : ''}}>{{$store->name . ' ' . $store->user_name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                            <label for="position_id">جایگاه ارتقا :</label>
                                            <select name="position_id" id="position_id"
                                                    class="js-example-basic-single">
                                                    <option value="0" {{(app('request')->position_id == 0 || !app('request')->position_id) ? 'selected' : ''}}>همه</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{$position->id}}" {{$position->id == app('request')->position_id ? 'selected' : ''}}>{{$position->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-success">فیلتر</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>کاربر</th>
                                                <th>ارتقا فروشگاه / محصول / خدمت</th>
                                                <th>جایگاه</th>
                                                <th>مبلغ</th>
                                                <th>کد پیگیری</th>
                                                <th>وضعیت</th>
                                                <th>تاریخ پرداخت</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($upgrades as $row)
                                                <tr>
                                                    <td>{{ $row->first_name . ' ' . $row->last_name }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->position_name }}</td>
                                                    <td>{{ number_format($row->price) }}</td>
                                                    <td>{{ $row->tracking_code }}</td>
                                                    <td>{{ $row->status == "approved" ? 'پرداخت شده' : 'پرداخت نشده' }}</td>
                                                    <td>{{ \Morilog\Jalali\Jalalian::forge($row->updated_at)->format('Y/m/d H:i:s') }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $upgrades->links() }}
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
    {{-- @foreach($list as $tr)
        <div class="modal fade" id="transaction-{{ $tr->id }}-logs" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Log ها</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>پیام</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tr->logs as $log)
                                    <tr>
                                        <td>{{ $log->result_message }}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($log->log_date)->format('%b %D %y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}
@endsection
