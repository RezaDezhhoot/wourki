@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | ثبت فروشگاه</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid save-store-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <form action="{{ route('stores.store') }}" method="post" class="form-horizontal"
                          enctype="multipart/form-data" id="editStoreForm">
                        {{ csrf_field() }}
                        <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                            @if(!$hasSubscription)
                                <div class="alert alert-warning text-center">کاربر گرامی، برای ثبت فروشگاه ابتدا باید
                                    پلن اشتراک تهیه کنید.
                                </div>
                            @elseif(!$hasAddress)
                                <div class="alert alert-warning text-center">
                                    کاربر گرامی برای ثبت فروشگاه ابتدا باید حداقل یک آدرس ثبت کنید.
                                </div>
                            @else
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fas fa-store"></i>
                                        مشخصات عمومی فروشگاه
                                    </div>
                                    <div class="panel-body">
                                        @include('frontend.errors')
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label data-toggle="tooltip"
                                                                   title="کاملا واضح است! نام فروشگاه برای معرفی فروشگاه شما استفاده می شود."
                                                                   for="storeName" class="col-sm-4 control-label">نام
                                                                فروشگاه</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="store_name" id="storeName"
                                                                       class="form-control">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="slogan" data-toggle="tooltip"
                                                                   title="متنی است یک خطی که برای معرفی فروشگاه شما به دیگران استفاده می شود و حداکثر می تواند 30 کاراکتر باشد."
                                                                   class="col-sm-4 control-label">شعار فروشگاه</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="slogan" id="slogan"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label data-toggle="tooltip"
                                                                   title="همان صنفی است که شما می خواهید در آن فعالیت کنید. زمینه فعالیت پس از ثبت فروشگاه غیر قابل تغییر خواهد بود."
                                                                   for="guild" class="col-sm-4 control-label">زمینه
                                                                فعالیت</label>
                                                            <div class="col-sm-8">
                                                                <select name="guild" id="guild" class="form-control">
                                                                    <option disabled selected>::انتخاب کنید::</option>
                                                                    @foreach($guilds as $guild)
                                                                        <option value="{{ $guild->id }}">{{ $guild->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label data-toggle="tooltip"
                                                                   title="نام کاربری به عنوان شناسه فروشگاه شما شناخته می شود."
                                                                   for="username" class="col-sm-4 control-label">نام
                                                                کاربری</label>
                                                            <div class="col-sm-8 username-container">
                                                                <input type="text" name="username" id="username"
                                                                       class="form-control">
                                                                <i class="fas fa-check-circle"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="form-group">
                                                            <label data-toggle="tooltip"
                                                                   title="متن کوتاهی بنویسید تا مخاطبینتان متوجه شوند فروشگاه شما چیست، چه کار می کند و چه محصولاتی عرضه می کند."
                                                                   for="about_store" class="control-label col-sm-2">درباره
                                                                فروشگاه</label>
                                                            <div class="col-sm-10">
                                                    <textarea name="about" id="about_store" cols="30"
                                                              rows="5" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label data-toggle="tooltip"
                                                                   title="این گزینه به شما امکان می دهد که فروشگاه را به طور موقت از دید مشتریان خود مخفی کنید. این گزینه برای زمانی مفید است که می خواهید به طور موقت از مشتریان تان سفارش نگیرید."
                                                                   for="visible">نمایش فروشگاه به مشتریان</label>
                                                            <input type="checkbox" name="visible" id="visible"
                                                                   class="switchery" checked/>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label data-toggle="tooltip"
                                                                   title="با قراردادن مقدار این گزینه روی استان محل فعالیت خودتان، فقط مشتریانی به شما سفارش می دهند که آدرس آن ها درون محدوده استان فروشگاه شما باشد. در صورتی که مقدار این گزینه روی کل کشور قرار بگیرد، شما می توانید از کل کشور سفارش بگیرید."
                                                                   for="activity_type" class="control-label col-sm-4">محدوده
                                                                فعالیت شما:</label>
                                                            <div class="col-sm-8">
                                                                <select name="activity_type" id="activity_type"
                                                                        class="form-control">
                                                                    <option value="province">استان محل فعالیت خودتان
                                                                    </option>
                                                                    <option value="country">کل کشور</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fas fa-dollar-sign"></i>
                                        مالی و حسابداری
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label data-toggle="tooltip"
                                                           title="این فیلد حداقل مبلغ سفارش خریداران را مشخص می کند. خریداران نمی توانند فاکتورهایی کمتر از این مبلغ برای  شما ارسال کنند."
                                                           for="min_pay" class="col-sm-5 control-label">حداقل مبلغ خرید
                                                        از
                                                        فروشگاه (تومان):

                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="number" min="1000" class="form-control"
                                                               id="min_pay"
                                                               name="min_pay">
                                                    </div>
                                                </div>
                                            </div>
                                            {{--<div class="col-xs-12 col-md-6">
                                                <div class="form-group">
                                                    <label data-toggle="tooltip"
                                                           title="این فیلد مشخص می کند کاربران به چه روش هایی می توانند پرداخت خود را انجام دهند."
                                                           class="control-label col-sm-4">نحوه پرداخت</label>
                                                    <div class="col-sm-8">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_type"
                                                                   id="payment_type_online" value="online">
                                                            آنلاین
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_type"
                                                                   id="payment_type_postal" value="postal">
                                                            پستی
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="payment_type"
                                                                   id="payment_type_both" value="both">
                                                            هر دو
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>--}}

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default communication-ways">
                                    <div class="panel-heading">
                                        <i class="fas fa-user-circle"></i>
                                        راه های ارتباطی با مشتریان
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-8 col-md-10">
                                                <div class="form-group">
                                                    <label for="telephone_number" class="control-label col-sm-2">تلفن
                                                        تماس</label>
                                                    <div class="col-sm-10">
                                                        <input type="tel" name="telephone_number" id="telephone_number"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-md-2">
                                                <div class="form-group btn-wrapper">
                                                    <label data-toggle="tooltip"
                                                           title="این گزینه مشخص می کند که شماره تماس به مشتریان نمایش داده بشود یا خیر."
                                                           for="show_telephone_number" data-toggle-checkbox
                                                           class="checkbox-inline btn btn-pink btn-bordered hover-without-style btn-xs">
                                                        <input type="checkbox" name="show_telephone_number"
                                                               id="show_telephone_number">
                                                        <span>نمایش تلفن تماس</span>
                                                    </label>
                                                </div>
                                            </div>


                                            {{--<div class="col-xs-8 col-md-4">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label for="mobile_number" class="control-label col-sm-4">تلفن--}}
                                            {{--همراه</label>--}}
                                            {{--<div class="col-sm-8">--}}
                                            {{--<input type="tel" name="mobile_number" id="mobile_number"--}}
                                            {{--class="form-control">--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-xs-4 col-md-2">--}}
                                            {{--<div class="form-group btn-wrapper">--}}
                                            {{--<label data-toggle="tooltip"--}}
                                            {{--title="این گزینه مشخص می کند که تلفن همراه به مشتریان نمایش داده بشود یا خیر."--}}
                                            {{--for="show_mobile_number" data-toggle-checkbox--}}
                                            {{--class="checkbox-inline btn btn-pink btn-bordered hover-without-style btn-xs">--}}
                                            {{--<input type="checkbox" name="show_mobile_number"--}}
                                            {{--id="show_mobile_number">--}}
                                            {{--<span>نمایش تلفن همراه</span>--}}
                                            {{--</label>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            <div class="col-xs-8 col-md-10">
                                                <div class="form-group">
                                                    <label data-toggle="tooltip"
                                                           title="کاملا واضح است! آدرس محل فعالیت فروشگاه!"
                                                           for="address"
                                                           class="control-label col-sm-2">انتخاب آدرس</label>
                                                    <div class="col-sm-10">
                                                        <select name="address" id="address" class="form-control">
                                                            <option disabled selected>::انتخاب کنید::</option>
                                                            @foreach($addresses as $address)
                                                                <option value="{{ $address->id }}">{{ $address->address }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-md-2">
                                                <div class="form-group select-address">
                                                    <a data-toggle="modal" data-target="#addAddressModal"
                                                       class="btn btn-pink btn-sm"><i class="fas fa-plus-circle"></i>&nbsp;
                                                        افزودن آدرس</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 text-center submit-btn-wrapper">
                                                <button name="store_type" value="product" type="submit" class="btn btn-pink btn-sm btn-bordered">ثبت
                                                    فروشگاه
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
    <script>
        $('#addAddressModal').on('shown.bs.modal', function () {
            window.resizeBy(1, 1);
        });
    </script>

    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HbDPHxl75YCub0FIOxXroYRyRd_dm9U&language=fa&region=IR&libraries=places"></script> --}}
    <script>
        $(document).ready(function () {

            $('#username').keyup(function () {
                var username = $(this);
                username.removeAttr("style");
                $.ajax({
                    url: '{{ route('stores.check.username') }}',
                    type: 'get',
                    data: {
                        'username': username.val(),
                    },
                    success: function () {
                        username.css('color', 'red');
                        username.css('font-weight', 'bold');
                    }

                });
            });

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
                    }
                });

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

      
        jQuery.validator.addMethod('CustomUrl', function (value, element) {
            if (!value) {
                return true;
            }
            var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
            var regex = new RegExp(expression);
            if (value.match(regex)) {
                return true;
            }
            return false;
        }, 'url نامعتبر است.');
        $('#editStoreForm').validate({
            rules: {
                store_name: {
                    required: true,
                    maxlength: 200
                },
                slogan: {
                    required: true,
                    maxlength: 300
                },
                guild: {
                    required: true,
                    number: true
                },
                about: {
                    required: true,
                    maxlength: 500
                },
                min_pay: {
                    required: true,
                    min: 1000
                },
                telephone_number: {
                    required: true,
                    digits: true
                },
                telegram_address: {
                    CustomUrl: true
                },
                instagram_address: {
                    CustomUrl: true
                },
                address: {
                    required: true
                }
            },
            messages: {
                store_name: {
                    required: 'نام فروشگاه الزامی است.',
                    maxlength: 'نام فروشگاه طولانی تر از حد مجاز است.'
                },
                slogan: {
                    required: 'شعار فروشگاه الزامی است.',
                    maxlength: 'شعار فروشگاه طولانی تز از حد مجاز است.'
                },
                guild: {
                    required: 'زمینه فعالیت الزامی است.',
                    number: 'زمینه فعالیت نامعتبر است.'
                },
                about: {
                    required: 'درباره فروشگاه الزامی است.',
                    maxlength: 'درباره فروشگاه طولانی تر از حد مجاز است.'
                },
                min_pay: {
                    required: 'حداقل مبلغ خرید از فروشگاه الزامی است.',
                    min: 'حداقل مبلغ خرید از فروشگاه باید 1000 تومان باشد.'
                },
                telephone_number: {
                    required: 'تلفن تماس الزامی است.',
                    digits: 'تلفن تماس باید متشکل از ارقام باشد.'
                },
                address: {
                    required: 'انتخاب آدرس الزامی است.'
                }
            },
            errorPlacement: function (error, element) {
                var placeholder = element.closest('.form-group').find('.text-danger.error-container');
                placeholder.html(error.text());
            }
        })
    </script>
@endsection