@extends('frontend.master')
@section('style')
    <title>وورکی | تسویه حساب</title>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="cart-page-container cart-page">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div style="padding-top:30px" class="box cart-container products-wrapper table-responsive">
                                        <h1>سبد خرید</h1>
                                        <div class="col-xs-12">
                                            <div style="margin-bottom: 20px" class="alert alert-danger pink-alert text-center">
                                                هشدار ! خریدار گرامی ، شما 2 روز مهلت دارید کالای تحویل گرفته شده خود را تایید
                                                نمایید در غیر اینصورت مبلغ واریزی شما به حساب فروشنده واریز می گردد
                                            </div>
                                        </div>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width:200px;"></th>
                                                <th>نام کالا</th>
                                                <th>هزینه حمل به تهران</th>
                                                <th>هزینه حمل به شهرستان</th>
                                                <th>افزایش</th>
                                                <th>تعداد</th>
                                                <th>کاهش</th>
                                                <th>قیمت محصول</th>
                                                <th>تخفیف</th>
                                                <th>قیمت افزایشی محصول</th>
                                                <th>قیمت کل</th>
                                                <th>حذف کردن</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($carts as $cart)
                                                <tr>
                                                    <input type="hidden" class="cartId" value="{{ $cart->id }}">
                                                    <td>
                                                        @if($cart->photo != null)
                                                            <img src="{{ url()->to('/image/product_seller_photo/') }}/{{ $cart->photo }}"
                                                                 alt="{{ $cart->product_name }}" height="40px">
                                                        @else
                                                            <img src="{{ url()->to('/image/logo.png') }}"
                                                                 alt="{{ $cart->product_name }}" height="40px">
                                                        @endif
                                                    </td>
                                                    <td class="product-name-field">
                                                        <b>{{ $cart->product_name }}</b>
                                                        <ul>
                                                            @foreach($cart->attributesProduct as $attribute)
                                                                <li>{{ $attribute->attribute . ' ' . $attribute->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>{{ $cart->product->shipping_price_to_tehran }} تومان</td>
                                                    <td>{{ $cart->product->shipping_price_to_other_towns }} تومان</td>
                                                    <td>
                                                        <a href="{{ route('increase.cart.quantity' , $cart->id) }}"
                                                           class="increase">
                                                            <i class="fas fa-chevron-up text-success increase-count"></i>
                                                        </a>
                                                    </td>
                                                    <td class="quantity">{{ $cart->quantity }}</td>
                                                    <td>
                                                        <a href="{{ route('decrease.cart.quantity' , $cart->id) }}"
                                                           class="decrease">
                                                            <i class="fas fa-chevron-down text-danger decrease-count"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ number_format($cart->price) }} تومان</td>
                                                    <td>{{ $cart->discount }}%</td>
                                                    <td>{{ number_format($cart->sumAttrPrice) }} تومان</td>
                                                    <td class="totalPrice">{{ number_format($cart->totalPrice) }}
                                                        تومان
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('delete.cart' , $cart->id) }}"
                                                              method="post">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit" class="btn btn-xs btn-danger"><i
                                                                        class="fa fa-remove"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box customer-info-box">
                                        <form action="{{ route('cart.store') }}" method="post" class="form-horizontal"
                                              target="_blank">
                                            {{ csrf_field() }}
                                            <h2>اطلاعات فاکتور</h2>
                                            <div class="form-group">
                                                <label for="address" class="col-sm-2 control-label">آدرس شما:</label>
                                                <div class="col-xs-12 col-sm-8">
                                                    <select name="address" id="address" class="form-control">
                                                        <option value="0" disabled selected>::انتخاب کنید::</option>
                                                        @foreach($addresses as $address)
                                                            <option data-shipping-price="{{ $address->city_id == 118 ? $totalShippingPriceToTehran : $totalShippingPriceToOtherTowns }}"
                                                                    data-is-tehran="{{ $address->city_id == 118 ? 1 : 0  }}"
                                                                    value="{{ $address->id }}">{{ $address->address }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xs-12 col-sm-2">
                                                    <button id="addAddressButton" data-toggle="modal"
                                                            data-target="#addAddressModal"
                                                            type="button" class="btn btn-pink btn-xs"><i
                                                                class="fas fa-plus-circle"></i>
                                                        افزودن آدرس
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pay-type" class="col-sm-2 control-label">نحوه
                                                    پرداخت:</label>
                                                <div class="col-sm-10">
                                                    <select name="pay_type" id="pay-type" class="form-control">
                                                        <option value="online">پرداخت آنلاین</option>
                                                        <option value="wallet">کیف پول</option>
                                                    </select>
                                                    <p id="currentCharge"
                                                       style="display:none;color: #fc2a23;font-weight: bold;margin-top:10px;margin-bottom:5px;"
                                                       class="text-danger">شارژ کنونی کیف پول: {{ $userWallet }}
                                                        تومان</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="total_price" class="col-sm-2 control-label">مبلغ کل
                                                    محصولات:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" disabled id="sumPrice"
                                                           value="{{ number_format($sumPrice) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <label for="shippingPrice" class="col-sm-2 control-label">هزینه حمل:</label>
                                            <div class="col-sm-10">
                                                <input type="text" disabled id="shippingPrice" value=""
                                                       class="form-control">
                                            </div>
                                            </div>
                                            <div class="form-group">
                                            <label for="discount_code" class="col-sm-2 control-label">کد تخفیف</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="discount_code" id="discount_code" placeholder="(اختیاری)" value=""
                                                       class="form-control">
                                                <input type="text" id="discount" name="discount" hidden />
                                            </div>
                                            <div class="col-sm-2">
                                                <a id="apply-discount-button" class="btn btn-pink btn-xs">اعمال تخفیف</a>
                                            </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-pink btn-xs">ثبت سفارش</button>
                                                </div>
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


    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="">
                    <div class="modal-body">
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <i class="fas fa-map-marker-alt"></i>
                                افزودن آدرس جدید
                            </div>
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="province">استان</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-map-pin"></i>
                                                <select name="province" class="form-control" id="province">
                                                    <option disabled selected>::انتخاب کنید::</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="modal_city">شهر</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-map-pin"></i>
                                                <select name="modal_city" class="form-control" id="modal_city">

                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="modal_address">آدرس:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <input type="text" name="modal_address" id="modal_address"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="modal_postal_code">کد پستی:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-envelope-open"></i>
                                                <input type="text" name="modal_postal_code" id="modal_postal_code"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="modal_phone_number">تلفن تماس:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-phone"></i>
                                                <input type="text" name="modal_phone_number" id="modal_phone_number"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            <label for="modal_type">نوع محل:</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-building"></i>
                                                <select name="modal_type" class="form-control" id="modal_type">
                                                    <option value="home">خانه</option>
                                                    <option value="store">مغازه</option>
                                                    <option value="warehouse">انبار</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div>
                                            <input type="text" placeholder="نام مکان را جستجو کنید.." id="pac-input"
                                                   style="opacity:0.6;width: 420px;font-family: IRANSans;"
                                                   class="form-control">
                                        </div>
                                        <div id="map-canvas" style="width:100%;height:350px;">

                                        </div>

                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="createAddress" class="btn btn-pink pull-right btn-sm">ذخیره</button>
                        <button type="button" class="btn btn-gray pull-right btn-sm" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HbDPHxl75YCub0FIOxXroYRyRd_dm9U&language=fa&region=IR&libraries=places"></script>
    <script>
        var discount_used = false;
        function number_format (number, decimals, decPoint, thousandsSep) { 

            number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
            const n = !isFinite(+number) ? 0 : +number
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
            const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
            const dec = (typeof decPoint === 'undefined') ? '.' : decPoint
            let s = ''
            const toFixedFix = function (n, prec) {
                if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
                } else {
                const arr = ('' + n).split('e')
                let sig = ''
                if (+arr[1] + prec > 0) {
                    sig = '+'
                }
                return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
                }
            }
            // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || ''
                s[1] += new Array(prec - s[1].length + 1).join('0')
            }
            return s.join(dec)
            }
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
                    type : 'product-service',
                    id : "null",
                    code : discount_code,
                    address : $('#address').find('option:selected').val() == 0 ? null : $('#address').find('option:selected').val()
                },
                success: function (response) {
                    if(response.is_sending){
                        var $position = response.sumPrice;
                        var $this = $('#address');
                        var shippingPrice = $('#shippingPrice');
                        if ($position) {
                            shippingPrice.val('همراه با تخفیف ' + number_format($position.toString()) + ' تومان');
                        } else {
                            swal('خطا', 'لطفا ابتدا آدرس خود را مشخص کنید', 'error');
                            shippingPrice.val('');
                            return;
                        }
                        $('#discount').val(response.data.id);
                        discount_used = true;
                        swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');
                    }else{
                    var $position = response.sumPrice;
                    $('#sumPrice').val('قیمت همراه با تخفیف :' + number_format($position.toString()));
                    $('#discount').val(response.data.id);
                    discount_used = true;
                    swal('موفقیت آمیز', 'تخفیف مورد نظر با موفقیت اعمال شد', 'success');

                    }
                },
                error: function (data){
                    swal('خطا', 'کد تخفیف وارد شده معتبر نیست', 'error');

                }
            });
            }
        });
        $(document).ready(function () {

        
            $('#createAddress').click(function (e) {
                e.preventDefault();
                var _token = $('#_token').val();
                $.ajax({
                    url: '{{ route('user.address.createByAjax') }}',
                    type: 'post',
                    data: {
                        'city_id': $('#modal_city').val(),
                        'address': $('#modal_address').val(),
                        'postal_code': $('#modal_postal_code').val(),
                        'phone_number': $('#modal_phone_number').val(),
                        'type': $('#modal_type').val(),
                        'latitude': $('#latitude').val(),
                        'longitude': $('#longitude').val(),
                        '_token': _token,
                    },
                    success: function () {
                        $('#addAddressModal').modal('hide');
                        swal("ثبت موفقیت.", "ثبت آدرس با موفقیت انجام شد.", "success");

                        var address = $('#address');
                        $.ajax({
                            type: 'get',
                            url: '{{ route('get.user.address.by.ajax') }}',
                            data: {},

                            success: function (response) {
                                var shippingPrice = response.city_id == 118 ? '{{ $totalShippingPriceToTehran }}' : '{{ $totalShippingPriceToOtherTowns }}';
                                var isTehran = response.city_id == 118 ? 1 : 0;
                                address.append('<option data-is-tehran="'+ isTehran +'" data-shipping-price="'+ shippingPrice +'" value=" ' + response.id + ' ">' + response.address + '</option>');
                            }
                        })
                    }
                });
            });

            $('.total_price1').change(function () {
                alert('hi');
            });

            $('#province').change(function () {
                var province = $(this);
                var city = $('#modal_city');

                $.ajax({
                    url: '{{ route('userGetCityByAjax') }}',
                    type: 'get',
                    data: {
                        'province': province.val(),
                    },

                    success: function (response) {
                        city.html('<option disabled selected>::انتخاب کنید::</option>');
                        for (var i = 0; i < response.length; i++) {
                            city.append('<option value=" ' + response[i].id + ' ">' + response[i].name + '</option>');
                        }
                    }
                })
            });

        });

        $(document).on('keypress', '#pac-input', function (e) {
            if (e.keyCode == 13) {
                return false;
            }
        });

        var center = {
            lat: 32.646911,
            lng: 51.667878
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: center,
            zoom: 13
        });
        var removeLocationButton = document.getElementById('remove-location');
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });
        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
        var marker;

        function addMarker(location) {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }

        function setMapOnAll(map) {
            marker.setMap(map);
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function showMarkers() {
            setMapOnAll(map);
        }

        function deleteMarkers() {
            clearMarkers();
            marker = null;
        }

        function initMap() {
            map.addListener('click', function (e) {
                if (marker != undefined) {
                    clearMarkers();
                }
                marker = new google.maps.Marker({
                    position: e.latLng,
                    map: map
                });
                setMapOnAll(map);

                document.getElementById('latitude').value = e.latLng.lat();
                document.getElementById('longitude').value = e.latLng.lng();
            });
            removeLocationButton.addEventListener('click', function () {
                if (marker != undefined) {
                    clearMarkers();
                }
                document.getElementById('latitude').value = "";
                document.getElementById('longitude').value = "";
            });
        }

        window.onload = initMap;

        $('#pay-type').on('change ready', function () {
            var val = $(this).val();
            var current_charge = $('#currentCharge');
            if (val == 'wallet') {
                current_charge.css('display', 'block');
            } else {
                current_charge.css('display', 'none');
            }
        });

        $('#address').change(function () {
            var $this = $(this);
            var shippingPrice = $('#shippingPrice');
            if (!$this.is(':disabled')) {
                shippingPrice.val($this.find('option:selected').data('shipping-price') + ' تومان');
            } else {
                shippingPrice.val('');
            }
        });
    </script>
@endsection