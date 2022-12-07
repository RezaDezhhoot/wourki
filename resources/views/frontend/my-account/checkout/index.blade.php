@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | تسویه حساب</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid checkout-records-list">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-credit-card"></i>
                                    تسویه حساب
                                </div>
                                <div class="panel-body">
                                    <div class="panel-body">
                                        <form id="shaba-form" style="display: none;" action="" class="form-inline">
                                            <div class="form-group">
                                                <input type="hidden" id="store_id" value="{{ optional($store)->id }}">
                                                <label for="sheba_number">شماره شبای بانکی خود را بدون IR ابتدای آن وارد
                                                    کنید</label>
                                                <input type="text" id="shaba-number"
                                                       value="{{ optional($store)->shaba_code }}"
                                                       class="form-control" placeholder="شروع به نوشتن کنید...">
                                            </div>
                                            <button id="shaba-submit" class="btn btn-pink btn-bordered btn-sm">ثبت
                                            </button>
                                        </form>
                                        <button id="shaba-btn" style="display: inline-block;" type="button"
                                                class="btn btn-secondary btn-sm btn-pink btn-bordered">ثبت شماره شبای
                                            بانکی
                                        </button>
                                        <a href="{{route('user.checkoutRequest')}}" id="checkoutRequest"
                                           style="display: inline-block;" type="button"
                                           class="btn btn-secondary btn-sm btn-pink btn-bordered">درخواست تسویه
                                        </a>
                                        <div style="display: inline;margin-right:20px" class="form-group">
                                        <label for="store_type"> انتخاب فروشگاه:</label>
                                        <select  class="form-control" id="store_type">
                                            <option {{ request()->store_type == 'product' ? 'selected' : '' }} value="product">
                                                محصولات
                                            </option>
                                            <option {{ request()->store_type == 'service' ? 'selected' : '' }} value="service">
                                                خدمات
                                            </option>
                                            <option {{ request()->store_type == 'market' ? 'selected' : '' }} value="market">
                                                بازاریابی
                                            </option>
                                        </select>
                                    </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>شناسه سند حسابداری</th>
                                                    <th>شرح</th>
                                                    <th>بستانکار</th>
                                                    <th>بدهکار</th>
                                                    <th>نوع</th>
                                                    <th>شناسه صورت حساب</th>
                                                    <th>مبلغ تسویه حساب</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($lists)
                                                    @foreach($lists as $index => $list)
                                                        <tr>
                                                            <td>{{ $list->id }}</td>
                                                            <td>{{ $list->description }}</td>
                                                            @if($list->type == 'bill' || $list->type == 'commission')
                                                                <td class="success font-weight-bold">{{ number_format($list->balance) }}
                                                                    تومان
                                                                </td>
                                                            @else
                                                                <td class="font-weight-bold text-center">
                                                                    -
                                                                </td>
                                                            @endif

                                                            @if($list->type != 'bill' && $list->type != 'commission')
                                                                <td class="danger font-weight-bold">{{ number_format($list->balance) }}
                                                                    تومان
                                                                </td>
                                                            @else
                                                                <td class="font-weight-bold text-center">
                                                                    -
                                                                </td>
                                                            @endif


                                                            @if($list->type == 'bill' || $list->type == 'commission')
                                                                <th>خرید صورتحساب</th>
                                                            @elseif($list->type == "checkout")
                                                                <th>تسویه صورتحساب</th>
                                                            @else
                                                                <th>صورتحساب</th>
                                                            @endif

                                                            @if($list->type == 'bill' || $list->type == 'commission')
                                                                <td class="danger font-weight-bold">{{ $list->billPayID}}</td>
                                                            @else
                                                                <td class="font-weight-bold text-center">
                                                                    -
                                                                </td>
                                                            @endif
                                                            {{--<td class="font-weight-bold">--}}
                                                            {{--<a href="" target="_blank" class="font-weight-bold">#22344</a>--}}
                                                            {{--</td>--}}

                                                            @if($list->type == 'checkout')
                                                                <td class="font-weight-bold">{{ number_format($list->balance) }}
                                                                    تومان
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="5" class="success">
                                                        مانده:

                                                        <b class="font-weight-bold">{{ number_format($totalBalance) }} تومان</b>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        
        jQuery(document).ready(function () {

            $('#shaba-btn').click(function () {
                $('#shaba-form').css('display', 'block');
                $(this).css('display', 'none');
            });
            $('#store_type').change(function (){
            var value = $(this).find(":selected").val();
            window.location.href = "{{route('user.accounting.document').'?store_type='}}" + value;
            });
            $('#shaba-submit').click(function (e) {
                e.preventDefault();
                var shaba = $('#shaba-number').val();
                var store_id = $('#store_id').val();

                $.ajax({
                    type: 'post',
                    url: '{{ route('create.user.shaba.code') }}',
                    data: {
                        'shaba': shaba,
                        'store_id': store_id,
                        '_token': '{{ csrf_token() }}',
                    },

                    success: function () {
                        swal('موفقیت آمیز.', 'کد شبا با موفقیت ثبت شد.', 'success');
                    }
                });
            });

        });
    </script>
@endsection