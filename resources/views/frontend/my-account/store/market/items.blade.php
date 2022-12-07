@extends('frontend.master')
@section('style')
    <style>
.sweet-overlay {
  z-index: 100000000000;
}
.sweet-alert {
  z-index: 100000000000;
}
        .btn-pink{
            border: 1px solid #fc2a23;
            color: #fff;
            background-color: #fc2a23;
            padding:10px 20px;
            transition: 500ms;
            margin : 5px 20px;
        }
        .btn-pink:hover{
            color : #fff;
        }
    </style>
    <title>وورکی | حساب کاربری من | محصولات و خدمات فروشگاه بازاریابی</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid products-list-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        @if(!$userStore)
                            <div class="alert alert-warning text-center">کاربر گرامی برای نمایش محصولات فروشگاه ابتدا باید فروشگاه
                                خود را ثبت کنید.
                            </div>
                        @else
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-box"></i>
                                    لیست محصولات و خدمات فروشگاه بازاریابی
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="showcase-tab">
                                            <div class="row">
                                            <div class="alert alert-warning" style="margin: 0 10px;">
                                                {!! nl2br('برای اضافه کردن محصول یا خدمت به فروشگاه بازاریابی ابتدا محصول یا خدمت مورد نظر را در سایت پیدا کنید و در صفحه ی نمایش آن بر روی گزینه "افزودن به فروشگاه بازاریابی" کلیک کنید') !!}
                                                <div class="text-center">
                                                <a href="{{ url()->to('search-list?search_in=product&keyword=') }}"
                                                   class="btn btn-xs btn-pink">صفحه محصولات</a>

                                                <a href="{{ url()->to('search-list?search_in=service&keyword=') }}"
                                                   class="btn btn-xs btn-pink">صفحه خدمات</a>
                                                </div>
                                            </div>
                                                @if(count($products) > 0)
                                                    @foreach($products as $product)
                                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                                            <div class="product-item-wrapper" data-link="{{url()->to('product/'.$product->id) . '?code=' . $userStore->id}}">
                                                                <a class="menu">
                                                                    <i class="fas fa-ellipsis-h"></i>
                                                                </a>
                                                                <ul class="list-unstyled ul-menu" style="display:none;">
                                                                        <li>
                                                                        <a class="copy-link" data-link="{{url()->to('product/'.$product->id) . '?code=' . $userStore->id}}">کپی لینک معرفی
                                                                            </a></li>
                                                                        <li>
                                                                        <a href="{{ route('market.products.delete' , ['product_id' => $product->id]) }}">حذف
                                                                            </a></li>
                                                                        <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-modal-{{$product->id}}" >ارتقا محصول / خدمت
                                                                            </a></li>
                                                                        <li>
                                                                        <a data-toggle="modal" data-target="#upgrade-history-modal-{{$product->id}}" href="">تاریخچه ارتقا محصول / خدمت
                                                                            </a></li>
                                                                        
                                                                </ul>
                                                                @php
                                                                   $commission = App\MarketCommission::where('category_id' , $product->category_id)->first()
                                                                @endphp
                                                                <span class="label label-success">میزان پورسانت : {{$commission ? $commission->amount : 0}}%</span>
                                                                @if($product->photo != null)
                                                                    <img src="{{ url()->to('/image/product_seller_photo/') }}/{{ $product->photo->file_name }}"
                                                                         alt="" class="img-thumbnail">
                                                                @else
                                                                    <img src="{{ url()->to('/image/logo.png') }}" alt=""
                                                                         class="img-thumbnail">
                                                                @endif

                                                                <div class="product-details">
                                                                    <p class="product-name">
                                                                        <a href="">{{ $product->name }}</a>
                                                                    </p>
                                                                    <p class="old-price">قیمت:
                                                                        @if($product->discount != 0)
                                                                            <del class="text-danger">{{ number_format($product->price) }}
                                                                                تومان
                                                                            </del>
                                                                        @endif
                                                                        <b class="new-price">
                                                                            {{ number_format($product->price - (($product->price * $product->discount) / 100)) }}
                                                                            تومان
                                                                        </b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach($products as $item)
    <div class="modal fade" id="upgrade-modal-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('upgrades.product.create') }}" method="POST"
                                  class="form-horizontal" enctype="multipart/form-data"> 
                                {{ csrf_field() }}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ارتقا محصول / خدمت
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                                    <div>                                                         
                                    <label for="position_id">انتخاب جایگاه:</label>
                                    <select name="position_id" id="position_id_{{$item->id}}" 
                                            class="form-control">
                                        @php
                                            $index = 0;
                                            $cond = true;
                                        @endphp
                                        @foreach($positions as $i => $position)
                                            @if(str_contains($position->position, $is_service ? "service" : "product"))
                                            <option data-id={{$position->id}} data-price="{{$position->price}}" {{$cond ? "selected" : ""}}  value="{{ $position->id }}">{{ $position->name }}</option>
                                            @php
                                                if($cond)
                                                $index = $i;

                                                $cond = false;
                                            @endphp
                                            @endif
                                        @endforeach
                                    </select>
                                    <div style="margin-top:20px">
                                    <label for="discount_code">کد تخفیف : </label>
                                    <div class="row" >
                                    <div class="col-sm-8" class="form-control-wrapper">
                                        <input type="text" name="discount_code" id="discount_code_{{$item->id}}" placeholder="(اختیاری)"
                                            class="form-control">
                                        <input type="hidden" name="discount" id="discount" />
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-pink apply-discount-button" data-id="{{$item->id}}">
                                            اعمال تخفیف
                                        </button>
                                    </div>
                                    </div>
                                    </div>
                                    <p style="margin-top : 20px" id="position_price_{{$item->id}}" class="alert alert-info">مبلغ قابل پرداخت : {{optional($positions[$index])->price}} تومان</p> 
                                    <input hidden type="number" name="product_seller_id"  value="{{$item->id}}" />
                                    <input hidden type="text" name="from_marketer"  value="true" />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="پرداخت آنلاین" />
                            <input type="submit" name="wallet" value="پرداخت کیف پول" class="btn btn-success" />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
             @php
             $upgrades = $item->upgrades()->where('upgrades.status' , 'approved')->whereNotNull('upgrades.from_marketer')->orderByDesc('upgrades.updated_at')->paginate(20);   
            @endphp
            <div class="modal fade" id="upgrade-history-modal-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">تاریخچه ارتقا محصول / خدمت
                                <b class="text-primary">{{ $item->product_name }}</b>
                            </h4>
                        </div>
                        <div class="modal-body">
                            @if(count($upgrades) > 0)
                             <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>جایگاه ارتقا</th>
                                        <th>روش پرداخت</th>
                                        <th>مبلغ پرداختی</th>
                                        <th>تاریخ  و ساعت ارتقا</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($upgrades as $upgrade)
                                    <tr>
                                        <td>{{ $upgrade->position->name}}</td>
                                        <td>{{ $upgrade->pay_type == "admin" ? "توسط مدیریت وورکی" : ($upgrade->pay_type == "wallet" ? "کیف پول" : ($upgrade->pay_type == "online" ? "آنلاین" : "پرداخت درون برنامه ای"))}}</td>
                                        <td>{{ $upgrade->price}}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($upgrade->updated_at)->format('H:i:s %d %B %Y') }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        {{ $upgrades->links() }}
                        @else
                            <p class="danger">این محصول تا به حال ارتقا داده نشده است</p>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
