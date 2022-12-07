@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/switch.css') }}">
    <style>
        #address-field-container {
            position: relative;
        }

        #address-field-container button {
            position: absolute;
            top: 6px;
            left: 11px;
            margin-top: -5px;
            height: 36px;
        }

        h1 {
            font-size: 16px;
        }

        input[type="checkbox"].ios8-switch {
            position: absolute;
            margin: 8px 0 0 16px;
        }

        input[type="checkbox"].ios8-switch + label {
            position: relative;
            padding: 5px 0 0 50px;
            line-height: 2.0em;
        }

        input[type="checkbox"].ios8-switch + label:before {
            content: "";
            position: absolute;
            display: block;
            left: 0;
            top: 0;
            width: 40px; /* x*5 */
            height: 24px; /* x*3 */
            border-radius: 16px; /* x*2 */
            background: #fff;
            border: 1px solid #d9d9d9;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }

        input[type="checkbox"].ios8-switch + label:after {
            content: "";
            position: absolute;
            display: block;
            left: 0px;
            top: 0px;
            width: 24px; /* x*3 */
            height: 24px; /* x*3 */
            border-radius: 16px; /* x*2 */
            background: #fff;
            border: 1px solid #d9d9d9;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }

        input[type="checkbox"].ios8-switch + label:hover:after {
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        input[type="checkbox"].ios8-switch:checked + label:after {
            margin-left: 16px;
        }

        input[type="checkbox"].ios8-switch:checked + label:before {
            background: #55D069;
        }

        /* SMALL */

        input[type="checkbox"].ios8-switch-sm {
            margin: 5px 0 0 10px;
        }

        input[type="checkbox"].ios8-switch-sm + label {
            position: relative;
            padding: 0 0 0 32px;
            line-height: 1.3em;
        }

        input[type="checkbox"].ios8-switch-sm + label:before {
            width: 25px; /* x*5 */
            height: 15px; /* x*3 */
            border-radius: 10px; /* x*2 */
        }

        input[type="checkbox"].ios8-switch-sm + label:after {
            width: 15px; /* x*3 */
            height: 15px; /* x*3 */
            border-radius: 10px; /* x*2 */
        }

        input[type="checkbox"].ios8-switch-sm + label:hover:after {
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
        }

        input[type="checkbox"].ios8-switch-sm:checked + label:after {
            margin-left: 10px; /* x*2 */
        }

        /* LARGE */

        input[type="checkbox"].ios8-switch-lg {
            margin: 10px 0 0 20px;
        }

        input[type="checkbox"].ios8-switch-lg + label {
            position: relative;
            padding: 7px 0 0 60px;
            line-height: 2.3em;
        }

        input[type="checkbox"].ios8-switch-lg + label:before {
            width: 50px; /* x*5 */
            height: 30px; /* x*3 */
            border-radius: 20px; /* x*2 */
        }

        input[type="checkbox"].ios8-switch-lg + label:after {
            width: 30px; /* x*3 */
            height: 30px; /* x*3 */
            border-radius: 20px; /* x*2 */
        }

        input[type="checkbox"].ios8-switch-lg + label:hover:after {
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
        }

        input[type="checkbox"].ios8-switch-lg:checked + label:after {
            margin-left: 20px; /* x*2 */
        }


    </style>
@endsection
@section('content')
    <div class="modal fade" tabindex="-1" id="add-address-modal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">افزودن آدرس برای کاربر: <b
                                class="text-success">{{ $user->first_name }} {{ $user->last_name }}</b></h4>
                </div>
                <form action="{{ route('admin.address.save_in_admin_panel') }}" method="post" id="saveAddressForm"
                      enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="province">استان:</label>
                                    <select name="province" id="province" class="form-control">
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="city">شهر:</label>
                                    <select name="city" id="city" class="form-control">

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="new_address">آدرس:</label>
                                    <input type="text" name="address" id="new_address" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="postal_code">کد پستی:</label>
                                    <input type="text" name="postal_code" id="postal_code" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="telephone">تلفن تماس:</label>
                                    <input type="text" name="telephone" id="telephone" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="place_type">نوع محل:</label>
                                    <select name="place_type" id="place_type" class="form-control">
                                        <option value="home">خانه</option>
                                        <option value="store">مغازه</option>
                                        <option value="warehouse">انبار</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" id="remove-location" class="btn btn-danger btn-sm">حذف موقعیت
                                </button>
                                <div>
                                    <input type="text" placeholder="نام مکان را جستجو کنید.." id="pac-input"
                                           style="opacity:0.6;width: 420px;font-family: IRANSans;"
                                           class="form-control">
                                </div>
                                <div id="map-canvas" style="width:100%;height:300px;"></div>
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">ثبت آدرس</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="card-box">
                    <h1 class="text-center">ساخت فروشگاه برای کاربر
                        <span class="text-success">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </h1>
                    <form class="form-horizontal" action="{{ route('admin.store.save') }}" method="POST"
                          enctype="multipart/form-data" id="FormSaveStore">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xs-10">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <div class="form-group">
                                            <label for="store_name" class="col-sm-3 control-label">نام فروشگاه:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="store_name" value="{{ old('store_name') }}"
                                                       id="store_name"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_slogan" class="col-sm-3 control-label">شعار
                                                فروشگاه:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="store_slogan" value="{{ old('store_slogan') }}"
                                                       id="store_slogan" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_type" class="col-sm-3 control-label">نوع فروشگاه:</label>
                                            <div class="col-sm-9">
                                                <select name="store_type" id="store_type" class="form-control">
                                                    <option value="product">محصولات</option>
                                                    <option value="service">خدمات</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="guild" class="col-sm-3 control-label">صنف:</label>
                                            <div class="col-sm-9">
                                                <select name="guild" id="guild" class="form-control">
                                                    @foreach($guilds as $guild)
                                                        <option value="{{ $guild->id }}">{{ $guild->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="address-field-container">
                                            <label for="address" class="col-sm-3 control-label">آدرس:</label>
                                            <div class="col-sm-9">
                                                <select name="address" id="address" class="form-control">
                                                    @foreach($addresses as $address)
                                                        <option value="{{ $address->id }}">{{ $address->city->province->name }}
                                                            - {{ $address->city->name }}
                                                            - {{ $address->address }}</option>
                                                    @endforeach
                                                </select>
                                                <button data-toggle="modal" data-target="#add-address-modal"
                                                        type="button"
                                                        class="btn btn-danger"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="username" class="col-sm-3 control-label">نام کاربری
                                                فروشگاه:</label>
                                            <div class="col-sm-9">
                                                <input dir="ltr" type="text" name="username" id="username"
                                                       class="form-control"
                                                       autocomplete="off">
                                                <span class="text-danger" id="username_exists_or_not_text"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="min_pay" class="col-sm-3 control-label">حداقل مبلغ خرید از
                                                فروشگاه:</label>
                                            <div class="col-sm-9">
                                                <input type="number" min="0" step="1000" name="min_pay" id="min_pay"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="col-sm-3 control-label">وضعیت تایید:</label>
                                            <div class="col-sm-9">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="pending">در انتظار تایید</option>
                                                    <option value="approved">تایید شده</option>
                                                    <option value="rejected">رد شده</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="visible" class="col-sm-3 control-label">وضعیت نمایش:</label>
                                            <div class="col-sm-9">
                                                <select name="visible" id="visible" class="form-control">
                                                    <option value="yes">بله</option>
                                                    <option value="no">خیر</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="about" class="col-sm-3 control-label">درباره فروشگاه:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="about" id="about" cols="30"
                                                          rows="4"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tel" class="col-sm-3 control-label">تلفن تماس:</label>
                                            <div class="col-sm-9">
                                                <input type="tel" dir="ltr" name="tel" id="tel" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="thumbnail_photo" class="col-sm-3 control-label">تصویر
                                                بندانگشتی:</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="thumbnail_photo" id="thumbnail_photo"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number_visibility" class="col-sm-3 control-label">نمایش
                                                شماره تماس:</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" name="phone_number_visibility"
                                                       id="phone_number_visibility"
                                                       class="phone-number-visibility-checkbox-switch">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile_visibility" class="col-sm-3 control-label">نمایش شماره
                                                همراه:</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" name="mobile_visibility" id="mobile_visibility"
                                                       class="mobile-visibility-checkbox-switch">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pay_type" class="col-sm-3 control-label">نحوه پرداخت:</label>
                                            <div class="col-sm-9">
                                                <select name="pay_type" id="pay_type" class="form-control">
                                                    <option value="online">آنلاین</option>
                                                    <option value="postal">پستی</option>
                                                    <option value="both">هر دو</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="activity_type" class="col-sm-3 control-label">حوزه
                                                فعالیت:</label>
                                            <div class="col-sm-9">
                                                <select name="activity_type" id="activity_type" class="form-control">
                                                    <option value="country">در کشور</option>
                                                    <option value="province">در استان</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="shaba_code" class="col-sm-3 control-label">شماره شبای
                                                بانکی:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="shaba_code" id="shaba_code"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="photo_1" class="col-sm-3 control-label">تصویر قاب:</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="photo[]" id="photo_1" class="form-control">
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <div class="col-sm-9 col-sm-offset-3">
                                            <button type="submit" class="btn btn-primary">ثبت فروشگاه</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url()->to('/admin/assets/js/switch.js') }}"></script>
    <script>
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
        $(document).ready(function () {
            var province = $('#province');
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.get_cities_by_province_id') }}',
                data: {
                    province: province.val()
                },
                success: function (response) {
                    var city = $('#city');
                    city.html('');
                    for (var i = 0; i < response.length; i++) {
                        city.append('<option value="' + response[i].id + '">' + response[i].name + '</option>');
                    }
                }
            });
        });
        $('#province').change(function () {
            var $this = $(this);
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.get_cities_by_province_id') }}',
                data: {
                    province: $this.val()
                },
                success: function (response) {
                    var city = $('#city');
                    city.html('');
                    for (var i = 0; i < response.length; i++) {
                        city.append('<option value="' + response[i].id + '">' + response[i].name + '</option>');
                    }
                }
            });
        });

        $('#saveAddressForm').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function (response) {
                    var address = $('#address');
                    address.append('<option value="' + response.address.id + '">' + response.address.address + '</option>');
                    $('#add-address-modal').modal('hide');
                }
            });
        });

        $('#username').keyup(function () {
            var val = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('check_username_duplication') }}',
                data: {
                    username: val
                },
                success: function (response) {
                    var hint = $('#username_exists_or_not_text');
                    if (response.status == 400) {
                        hint.html('نام کاربری از قبل ثبت شده است.').addClass('text-danger').removeClass('text-success');
                    } else if (response.status == 200) {
                        hint.html('نام کاربری در دسترس است.').addClass('text-success').removeClass('text-danger');
                    }
                }
            });
        });

        $('#FormSaveStore').validate({
            rules: {
                store_name: {
                    required: true,
                    maxlength: 200
                },
                store_slogan: {
                    required: true
                },
                guild: {
                    required: true,
                    number: true
                },
                address: {
                    required: true,
                    number: true
                },
                username: {
                    required: true
                },
                min_pay: {
                    required: true,
                    number: true,
                    min: 0
                },
                status: {
                    required: true
                },
                visible: {
                    required: true
                },
                about: {
                    required: true,
                },
                tel: {
                    required: true,
                    number: true,
                },
                telegram: {
                    required: false,
                    url: true
                },
                instagram: {
                    required: false,
                    url: true
                },
                phone_number_visibility: {
                    required: true
                },
                telegram_visibility: {
                    required: true
                },
                mobile_visibility: {
                    required: true
                },
                instagram_visibility: {
                    required: true
                },
                pay_type: {
                    required: true
                },
                activity_type: {
                    required: true
                },
                shaba_code: {
                    maxlength: 26
                }
            },
            messages: {
                store_name: {
                    required: 'نام فروشگاه الزامی است.',
                    maxlength: 'نام فروشگاه حداکثر می تواند 200 کاراکتر باشد.'
                },
                store_slogan: {
                    required: 'شعار فروشگاه الزامی است.'
                },
                guild: {
                    required: 'انتخاب صنف الزامی است.',
                    number: 'صنف نامعتبر است.'
                },
                address: {
                    required: 'انتخاب آدرس الزامی است.',
                    number: 'آدرس نامعتبر است.'
                },
                username: {
                    required: 'وارد کردن نام کاربری الزامی است.'
                },
                min_pay: {
                    required: 'حداقل مبلغ خرید از فروشگاه الزامی است.',
                    number: 'حداقل مبلغ خرید از فروشگاه نامعتبر است.',
                    min: 'کمترین میزان خرید از فروشگاه حداقل باید صفر باشد.'
                },
                status: {
                    required: 'انتخاب وضعیت فروشگاه الزامی است.'
                },
                visible: {
                    required: 'نمایش فروشگاه الزامی است.'
                },
                about: {
                    required: 'درباره فروشگاه الزامی است.'
                },
                tel: {
                    required: 'تلفن تماس الزامی است.',
                    number: 'تلفن تماس نامعتبر است.'
                },
                telegram: {
                    url: 'آدرس تلگرام نامعتبر است.'
                },
                instagram: {
                    url: 'آدرس اینستاگرام نامعتبر است.'
                },
                phone_number_visibility: {
                    required: 'انتخاب فلید نمایش عمومی تلفن تماس الزامی است.'
                },
                telegram_visibility: {
                    required: 'انتخاب فلید نمایش عمومی تلگرام الزامی است.'
                },
                mobile_visibility: {
                    required: 'انتخاب فلید نمایش عمومی تلفن همراه الزامی است.'
                },
                instagram_visibility: {
                    required: 'انتخاب فلید نمایش عمومی اینستاگرام الزامی است.'
                },
                pay_type: {
                    required: 'انتخاب فیلد نحوه پرداخت الزامی است.'
                },
                activity_type: {
                    required: 'انتخاب فیلد محدوده فعالیت الزامی است.'
                },
                shaba_code: {
                    maxlength: 'فیلد شماره شبای فروشنده طولانی تر از حد مجاز است.'
                }
            },
            errorClass: 'text-danger'
        });

        var phone_number_visibility = document.querySelector('.phone-number-visibility-checkbox-switch');
        var phone_number_switch = new Switch(phone_number_visibility);

        var mobile_number_visibility = document.querySelector('.mobile-visibility-checkbox-switch');
        var mobile_switch = new Switch(mobile_number_visibility);


    </script>
@endsection