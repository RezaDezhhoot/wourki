@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/datepicker-theme.css') }}">
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

        .list-unstyled li {
            font-size: 12px;
        }

        .select2-container .select2-selection--single {
            height: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
            font-size: 12px;
            text-align: right;
            color: #888 !important;
        }

        #btn-filter {
            margin: 33px 0;
        }

        .filters .form-group {
            height: 76px;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                @if(count($errors->all()) > 0 )
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger text-center">
                                            @foreach($errors->all() as $error)
                                                {{ $error }} <br/>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title">?????????????? ????????????
                                        <b class="text-success">{{ $position->name }}</b>
                                        <a href="{{ route('ads_management') }}" class="btn btn-success btn-xs"
                                           style="float:left;">???????????? ???? ???????? ???????????? ????</a>
                                    </h4>
                                    <div class="clearfix"></div>
                                    <div class="filters">
                                        <form action="{{ url()->current() }}">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="link_type_in_filter">?????? ????????:</label>
                                                        <select name="link_type" id="link_type_in_filter"
                                                                class="form-control">
                                                            <option {{ request()->input('link_type') == 'all' ? 'selected' : '' }} value="all">
                                                                :: ???????? ???????????? ::
                                                            </option>
                                                            <option {{ request()->input('link_type') == 'product' ? 'selected' : '' }} value="product">
                                                                ??????????
                                                            </option>
                                                            <option {{ request()->input('link_type') == 'store' ? 'selected' : '' }} value="store">
                                                                ??????????????
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="product_in_filter">??????????:</label>
                                                        <select name="product" id="product_in_filter"
                                                                class="form-control">
                                                            @if($currentProduct)
                                                                <option value="{{ $currentProduct->id }}">{{ $currentProduct->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="store_in_filter">??????????????:</label>
                                                        <select name="store" id="store_in_filter"
                                                                class="form-control">
                                                            @if($currentStore)
                                                                <option value="{{ $currentStore->id }}">{{ $currentStore->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="pay_status_in_filter">?????????? ????????????:</label>
                                                        <select name="pay_status" id="pay_status_in_filter"
                                                                class="form-control">
                                                            <option {{ request()->input('pay_status') == 'all' ? 'selected' : '' }} value="all">
                                                                :: ???????? ???????????? ::
                                                            </option>
                                                            <option {{ request()->input('pay_status') == 'paid' ? 'selected' : '' }} value="paid">
                                                                ???????????? ??????
                                                            </option>
                                                            <option {{ request()->input('pay_status') == 'unpaid' ? 'selected' : '' }} value="unpaid">
                                                                ???????????? ????????
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="confirmation_status_in_filter">?????????? ??????????:</label>
                                                        <select name="confirmation_status"
                                                                id="confirmation_status_in_filter" class="form-control">
                                                            <option {{ request()->input('confirmation_status') == 'all' ? 'selected' : '' }} value="all">
                                                                :: ???????? ???????????? ::
                                                            </option>
                                                            <option {{ request()->input('confirmation_status') == 'pending' ? 'selected' : '' }} value="pending">
                                                                ???? ???????????? ??????????
                                                            </option>
                                                            <option {{ request()->input('confirmation_status') == 'approved' ? 'selected' : '' }} value="approved">
                                                                ?????????? ??????
                                                            </option>
                                                            <option {{ request()->input('confirmation_status') == 'rejected' ? 'selected' : '' }} value="rejected">
                                                                ???? ??????
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="user_name_in_filter">?????? ??????????:</label>
                                                        <select name="user_name" id="user_name_in_filter"
                                                                class="form-control">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <button id="btn-filter" type="submit" class="btn btn-linkedin">
                                                        ??????????
                                                    </button>
                                                    <a class="btn btn-secondary" href="{{ url()->current() }}">?????? ??????????
                                                        ????</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @if(count($list) > 0 )
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>?????? ????????</th>
                                                    <th>??????????</th>
                                                    <th>??????????????</th>
                                                    <th>??????????????</th>
                                                    <th>?????????? ????????????</th>
                                                    <th>?????????? ??????????</th>
                                                    <th>?????? ??????????</th>
                                                    <th>???????? ????????????</th>
                                                    <th>??????????</th>
                                                    <th></th>
                                                    <th>???????????? ????</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($list as $ad)
                                                    <tr>
                                                        <td>
                                                            @if($ad->pay_status == 'unpaid')
                                                                <a href="#" data-toggle="modal"
                                                                   data-target="#pay_ad_{{ $ad->id }}_cost_from_user_wallet"
                                                                   class="btn btn-pinterest btn-xs">????????????</a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $ad->link_type == 'store' ? '??????????????' : '??????????' }}
                                                        </td>
                                                        <td>
                                                            @if($ad->product)
                                                                <a href="{{ route('showSingleProduct' , [
                                                                    $ad->product->store->id,
                                                                    $ad->product->id
                                                                ]) }}">{{ $ad->product->name }}</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($ad->store)
                                                                <a href="{{ route('listOfProductSeller' , $ad->store->user_name) }}">{{ $ad->store->name }}</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $ad->description }}</td>
                                                        <td>
                                                            @if($ad->pay_status == 'paid')
                                                                <span class="text-success">???????????? ??????</span>
                                                            @else
                                                                <span class="text-danger">???????????? ????????</span>
                                                        @endif
                                                        <td>
                                                            @if($ad->status == 'pending')
                                                                <b class="text-warning">???? ???????????? ??????????</b>
                                                            @elseif($ad->status == 'approved')
                                                                <b class="text-success">?????????? ??????</b>
                                                            @elseif($ad->status == 'rejected')
                                                                <b class="text-danger">???? ??????</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($ad->user)
                                                                {{ $ad->user->first_name }}
                                                                {{ $ad->user->last_name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $ad->payment_type == 'wallet' ? '?????? ??????' : '????????????'}}
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                        class="btn btn-default btn-xs dropdown-toggle"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                    ??????????<span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="{{ route('ads.status.change' , [
                                                                        'ads' => $ad->id,
                                                                        'status' => 'pending'
                                                                    ]) }}">???? ???????????? ??????????</a></li>
                                                                    <li><a href="{{ route('ads.status.change' , [
                                                                        'ads' => $ad->id,
                                                                        'status' => 'approved'
                                                                    ]) }}">?????????? ??????</a></li>
                                                                    <li><a href="{{ route('ads.status.change' , [
                                                                        'ads' => $ad->id,
                                                                        'status' => 'rejected'
                                                                    ]) }}">???? ??????</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#change_ads_{{ $ad->id }}_position"
                                                               class="btn btn-xs btn-success">?????????? ????????????</a>
                                                        </td>
                                                        <td>
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#payments_list_of_ad_{{ $ad->id }}"
                                                               class="btn btn-success btn-xs">?????????????? ???????????? ????</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('ads.show' , $ad->id) }}"
                                                               class="btn btn-success btn-xs">???? ?????? ?????????? ??????????</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('delete.ads' , $ad->id) }}"
                                                               class="btn btn-danger btn-xs">??????</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger text-center">?????????? ???????? ??????!</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
        @foreach($list as $item)
            <div class="modal fade" id="change_ads_{{ $item->id }}_position" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                ?????????? ????????????
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ads.position.change' , $item->id) }}" method="POST">
                                {{csrf_field()}}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <select name="position" id="ads_{{ $item->id }}_position_to_change"
                                            class="form-control">
                                        @foreach($positions as $ps)
                                            <option {{ $item->ads_position_id == $ps->id ? 'selected' : '' }} value="{{ $ps->id }}">{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">????????????</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="pay_ad_{{ $item->id }}_cost_from_user_wallet" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                ???????????? ?????????? ?????????? ???? ?????? ?????? ??????????
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('my_account.ads_manually_pay.with_wallet' , $item->id) }}"
                                  method="POST">
                                {{csrf_field()}}
                                {{ method_field('PUT') }}
                                <div class="form-group">
                                    <input type="hidden" name="user_id" value="{{ $item->user->id }}">
                                    <label for="ad_{{ $item->id }}_user_name">?????? ??????????:</label>
                                    <input type="text" name="user_name"
                                           disabled
                                           value="{{ $item->user->first_name }} {{ $item->user->last_name }}"
                                           id="ad_{{ $item->id }}_user_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="ad_{{ $item->id }}_user_wallet_stock">???????????? ?????? ??????:</label>
                                    <input type="text" name="wallet_stock"
                                           class="form-control"
                                           disabled
                                           value="{{ $item->user->wallet_stock }} ??????????"
                                           id="ad_{{ $item->id }}_user_wallet_stock">
                                    @if($item->user->wallet_stock < $position->price)
                                        <p class="text-danger">???????????? ?????? ?????? ????????????</p>
                                    @else
                                        <p class="text-success">???????????? ?????? ?????? ????????</p>
                                    @endif
                                </div>
                                <button
                                        {{ $item->user->wallet_stock < $position->price ? 'disabled' : '' }}
                                        type="submit" class="btn btn-primary">????????????
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="payments_list_of_ad_{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                ?????????????? ???????????? ????
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>????????</th>
                                        <th>?????? ????????????</th>
                                        <th>?????????? ????????????</th>
                                        <th>?????????? ??????????</th>
                                        <th>?????????? ????????????</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->price }}</td>
                                            <td>{{ $payment->payment_type == 'wallet' ? '?????? ??????' : '????????????' }}</td>
                                            <td>{{ $payment->tracking_code }}</td>
                                            <td>{{ $payment->ref_id }}</td>
                                            <td>{{ \Morilog\Jalali\Jalalian::forge($payment->pay_date)->format('%d %B %y') }}</td>
                                            <td>{{ $payment->initial_pay == 'initial' ? '???????????? ??????????'  : '??????????????'}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">??????????</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">????????</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            changeProductAndStoreFieldsStatus();
        });
        $('#link_type_in_filter').change(function () {
            changeProductAndStoreFieldsStatus();
        });

        function changeProductAndStoreFieldsStatus() {
            var link_type = $('#link_type_in_filter');
            var product = $('#product_in_filter');
            var store = $('#store_in_filter');
            if (link_type.val() == 'store') {
                store.removeAttr('disabled');
                product.attr('disabled', 'disabled');
            } else if (link_type.val() == 'product') {
                store.attr('disabled', 'disabled');
                product.removeAttr('disabled');
            } else {
                store.removeAttr('disabled');
                product.removeAttr('disabled');
            }
        }

        $('#product_in_filter').select2({
            placeholder: '???????????? ??????????...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('ads_products_get_via_ajax') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (dt) {
                            return {
                                id: dt.id,
                                text: dt.name
                            };
                        })
                    }
                }
            }
        });
        $('#user_name_in_filter').select2({
            placeholder: '???????????? ??????????...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('ads_filter.users.ajax') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (dt) {
                            return {
                                id: dt.id,
                                text: dt.first_name + ' ' + dt.last_name + ' - ' + dt.mobile
                            };
                        })
                    }
                }
            }
        });
        $('#store_in_filter').select2({
            placeholder: '???????????? ??????????????...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('ads_stores_get_via_ajax') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (dt) {
                            return {
                                id: dt.id,
                                text: dt.name
                            };
                        })
                    }
                }
            }
        });

    </script>
@endsection
