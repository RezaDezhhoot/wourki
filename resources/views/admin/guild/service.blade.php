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

        .dropdown-menu li a {
            border-radius: 0;
        }

        .list-unstyled li, textarea {
            font-size: 12px !important;
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
                            <h4 class="m-t-0 header-title"><b>افزودن صنف خدماتی</b></h4><br>
                            @if($errors->any())
                                <div class="alert alert-danger text-center">
                                    <ul class="list-unstyled">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form role="form" action="{{ route('createGuild') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="name">نام صنف <span style="color: red;">*</span></label>
                                            <input name="name" value="{{ old('name') }}" type="text"
                                                   class="form-control input-sm" id="name"
                                                   placeholder="نام صنف را وارد کنید..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="photo">تصویر </label>
                                            <input name="photo" type="file"
                                                   class="form-control input-sm" id="photo">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button name="guild_type" value="service" type="submit" style="margin-top: 25px;"
                                                    class="btn btn-block btn-sm btn-purple waves-effect waves-light">ثبت
                                            </button>
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
                                    <h4 class="m-t-0 header-title"><b>لیست اصناف خدمات</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-20">
                                        <form id="order-form" action="">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام صنف</th>
                                                    <th>تصویر</th>
                                                    <th>اختیارات</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                <?php $id = 1; ?>
                                                @foreach($guilds as $guild)
                                                    <tr>
                                                        <th scope="row">{{ $id }}</th>
                                                        <th>{{ $guild->name }}</th>
                                                        <th>
                                                            @if($guild->pic)
                                                                <img width="80px" src="{{ url()->to('/icon') . '/' . $guild->pic }}"
                                                                     alt="guild photo">
                                                            @endif
                                                        </th>
                                                        <th>
                                                            <div class="btn-group m-b-20">
                                                                <div class="btn-group">
                                                                    <a data-toggle="modal"
                                                                       data-target="#update-category-{{ $guild->id }}-modal"
                                                                       class="btn btn-xs btn-info">ویرایش</a>
                                                                    <a href="{{ route('deleteGuild' , $guild->id) }}"
                                                                       class="btn btn-xs btn-danger">حذف</a>
                                                                    <a href="{{ route('categoryOfGuild' , $guild->id) }}"
                                                                       class="btn btn-xs btn-default">مشاهده دسته ها</a>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <?php $id++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            {{ $guilds->links() }}
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
    @foreach($guilds as $guild)
        <div id="update-category-{{ $guild->id }}-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">ویرایش صنف</h4>
                    </div>

                    <form action="{{ route('updateGuild') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <input type="hidden" name="id" value="{{ $guild->id }}">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="control-label"> نام صنف <span style="color: red;">*</span></label>
                                    <input name="name" value="{{ $guild->name }}" type="text"
                                           class="form-control input-sm"
                                           id="name" placeholder="نام صنف را وارد کنید..." required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="photo" class="control-label"> تصویر</label>
                                    <input name="photo" type="file"
                                           class="form-control input-sm"
                                           id="photo">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-sm btn-info waves-effect waves-light">ذخیره
                                        تغیرات
                                    </button>
                                    <button type="button" class="btn btn-sm btn-default waves-effect"
                                            data-dismiss="modal">بستن
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection