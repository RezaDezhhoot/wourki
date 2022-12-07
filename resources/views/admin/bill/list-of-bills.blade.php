@extends('admin.master')
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
                                    <div class="col-sm-4">
                                        <label for="province" class="control-label">انتخاب استان محصول</label>
                                        <select name="province" id="province" class="js-data-example-ajax form-control">
                                            <option value="all" selected="selected">همه</option>
                                            @foreach($provinces as $province)
                                                <option {{ $province->id == request()->input('province') ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <script>
                                        $('#province').change(function () {
                                            var value = $(this).val();

                                            $.ajax({
                                                type: 'get',
                                                url: '{{ url()->to('/admin/provinces') }}/' + value + '/city',
                                                data: {},
                                                success: function (response) {
                                                    var list = response;
                                                    var overloads = $('#city');
                                                    if (list.length > 0) {
                                                        overloads.html('<option value="all">همه</option>');
                                                        for (var i = 0; i < list.length; i++) {
                                                            overloads.append('<option value="' + list[i].id + '">' + list[i].name + '</option>')
                                                        }

                                                    }
                                                }
                                            })
                                        });

                                        $(document).ready(function () {
                                            @if(request()->has('province') && request()->input('province') != 'all')
                                            var value = {{ request()->input('province') }};

                                            $.ajax({
                                                type: 'get',
                                                url: '{{ url()->to('/admin/provinces') }}/' + value + '/city',
                                                data: {},
                                                success: function (response) {
                                                    var list = response;
                                                    var overloads = $('#city');
                                                    if (list.length > 0) {
                                                        overloads.html('<option value="all">همه</option>');
                                                        for (var i = 0; i < list.length; i++) {
                                                            overloads.append('<option  value="' + list[i].id + '">' + list[i].name + '</option>')
                                                        }
                                                        @if(request()->has('city'))
                                                            overloads.val({{ request()->input('city') }});
                                                        @endif
                                                    }
                                                }
                                            })
                                            @endif
                                        });
                                    </script>

                                    <div class="col-sm-4">
                                        <label for="city" class="control-label">انتخاب شهر محصول</label>
                                        <select name="city" id="city" class="js-data-example-ajax form-control">
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="pay_type" class="control-label">انتخاب نوع پرداخت محصول</label>
                                        <select name="pay_type" id="pay_type" class="js-data-example-ajax form-control">
                                            <option value="all" {{ request()->input('pay_type') == 'all' ? 'selected' : '' }} selected="selected">همه</option>
                                            <option {{ request()->input('pay_type') == 'online' ? 'selected' : '' }} value="online">
                                                آنلاین
                                            </option>
                                            <option {{ request()->input('pay_type') == 'venal' ? 'selected' : '' }} value="venal">
                                                پستی
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="status" class="control-label">انتخاب وضعیت محصول</label>
                                        <select name="status" id="status" class="js-data-example-ajax form-control">
                                            <option {{ request()->input('status') == 'all'  ? 'selected' : '' }} value="all">همه</option>
                                            <option {{ request()->input('status') == 'bought'  ? 'selected' : '' }} value="bought">
                                                خریداری شده
                                            </option>
                                            <option {{ request()->input('status') == 'shipping' ? 'selected' : '' }} value="shipping">
                                                درحال ارسال
                                            </option>
                                            <option {{ request()->input('status')  == 'delivered' ? 'selected' : '' }} value="delivered">
                                                در انتظار تایید
                                            </option>
                                            <option {{ request()->input('status') == 'returned'  ? 'selected' : '' }} value="returned">
                                                بازگشت شده
                                            </option>
                                            <option {{ request()->input('status')  == 'rejected' ? 'selected' : '' }} value="rejected">
رد شده
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="mobile" class="control-label">انتخاب موبایل</label>
                                        <input style="font-size: 12px;"
                                               value="{{ request()->input('mobile') }}"
                                               placeholder="شماره موبایل را وارد کنید..." type="text" name="mobile"
                                               id="mobile" class="form-control">
                                        @if($errors->has('mobile'))
                                            <b class="text-danger">{{ $errors->first('mobile') }}</b>
                                        @endif
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">اعمال
                                            فیلتر
                                        </button>
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
                                    <h4 class="m-t-0 header-title"><b>لیست صورتحساب</b></h4>
                                    <p class="text-muted font-13"></p>
                                    <div class="p-20">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام کاربر</th>
                                                    <th>استان</th>
                                                    <th>شهر</th>
                                                    <th>آدرس</th>
                                                    <th>کدپستی</th>
                                                    <th>وضعیت</th>
                                                    <th>نوع پرداخت</th>
                                                    <th>کد ارجاعی پرداخت</th>
                                                    <th>تاریخ سفارش</th>
                                                    <th>اقلام</th>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach($bills as $bill)

                                                    <tr>
                                                        <th scope="row">{{ $i }}</th>
                                                        <td>{{ $bill->user_first_name }}&nbsp;{{ $bill->user_last_name }}</td>
                                                        <td>{{ $bill->province_name }}</td>
                                                        <td>{{ $bill->city_name }}</td>
                                                        <td>{{ $bill->address }}</td>
                                                        <td>{{ $bill->postal_code }}</td>
                                                            <td>
                                                            <div class="btn-group dropdown changeStatus">
                                                                {{--<button type="button" class="btn btn-success waves-effect waves-light">--}}
                                                                    @if($bill->status == 'bought')
                                                                        <button class="btn btn-default waves-effect waves-light btn-xs">در انتظار تایید</button>
                                                                        <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'rejected']) }}">رد شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'bought']) }}">در انتظار تایید</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'shipping']) }}">در حال ارسال</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'returned']) }}">بازگشت داده شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'delivered']) }}">تحویل داده شده</a></li>
                                                                        </ul>
                                                                    @elseif($bill->status == 'rejected')
                                                                        <button class="btn btn-danger waves-effect waves-light btn-xs">رد شده</button>
                                                                        <button type="button" class="btn btn-danger dropdown-toggle waves-effect waves-light btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'rejected']) }}">رد شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'bought']) }}">در انتظار تایید</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'shipping']) }}">در حال ارسال</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'returned']) }}">بازگشت داده شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'delivered']) }}">تحویل داده شده</a></li>
                                                                        </ul>
                                                                    @elseif($bill->status == 'delivered')
                                                                        <button class="btn btn-success waves-effect waves-light btn-xs">تحویل داده شده</button>
                                                                        <button type="button" class="btn btn-success dropdown-toggle waves-effect waves-light btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'rejected']) }}">رد شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'bought']) }}">در انتظار تایید</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'shipping']) }}">در حال ارسال</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'returned']) }}">بازگشت داده شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'delivered']) }}">تحویل داده شده</a></li>
                                                                        </ul>
                                                                    @elseif($bill->status == 'shipping')
                                                                        <button class="btn btn-info waves-effect waves-light btn-xs">در حال ارسال</button>
                                                                        <button type="button" class="btn btn-info dropdown-toggle waves-effect waves-light btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'rejected']) }}">رد شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'bought']) }}">در انتظار تایید</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'shipping']) }}">در حال ارسال</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'returned']) }}">بازگشت داده شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'delivered']) }}">تحویل داده شده</a></li>
                                                                        </ul>
                                                                    @else
                                                                        <button class="btn btn-warning waves-effect waves-light btn-xs">بازگشت داده شده</button>
                                                                        <button type="button" class="btn btn-warning dropdown-toggle waves-effect waves-light btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'rejected']) }}">رد شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'bought']) }}">در انتظار تایید</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'shipping']) }}">در حال ارسال</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'returned']) }}">بازگشت داده شده</a></li>
                                                                            <li><a href="{{ route('changeStatusOfBill' , ['billId' => $bill->id , 'status' => 'delivered']) }}">تحویل داده شده</a></li>
                                                                        </ul>
                                                                    @endif
                                                            </div>

                                                        </td>
                                                        <td>
                                                            @if($bill->pay_type == 'online') آنلاین @endif
                                                            @if($bill->pay_type == 'venal') پستی @endif
                                                        </td>
                                                        <td>{{ $bill->pay_referral_code }}</td>
                                                        <td>{{ jdate($bill->created_at)->format('%B %d، %Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('showListOfBillItem' , $bill->id) }}">
                                                                <button type="button" class="btn btn-primary btn-xs">مشاهده اقلام</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
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

                @if(count($bills) > 0)
                    <div class="row">
                        <div class="col-xs-12">
                            <ul class="pagination pagination-split">
                                @if($bills->currentPage() != 1)
                                    <li>
                                        <a href="{{ $bills->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                @endif
                                @for($i =1 ; $i <= $bills->lastPage() ; $i++)
                                    <li class="{{ $i == $bills->currentPage() ? 'active' : '' }}">
                                        <a href="{{ $bills->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                @if($bills->currentPage() != $bills->lastPage())
                                    <li>
                                        <a href="{{ $bills->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"
            type="text/javascript"></script>

    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    {{--<script type="text/javascript" src="{{ url()->to('/admin') }}/assets/pages/autocomplete.js"></script>--}}


@endsection