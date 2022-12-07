@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | افزودن آدرس</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid my-account-tabs-content ">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-map-marker-alt"></i>
                                افزودن آدرس جدید
                            </div>
                            <div class="panel-body">
                                @include('frontend.errors')
                                <form action="{{ route('user.address.create') }}" method="post" id="saveAddressForm">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="province">استان</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-map-pin"></i>
                                                    <select required name="province" class="form-control" id="province">
                                                        <option disabled selected>::انتخاب کنید::</option>
                                                        @foreach($provinces as $province)
                                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="city">شهر</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-map-pin"></i>
                                                    <select required name="city" class="form-control" id="city">

                                                    </select>
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="address">آدرس:</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <input required type="text" name="address" id="address"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="postal_code">کد پستی:</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-envelope-open"></i>
                                                    <input type="text" name="postal_code" id="postal_code"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="phone_number">تلفن تماس:</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-phone"></i>
                                                    <input type="text" name="phone_number" id="phone_number" required
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="type">نوع محل:</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-building"></i>
                                                    <select required name="type" class="form-control" id="type">
                                                        <option value="home">خانه</option>
                                                        <option value="store">مغازه</option>
                                                        <option value="warehouse">انبار</option>
                                                    </select>
                                                </div>
                                                <p class="text-danger error-container"></p>

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
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            <button type="submit" class="btn btn-pink btn-bordered">ثبت آدرس</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-map-marker-alt"></i>
                                آدرس های من
                            </div>
                            <div class="panel-body">
                                <div class="address-item-container">
                                    @foreach($addresses as $address)
                                        <ul class="list-unstyled address-item">
                                            <li>
                                                <i class="fas fa-map-marker-alt"></i>
                                                <p>استان {{ $address->provinceName }} - شهر {{ $address->cityName }}</p>
                                            </li>
                                            <li>
                                                <i class="fas fa-map-marker-alt"></i>
                                                <p>
                                                    آدرس : {{ $address->address }}
                                                </p>
                                            </li>
                                            <li>
                                                <i class="fas fa-envelope"></i>
                                                <p>
                                                    کد پستی : {{ $address->postal_code }}
                                                </p>
                                            </li>
                                            <li>
                                                <i class="fas fa-phone"></i>
                                                <p>
                                                    تلفن تماس : {{ $address->phone_number }}
                                                </p>
                                            </li>
                                            <li>
                                                <i class="fas fa-building"></i>
                                                <p>
                                                    نوع محل :
                                                    @if($address->type == 'home')  خانه
                                                    @elseif($address->type == 'store') فروشگاه
                                                    @elseانبار
                                                    @endif
                                                </p>
                                            </li>
                                            <li>
                                                <a href="{{ route('user.address.edit' , $address->id) }}"
                                                   class="btn btn-pink btn-border-hover btn-sm option-buttons"><i
                                                            class="fas fa-pen"></i> ویرایش</a>
                                                <a href="{{ route('user.address.delete' , ['address' => $address->id]) }}"
                                                   class="btn btn-pink btn-border-hover btn-sm option-buttons"><i
                                                            class="fas fa-trash-alt"></i> حذف</a>
                                            </li>
                                        </ul>
                                        <hr>
                                    @endforeach
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
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8HbDPHxl75YCub0FIOxXroYRyRd_dm9U&language=fa&region=IR&libraries=places"></script> --}}
    <script>
        $(document).ready(function () {

            $('#province').change(function () {
                var province = $(this);
                var city = $('#city');

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
        jQuery.validator.addMethod('postalCodeExact10Chars' , function(value , element){
            if(!value){
                return true;
            }
            if(isNaN(value)){
                return false;
            }
            value = value.toString();
            if(value.length != 10){
                return false;
            }
            return true;
        } , 'کد پستی دقیقا باید 10 رقم باشد.');
        $('#saveAddressForm').validate({
            rules: {
                province: {
                    required: true,
                    number: true
                },
                city:{
                    required: true,
                    // number: true
                },
                address:{
                    required: true,
                    maxlength: 300
                },
                postal_code:{
                    required: false,
                    postalCodeExact10Chars: true
                },
                phone_number:{
                    required: true,
                    digits: true,
                },
                type:{
                    required: true
                }
            },
            messages: {
                province:{
                    required: 'استان الزامی است.',
                    number: 'استان نامعتبر است.'
                },
                city:{
                    required: 'شهر الزامی است.',
                    number: 'شهر نامعتبر است.'
                },
                address:{
                    required: 'وارد کردن آدرس الزامی است.',
                    maxlength: 'آدرس حداکثر می تواند 300 کاراکتر باشد.'
                },
                phone_number:{
                    required: 'تلفن تماس الزامی است.',
                    digits: 'تلفن تماس باید متشکل از ارقام باشد.'
                },
                type:{
                    required: 'انتخاب نوع محل الزامی است.'
                }
            },
            errorPlacement: function(error , element){
                var placeholder = element.closest('.form-control-wrapper').next('.text-danger.error-container');
                placeholder.html(error);
            }
        });
    </script>
@endsection