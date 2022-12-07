@extends('admin.master')
@section('styles')
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

        .dropdown-menu li a {
            border-radius: 0;
        }

        .list-unstyled li  , textarea{
            font-size: 12px!important;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>افزودن پلن جدید</b></h4><br>
                            @if($errors->any())
                                <div class="alert alert-danger text-center">
                                    <ul class="list-unstyled">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form role="form" action="{{ route('createPlan') }}" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">نام پلن <span style="color: red;">*</span></label>
                                            <input name="name" value="{{ old('name') }}" type="text"
                                                   class="form-control input-sm" id="name"
                                                   placeholder="نام پلن جدید را وارد کنید..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="time">زمان پلن <span style="color: red;">*</span></label>
                                            <input name="time" value="{{ old('time') }}" type="text"
                                                   class="form-control input-sm" id="time" placeholder="زمان پلن را وارد کنید..."
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price">قیمت پلن <span style="color: red;">*</span></label>
                                            <input name="price" value="{{ old('price') }}" type="text"
                                                   class="form-control input-sm" id="price"
                                                   placeholder="قیمت پلن را وارد کنید..." required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">توضیحات پلن</label>
                                            <textarea name="description" style="font-family: IRANSans;"
                                                      class="form-control" required id="description" cols="20" rows="5"
                                                      placeholder="توضیحات پلن را وارد کنید..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                            <label for="type">نوع اشتراک :</label>
                                            <select name="type" id="type"
                                                    class="js-example-basic-single">
                                                <option value="store" selected>
                                                    اشتراک فروشگاه
                                                </option>
                                                <option value="market">
                                                    اشتراک بازاریابی
                                                </option>
                                            </select>
                                            @if($errors->has('type'))
                                                <b class="text-danger">{{ $errors->first('type') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="active">فعال</label>
                                            <input type="radio" name="status" value="show" id="active">&nbsp;&nbsp;&nbsp;
                                            <label for="inactive">غیر فعال</label>
                                            <input type="radio" name="status" value="hide" id="inactive">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-purple waves-effect waves-light">ثبت</button>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>پلن ها</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        <form id="order-form" action="">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>شناسه پلن</th>
                                                    <th>نام پلن</th>
                                                    <th>زمان</th>
                                                    <th>قیمت</th>
                                                    <th>نوع</th>
                                                    <th>توضیحات</th>
                                                    <th width="130px;">اختیارات</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                <?php $id = 1; ?>
                                                @foreach($plans as $plan)
                                                    <tr>
                                                        <th scope="row">{{ $id }}</th>
                                                        <th>{{$plan->id}}</th>
                                                        <th>{{ $plan->plan_name }}</th>
                                                        <th>{{ $plan->month_inrterval }} ماه</th>
                                                        <th>{{ $plan->price }} تومان</th>
                                                        <th>{{ $plan->type == 'store' ? 'اشتراک فروشگاه' : 'اشتراک بازاریابی' }}</th>
                                                        <th>{{ $plan->description }}</th>
                                                        <th>
                                                            <div class="btn-group m-b-20">
                                                                <div class="btn-group">
                                                                    <a data-toggle="modal"
                                                                       data-target="#update-category-{{ $plan->id }}-modal"
                                                                       class="btn btn-xs btn-info">ویرایش</a>
                                                                    @if($plan->status == 'show')
                                                                        <button type="button"
                                                                                class="btn btn-xs btn-success dropdown-toggle waves-effect"
                                                                                data-visibility-button
                                                                                data-toggle="dropdown"
                                                                                aria-expanded="false"> فعال <span
                                                                                    class="caret"></span></button>
                                                                    @else
                                                                        <button type="button"
                                                                                class="btn btn-xs btn-danger dropdown-toggle waves-effect"
                                                                                data-visibility-button
                                                                                data-toggle="dropdown"
                                                                                aria-expanded="false"> غیرفعال <span
                                                                                    class="caret"></span></button>
                                                                    @endif
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a href="{{ route('activePlan' , $plan->id) }}" class="btn btn-block btn-xs btn-success"
                                                                                    id="show_product">فعال</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ route('deactivePlan' , $plan->id) }}" class="btn btn-block btn-xs btn-danger"
                                                                                    id="hide_product">غیرفعال</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <?php $id++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            {{ $plans->links() }}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
    @foreach($plans as $plan)
        <div id="update-category-{{ $plan->id }}-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">ویرایش پلن</h4>
                    </div>
                    {{--@if(count($errors) > 0)--}}
                        {{--<div class="alert alert-danger text-center">--}}
                            {{--@foreach($errors->all() as $error)--}}
                                {{--{{ $error }} <br>--}}
                            {{--@endforeach--}}
                        {{--</div>--}}
                    {{--@endif--}}
                    <form action="{{ route('updatePlan') }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <input type="hidden" name="id" value="{{ $plan->id }}">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="control-label"> نام پلن <span style="color: red;">*</span></label>
                                    <input name="name" value="{{ $plan->plan_name }}" type="text" class="form-control input-sm"
                                           id="name" placeholder="نام پلن را وارد کنید..." required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="time" class="control-label"> زمان پلن <span style="color: red;">*</span></label>
                                    <input name="time" required="required" value="{{ $plan->month_inrterval }}"
                                           type="number" class="form-control input-sm" id="time"
                                           placeholder="زمان پلن را وارد کنید...">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="price" class="control-label"> قیمت پلن<span style="color: red;">*</span></label>
                                    <input name="price" required="required" value="{{ $plan->price }}" type="number"
                                           class="form-control input-sm" id="price" placeholder="قیمت پلن را وارد کنید...">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">توضیحات پلن</label>
                                    <textarea name="description" style="font-family: IRANSans;font-size: 12px;" class="form-control" id="description" cols="20" rows="5" placeholder="توضیحات پلن را وارد کنید...">{{ $plan->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="type-{{$plan->id}}">نوع اشتراک :</label>
                                    <select name="type" id="type-{{$plan->id}}"
                                            class="js-example-basic-single">
                                        <option value="store" {{$plan->type == 'store' ? 'selected' : ''}}>
                                            اشتراک فروشگاه
                                        </option>
                                        <option value="market" {{$plan->type == 'market' ? 'selected' : ''}}>
                                            اشتراک بازاریابی
                                        </option>
                                    </select>
                                    @if($errors->has('type'))
                                        <b class="text-danger">{{ $errors->first('type') }}</b>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="active">فعال</label>
                                    <input type="radio" name="status" value="show"
                                           id="active" {{ $plan->status == 'show' ? 'checked' : '' }}>&nbsp;&nbsp;&nbsp;
                                    <label for="inactive">غیر فعال</label>
                                    <input type="radio" name="status" value="hide"
                                           id="inactive" {{ $plan->status == 'hide' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-sm btn-info waves-effect waves-light">ذخیره تغیرات
                                    </button>
                                    <button type="button" class="btn btn-sm btn-default waves-effect" data-dismiss="modal">بستن
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection