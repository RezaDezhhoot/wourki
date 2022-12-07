@extends('admin.master')
@section('content')
<?php use App\Store; ?>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>نام کاربر</th>
                                            <th>موبایل</th>
                                            <th>فروشگاه محصولات</th>
                                            <th>فروشگاه خدمات</th>
                                            <th>فروشگاه بازاریابی</th>
                                            <th>استان و شهر</th>
                                            <th>تاریخ ثبت</th>
                                        </tr>
                                        <tr>
                                            @php
                                             $productStore = $user->stores()->where('store_type' , 'product')->first();
                                             $serviceStore = $user->stores()->where('store_type' , 'service')->first();
                                             $marketStore = $user->stores()->where('store_type' , 'market')->first();

                                            @endphp
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>                                                
                                                @if($productStore)
                                                <a href="{{route('listOfProductSeller' , ['storeUserNameSlug' => $productStore->user_name])}}"> {{$productStore->name}} </a>   
                                                @else
                                                    ندارد
                                                @endif</td>
                                            <td>
                                                @if($serviceStore)
                                                <a href="{{route('listOfProductSeller' , ['storeUserNameSlug' => $serviceStore->user_name])}}"> {{$serviceStore->name}} </a>   
                                                @else
                                                    ندارد
                                                @endif
                                            </td>
                                            <td>                                                
                                                @if($marketStore)
                                                <a href="{{route('listOfProductSeller' , ['storeUserNameSlug' => $marketStore->user_name])}}"> {{$marketStore->name}} </a>   
                                                @else
                                                    ندارد
                                                @endif</td>
                                            <td>{{ $user->province_name }}-{{ $user->city_name }}</td>
                                            <td>{{ $user->submitted_date }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('ads_of_users' , $user->id) }}"
                                   style="margin-bottom:20px;" class="btn btn-facebook btn-block">تبلیغات این کاربر</a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('editUser' , $user->id) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">ویرایش</a>
                            </div>
                            <div class="col-md-3">
                                <button data-toggle="modal"
                                        data-target="#quick_edit_user_{{ $user->id }}"
                                        data-quick-edit-user-button type="button" style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">ویرایش سریع
                                </button>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('user.wallet.list' , $user->id) }}"
                                   style="margin-bottom:20px;" class="btn btn-facebook btn-block">کیف پول</a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('list.of.user.reagented' , $user->id) }}"
                                   style="margin-bottom:20px;" class="btn btn-facebook btn-block">
                                    کاربران
                                    معرفی کرده
                                    ({{ $user->reagent_user ? $user->reagent_user : '0' }})
                                </a>
                            </div>
                            @if(count($user->stores) > 0)
                                <div class="col-md-3">
                                    @php
                                      $sp = Store::where('user_id' , $user->id)->where('store_type' , 'product')->first();
                                      $ss = Store::where('user_id' , $user->id)->where('store_type' , 'service')->first(); 
                                      $sm = Store::where('user_id' , $user->id)->where('store_type' , 'market')->first(); 
                                    @endphp
                                    @if($sp)
                                    <a href="{{ route('admin.store.edit' , $sp -> id) }}"
                                       style="margin-bottom:20px;" class="btn btn-facebook btn-block">
                                        به روز رسانی فروشگاه محصولات
                                    </a>
                                    @endif
                                    @if($ss)
                                    <a href="{{ route('admin.store.edit' , $ss->id) }}"
                                       style="margin-bottom:20px;" class="btn btn-facebook btn-block">
                                        به روز رسانی فروشگاه خدمات
                                    </a>
                                    @endif
                                    @if($sm)
                                    <a href="{{ route('admin.store.edit' , $sm->id) }}"
                                       style="margin-bottom:20px;" class="btn btn-facebook btn-block">
                                        به روز رسانی فروشگاه بازاریابی
                                    </a>
                                    @endif
                                </div>
                            @endif
                            @if(count($user->stores()->where('store_type' , '!=' , 'market')->get()) < 2)
                                <div class="col-md-3">
                                    <a href="{{ route('admin.store.create' , $user->id) }}"
                                       style="margin-bottom:20px;" class="btn btn-instagram btn-block">
                                        ساخت فروشگاه
                                    </a>
                                </div>
                            @endif
                            @if($user->banned == '0')
                                <div class="col-md-3">
                                    <a href="{{ route('bennUser' , $user->id) }}" style="margin-bottom:20px;"
                                       class="btn btn-pinterest btn-block">
                                        مسدود کردن
                                        کاربر
                                    </a>
                                </div>
                            @elseif($user->banned == '1')
                                <div class="col-md-3">
                                    <a href="{{ route('activeUser' , $user->id) }}" style="margin-bottom:20px;"
                                       class="btn btn-success btn-block">
                                        خارج کردن
                                        کاربر از حالت مسدود
                                    </a>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <a href="{{ route('bills.index' , ['user' => $user->id]) }}"
                                   style="margin-bottom:20px;" class="btn btn-pink btn-block">
                                    فاکتورهای خرید
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        @if($user->store)
                                        href="{{ route('bills.index' , ['store' => $user->store->id]) }}"
                                        @endif
                                        style="margin-bottom:20px;"
                                        class="btn btn-pink btn-block {{ count($user->stores) < 0  ? 'disabled' : '' }}">
                                    فاکتورهای فروش
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        @if($user->store)
                                        href="{{ route('productSellerComments' , ['store_name' => $user->store->id]) }}"
                                        @endif
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block {{ count($user->stores) < 0  ? 'disabled' : '' }}">
                                    نظرات
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        data-toggle="modal"
                                        data-target="#send_quick_message_for_user_{{ $user->id }}"
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">
                                    ارسال پیام سریع
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        href="{{ route('message.index' , $user->id) }}"
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">
                                    مشاهده پیام ها
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        href="{{ route('admin.chats.index' , $user->id) }}"
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">
                                    مشاهده گفت و گو ها
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        href="{{ route('admin.chats.block' , $user->id) }}?block={{$user->chats_blocked ? 0 : 1}}"
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">
                                    @if(!$user->chats_blocked)
                                    بلاک کردن گفت و گو ها
                                    @else
                                    آنبلاک کردن گفت و گو ها
                                    @endif
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a
                                        href="{{ route('admin.user.login' , $user->id) }}"
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">
                                    ورود به سیستم به عنوان کاربر
                                </a>
                            </div>

                        </div>
                        </div>
                    </div>
                </div>
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