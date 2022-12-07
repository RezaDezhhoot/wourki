@extends('admin.master')
@section('styles')
    <style>
        label , textarea , li {font-size: 12px !important;}
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        @include('frontend.errors')
                                        <div class="panel-heading">
                                            <h3 class="panel-title">ویرایش فروشگاه</h3>
                                        </div>
                                        <form action="{{ route('updateStore' , $slug->user_name) }}" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="panel-body">

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="name"> نام فروشگاه  حداقل مبلغ خرید از فروشگاه (تومان) <span class="text-danger">*</span></label>
                                                            <input type="text" value="{{ $store->store_name }}" id="name" required name="name" class="form-control input-sm" placeholder="نام فروشگاه را وارد کنید...">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="mobile">تلفن همراه</label>
                                                            <input type="text" value="{{ $store->mobile }}" id="mobile" readonly class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="email">ایمیل</label>
                                                            <input type="text" value="{{ $store->email }}" id="email" readonly class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="phone_number"> تلفن تماس </label>
                                                            <input type="number" value="{{ $store->mobile }}" id="phone_number" name="phone_number" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label" for="min_pay"> حداقل مبلغ خرید از فروشگاه (تومان) <span class="text-danger">*</span> </label>
                                                            <input type="number" value="{{ $store->min_pay }}" required id="min_pay" name="min_pay" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="guild"> انتخاب صنف <span class="text-danger">*</span></label>
                                                            <select name="guild" required id="guild" class="form-control input-sm">
                                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                                @foreach($guilds as $guild)
                                                                    <option {{ $store->guild_id == $guild->id ? 'selected' : '' }} value="{{ $guild->id }}">{{ $guild->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="pay_type">نوع پرداختی</label>
                                                            <select name="pay_type" id="pay_type" class="form-control input-sm" required>
                                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                                <option {{ $store->pay_type == 'online' ? 'selected' : '' }} value="online">آنلاین</option>
                                                                <option {{ $store->pay_type == 'postal' ? 'selected' : '' }} value="postal">پستی</option>
                                                                <option {{ $store->pay_type == 'both' ? 'selected' : '' }} value="both">هردو</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="activity_type">نوع فعالیت</label>
                                                            <select name="activity_type" id="activity_type" class="form-control input-sm" required>
                                                                <option value="all" disabled selected>::انتخاب کنید::</option>
                                                                <option {{ $store->activity_type == 'country' ? 'selected' : '' }} value="country">در کشور</option>
                                                                <option {{ $store->activity_type == 'province' ? 'selected' : '' }} value="province">در استان</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="address"> آدرس فروشگاه <span class="text-danger">*</span> </label>
                                                            <textarea required name="address" id="address" class="form-control" cols="30" rows="5">{{ $store->address }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="about"> درباره فروشگاه <span class="text-danger">*</span> </label>
                                                            <textarea required name="about" id="about" class="form-control" cols="30" rows="5">{{ $store->about }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="slogan"> شعار فروشگاه <span class="text-danger">*</span> </label>
                                                            <input type="text" value="{{ $store->slogan }}" required id="slogan" name="slogan" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="logo">لوگو</label>
                                                            <input type="file" name="thumbnail_photo" id="logo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label" for="visible"> نمایش فروشگاه </label>
                                                            <input type="checkbox" {{ $store->visible == 1 ? 'checked' : '' }} name="visible" id="visible">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label" for="mobile_number_visibility"> نمایش تلفن تماس </label>
                                                            <input type="checkbox" {{ $store->mobile_visibility == 'show' ? 'checked' : '' }} name="mobile_number_visibility" id="mobile_number_visibility">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label" for="phone_number_visibility"> نمایش تلفن تماس </label>
                                                            <input type="checkbox" {{ $store->phone_number_visibility == 'show' ? 'checked' : '' }} name="phone_number_visibility" id="phone_number_visibility">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-sm btn-success">ثبت</button>
                                                            <a href="{{ route('listOfProductSeller' , $store->user_name) }}" class="btn btn-sm btn-default"> بازگشت <span class="glyphicon glyphicon-arrow-left"></span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
@endsection
@section('scripts')

@endsection
