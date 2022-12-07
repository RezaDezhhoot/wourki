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
                        <form action="{{ url()->current() }}" method="get">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="user" class="control-label">انتخاب کاربر</label>
                                        <select name="user" id="user" class="form-control js-example-basic-single input-sm" dir="rtl">
                                            <option value="all" disabled selected>::انتخاب کنید::</option>
                                            @foreach($users as $user)
                                                <option {{ request()->input('user') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->first_name .' '. $user->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-facebook">اعمال فیلتر</button>
                                        <a href="{{ url()->current() }}" class="btn btn-sm btn-linkedin">حذف فیلترها</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>لیست کاربران</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-10">
                                        <form action="{{ route('submitDocumentOfWallet') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="table-responsive">
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr style="background-color: #81bfde;">
                                                        <th><input type="checkbox" id="check-all" title="انتخاب همه"></th>
                                                        <th>ردیف</th>
                                                        <th>کاربر</th>
                                                        <th>مبلغ (تومان)</th>
                                                        <th>کد تراکنش</th>
                                                        <th>تاریخ</th>
                                                    </thead>
                                                    <tbody id="sortable-list">
                                                    @foreach($wallets as $index => $wallet)
                                                        <tr>
                                                            <th><input type="checkbox" class="check-item"
                                                                       name="walletId[]" value="{{ $wallet->id }}"></th>
                                                            <th scope="row">{{ ++$index }}</th>
                                                            <th>{{ $wallet->first_name }}
                                                                &nbsp;{{ $wallet->last_name }}</th>
                                                            <th>{{ $wallet->cost }}</th>
                                                            <th>{{ $wallet->tracking_code }}</th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($wallet->created_at)->format('Y/m/d H:i') }}</th>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $wallets->links() }}
                                                <br>
                                                <button type="submit"
                                                        class="btn input-sm btn-purple waves-effect waves-light">ثبت
                                                    اسناد
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $('#check-all').change(function () {
            var check = $(this).is(':checked');
            if (check == true)
                $('.check-item').prop('checked' , true);
            else
                $('.check-item').prop('checked' , false);
        });
    </script>
@endsection