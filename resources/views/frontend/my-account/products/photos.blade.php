@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | آپلود تصاویر محصولات</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid save-product-photo-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <form action="{{ route($product->store->store_type == 'product' ? 'user.product.upload.photo' : 'user.service.upload.photo' , $product->id) }}" method="post"
                          class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-xs-12 col-md-10 col-md-offset-1">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-camera-retro"></i>
                                    آپلود تصاویر
                                </div>
                                <div class="panel-body">
                                    @include('frontend.errors')
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="alert alert-warning text-center">
                                                @if($product->store->store_type == 'product')
                                                اولین تصویر
                                                آپلود شده، تصویر کاور محصول خواهد بود.
                                                @else
                                                اولین تصویر
                                                آپلود شده، تصویر کاور خدمت خواهد بود.
                                                @endif
                                            </div>
                                        </div>
                                        @foreach($photos as $photo)
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <div class="product-photo-wrapper margin-top-21">
                                                    <a
                                                            class="remove-product-photo-link"
                                                            data-toggle="tooltip" title="حذف تصویر"
                                                            href="{{ route('user.product.photo.delete' , $photo->id) }}">
                                                        <i class="fas fa-remove"></i>
                                                    </a>
                                                    <img class="image-placeholder"
                                                         src="{{ url()->to('/image/product_seller_photo/') }}/{{ $photo->file_name }}"
                                                         alt="product image placeholder">
                                                </div>
                                            </div>
                                        @endforeach
                                        @for($i = 1 ; $i <= 8 - count($photos) ; $i ++)
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <div class="product-photo-wrapper">
                                                    <input type="file" name="file[]" id="file-{{ $i }}"
                                                           class="selected-image">
                                                    <label for="file-{{ $i }}" data-toggle="tooltip"
                                                           title="انتخاب تصویر"
                                                           data-placement="right">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </label>
                                                    <img class="image-placeholder"
                                                         src="{{ url()->to('/image/shop_pattern.png') }}"
                                                         alt="product image placeholder">
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            <div style="margin:20px;">
                                                <button type="submit" id="submit-form" class="btn btn-pink">
                                                    @if(count($photos) > 0)
                                                        ویرایش تصاویر
                                                    @else
                                                        ثبت تصاویر
                                                    @endif
                                                </button>
                                            </div>
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
        $('.selected-image').change(function () {
            var input = this;
            var $this = $(input);
            var placeholder = $this.closest('.product-photo-wrapper').find('.image-placeholder');
            if (input.files && input.files[0]) {
                var extension = getFileExtension(input.files[0].name.toLowerCase());
                if (extension !== 'jpg' && extension !== 'png') {
                    bootoast.toast({
                        message: 'خطا! فرمت تصویر پشتیبانی نمی شود.',
                        type: 'danger'
                    });
                    return false;
                }
                var reader = new FileReader();

                reader.onload = function (e) {
                    placeholder.attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                placeholder.attr('src', '{{ url()->to('/image/shop_pattern.png') }}')
            }
        });

        function getFileExtension(fileName) {
            return (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName)[0] : undefined;
        }
    </script>
@endsection