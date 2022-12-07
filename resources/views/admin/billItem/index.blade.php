@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/bootstrap-datepicker.min.css') }}">

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

        .seller-info span, .seller-info a, .seller-info b {
            font-size: 11px;
        }

        .store-info span, .store-info b, .store-info a {
            font-size: 11px;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                @if($bill->confirmed == 0 or $bill->confirmed==2)
                    @if($bill->confirmed == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="row">
                                    </div>
                                    <form role="form"
                                          action="{{ route('bills.adminConfirmBill') }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="billId[]" value="{{$bill->id}}">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <button id="send" type="submit"
                                                        class="btn input-sm btn-success waves-effect waves-light"
                                                        onclick="return confirm('آیا از تایید این سفارش مطمئن هستید ؟')">
                                                    تایید
                                                    سفارش
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
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
                                <h4 class="m-t-0 header-title"><b>رد سفارش </b></h4><br>
                                <h5 class="m-t-0 header-title">کاربر مورد نظر روش باز پرداخت
                                    را {{$bill->user->returnPayType ==0  ? 'از طریق واریز به حساب بانکی':'شارژ کیف پول'}}
                                    انتخاب کرده است</h5>
                                <br>
                                @if($bill->user->returnPayType ==0)
                                    <h4 class="m-t-0 header-title"><b>شماره کارت : {{$bill->user->card}}</b></h4><br>
                                    <h4 class="m-t-0 header-title"><b>شماره شبا : {{$bill->user->shaba_code}}</b></h4>
                                    <br>
                                @endif
                                <form role="form" action="{{ route('bills.adminRejectBill',[$bill->id]) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{method_field('PATCH')}}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">انتخاب تاریخ</label>
                                                <input type="text" name="date" autocomplete="off"
                                                       value="{{$bill->confirmed == 2 ? \Morilog\Jalali\Jalalian::forge($bill->reject_pay_date)->format('Y/m/d H:i:s') : old('date') }}"
                                                       id="date" class="datepicker form-control input-sm"
                                                       placeholder="تاریخ را انتخاب کنید..." {{$bill->confirm == 2 ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="price">مبلغ</label>
                                                <input type="number" name="price"
                                                       value="{{$bill->confirmed == 2 ? $bill->reject_pay_price :old('price') }}"
                                                       id="price" class="form-control input-sm"
                                                       placeholder="مبلغ تسویه را وارد کنید..." {{$bill->confirm == 2 ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pay_id">شماره پیگیری</label>
                                                <input type="text" name="pay_id"
                                                       value="{{$bill->confirmed == 2 ? $bill->reject_pay_tracking_code : old('pay_id') }}"
                                                       id="pay_id" class="form-control input-sm"
                                                       placeholder="شماره پیگیری را وارد کنید..." {{$bill->confirm == 2 ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="reject_reason">علت</label>
                                                <input type="text" name="reject_reason"
                                                       value="{{$bill->confirmed == 2 ? $bill->reject_reason : old('reject_reason') }}"
                                                       id="reject_reason" class="form-control input-sm"
                                                       placeholder="علت را وارد کنید..." {{$bill->confirm == 2 ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input type="radio" name="reject_pay_type" value="1"
                                                       id="wallet"
                                                       @if(($bill->confirmed == 2 and $bill->reject_pay_type == 1) or ($bill->confirmed == 0 and $bill->user->returnPayType ==1))
                                                       checked="checked"
                                                        @endif {{($bill->confirmed == 2 and $bill->reject_pay_type == 0)  ? 'disabled' : ''}}>
                                                <label for="wallet">واریز به کیف پول</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="radio" name="reject_pay_type" value="0"
                                                       id="bank"
                                                       @if(($bill->confirmed == 2 and $bill->reject_pay_type == 0) or ($bill->confirmed == 0 and $bill->user->returnPayType ==0))
                                                       checked="checked"
                                                        @endif {{($bill->confirmed == 2 and $bill->reject_pay_type == 1)  ? 'disabled' : ''}}>
                                                <label for="bank">واریز به حساب بانکی</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    @if($bill->confirmed != 2)
                                        <div class="row">
                                            <div class="col-md-1">
                                                <button id="send" type="submit"
                                                        class="btn input-sm btn-danger waves-effect waves-light"
                                                        onclick="return confirm('آیا از رد این سفارش مطمئن هستید ؟')">رد
                                                    سفارش
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-4 store-info">
                                    @if($billItemsInfo != null)
                                        <span>نام فروشگاه :</span>
                                        <span style="color:#777">{{ $billItemsInfo->store_name }}</span><br>
                                        <span>نام خریدار : </span>
                                        <span style="color:#777">{{ $billItemsInfo->full_name }}</span><br>
                                        <span>صنف : </span>
                                        <span style="color:#777">{{ $billItemsInfo->guild_name }} {{ $billItemsInfo->last_name }}</span>
                                        <br>
                                        <span>شماره تماس فروشگاه : </span>
                                        <span style="color:#777">{{ $billItemsInfo->phone_number }}</span><br>
                                        <span>شماره تماس خریدار : </span>
                                        <span style="color:#777">{{ $billItemsInfo->mobile }}</span><br>
                                        <span>تاریخ صدور فاکتور :</span>
                                        <span style="color:#777">{{ \Morilog\Jalali\Jalalian::forge($billItemsInfo->created_at)->format('%d %B %Y') }}</span>
                                        <br>
                                        <span style="color: #fe6500">نوع پرداختی:</span>
                                        <span style="color:#777;">
                                        @if($billItemsInfo->billItem_pay_type == 'online')آنلاین
                                            @elseپستی
                                            @endif
                                        </span><br>
                                        <span>آدرس فروشنده : </span>
                                        <span style="color:#777">{{ $billItemsInfo->seller_address }}</span><br>
                                        <span>آدرس خریدار : </span>
                                        <span style="color:#777">{{ $billItemsInfo->buyer_address }}</span><br>
                                        <span style="color: #fe6500">تعداد اقلام : </span>
                                        <span style="color:#777">{{ $billItemsInfo->billItemCount }}</span><br>
                                        <span style="color: #fe6500">مبلغ کل:</span>
                                        <span style="color:#777">{{ $billItemsInfo->total_price }} تومان </span><br>
                                        <span style="color: #fe6500">وضعیت فعالیت : </span>
                                        <span style="color:#777;font-weight: bold;">
                                        @if($billItemsInfo->bill_status == 'delivered')
                                                <span class="text-success">تحویل داده شده</span>
                                            @elseif($billItemsInfo->bill_status == 'rejected')
                                                <span class="text-danger">رد شده</span>
                                            @elseif($billItemsInfo->bill_status == 'pending')
                                                <span class="text-info">در انتظار تایید</span>
                                            @elseif($billItemsInfo->bill_status == 'paid')
                                                <span class="text-primary">پرداخت شده</span>
                                            @endif
                                         </span><br>
                                </div>
                                @endif
                                <div class="col-md-8"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if(count($billItems) > 0)
                                        <div class="p-10">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام محصول</th>
                                                    <th>تعداد</th>
                                                    <th>قیمت</th>
                                                    <th>درصد تخفیف</th>
                                                    <th>قیمت کل</th>
                                                </tr>
                                                </thead>
                                                <?php $id = 1; ?>
                                                <tbody id="sortable-list">
                                                @foreach($billItems as $item)
                                                    <tr>
                                                        <th>{{ $id }}</th>
                                                        <th>{{ $item->product_name }}</th>
                                                        <th>{{ $item->quantity }}</th>
                                                        <th>{{ $item->price }} تومان</th>
                                                        <th>%{{ $item->discount  }}</th>
                                                        <th>{{ $item->total_price }}</th>
                                                    </tr>
                                                    <?php $id++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">
                                            محصولی برای این فاکتور ثبت نشده است!
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
@section('scripts')
    <script src="{{ url()->to('/admin/assets/js/bootstrap-datepicker.fa.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".datepicker").datepicker();
        });
    </script>
@endsection
