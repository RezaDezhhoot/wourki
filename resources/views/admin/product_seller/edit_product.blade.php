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
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        @include('frontend.errors')
                        <div class="panel-heading">
                            <h3 class="panel-title">ویرایش محصول</h3>
                        </div>
                        <form action="{{ route('admin.product.update' , ['product' => $product->id]) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="name">نام محصول<span class="text-danger">*</span></label>
                                            <input type="text" value="{{ $product->name }}" id="name" required name="name" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="price">قیمت</label>
                                            <input type="text" value="{{ $product->price }}" name="price" id="price" required class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="quantity">تعداد</label>
                                            <input type="number" value="{{ $product->quantity }}" id="quantity" required name="quantity" class="form-control input-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="discount">درصد تخفیف</label>
                                            <input type="number" value="{{ $product->discount }}" id="discount" name="discount" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="category">دسته بندی</label>
                                            <select name="category" id="category" class="form-control input-sm" required>
                                                @foreach($categories as $category)
                                                    <option {{ $category->id == $product->category_id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="shippingPriceToTehran">هزینه حمل به تهران(تومان)</label>
                                            <input class="form-control" type="number" name="shipping_price_to_tehran"
                                                   value="{{ $product->shipping_price_to_tehran }}"
                                                   id="shippingPriceToTehran">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="shippingPriceToOtherTowns">هزینه حمل به شهرستان ها(تومان)</label>
                                            <input class="form-control" type="number" name="shipping_price_to_other_towns"
                                                   value="{{ $product->shipping_price_to_other_towns }}"
                                                   id="shippingPriceToOtherTowns">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="deliveryDaysInTehran">زمان تحویل در تهران (روز)</label>
                                            <input class="form-control" type="number" name="deliver_time_in_tehran"
                                                   value="{{ $product->deliver_time_in_tehran }}"
                                                   id="deliveryDaysInTehran">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="deliveryDaysInOtherTowns">زمان تحویل در شهرستان ها (روز)</label>
                                            <input class="form-control" type="number" name="deliver_time_in_other_towns"
                                                   value="{{ $product->deliver_time_in_other_towns }}"
                                                   id="deliveryDaysInOtherTowns">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="description">توضیحات</label>
                                            <textarea name="description" id="description" cols="20" rows="10"
                                                      class="form-control"
                                                      required>{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-success">ثبت</button>
                                            <a href="{{ route('allProductSellerList') }}" class="btn btn-sm btn-default"> بازگشت به لیست محصولات <span class="glyphicon glyphicon-arrow-left"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- container -->
        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection
@section('scripts')

@endsection
