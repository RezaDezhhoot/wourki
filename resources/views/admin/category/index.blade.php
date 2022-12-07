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

        .list-unstyled li {
            font-size: 12px;
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
                            @if(count($errors) > 0)
                                <div class="alert alert-danger text-center">
                                    @foreach($errors->all() as $error)
                                        {{ $error }} <br>
                                    @endforeach
                                </div>
                            @endif
                            <h4 class="m-t-0 header-title"> افزودن دسته جدید به صنف <span
                                        class="text-success">{{ $guild->name }}</span></h4>
                            <br>
                            <form role="form" action="{{ route('saveCategory') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <input type="hidden" name="guild" value="{{ $guild->id }}">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category">نام دسته</label>
                                            <input name="name" required value="{{ old('name') }}" type="text"
                                                   class="form-control input-sm" id="category"
                                                   placeholder="نام دسته جدید را وارد کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category">پورسانت</label>
                                            <input name="commission" required value="{{ old('commission') }}"
                                                   type="number" min="0" max="99"
                                                   class="form-control input-sm" id="category"
                                                   placeholder="میزان پورسانت را وارد کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="photo">تصویر</label>
                                            <input name="photo" type="file"
                                                   class="form-control input-sm" id="photo">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button style="margin-top: 25px;" type="submit"
                                                    class="btn btn-sm btn-purple waves-effect waves-light">ثبت
                                            </button>
                                            <a style="margin-top: 25px;" href="{{ route('guildList') }}"
                                               class="btn btn-sm btn-default waves-effect waves-light">بازگشت</a>
                                        </div>
                                    </div>
                                </div>
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
                                    <h4 class="m-t-0 header-title"><b> دسته بندی های صنف <span
                                                    class="text-success">{{ $guild->name }}</span></b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        @if(count($categories) > 0)
                                            <form id="order-form" action="">
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام دسته</th>
                                                        <th>پورسانت</th>
                                                        <th>تصویر</th>
                                                        <th>زیر دسته ها</th>
                                                        <th>اختیارات</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody id="sortable-list">

                                                    <?php $id = 1; ?>
                                                    @foreach($categories as $category)
                                                        <tr>
                                                            <td scope="row">{{ $id }}</td>
                                                            <td>{{ $category->name }}</td>
                                                            <td>{{ $category->commission }}</td>
                                                            <td>
                                                                @if($category->icon)
                                                                    <img src="{{ url()->to('/icon') . '/' . $category->icon }}"
                                                                         width="80px" alt="">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a style="font-weight: bold;"
                                                                   href="{{ route('showSubCategories' , $category->id) }}">مشاهده
                                                                    زیر دسته ها</a>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-xs btn-info" data-toggle="modal"
                                                                   data-target="#update-category-{{ $category->id }}-modal">ویرایش</a>
                                                                <a class="btn btn-xs btn-danger"
                                                                   href="{{ route('deleteCategory' , $category->id) }}">حذف</a>
                                                            </td>
                                                        </tr>
                                                        <?php $id++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </form>
                                        @else
                                            <div class="alert alert-danger text-center">
                                                <p>زیر دسته ای یافت نشد!</p>
                                            </div>
                                        @endif
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
    @foreach($categories as $category)
        <div id="update-category-{{ $category->id }}-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title"> ویرایش دسته مربوط صنف <span
                                    class="text-success">{{ $guild->name }}</span></h4>
                    </div>
                    <form action="{{ route('updateCategory' , $category->id) }}" method="post"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category-{{ $category->id }}" class="control-label">نام دسته</label>
                                        <input name="name" required value="{{ $category->name }}" type="text"
                                               class="form-control input-sm" id="category-{{ $category->id }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category-{{ $category->commission }}" class="control-label">پورسانت
                                            دسته</label>
                                        <input name="commission" required value="{{ $category->commission }}"
                                               type="text"
                                               class="form-control input-sm" id="category-{{ $category->commission }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category-{{ $category->id }}_photo"
                                               class="control-label">تصویر</label>
                                        <input name="photo" type="file"
                                               class="form-control input-sm" id="category-{{ $category->id }}_photo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-info waves-effect waves-light">ذخیره تغیرات
                            </button>
                            <button type="button" class="btn btn-sm btn-default waves-effect" data-dismiss="modal">
                                بستن
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection