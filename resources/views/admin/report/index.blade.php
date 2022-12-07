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
            border-radius:0;
        }
        .list-unstyled li {font-size: 12px;}

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
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن </b></h4><br>
                            @include('frontend.errors')
                            <form role="form" action="{{ URL::current() }}" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store">نام فروشگاه</label>
                                            <select name="store" id="store" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                @foreach($stores as $store)
                                                    <option {{ request()->input('store') == $store->id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="send" class="btn input-sm btn-purple waves-effect waves-light">اعمال</button>
                                        <a href="{{ url()->current() }}" class="btn input-sm btn-default waves-effect waves-light">حذف فیلترها</a>
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
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>لیست تخلفات فروشگاه ها</b></h4>
                                    <p class="text-muted font-13"></p>
                                    @if(count($reports) > 0)
                                        <div class="p-10">
                                            <form action="{{ route('submitDocumentOfBill') }}" method="post">
                                                {{ csrf_field() }}
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام خریدار</th>
                                                        <th>نام فروشگاه</th>
                                                        <th>متن تخلف</th>
                                                        <th>تاریخ صدور تخلف</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="sortable-list">
                                                    @foreach($reports as $index => $report)
                                                        <tr>
                                                            <th>{{ $index }}</th>
                                                            <th>{{ $report->first_name .' '. $report->last_name }}</th>
                                                            <th>{{ $report->storeName }}</th>
                                                            <th>{{ $report->text }}</th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($report->created_at)->format('%d %B %Y') }}</th>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $reports->links() }}
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            گزارشی یافت نشد!
                                        </div>
                                    @endif

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
