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

        .dropdown-menu li button {
            border-radius: 0;
        }
        .product-info span , .product-info a , .product-info b {
            font-size: 11px;
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
                            <h4 class="m-t-0 header-title"><b>افزودن ویژگی جدید <span class="text-primary">{{ $productSeller->name }}</span></b></h4><br>
                            @if($errors->any())
                                <div class="alert alert-danger text-center">
                                    <ul class="list-unstyled">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form role="form" action="{{ route('product.seller.attribute.create') }}" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <input type="hidden" name="product_seller_id" value="{{ $productSeller->id }}">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="attribute" class="control-label"> نام ویژگی <span style="color: red;">*</span></label>
                                            <select name="attribute_id" id="attribute" class="form-control input-sm" required>
                                                <option disabled selected>::انتخاب کنید::</option>
                                                @foreach ($attributes as $attribute)
                                                    <option value="{{$attribute->id}}">{{$attribute->type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="title1">مشخصات ویژگی <span style="color: red;">*</span></label>
                                            <input name="title" value="{{ old('title') }}" type="text"
                                                   class="form-control input-sm" id="title1"
                                                   placeholder="مشخصات ویژگی را وارد کنید..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="extra_price">قیمت افزایشی <span style="color: red;">*</span></label>
                                            <input name="extra_price" value="{{ old('extra_price') }}" type="number"
                                                   class="form-control input-sm" id="extra_price"
                                                   placeholder="قیمت افزایشی را وارد کنید..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" style="margin-top: 25px;" class="btn btn-block btn-sm btn-facebook waves-effect waves-light">اضافه کردن به لیست ویژگی ها</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if($store->store_type == "product")
                                    <h4 class="m-t-0 header-title"><b>لیست ویژگی های محصول <span class="text-primary">{{ $productSeller->name }}</span></b></h4>
                                    @else
                                    <h4 class="m-t-0 header-title"><b>لیست ویژگی های خدمت <span class="text-primary">{{ $productSeller->name }}</span></b></h4>
                                    @endif
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        <form id="order-form" action="">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    @if($store->store_type == "product")
                                                    <th>نام محصول</th>
                                                    @else
                                                    <th>نام خدمت</th>
                                                    @endif
                                                    <th>نام ویژگی</th>
                                                    <th>مشخصات ویژگی</th>
                                                    <th>قیمت افزایشی</th>
                                                    <th>اختیارات</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                <?php $id = 1; ?>
                                                @foreach($productSellerAttributes as $attribute)
                                                    <tr>
                                                        <th scope="row">{{ $id }}</th>
                                                        <th>{{ $attribute->name }}</th>
                                                        <th>{{ $attribute->type }}</th>
                                                        <th>{{ $attribute->title }}</th>
                                                        <th>{{ $attribute->extra_price }}</th>
                                                        <th>
                                                            <div class="btn-group m-b-20">
                                                                <div class="btn-group">
                                                                    <a data-toggle="modal"
                                                                       data-target="#update-attribute-{{ $attribute->id }}-modal"
                                                                       class="btn btn-xs btn-info">ویرایش</a>
                                                                    <a href="{{ route('product.seller.attribute.delete' , $attribute->id) }}" class="btn btn-xs btn-danger">حذف</a>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <?php $id++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a style="float: left;" href="{{ URL::previous() }}" class="btn btn-pinterest btn-sm">بازگشت</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($productSellerAttributes as $attribute)
                    <div id="update-attribute-{{ $attribute->id }}-modal" class="modal fade" tabindex="-1" role="dialog"
                         aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">ویرایش ویژگی</h4>
                                </div>

                                <form action="{{ route('product.seller.attribute.update' , $attribute->id) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <input type="hidden" name="id" value="{{ $attribute->id }}">
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="attribute" class="control-label"> نام ویژگی <span style="color: red;">*</span></label>
                                                <select name="attribute" id="attribute" class="form-control input-sm">
                                                    @foreach($attributes as $attr)
                                                        <option {{ $attribute->attribute_id == $attr->attr_id ? 'selected' : '' }} value="{{ $attr->id }}">{{ $attr->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="title1" class="control-label"> مشخصات ویژگی <span style="color: red;">*</span></label>
                                                <input name="title" value="{{ $attribute->title }}" type="text" class="form-control input-sm"
                                                       id="title1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="price" class="control-label"> قیمت افزایشی <span style="color: red;">*</span></label>
                                                <input name="extra_price" value="{{ $attribute->extra_price }}" type="number" class="form-control input-sm"
                                                       id="price" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light">ویرایش
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
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection
@section('scripts')

@endsection
