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
                            @include('frontend.errors')
                            <div class="card-box">
                                <div class="row">
                                    <form action="{{ route('send.notification') }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="search">جستجو کاربر</label>
                                                <select id="search" name="notify[]" class="js-example-basic-single"
                                                        multiple="multiple">
                                                    @foreach($selectUsers as $user)
                                                        <option value="{{ $user->id }}">{{ $user->first_name .' '. $user->last_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="store">جستجو فروشگاه</label>
                                                <select id="store" name="notify[]" class="js-example-basic-single"
                                                        multiple="multiple">
                                                    @foreach($stores as $store)
                                                        <option value="{{ $store->user->id }}">{{ $store->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="message" class="control-label">متن پیام جهت
                                                        ارسال</label>
                                                    <textarea name="message" id="message" cols="20" rows="5"
                                                              class="form-control"
                                                              placeholder="متن پیام را وارد کنید..."
                                                              required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <button type="submit" name="selected" class="btn btn-sm btn-facebook">
                                                    ارسال به کاربران منتخب
                                                </button>
                                                <button type="submit" name="all" class="btn btn-sm btn-pinterest">ارسال
                                                    به همه کاربران
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>لیست کاربران</b></h4>
                                    <p class="text-muted font-13"></p>
                                    <br>
                                    <div class="p-10">
                                        <div class="table-responsive" style="height: 800px">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr style="background-color: #81bfde;">
                                                    <th>نام</th>
                                                    <th>موبایل</th>
                                                    <th>فروشگاه</th>
                                                    <th>تاریخ ثبت نام</th>
                                                {{--<th><input data-toggle="tooltip" title="انتخاب همه کاربران" style="width: 17px;height: 17px;" type="checkbox" id="checkAll"></th>--}}
                                                </thead>
                                                <tbody id="sortable-list">
                                                @foreach($users as $index => $user)
                                                    <tr>
                                                        <th>{{ $user->first_name }}&nbsp;{{ $user->last_name }}</th>
                                                        <th>{{ $user->mobile }}</th>
                                                        <th>{!! optional($user->store()->first())->name ? $user->store()->first()->name : '<p class="text-primary">ثبت نشده</p>' !!}</th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($user->created_at)->format('Y/m/d') }}</th>
                                                        {{--<th>--}}
                                                        {{--<input style="width: 17px;height: 17px;"--}}
                                                        {{--type="checkbox" class="notify" id="notify-{{ $user->id }}" name="notify[]"--}}
                                                        {{--value="{{ $user->id }}">--}}
                                                        {{--</th>--}}
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        {{ $users->links() }}
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
        $(document).ready(function () {
            // $("#search , #store").on("select2:select", function (e) {
            //     $('#notify-'+e.params.data.id).prop('checked', true);
            // });
            //
            // $("#search , #store").on("select2:unselect", function (e) {
            //     $('#notify-'+e.params.data.id).prop('checked', false);
            // });

            $('#checkAll').click(function () {
                if ($(this).prop('checked')) {
                    $('.notify').prop('checked', true);
                } else {
                    $('.notify').prop('checked', false);
                }
            });
        });
    </script>
@endsection
