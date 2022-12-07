@extends('admin.master')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ url()->current() }}" method="get">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="product_name" class="control-label">نام محصول</label>
                                        <input type="text" name="keyword" id="product_name"
                                               value="{{ request()->input('keyword') }}" class="form-control">
                                        @if($errors->has('keyword'))
                                            <b class="text-danger">{{ $errors->first('keyword') }}</b>
                                        @endif
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="product_id" class="control-label">انتخاب دسته محصول</label>
                                        <select name="category" id="category" class="js-data-example-ajax form-control">
                                            <option value="all" selected="selected">همه</option>
                                            @foreach($categories as $category)
                                                <option {{ $category->id == request()->input('category') ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <script>
                                        $('#category').change(function () {
                                            var value = $(this).val();
                                            $.ajax({
                                                type: 'get',
                                                url: '{{ url()->to('/admin/categories') }}/' + value + '/subcategory',
                                                data: {},
                                                success: function (response) {
                                                    var list = response;
                                                    var overloads = $('#subCategory');
                                                    if (list.length > 0) {
                                                        overloads.html('<option value="all">همه</option>');
                                                        for (var i = 0; i < list.length; i++) {
                                                            overloads.append('<option value="' + list[i].id + '">' + list[i].name + '</option>');
                                                        }
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                    <div class="col-sm-4">
                                        <label for="product_id" class="control-label">انتخاب زیر دسته محصول</label>
                                        <select name="subCategory" id="subCategory"
                                                class="js-data-example-ajax form-control">
                                            <option value="all" selected="selected">همه</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">تخفیف</label>
                                        <input style="font-size: 12px;"
                                               oninvalid="setCustomValidity('تخفیف را بصورت عددی وارد کنید')"
                                               type="number" name="discount" class="form-control"
                                               value="{{ request()->input('discount') }}"
                                               placeholder="درصد تخفیف را وارد کنید">
                                        @if($errors->has('discount'))
                                            <b class="text-danger">{{ $errors->first('discount') }}</b>
                                        @endif
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="control-label">قیمت</label><br>

                                        <input type="number"
                                               oninvalid="setCustomValidity('قیمت را بصورت عددی وارد کنید')"
                                               style="width: 50%;font-size: 12px;" name="from"
                                               value="{{ request()->input('from') }}" id="from"
                                               class="form-control col-lg-12" placeholder="قیمت از">
                                        @if($errors->has('from'))
                                            <b class="text-danger">{{ $errors->first('from') }}</b>
                                        @endif

                                        <input type="number"
                                               oninvalid="setCustomValidity('قیمت را بصورت عددی وارد کنید')"
                                               style="width: 50%;font-size: 12px;" value="{{ request()->input('to') }}"
                                               name="to" id="to" class="form-control col-lg-12" placeholder="قیمت تا">
                                        @if($errors->has('to'))
                                            <b class="text-danger">{{ $errors->first('to') }}</b>
                                        @endif

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <input id="checkbox0" name="visible" type="checkbox">
                                            <label for="checkbox0">نمایش محصولات قابل مشاهده</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">اعمال
                                            فیلتر
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="m-t-0 header-title"><b>لیست محصولات</b></h4>
                                    <p class="text-muted font-13">
                                    </p>

                                    <div class="p-20">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام محصول</th>
                                                    <th>توضیحات</th>
                                                    <th>تعداد محصول</th>
                                                    <th>قیمت</th>
                                                    <th>تخفیف</th>
                                                    <th>دسته</th>
                                                    <th>زیر دسته</th>
                                                    <th>قابل مشاهده</th>
                                                    <th>تنظیمات</th>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach($products as $product)

                                                    <tr>
                                                        <th scope="row">{{ $i }}</th>
                                                        <td>{{ $product->name }}</td>
                                                        <td>
                                                            <textarea style="text-align: right;" class="form-control"
                                                                      cols="70" rows="10">
                                                                {{ $product->description }}
                                                            </textarea>
                                                        </td>
                                                        <td>{{ $product->quantity }}</td>
                                                        <td>{{ number_format($product->price) }}</td>
                                                        <td>{{ $product->discount }}</td>
                                                        <td>{{ $product->category_name }}</td>
                                                        <td>{{ $product->sub_cat_name }}</td>
                                                        <td>
                                                            @if($product->visible == '1')
                                                                <?= 'بله'; ?>
                                                            @else
                                                                <?= 'خیر'; ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group mr-2" role="group"
                                                                 aria-label="First group">
                                                                <a href="{{ route('deleteProductById' , $product->id) }}">
                                                                    <button type="button"
                                                                            @if($product->show_msg_for_deleting_product)
                                                                            {{--data-show-msg-for-remove-slider--}}
                                                                                    onclick="return confirm('در صورت حذف این محصول اسلایدر های آن نیز حذف خواهد شد. آیا مطمئن هستید؟' , true , false)"
                                                                            @endif
                                                                            class="btn btn-danger btn-xs">حذف
                                                                    </button>
                                                                </a>
                                                                <a href="{{ route('editProduct' , $product->id) }}">
                                                                    <button type="button"
                                                                            class="btn btn-success btn-xs">ویرایش
                                                                    </button>
                                                                </a>
                                                                <a href="{{ route('showProductsPhotoEditPage' , $product->id) }}">
                                                                    <button type="button"
                                                                            class="btn btn-primary btn-xs">تصاویر
                                                                    </button>
                                                                </a>
                                                                <a href="{{ route('showListOfUserComment' , $product->id) }}">
                                                                    <button type="button"
                                                                            class="btn btn-default btn-xs">نظرها
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($products) > 0)
                    <div class="row">
                        <div class="col-xs-12">
                            <ul class="pagination pagination-split">
                                @if($products->currentPage() != 1)
                                    <li>
                                        <a href="{{ $products->previousPageUrl() }}"><i
                                                    class="fa fa-angle-left"></i></a>
                                    </li>
                                @endif
                                @for($i =1 ; $i <= $products->lastPage() ; $i++)
                                    <li class="{{ $i == $products->currentPage() ? 'active' : '' }}">
                                        <a href="{{ $products->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                @if($products->currentPage() != $products->lastPage())
                                    <li>
                                        <a href="{{ $products->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>


@endsection

{{--@section('styles')

    <style>

    </style>
@endsection--}}

@section('scripts')
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"
            type="text/javascript"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"
            type="text/javascript"></script>

    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    {{--<script type="text/javascript" src="{{ url()->to('/admin') }}/assets/pages/autocomplete.js"></script>--}}

{{--
    <script>

        $('[data-show-msg-for-remove-slider]').click(function () {
            if (!confirm('در صورت حذف این محصول اسلایدر های آن نیز حذف خواهد شد. آیا مطمئن هستید؟')) {
                return false;
            }
        });
    </script>
--}}


@endsection