@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="user-dashboard-page-wrapper">
                <div class="row">
                    <div class="col-md-2 col-xs-3 xs-pad">
                    </div>
                    <div class="col-md-10 col-xs-9 xs-pad">
                        <div class="wrapper3 page-content-panel">
                            <h1 class="text-center" style="font-size:20px;">ثبت محصول</h1>
                            <form action="{{ route('saveNewProduct') }}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}

                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="material-icons">info_outline</i><span>اطلاعات محصول</span></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="adding-products-fields-wrapper">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <label for="product-name-in-adding-products">نام محصول</label>
                                                            <input class="form-control" type="text" id="product-name-in-adding-products" value="{{ old('name') }}" name="name" placeholder="نام محصول را وارد کنید">
                                                            @if($errors->has('name'))
                                                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label for="product-quantity-in-adding-products">تعداد موجودی محصول</label>
                                                            <input class="form-control" type="text" id="product-quantity-in-adding-products" value="{{ old('quantity') }}" name="quantity" placeholder="مقدار موجودی محصول را وارد کنید">
                                                            @if($errors->has('quantity'))
                                                                <p class="text-danger">{{ $errors->first('quantity') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label for="ckeditor">توضیحات</label>
                                                            <textarea class="form-control" id="ckeditor" value="{{ old('description') }}" name="description" placeholder="توضیحات محصول را وارد کنید"></textarea>
                                                            @if($errors->has('description'))
                                                                <p class="text-danger">{{ $errors->first('description') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <label for="category">نوع دسته محصول</label>
                                                            <select id="category" class="form-control">
                                                                <option value="none" selected="selected">...</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if($errors->has('subCategory'))
                                                                <p class="text-danger">{{ $errors->first('subCategory') }}</p>
                                                            @endif
                                                        </div>
                                                        <script>
                                                            $('#category').change(function () {
                                                                var value = $(this).val();
                                                                if(value == 'none'){
                                                                    $('#subcat').slideUp(200);
                                                                    $('#subCategory').html('');

                                                                }else{
                                                                    $.ajax({
                                                                        type: 'get',
                                                                        url: '{{ url()->to('/admin/categories') }}/'+ value +'/subcategory',
                                                                        data: {

                                                                        },
                                                                        success: function (response) {
                                                                            var list = response;
                                                                            var div = $('#subcat');
                                                                            var overloads = $('#subCategory');
                                                                            if(list.length > 0 ){
                                                                                div.slideDown(200);
                                                                                overloads.html('');
                                                                                for(var i = 0 ; i < list.length ; i++)
                                                                                {
                                                                                    overloads.append('<option value="'+ list[i].id +'">'+ list[i].name +'</option>');
                                                                                }
                                                                            }
                                                                        }
                                                                    });
                                                                }

                                                            });

                                                        </script>
                                                        <div class="col-md-6 col-xs-12" style="display: none;" id="subcat">
                                                            <label for="subCategory">نوع زیر دسته محصول</label>
                                                            <select id="subCategory" name="subCategory" class="form-control">
                                                            </select>
                                                            {{--@if($errors->has('subCategory'))
                                                                <b class="text-danger">{{ $errors->first('subCategory') }}</b>
                                                            @endif--}}
                                                        </div>
                                                        <div class="col-md-3 col-lg-12 col-xs-12 checkbox-adding-products-page">



                                                            <div class="checkbox">
                                                                <input name="visible" value="1"  id="checkbox22" type="checkbox" checked="checked">
                                                                <label for="checkbox22">نمایش محصول فروشگاه</label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <input name="is_vip" value="1" id="checkbox33" type="checkbox">
                                                                <label for="checkbox33">انتخاب به عنوان محصول ویژه</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="material-icons">description</i><span>قیمت</span></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="product-price-wrapper">
                                                    <div class="col-md-4 col-xs-12">
                                                        <label for="product-price-in-adding-products">قیمت محصول</label>
                                                        <input class="form-control" value="{{ old('price') }}" type="text" id="product-price-in-adding-products" name="price" placeholder="قیمت محصول را وارد کنید">
                                                        @if($errors->has('price'))
                                                            <p class="text-danger">{{ $errors->first('price') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="product-discount-in-adding-products">درصد تخفیف</label>
                                                            <input maxlength="2" value="{{ old('discount') }}" class="form-control" type="text" id="product-discount-in-adding-products" name="discount" placeholder="درصد تخفیف را وارد کنید">
                                                            @if($errors->has('discount'))
                                                                <p class="text-danger">{{ $errors->first('discount') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-12">
                                                        <div class="form-group">
                                                            <label>قیمت فروش</label>
                                                            <input type="text" name="" id="final_price" class="form-control" disabled >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $(document).ready(function (e) {
                                                    var price = $('#product-price-in-adding-products').val();
                                                    if (price == '' || isNaN(price)) {
                                                        price = 0;
                                                    }
                                                    var discount = $('#product-discount-in-adding-products').val();
                                                    if (discount == '' || isNaN(discount)) {
                                                        discount = 0;
                                                    }
                                                    var finalPrice = price - (price * (discount / 100));
                                                    $('#final_price').val(finalPrice);
                                                });
                                                $('#product-price-in-adding-products , #product-discount-in-adding-products').keyup(function (e) {
                                                    var price = $('#product-price-in-adding-products').val();
                                                    if (price == '' || isNaN(price)) {
                                                        price = 0;
                                                    }
                                                    var discount = $('#product-discount-in-adding-products').val();
                                                    if (discount == '' || isNaN(discount)) {
                                                        discount = 0;
                                                    }
                                                    var finalPrice = price - (price * (discount / 100));
                                                    $('#final_price').val(finalPrice);
                                                });
                                            </script>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="submit-button-wrapper-in-store-information text-center">
                                                    <button class="btn btn-primary submit-btn-in-dashboard-pages" type="submit">ثبت</button>
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
        </div>
    </div>
@endsection

@section('scripts')
    {{--<script src="{{ URL::to('admin') }}/assets/js/ckeditor.js"></script>--}}
    <script src="https://cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'ckeditor' );
    </script>
@endsection