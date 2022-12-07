@extends('admin.master')
@section('styles')
    <style>
        table tbody tr th {
            font-size: 13px !important;
            font-weight: normal !important;
            color: #202020 !important;

        }

        table thead tr th {
            font-size: 13px !important;
            font-weight: bold !important;
            color: #000 !important;
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
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>لیست اعلانات ارسال شده</b></h4>
                                    <p class="text-muted font-13"></p>
                                    <br>
                                    <div class="p-10">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th>#</th>
                                                    <th>متن اعلان</th>
                                                    <th>تاریخ ارسال</th>
                                                    <th>لیست کاربران ارسالی</th>
                                                </thead>
                                                <tbody id="sortable-list">
                                                @foreach($notifications as $index => $notify)
                                                    <tr>
                                                        <th scope="row">{{ ++$index }}</th>
                                                        <th>{{ $notify->message }}</th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($notify->created_at)->format('Y/m/d') }}</th>
                                                        <th>
                                                            <select class="form-control js-example-basic-single input-sm"
                                                                    dir="rtl">
                                                                @foreach($notify->users as $user)
                                                                    <option>{{ $user->first_name .' '. $user->last_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
