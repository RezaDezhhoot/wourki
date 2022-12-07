@extends('admin.master')
@section('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet"
    />
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
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ url()->current() }}" method="get">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="user" class="control-label">انتخاب کاربر</label>
                                        <select dir="rtl" name="user" id="user"
                                                class="input-sm form-control">
                                            <option value="all">::انتخاب کنید::</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="store">انتخاب فروشگاه</label>
                                            <select dir="rtl" name="store" id="store">
                                                <option value="all">:: انتخاب کنید ::</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user_mobile">تلفن همراه کاربر</label>
                                            <input type="text" name="user_mobile" id="user_mobile" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox"
                                               name="filter_unread_msg"
                                               {{ request()->has('filter_unread_msg') ? 'checked' : '' }}
                                               id="filter_unread_msg">
                                        <label for="filter_unread_msg" class="control-label">نمایش پیام های خوانده نشده</label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-facebook">اعمال فیلتر</button>
                                        <a href="{{ url()->current() }}" class="btn btn-sm btn-linkedin">حذف فیلترها</a>
                                        <a href="{{ route('admin.messages.batchDelete.show') }}" class="btn btn-sm btn-pinterest">حذف انبوه پیام ها</a>
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
                                    @include('frontend.errors')
                                    <form action="{{ route('batch.send.message') }}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <h4 class="m-t-0 header-title"><b>کاربران</b></h4><br>
                                        <p class="text-muted font-13"></p>
                                        <div class="p-20">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkAll"></th>
                                                    <th>ردیف</th>
                                                    <th>کاربر</th>
                                                    <th>ثبت نام از طریق</th>
                                                    <th> فروشگاه ها</th>
                                                    <th>شماره موبایل</th>
                                                    <th>تعداد پیام های خوانده نشده</th>
                                                    <th>آخرین پیام ارسال شده</th>
                                                    <th>تاریخ آخرین پیام</th>
                                                    <th>مشاهده پیام ها</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                @foreach($usersList as $index => $user)
                                                    @if($user->unread_message_count > 0)
                                                        <tr style="background-color: aquamarine !important;">
                                                    @else
                                                        <tr>
                                                    @endif
                                                            <th><input type="checkbox" name="userId[]"
                                                                       value="{{ $user->id }}" class="userId"></th>
                                                            <th scope="row">{{ ++$index }}</th>
                                                            <th>{{ $user->first_name .' '. $user->last_name }}</th>
                                                            <th>{{is_null($user->register_from) ? "نا معلوم" : ($user->register_from == "android" ? "اپلیکیشن اندروید" : "وب سایت")}}</th>
                                                            <th>
                                                                @php
                                                                 $stores = $user->stores;   
                                                                @endphp
                                                                @if(count($stores) > 0)
                                                                @foreach ($stores as $i => $s)
                                                                
                                                                    <a style="font-weight: bold;"
                                                                       href="{{ route('listOfProductSeller' , $s->user_name) }}">{{ $s->name }}</a>
                                                                    @if($i != count($user->stores) - 1)
                                                                        -
                                                                    @endif
                                                                    @endforeach
                                                                @else ثبت نشده
                                                                @endif
                                                            </th>
                                                            <th>{{ $user->mobile }}</th>
                                                            <th>{{ $user->unread_message_count }}</th>
                                                            <th>{{ $user->last_message }}</th>
                                                            <th>{{ \Morilog\Jalali\Jalalian::forge($user->last_message_datetime)->format('Y-m-d H:i:s') }}</th>
                                                            <th><a style="font-weight: bold;"
                                                                   href="{{ route('message.index' , ['user' => $user->id]) }}">مشاهده</a>
                                                            </th>
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                            </table>
                                            {{ $usersList->links() }}
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="message">متن پیام</label>
                                                <textarea class="form-control" name="message" id="message" cols="30"
                                                          rows="10" required></textarea>
                                                    <input class="filepond" 
                                                        data-allow-reorder="true"
                                                        data-max-file-size="3MB"
                                                        name="file" type="file" />
                                                    <input type="checkbox" name="send_sms" id="send_sms" />
                                                    <label for="send_sms">پیامک برای کاربران ارسال شود</label>
                                                {{--<input type="text" class="form-control input-sm" name="message" id="message" required>--}}
                                            </div>
                                            <div class="col-md-4" style="margin-top: 25px;display:flex;flex-direction:column;justify-content:center;align-items:center">
                                                <button type="submit" class="btn btn-facebook btn-sm"
                                                        name="selectedUser" style="margin-top:5px;width:100%">ارسال به کاربران منتخب
                                                </button>
                                                <button type="submit" class="btn btn-pinterest btn-sm" style="margin-top:5px;width:100%" name="allUser">
                                                    ارسال به تمام کاربران
                                                </button>
                                                <button type="submit" class="btn btn-linkedin btn-sm" style="margin-top:5px;width:100%" name="allStore">
                                                    ارسال به تمام کاربران فروشگاه
                                                </button>
                                                <button type="submit" class="btn btn-sm" style="margin-top:5px;width:100%;background-color:#d19a04;color:white" name="allProductStore">
                                                     ارسال به تمام کاربران فروشگاه های محصولات
                                                </button>
                                                <button type="submit" class="btn btn-sm" style="margin-top:5px;width:100%;background-color:#10c948;color:white" name="allServiceStore">
                                                    ارسال به تمام کاربران فروشگاه های خدمات
                                                </button>
                                                <button type="submit" class="btn btn-sm" style="margin-top:5px;width:100%;background-color:#c910c6;color:white" name="allMarkets">
                                                    ارسال به تمام کاربران فروشگاه های بازاریابی
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#checkAll').click(function () {
                if ($(this).prop('checked'))
                    $('.userId').prop('checked', true);
                else
                    $('.userId').prop('checked', false);
            });

        });


        $('#user').select2({
            placeholder: 'بخشی از نام کاربر را وارد کنید...',
            ajax: {
                url: '{{ route('users.get_via_ajax.in_support_page') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.first_name + ' ' + item.last_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: false
            }
        });
        $('#store').select2({
            placeholder: 'بخشی از نام فروشگاه را وارد کنید...',
            ajax: {
                url: '{{ route('stores.get_via_ajax.in_support_page') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: false
            }
        });

    </script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        // Get a reference to the file input element
        var inputElement = document.querySelector('input[type="file"]');
        FilePond.registerPlugin(FilePondPluginImagePreview)
        // Create a FilePond instance
        var pond = FilePond.create(
            inputElement,
            {
                labelIdle : '<div style="display:flex;justify-content:center;align-items:center;cursor:pointer"><span style="font-size:12px;margin-right:10px">جهت درج تصویر لطفا فایل خود را به اینجا بکشید و یا کلیک کنید </span> <i class="fa fa-camera" style="font-size:20px"></i></div>',
                imagePreviewHeight: 170,
                stylePanelLayout: 'compact',
                storeAsFile:true,
                instantUpload:false,
                credits : null
            });
    </script>
@endsection