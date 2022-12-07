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
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if(count($lists) > 0)
                                    <h4 class="m-t-0 header-title"><b>لیست کاربران معرفی شده</b></h4>
                                    <p class="text-muted font-13"></p>
                                    <div class="p-20">
                                        <form id="order-form" action="">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th>#</th>
                                                    <th>کاربر معرفی شده</th>
                                                    <th>مبلغ دریافت شده</th>
                                                    <th>تاریخ معرفی</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                @foreach($lists as $index => $list)
                                                    <tr>
                                                        <th>{{ ++$index }}</th>
                                                        <th>{{ $list->first_name .' '. $list->last_name }}</th>
                                                        <th>{{ $list->reagent_user_fee }}</th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($list->created_at)->format('%d %B %Y') }}</th>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            {{ $lists->links() }}
                                        </form>
                                    </div>
                                    @else
                                        <div class="alert alert-danger">کاربری معرفی نکرده است.</div>
                                    @endif
                                    <a href="{{ route('showListOfUsers') }}" class="btn btn-sm btn-facebook">بازگشت</a>
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