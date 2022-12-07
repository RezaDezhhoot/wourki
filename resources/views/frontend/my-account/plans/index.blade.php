@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | خرید پلن اشتراک</title>
@endsection
@section('content')
@include('frontend.my-account.tabs')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="plans-index-page">
                    <h1 class="text-center">
                        پلن مورد نظر خود را انتخاب کنید
                    </h1>
                    @include('frontend.errors')
                    <form action="{{ route('verify.plan') }}" method="post" target="_blank">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width:40px;"></th>
                                    <th style="width:100px;">عنوان پلن</th>
                                    <th style="width:100px;">نوع پلن</th>
                                    <th style="width:100px;">بازه زمانی</th>
                                    <th style="width:100px;">قیمت</th>
                                    <th>توضیحات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($plans as $plan)
                                    <tr data-plan-price="{{ $plan->price }}">
                                        <td>
                                            <input class="plan-radio" type="radio"  name="plan" id="plan_{{ $plan->id }}"
                                                   data-price="{{$plan->price}}" value="{{ $plan->id }}">
                                        </td>
                                        <td>
                                            <label for="plan_{{ $plan->id }}">{{ $plan->plan_name }}</label>
                                        </td>
                                        <td>
                                            <label for="plan_{{ $plan->id }}">{{ $plan->type == 'store' ? 'عادی' : 'بازاریابی' }}</label>
                                        </td>
                                        <td>
                                            <label for="plan_{{ $plan->id }}">{{ $plan->month_inrterval }} ماه</label>
                                        </td>
                                        <td>
                                            @if($plan->price != 0)
                                                <label for="plan_{{ $plan->id }}">{{ number_format($plan->price) }}
                                                    تومان</label>
                                            @else
                                                <label style="font-weight: bold;" class="text-danger"
                                                       for="plan_{{ $plan->id }}">رایگان</label>
                                            @endif
                                        </td>
                                        <td>
                                            <label for="plan_{{ $plan->id }}">{{ $plan->description }}</label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                            <div class="form-group">
                                <div class="col-sm-10" class="form-control-wrapper">
                                    <input type="text" name="discount_code" id="discount_code" placeholder="کد تخفیف(اختیاری)"
                                        class="form-control">
                                    <input type="hidden" name="discount" id="discount" />
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-pink btn-sm" id="apply-discount-button">
                                        اعمال تخفیف
                                    </button>
                                </div>
                                <p class="text-danger error-container"></p>
                            </div>
                            </div>
                            <div class="row" style="margin-top : 10px">
                                <div class="form-group w-100">
                                    <div class="col-sm-12">
                                        <div class="alert alert-info">
                                            مبلغ قابل پرداخت:
                                            <span class="position-price"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment_type">
                                <span style="color: #fc2a23;font-weight:bold;">نحوه پرداخت:</span>
                                <label for="wallet">
                                    <input type="radio" name="pay_type" id="wallet" value="wallet">
                                    کیف پول
                                </label>
                                <label for="online_pay">
                                    <input type="radio" name="pay_type" id="online_pay" value="online">
                                    پرداخت آنلاین
                                </label>
                            </div>

                            <p id="wallet_current_charge" style="display:none;color: #fc2a23;font-weight:bold;margin-top:10px;margin-bottom:10px;">
                                موجودی کیف پول شما:
                                {{ $userWallet }} تومان
                            </p>
                            <div class="text-center">
                                <button type="submit" class="btn btn-pink btn-sm disabled" disabled="">
                                    @if($storeValidatePlan == false)خرید پلن
                                    @else تمدید پلن
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var discount_used = false;
        var discount_price = 0;
        var selected_price = 0;
        $('#apply-discount-button').click(function(e){
            e.preventDefault();
            if(!discount_used){
            var discount_code = $('#discount_code').val();
            
                $.ajax({
                type: 'get',
                url: '{{url()->to("api/discount/validate")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {
                    type : 'plan',
                    id : $('input[name="plan"]:checked').val(),
                    code : discount_code,
                    price : selected_price
                },
                success: function (response) {
                    swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                    var $price = selected_price;
                    $('.position-price').html((parseInt($price) * response.data.percentage / 100).toString() + ' - ' + $price + ' تومان');
                    $('#discount').val(response.data.id);
                    discount_used = true;
                },
                error: function (data){
                    swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                }
            });
            }
        });
            $('input[type=radio][name=plan]').change(function() {
                $('.position-price').html(`${$(this).data('price')} تومان`);
                var discount_used = false;
                selected_price = $(this).data('price');
            });
        $('#wallet , #online_pay').change(function () {
            var $this = $('#wallet');
            var current_charge = $('#wallet_current_charge');
            if($this.is(':checked')){
                current_charge.show(400);
            }else{
                current_charge.hide(400);
            }
        });
        $('input[name="plan"]').change(function () {
            var btn = $('button[type="submit"]');
            btn.removeAttr('disabled');
            btn.removeClass('disabled');
        });

        $('input[name="plan"]').change(function () {
            var $this = $(this);
            var wallet = $('#wallet');
            var online_pay = $('#online_pay');
            if($this.is(':checked')){
                var tr = $this.closest('tr');
                var plan_price = tr.data('plan-price');
                if(plan_price == 0 ){
                    wallet.attr('disabled' , 'disabled');
                    online_pay.attr('disabled' , 'disabled');
                }else{
                    wallet.removeAttr('disabled');
                    online_pay.removeAttr('disabled');
                }
            }
        });
    </script>
@endsection