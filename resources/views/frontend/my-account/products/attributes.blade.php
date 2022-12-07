@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | ویژگی های محصول</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid save-product-attribute-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="form-horizontal">
                        <div class="col-xs-10 col-md-8 col-xs-offset-1 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-info"></i>
                                    لیست ویژگی ها
                                </div>
                                <div class="panel-body">
                                    <div class="row">
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
                                    </div>
                                </div>
                            </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            $('.edit-attribute').click(function (e) {
                e.preventDefault();
                var id = $(this).closest('.parent').find('.id').val();
                var type = $(this).closest('.parent').find('.type-attr').val();
                var name = $(this).closest('.parent').find('.name-attr').val();
                var price = $(this).closest('.parent').find('.price-attr').val();

                $.ajax({
                    url: '{{ route('user.product.attributes.edit') }}',
                    type: 'post',
                    data: {
                        'type': type,
                        'name': name,
                        'price': price,
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },

                    success: function () {
                        swal("تبریک", "ویژگی با موفقیت ویرایش شد.", "success");
                    }
                });
            });

            $('#create-attribute').click(function () {
                var type = $('#type');
                var name = $('#attr-name');
                var price = $('#attr-price');

                $.ajax({
                    url: '{{ route('user.product.attributes.store' , $product->id) }}',
                    type: 'post',
                    data: {
                        'type': type.val(),
                        'name': name.val(),
                        'price': price.val(),
                        '_token': '{{ csrf_token() }}',
                    },

                    success: function () {
                        location.reload();
                        swal("تبریک", "ویژگی با موفقیت برای این محصول ثبت شد.", "success");
                    }
                });
            });
        });
    </script>
@endsection