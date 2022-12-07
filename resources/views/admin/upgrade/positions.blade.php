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
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>جایگاه های ارتقا</b></h4>
                                    <div class="clearfix"></div>
                                    @if(count($positions) > 0 )
                                    <form action="{{ route('upgrades.admin.positions.update')}}"
                                        method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>شناسه</th>
                                                    <th>موقعیت</th>
                                                    <th></th>
                                                    <th>قیمت (تومان)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($positions as $position)
                                                    <tr>
                                                        <td>
                                                            {{ $position->id}}
                                                        </td>
                                                        <td>
                                                            {{ $position->name }}
                                                        </td>
                                                        <td>
                                                            <a type="button" data-target="#upgrade-modal-{{$position->id}}" data-toggle="modal"
                                                               class="btn btn-success">ارتقا ها
                                                            </a>
                                                        </td>
                                                        <td>

                                                        <input type="number" name="price_{{$position->id}}"
                                                                value="{{ $position->price }}"
                                                                id="position_{{ $position->id }}_price"
                                                                class="form-control">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="submit" class="btn btn-primary">ثبت قیمت ها</button>
                                    </form>
                                    @else
                                        <div class="alert alert-danger text-center">موردی یافت نشد!</div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
        @foreach ($positions as $position)
            <div class="modal fade" id="upgrade-modal-{{$position->id}}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ارتقا ها {{$position->name}}
                            </h4>
                        </div>
                        <div class="modal-body">
                            @php
                                $upgrades = $position->upgrades()->where('upgrades.status' , 'approved')->orderByDesc('upgrades.updated_at')->paginate(20);
                            @endphp
                            @if(count($upgrades) > 0)
                             <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>نام کاربر</th>
                                        <th>نام آیتم ارتقا یافته</th>
                                        <th>جایگاه ارتقا</th>
                                        <th>روش پرداخت</th>
                                        <th>مبلغ پرداختی</th>
                                        <th>تاریخ  و ساعت ارتقا</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($upgrades as $upgrade)
                                    <tr>
                                        <td>{{ $upgrade->upgradable_type == Store::class ? optional(optional($upgrade->upgradable)->user)->first_name . ' ' . optional(optional($upgrade->upgradable)->user)->last_name : optional(optional($upgrade->upgradable->store)->user)->first_name . ' ' . optional(optional($upgrade->upgradable->store)->user)->first_name}}</td>
                                        <td>{{ $upgrade->upgradable->name}}</td>
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
                            <p class="text-danger">هیچ ارتقایی در این جایگاه موجود نمیباشد</p>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
            
    </div>
@endsection
@section('scripts')

@endsection
