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

        .list-unstyled li  , textarea{
            font-size: 12px!important;
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
                            <h4 class="m-t-0 header-title"><b>فیلتر</b></h4><br>
                            @include('frontend.errors')
                            <form role="form" action="{{ url()->current() }}" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user">کاربر معرف </label>
                                            <select name="user" id="user" class="form-control input-sm">
                                                <option disabled selected>::انتخاب کنید::</option>
                                                @foreach($users as $user)
                                                    <option {{ request()->user == $user->user_id ? 'selected' : '' }} value="{{ $user->user_id }}">{{ $user->first_name .' '. $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="reagent_type">نوع معرفی </label>
                                            <select name="reagent_type" id="reagent_type" class="form-control input-sm">
                                                <option disabled selected>::انتخاب کنید::</option>
                                                <option value="reagent" {{ request()->reagent_type == 'reagent' ? 'selected' : '' }}>برای معرفی فروشگاه</option>
                                                <option value="create_store" {{ request()->reagent_type == 'create_store' ? 'selected' : '' }}>برای ساخت فروشگاه</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user_mobile">تلفن همراه کاربر معرف</label>
                                            <input type="text" name="user_mobile" id="user_mobile" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-pinterest">فیلتر</button>
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-facebook">حذف فیلتر</a>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>کاربران</b></h4>
                                    <p class="text-muted font-13"></p>
                                    <div class="p-20">
                                        <form id="order-form" action="">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th>#</th>
                                                    <th>کاربر معرف</th>
                                                    <th>مبلغ دریافت شده</th>
                                                    <th>کاربر معرفی شده</th>
                                                    <th>مبلغ دریافت شده</th>
                                                    <th>نوع معرفی</th>
                                                    <th>تاریخ معرفی</th>
                                                </tr>
                                                </thead>
                                                <tbody id="sortable-list">
                                                @foreach($lists as $index => $list)
                                                    <tr>
                                                        <th>{{ ++$index }}</th>
                                                        <th style="background-color: #ccc;">{{ $list->referrer_first_name . ' ' . $list->referrer_last_name }}</th>
                                                        <th style="background-color: #ccc;">{{ $list->reagent_user_fee }}</th>
                                                        <th style="background-color: #7acaff;">{{ $list->referred_first_name . ' ' . $list->referred_last_name }}</th>
                                                        <th style="background-color: #7acaff;">{{ $list->reagented_user_fee }}</th>
                                                        <th>{!! $list->type == 'reagent' ? '<span class="text-danger">برای معرفی فروشگاه</span>' : '<span class="text-success">برای ساخت فروشگاه</span>' !!}</th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($list->created_at)->format('%d %B %Y') }}</th>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            {{ $lists->links() }}
                                        </form>
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