@endsection

@section('script')
<script>
            $(window).click(function () {
            $('.products-list-page .product-item-wrapper .ul-menu ').hide(300);
        });
        $('.products-list-page .product-item-wrapper .menu ').click(function (e) {
            e.stopPropagation();
            var $this = $(this);
            var ul = $this.closest('.product-item-wrapper').find('.ul-menu');
            ul.toggle(300);
        });


</script>
        <script>
        $('.copy-link').click(function(){
            navigator.clipboard.writeText($(this).data('link'));
            Toastify({
                text: "لینک معرفی کپی شد !",
                duration: 3000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "#FC2A23",
                    direction : 'rtl'
                },
                onClick: function(){} // Callback after click
            }).showToast();
        });
        @foreach($products as $item)
        $('#position_id_{{$item->id}}').on('change' , function(){
            $('#position_price_{{$item->id}}').html(`مبلغ قابل پرداخت : ${$('#position_id_{{$item->id}} option:selected').data('price')} تومان`)
        });
        var discount_used = false;
        var discount_price = 0;
        $('.apply-discount-button').click(function(e){
            e.preventDefault();
            if(!discount_used){
            var discount_code = $('#discount_code_' + $(this).data('id')).val();
            
                $.ajax({
                type: 'get',
                url: '{{url()->to("api/discount/validate")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {
                    type : 'upgrade',
                    id : $('#position_id_{{$item->id}} option:selected').data('id'),
                    code : discount_code,
                },
                success: function (response) {
                    swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                    var $position = $('#position_id option:selected').data('price');
                    if(response.data.type == 'percentage')
                    $('#position_price_{{$item->id}}').html('مبلغ قابل پرداخت : ' +(parseInt($position) * response.data.percentage / 100).toString() + ' - ' + $position + ' تومان');
                    else
                    $('#position_price_{{$item->id}}').html('مبلغ قابل پرداخت : ' + response.data.percentage.toString() + ' - ' + $position + ' تومان');
                    $('#discount').val(response.data.id);
                    discount_used = true;
                },
                error: function (data){
                    swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                }
            });
            }
        });
        @endforeach
    </script>
@endsection