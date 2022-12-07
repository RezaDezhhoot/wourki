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
                            <h4 class="m-t-0 header-title"><b>فیلتر کردن</b></h4><br>
                            <form role="form" action="{{ url()->current() }}" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user">انتخاب کاربر</label>
                                            <select name="user" id="user" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب همه::</option>
                                                @foreach($users as $user)
                                                    <option {{ request()->input('user') == $user->id ? 'selected' : '' }} value="{{ $user->first_name }}">{{ $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">وضعیت</label>
                                            <select name="status" id="status" class="js-example-basic-single">
                                                <option value="all" disabled selected>::انتخاب همه::</option>
                                                <option value="0" {{ request()->input('status') == 0 ? 'selected' : '' }}>
                                                    نامشخص
                                                </option>
                                                <option value="1" {{ request()->input('status') == 1 ? 'selected' : '' }}>
                                                    تایید شده
                                                </option>
                                                <option value="2" {{ request()->input('status') ==2 ? 'selected' : '' }}>
                                                    رد شده
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="send" type="submit"
                                                class="btn input-sm btn-purple waves-effect waves-light">اعمال
                                        </button>
                                        <a href="{{ url()->current() }}"
                                           class="btn input-sm btn-default waves-effect waves-light">حذف فیلترها</a>
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
                                    <h4 class="m-t-0 header-title"><b>لیست درخواست های تسویه حساب</b></h4>
                                    <p class="text-muted font-13"></p>
                                    @if(count($requests) > 0)
                                        <div class="p-10">
                                            <form id="order-form" action="{{ route('submitDocumentOfCheckout') }}"
                                                  method="post">
                                                {{ csrf_field() }}
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="check-all" title="انتخاب همه">
                                                        </th>
                                                        <th>ردیف</th>
                                                        <th>نام فروشگاه</th>
                                                        <th>نام شخص</th>
                                                        <th>وضعیت درخواست</th>
                                                        <th>شماره تسویه</th>
                                                        <th>تاریخ درخواست</th>
                                                        <th>
                                                            <span style="font-size: 20px;margin-right: 20%;"
                                                                  class="glyphicon glyphicon-edit"></span>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <?php $id = 1; ?>
                                                    <tbody id="sortable-list">
                                                    @foreach($requests as $request)
                                                        <tr>
                                                            <th><input type="checkbox" class="check-item"
                                                                       name="checkoutId[]" value="{{ $request->id }}">
                                                            </th>
                                                            <th>{{ $id }}</th>
                                                            <th>{{ $request->user->store->name ?? ' - ' }}</th>
                                                            <th>{{ $request->user->first_name.' '.$request->user->last_name }}</th>
                                                            <th>
                                                                @switch($request->status)
                                                                    @case(0)<span class="badge badge-info">نامشخص</span>
                                                                    @break
                                                                    @case(1)<span
                                                                            class="badge badge-success">تایید شده</span>
                                                                    @break

                                                                    @case(2)<span
                                                                            class="badge badge-danger">رد شده</span>
                                                                    @break
                                                                @endswitch
                                                            </th>
                                                            <th>{{$request->checkout_id ?? '-' }}</th>

                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($request->created_at)->format('%d %B %Y') }}</th>
                                                            <th><a style="cursor: pointer;font-weight: bold;"
                                                                   href="{{route('checkoutrequest.edit',['checkoutRequests'=>$request->id])}}">عملیات</a>
                                                            </th>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $requests->links() }}
                                                <br>
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            درخواستی یافت نشد!
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--                @foreach($checkouts as $checkout)--}}
                {{--                    <div class="modal fade" id="updateCheckout_{{ $checkout->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
                {{--                        <div class="modal-dialog" role="document">--}}
                {{--                            <div class="modal-content">--}}
                {{--                                <div class="modal-header">--}}
                {{--                                    <h5 class="modal-title" id="exampleModalLabel"> ویرایش لیست سند {{ $checkout->name }}</h5>--}}
                {{--                                </div>--}}
                {{--                                <form action="{{ route('store.checkout.update' , $checkout->id) }}" method="post">--}}
                {{--                                    {{ method_field('put') }}--}}
                {{--                                    {{ csrf_field() }}--}}
                {{--                                    <div class="modal-body">--}}
                {{--                                        <div class="row">--}}
                {{--                                            <div class="col-md-6">--}}
                {{--                                                <div class="form-group">--}}
                {{--                                                    <label for="store">فروشگاه</label>--}}
                {{--                                                    <select required name="store" id="store" class="form-control input-sm">--}}
                {{--                                                        <option value="all" disabled selected>::همه::</option>--}}
                {{--                                                        @foreach($stores as $store)--}}
                {{--                                                            <option {{ $store->id == $checkout->store_id ? 'selected' : '' }} value="{{ $store->id }}">{{ $store->name }}</option>--}}
                {{--                                                        @endforeach--}}
                {{--                                                    </select>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                            <div class="col-md-6">--}}
                {{--                                                <div class="form-group">--}}
                {{--                                                    <label for="price">مبلغ</label>--}}
                {{--                                                    <input required type="number" name="price" id="price" class="form-control input-sm" value="{{ $checkout->price }}">--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                        <div class="row">--}}
                {{--                                            <div class="col-md-6">--}}
                {{--                                                <div class="form-group">--}}
                {{--                                                    <div class="form-group">--}}
                {{--                                                        <label for="pay_id">کد پرداخت</label>--}}
                {{--                                                        <input required type="number" name="pay_id" id="pay_id" class="form-control input-sm" value="{{ $checkout->pay_id }}">--}}
                {{--                                                    </div>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                            <div class="col-md-6">--}}
                {{--                                                <div class="form-group">--}}
                {{--                                                    <label for="date">تاریخ</label>--}}
                {{--                                                    <input required type="text" name="date" id="date" class="form-control input-sm" value="{{ \Morilog\Jalali\Jalalian::forge($checkout->created_at)->format('Y/m/d') }}">--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="modal-footer">--}}
                {{--                                        <button type="submit" class="btn btn-sm btn-primary">ویرایش</button>--}}
                {{--                                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">بستن</button>--}}
                {{--                                    </div>--}}
                {{--                                </form>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                @endforeach--}}
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection
