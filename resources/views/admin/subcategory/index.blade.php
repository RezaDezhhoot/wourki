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
                            <h4 class="m-t-0 header-title"><b> افزودن زیر دسته جدید به دسته بندی <span
                                            class="text-danger">{{ $category->name }}</span></b></h4>
                            <br>
                            <form role="form" action="{{ route('saveSubCategory' , $category->id) }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sub-category">نام زیر دسته</label>
                                            <input name="name" required value="{{ old('name') }}" type="text"
                                                   class="form-control input-sm" id="sub-category"
                                                   placeholder="نام دسته جدید را وارد کنید...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="commission">پورسانت</label>
                                            <input name="commission" required value="{{ old('commission') }}"
                                                   type="number" min="0" max="99"
                                                   class="form-control input-sm" id="commission"
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
                                            <a style="margin-top: 25px;"
                                               href="{{ route('categoryOfGuild' , $guild->id) }}"
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
                                    <h4 class="m-t-0 header-title"><b>زیر دسته های دسته بندی <span
                                                    class="text-danger">{{ $category->name }}</span></b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        @if(count($sub_categories) > 0)
                                            <form action="" id="order-form">
                                                <table class="table table-striped m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>نام زیر دسته</th>
                                                        <th>پورسانت</th>
                                                        <th>تصویر</th>
                                                        <th>اختیارات</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="sortable-list">
                                                    <?php $id = 1; ?>
                                                    @foreach($sub_categories as $sub_category)
                                                        <tr>
                                                            <td scope="row">{{ $id }}</td>
                                                            <td>{{ $sub_category->name }}</td>
                                                            <td>{{ $sub_category->commission }}</td>
                                                            <td>
                                                                @if($sub_category->icon)
                                                                    <img src="{{ url()->to('/icon') . '/' . $sub_category->icon }}"
                                                                         width="80px" alt="">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-xs btn-info" data-toggle="modal"
                                                                   data-target="#update-category-{{ $sub_category->id }}-modal">ویرایش</a>
                                                                <a class="btn btn-xs btn-danger"
                                                                   href="{{ route('deleteSubCategory' , $sub_category->id) }}">حذف</a>
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
    @foreach($sub_categories as $sub_category)
        <div id="update-category-{{ $sub_category->id }}-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">ویرایش زیر دسته</h4>
                    </div>
                    <form action="{{ route('updateSubCategory' , $sub_category->id) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category-{{ $sub_category->id }}" class="control-label">نام زیر
                                            دسته</label>
                                        <input name="name" value="{{ $sub_category->name }}" type="text"
                                               class="form-control" id="category-{{ $sub_category->id }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="commission-{{ $sub_category->commission }}" class="control-label">پورسانت
                                            زیر دسته</label>
                                        <input name="commission" required value="{{ $sub_category->commission }}"
                                               type="text"
                                               class="form-control input-sm"
                                               id="commission-{{ $sub_category->commission }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category-{{ $sub_category->id }}_image"
                                               class="control-label">تصویر</label>
                                        <input name="image" value="{{ $sub_category->name }}" type="file"
                                               class="form-control" id="category-{{ $sub_category->id }}_image">
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