@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | ویرایش محصول</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid save-product-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <form action="{{ route( $is_service ?  'user.service.update':'user.product.update' , $product->id) }}" method="post"
                          class="form-horizontal" id="updateProductForm">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-info-circle"></i>
                                    @if(!$is_service)
                                    مشخصات عمومی محصول
                                    @else
                                    مشخصات عمومی خدمت
                                    @endif
                                </div>
                                <div class="panel-body">
                                    @include('frontend.errors')
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="product-name" class="control-label col-sm-4">نام
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="name" id="product-name"
                                                           class="form-control" value="{{ $product->name }}">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                            </div>
                                        </div>
                                        @if(!$is_service)
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="product-qty" class="control-label col-sm-4">موجودی
                                                    انبار</label>
                                                <div class="col-sm-8">
                                                    <input type="number" name="quantity" id="product-qty"
                                                           class="form-control" value="{{ $product->quantity }}">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-xs-12 col-md-12">
                                            <div class="form-group">
                                                <label for="category" class="control-label col-sm-2">دسته بندی</label>
                                                <div class="col-sm-10">
                                                    <select name="category" id="category" class="form-control">
                                                        <option selected disabled>::انتخاب کنید::</option>
                                                        @foreach($categories as $category)
                                                            <option {{ $product->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}
                                                                - پورسانت وورکی : {{$category->commission}} %
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="description" class="control-label col-sm-2">توضیحات</label>
                                                <div class="col-sm-10">
                                                    <textarea style="height:70px;" name="description"
                                                              class="form-control" id="description" cols="30"
                                                              rows="5">{{ $product->description }}</textarea>
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-md-offset-2">
                                            <div class="form-group">
                                                <label data-toggle="tooltip"
                                                       title="این گزینه این امکان را می دهد که محصول از دید کاربران و خریداران مخفی شود."
                                                       for="visible">
                                                       @if(!$is_service)
                                                       نمایش محصول به مشتریان
                                                       @else
                                                       نمایش خدمت به مشتریان
                                                       @endif
                                                    </label>
                                                <input type="checkbox" id="visible" name="visible"
                                                       class="switchery" {{ $product->visible == '1' ? 'checked' : '' }} />
                                            </div>
                                        </div>
                                        @if(!$is_service)
                                        <div class="col-xs-12 col-md-4 col-md-offset-2">
                                            <div class="form-group">
                                                <label data-toggle="tooltip"
                                                       title="این گزینه مشخص می کند که محصول اوریجینال و اصل است یا خیر."
                                                       for="guarantee_mark">گارانتی اصالت محصول</label>
                                                <input type="checkbox" id="guarantee_mark" name="guarantee_mark"
                                                       class="switchery"
                                                        {{ $product->guarantee_mark == 1 ? 'checked' : '' }}
                                                />
                                            </div>
                                        {{--</div>--}}
                                            </div>
                                            @endif
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-dollar-sign"></i>
                                    قیمت
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="price" class="control-label col-sm-3">قیمت(تومان)</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="price" min="0" id="price"
                                                           class="form-control" value="{{ $product->price }}">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="discount" class="control-label col-sm-5">درصد تخفیف</label>
                                                <div class="col-sm-7">
                                                    <input type="number" name="discount" min="0" id="discount"
                                                           class="form-control" value="{{ $product->discount }}">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="finalPrice" class="control-label col-sm-6">قیمت تمام
                                                    شده(تومان)</label>
                                                <div class="col-sm-6">
                                                    <input disabled type="number" min="0" name="finalPrice"
                                                           id="finalPrice" class="form-control"
                                                           value="{{ $product->price - (($product->price * $product->discount) / 100) }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- <div class="wrapper">
                                    <div class="row">
                                        <div class="form-horizontal">
                                            <div class="col-xs-12">
                                                
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <i class="fas fa-info-circle"></i>
                                                        افزودن ویژگی جدید
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="attribute-row-wrapper">
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-md-3">
                                                                            <select class="form-control" id="type">
                                                                                <option selected disabled>نوع ویژگی</option>
                                                                                <option value="1">وزن</option>
                                                                                <option value="2">رنگ</option>
                                                                                <option value="3">سایز</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xs-12 col-md-3">
                                                                            <input type="text" data-toggle="tooltip"
                                                                                title="نام ویژگی به عنوان مثال برای نوع ویژگی سایز می تواند مقادیر Large , XLarge , XXLarge را داشته باشد."
                                                                                placeholder="نام ویژگی را وارد کنید."
                                                                                class="form-control" id="attr-name">
                                                                        </div>
                                                                        <div class="col-xs-12 col-md-3">
                                                                            <input type="number" data-toggle="tooltip"
                                                                                min="0"
                                                                                title="میزان قیمتی که با انتخاب این ویژگی توسط مشتری به مبلغ فاکتور اضافه می شود. مثلا ممکن است پیراهن XXL 5000 تومان گران تر از پیراهن XL باشد."
                                                                                placeholder="قیمت اقزایشی را وارد کنید..."
                                                                                class="form-control" id="attr-price">
                                                                        </div>
                                                                        <div class="col-xs-12 col-md-3">
                                                                            <button type="button" id="create-attribute"
                                                                                    class="btn btn-pink btn-xs">ثبت ویژگی
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <i class="fas fa-info"></i>
                                                        لیست ویژگی ها
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            @if(isset($attributes))
                                                            @foreach($attributes as $attribute)
                                                                <div class="col-xs-12">
                                                                    <div class="attribute-row-wrapper">
                                                                        <div class="row parent">
                                                                            <input type="hidden" value="{{ $attribute->id }}" class="id">
                                                                            <div class="col-xs-12 col-md-3">
                                                                                <select class="form-control type-attr">
                                                                                    <option {{ $attribute->attribute_id == 1 ? 'selected' : '' }} value="1">
                                                                                        وزن
                                                                                    </option>
                                                                                    <option {{ $attribute->attribute_id == 2 ? 'selected' : '' }} value="2">
                                                                                        رنگ
                                                                                    </option>
                                                                                    <option {{ $attribute->attribute_id == 3 ? 'selected' : '' }} value="3">
                                                                                        سایز
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-xs-12 col-md-3">
                                                                                <input type="text" data-toggle="tooltip"
                                                                                    title="نام ویژگی به عنوان مثال برای نوع ویژگی سایز می تواند مقادیر Large , XLarge , XXLarge را داشته باشد."
                                                                                    placeholder="نام ویژگی را وارد کنید."
                                                                                    class="form-control name-attr"
                                                                                    value="{{ $attribute->title }}">
                                                                            </div>
                                                                            <div class="col-xs-12 col-md-3">
                                                                                <input type="number" data-toggle="tooltip"
                                                                                    min="0"
                                                                                    title="میزان قیمتی که با انتخاب این ویژگی توسط مشتری به مبلغ فاکتور اضافه می شود. مثلا ممکن است پیراهن XXL 5000 تومان گران تر از پیراهن XL باشد."
                                                                                    placeholder="قیمت اقزایشی را وارد کنید..."
                                                                                    class="form-control price-attr"
                                                                                    value="{{ $attribute->extra_price }}">
                                                                            </div>
                                                                            <div class="col-xs-12 col-md-3">
                                                                                <div class="col-xs-12 col-md-6">
                                                                                    <button type="button"
                                                                                            class="btn btn-pink btn-xs edit-attribute">
                                                                                        ویرایش
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-xs-12 col-md-6">
                                                                                    <form action="{{ route('user.product.attributes.delete' , $attribute->id) }}"
                                                                                        method="post">
                                                                                        {{ csrf_field() }}
                                                                                        {{ method_field('delete') }}
                                                                                        <button type="submit"
                                                                                                class="btn btn-gray btn-xs delete-attribute">
                                                                                            حذف
                                                                                        </button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-shipping-fast"></i>
                                    زمان تحویل و ارسال
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="deliver_day_in_tehran"
                                                       class="control-label col-sm-3">زمان تحویل در تهران(روز)</label>
                                                <div class="col-sm-6">
                                                    <input type="number" name="deliver_day_in_tehran" min="0"
                                                           id="deliver_day_in_tehran"
                                                           value="{{ $product->deliver_time_in_tehran == 0 ? '' :  $product->deliver_time_in_tehran }}"
                                                           {{ $product->deliver_time_in_tehran == 0 ? 'disabled' : '' }}
                                                           class="form-control">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               for="deliver_today_in_tehran">تحویل در همان روز</label>
                                                        <input type="checkbox" id="deliver_today_in_tehran"
                                                               name="deliver_today_in_tehran"
                                                               {{ $product->deliver_time_in_tehran == 0 ? 'checked' : '' }}
                                                               class="deliver_today_in_tehran"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="deliver_day_in_other_towns"
                                                       class="control-label col-sm-3">زمان تحویل در شهرستان
                                                    ها(روز)</label>
                                                <div class="col-sm-6">
                                                    <input type="number" name="deliver_day_in_other_towns" min="0"
                                                           id="deliver_day_in_other_towns"
                                                           value="{{ $product->deliver_time_in_other_towns == 0 ? '' :  $product->deliver_time_in_other_towns }}"
                                                           {{ $product->deliver_time_in_other_towns == 0 ? 'disabled' : '' }}
                                                           class="form-control">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               for="deliver_day_in_other_towns_check">تحویل در همان
                                                            روز</label>
                                                        <input type="checkbox" id="deliver_day_in_other_towns_check"
                                                               {{ $product->deliver_time_in_other_towns == 0 ? 'checked' : '' }}
                                                               name="deliver_today_in_other_towns_check"
                                                               class="deliver_today_in_tehran"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="shipping_price_to_tehran"
                                                       class="control-label col-sm-3">هزینه ارسال به تهران
                                                    (تومان)</label>
                                                <div class="col-sm-6">
                                                    <input type="number" name="shipping_price_to_tehran" min="0"
                                                           id="shipping_price_to_tehran"
                                                           value="{{ $product->shipping_price_to_tehran == 0 ? '' :  $product->shipping_price_to_tehran }}"
                                                           {{ $product->shipping_price_to_tehran == 0 ? 'disabled' : '' }}
                                                           class="form-control">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                               for="delivery_in_tehran_without_price">بدون هزینه</label>
                                                        <input type="checkbox" id="delivery_in_tehran_without_price"
                                                               {{ $product->shipping_price_to_tehran == 0 ? 'checked' : '' }}
                                                               name="delivery_in_tehran_without_price"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="shipping_price_to_other_towns"
                                                       class="control-label col-sm-3">هزینه ارسال به شهرستان ها
                                                    (تومان)</label>
                                                <div class="col-sm-6">
                                                    <input type="number" name="shipping_price_to_other_towns" min="0"
                                                           id="shipping_price_to_other_towns"
                                                           value="{{ $product->shipping_price_to_other_towns == 0 ? '' :  $product->shipping_price_to_other_towns }}"
                                                           {{ $product->shipping_price_to_other_towns == 0 ? 'disabled' : '' }}
                                                           class="form-control">
                                                    <p class="text-danger error-container"></p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label data-toggle="tooltip"
                                                                                for="shipping_price_to_other_towns_checkbox">بدون
                                                            هزینه</label>
                                                        <input type="checkbox"
                                                               {{ $product->shipping_price_to_other_towns == 0 ? 'checked' : '' }}
                                                               id="shipping_price_to_other_towns_checkbox"
                                                               name="free_shipping_to_other_towns"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            <button type="submit"
                                                    class="btn btn-pink btn-bordered btn-border-hover">
                                                    @if(!$is_service)
                                                    ثبت محصول
                                                    @else
                                                    ثبت خدمت
                                                    @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('#price , #discount').keyup(function () {
            var price = $('#price');
            var discount = $('#discount');
            var finalPrice = $('#finalPrice');
            var calculatedPrice;
            var calculatedDiscount;
            if (isNaN(price.val())) {
                calculatedPrice = 0;
            } else {
                calculatedPrice = price.val();
            }
            if (isNaN(discount.val())) {
                calculatedDiscount = 0;
            } else {
                calculatedDiscount = discount.val();
            }
            var calculatedFinalPrice = calculatedPrice - (calculatedPrice * calculatedDiscount / 100);
            finalPrice.val(calculatedFinalPrice);
        });

        $('#updateProductForm').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 300
                },
                quantity: {
                    required: true,
                    number: true,
                    min: 0
                },
                category: {
                    required: true,
                    number: true
                },
                description: {
                    required: true,
                    maxlength: 2000
                },
                price: {
                    required: true,
                    number: true,
                    min: 0
                },
                discount: {
                    number: true,
                    min: 0,
                    max: 100
                }
            },
            messages: {
                name: {
                    required: 'نام محصول الزامی است.',
                    maxlength: 'نام محصول طولانی تر از حد مجاز است.'
                },
                quantity: {
                    required: 'موجودی انبار الزامی است.',
                    number: 'موجودی انبار باید به صورت عددی وارد شود.',
                    min: 'موجودی انبار باید حداقل مقدار صفر داشته باشد.'
                },
                category: {
                    required: 'انتخاب دسته بندی الزامی است.',
                    number: 'دسته بندی نامعتبر است.'
                },
                description: {
                    required: 'توضیحات الزامی است.',
                    maxlength: 'توضیحات طولانی تر از حد مجاز است.'
                },
                price: {
                    required: 'وارد کردن قیمت الزامی است.',
                    number: 'قیمت باید به صورت عددی وارد شود.',
                    min: 'قیمت باید حداقل مقدار صفر را داشته باشد.'
                },
                discount: {
                    number: 'تخفیف باید به صورت عددی وارد شود.',
                    min: 'تخفیف باید حداقل مقدار 0 را داشته باشد.',
                    max: 'تخفیف حداکثر می تواند مقدار 100 را داشته باشد.'
                }
            },
            errorPlacement: function (error, element) {
                var placeholder = element.closest('.form-group').find('.text-danger.error-container');
                error.appendTo(placeholder);
            },
            errorClass: 'error-container text-danger'
        })


        var switchery = document.querySelector('#guarantee_mark');
        var init = new Switchery(switchery);

        var switchery = document.querySelector('#deliver_today_in_tehran');
        var init = new Switchery(switchery);

        $('#deliver_today_in_tehran').on('change', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#deliver_day_in_tehran').attr('disabled', 'disabled');
            } else {
                $('#deliver_day_in_tehran').removeAttr('disabled');
            }
        });


        var switchery = document.querySelector('#deliver_day_in_other_towns_check');
        var init = new Switchery(switchery);

        $('#deliver_day_in_other_towns_check').on('change', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#deliver_day_in_other_towns').attr('disabled', 'disabled');
            } else {
                $('#deliver_day_in_other_towns').removeAttr('disabled');
            }
        });

        var switchery = document.querySelector('#delivery_in_tehran_without_price');
        var init = new Switchery(switchery);

        $('#delivery_in_tehran_without_price').on('change', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#shipping_price_to_tehran').attr('disabled', 'disabled');
            } else {
                $('#shipping_price_to_tehran').removeAttr('disabled');
            }
        });

        var switchery = document.querySelector('#shipping_price_to_other_towns_checkbox');
        var init = new Switchery(switchery);

        $('#shipping_price_to_other_towns_checkbox').on('change', function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                $('#shipping_price_to_other_towns').attr('disabled', 'disabled');
            } else {
                $('#shipping_price_to_other_towns').removeAttr('disabled');
            }
        });


    </script>
@endsection