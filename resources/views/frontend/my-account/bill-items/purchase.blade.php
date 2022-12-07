@extends('frontend.master')
@section('style')

@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid purchase-bill-item-page">
        <div class="row">
            <div class="col-xs-12">
                <div class="map-wrapper">
                    <div class="bill-info-box">
                        <p>
                            <i class="fas fa-hashtag"></i>
                            <span class="title">شناسه فاکتور:</span>
                            <span class="description">{{ $bill->id }}</span>
                        </p>
                        <p>
                            <i class="fas fa-store"></i>
                            <span class="title">نام فروشگاه:</span>
                            <span class="description">{{ $bill->store }}</span>
                        </p>
                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="title">آدرس فروشگاه:</span>
                            <span class="description">{{ $bill->address }}</span>
                        </p>
                        <p>
                            <i class="fas fa-credit-card"></i>
                            <span class="title">نحوه پرداخت:</span>
                            <span class="description">
                                @if($bill->pay_type == 'online') آنلاین
                                @elseif($bill->pay_type == 'wallet') کیف پول
                                @else نقدی
                                @endif
                            </span>
                        </p>
                        <p>
                            <i class="fas fa-dollar-sign"></i>
                            <span class="title">شماره پیگیری پرداخت:</span>
                            <span class="description">{{ $bill->pay_id }}</span>
                        </p>
                        <p>
                            <i class="fas fa-info"></i>
                            <span class="title">وضعیت:</span>
                            <span class="description">
                                @if($bill->status == 'pending') در انتظار تایید
                                @elseif($bill->status == 'delivered') تحویل داده شده
                                @elseif($bill->status == 'rejected') رد شده
                                @elseif($bill->status == 'paid_back') هزینه بازگشت داده شده
                                @else تایید شده
                                @endif
                            </span>
                        </p>
                        <p>
                            <i class="fas fa-cart-plus"></i>
                            <span class="title">تعداد اقلام:</span>
                            <span class="description">{{ count($billItems) }}</span>
                        </p>
                        <p>
                            <i class="fas fa-calendar-alt"></i>
                            <span class="title">تاریخ صدور فاکتور:</span>
                            <span class="description">{{ \Morilog\Jalali\Jalalian::forge($bill->created_at)->format('%d %B %Y') }}</span>
                        </p>

                    </div>
                    <div id="map-canvas" style="width:100%;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="wrapper">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>نام محصول</th>
                                <th>تعداد اقلام</th>
                                <th>قیمت واحد</th>
                                <th>تخفیف</th>
                                <th>قیمت کل</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($billItems as $billItem)
                                <input type="hidden" class="price" value="{{ $billItem->totalPrice }}">
                                <input type="hidden" class="shipping" value="{{ $billItem->shipping_price }}">
                                <tr>
                                    <td>{{ $billItem->product_name }}</td>
                                    <td>{{ $billItem->quantity }}</td>
                                    <td>{{ number_format($billItem->price) }} تومان</td>
                                    <td>{{ $billItem->discount != 0 ? $billItem->discount : 0 }}</td>
                                    <td>{{ number_format($billItem->totalPrice) }} تومان</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="1">هزینه پست :
                                    <b class="shipping-price"></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="1">قیمت کل :
                                    <b class="total-price"></b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTXMyHrMop4Xg485ElkARgV8-Jf75zUQU&language=fa&region=IR&libraries=places"></script> --}}
    <script>

        $(document).ready(function () {
            var total = 0;
            $('.price').each(function () {
                var price = $(this);
                var value = price.val();
                total = Number(total) + Number(value);
            });
            var shipping = 0;
            $('.shipping').each(function () {
                var price = $(this);
                var value = price.val();
                shipping = Number(shipping) + Number(value);
            });
            $('.shipping-price').html(shipping.toLocaleString() + " تومان ");
            $('.total-price').html((total + shipping).toLocaleString() + " تومان ");
        });

        var center = {
            lat: 32.660593,
            lng: 51.685317
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: center,
            zoom: 10
        });
        var marker = new google.maps.Marker({
            position: center,
            map: map
        });
    </script>
@endsection