@extends('admin.master')
@section('styles')
    <style>
        table tbody tr th {
            font-size: 13px !important;
            font-weight: normal !important;
            color: #202020 !important;

        }

        table thead tr th {
            font-size: 13px !important;
            font-weight: bold !important;
            color: #000 !important;
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
                        <form action="{{ url()->current() }}" method="get">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="name" class="control-label">نام و نام خانوادگی</label>
                                        <input type="text" name="name" id="name"
                                               placeholder="نام یا نام خانوادگی را وارد کنید"
                                               value="{{ request()->input('name') }}" class="form-control input-sm">
                                        @if($errors->has('name'))
                                            <b class="text-danger">{{ $errors->first('name') }}</b>
                                        @endif
                                    </div>

                                    <div class="col-sm-3">
                                        <label class="control-label">موبایل</label>
                                        <input type="text" name="mobile" class="form-control input-sm"
                                               value="{{ request()->input('mobile') }}"
                                               placeholder="شماره موبایل را وارد کنید">
                                        @if($errors->has('mobile'))
                                            <b class="text-danger">{{ $errors->first('mobile') }}</b>
                                        @endif
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label class="control-label">ایمیل</label><br>
                                        <input type="email" name="email" value="{{ request()->input('email') }}"
                                               id="from" class="form-control input-sm" placeholder="ایمیل را وارد کنید">
                                        @if($errors->has('email'))
                                            <b class="text-danger">{{ $errors->first('email') }}</b>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-facebook">اعمال فیلتر</button>
                                        <a href="{{ url()->current() }}" class="btn btn-sm btn-linkedin">حذف فیلترها</a>
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
                                    @if(count($errors->all()) > 0)
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="alert alert-danger text-center">
                                                    @foreach($errors->all() as $error)
                                                        {{ $error }} <br/>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(session('success_msg'))
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="alert alert-success text-center">
                                                    {{ session('success_msg') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <h4 class="m-t-0 header-title"><b>لیست کاربران بازاریاب</b></h4>
                                    <p class="text-muted font-13"></p>

                                    <div class="p-10">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام</th>
                                                    <th>تنظیمات</th>
                                                    <th>موبایل</th>
                                                    <th>ایمیل</th>
                                                    <th>کدشبا</th>
                                                    <th>موقعیت فروشگاه</th>
                                                    <th>موجودی کیف پول (تومان)</th>
                                                    <th>تاریخ ثبت نام</th>
                                                </thead>
                                                <?php $i = 1; ?>
                                                <tbody id="sortable-list">
                                                @foreach($users as $user)

                                                    <tr>
                                                        <th scope="row">{{ $i }}</th>
                                                        <th>{{ $user->first_name }}&nbsp;{{ $user->last_name }}</th>
                                                        <th>
                                                            <button type="button" class="btn btn-linkedin"
                                                                    data-toggle="modal"
                                                                    data-target="#user_{{ $user->id }}_option_buttons">
                                                                گزینه ها
                                                            </button>
                                                        </th>
                                                        <th>{{ $user->mobile }}</th>
                                                        <th>{{ $user->email == null ? 'ثبت نشده' : $user->email }}</th>
                                                        <th>{{ $user->shaba_code == null ? 'ثبت نشده' : $user->shaba_code }}</th>
                                                        <th>
                                                            @if($user->province_name && $user->city_name)
                                                                <span class="badge badge-success">{{ $user->province_name }} - {{ $user->city_name }}</span>
                                                            @else
                                                                <span class="badge badge-danger">
                                                                فاقد فروشگاه
                                                                </span>
                                                            @endif
                                                        </th>
                                                        <th>{{ $user->total_credit }} </th>
                                                        <th>{{ \Morilog\Jalali\Jalalian::forge($user->marketer_created_at)->format('Y/m/d') }}</th>

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

                {{ $users->links() }}
            </div>
        </div>
    </div>

    @foreach($users as $user)

        <div class="modal fade" id="user_{{ $user->id }}_option_buttons" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('editUser' , $user->id) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">ویرایش</a>
                            </div>
                            <div class="col-md-6">
                                <button data-toggle="modal"
                                        data-quick-edit-user-button
                                        data-target="#quick_edit_user_{{ $user->id }}" type="button"
                                        style="margin-bottom:20px;"
                                        class="btn btn-facebook btn-block">ویرایش سریع
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('marketer.wallet' , $user->id) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">کیف
                                    پول / تسویه</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('detach.marketer.user' , $user->id) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">حذف</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('message.index' , $user->id) }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">ارسال
                                    پیام</a>
                            </div>
                            <div class="col-md-6">
                                <a href="#"
                                   data-toggle="modal"
                                   data-target="#send_quick_message_for_user_{{ $user->id }}"
                                   style="display:inline-block;margin-bottom:20px;" class="btn btn-facebook btn-block">ارسال
                                    پیام سریع</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="quick_edit_user_{{ $user->id }}" tabindex="-1" role="dialog"
             data-user-id="{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ویرایش سریع اطلاعات کاربر</h4>
                    </div>
                    <form action="{{ route('admin.user_info.quick_update' , $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_first_name">نام:</label>
                                <input type="text" name="first_name" id="user_{{ $user->id }}_first_name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_last_name">نام خانوادگی:</label>
                                <input type="text" name="last_name" id="user_{{ $user->id }}_last_name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_password">رمز عبور:</label>
                                <input type="password" name="password" id="user_{{ $user->id }}_password"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_shaba_code">شماره شبا:</label>
                                <input type="text" name="shaba_code" id="user_{{ $user->id }}_shaba_code"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="user_{{ $user->id }}_email">ایمیل:</label>
                                <input type="email" name="email" id="user_{{ $user->id }}_email" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-default">ریست کردن فرم</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="send_quick_message_for_user_{{ $user->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ارسال پیام سریع</h4>
                    </div>
                    <form action="{{ route('admin.send_quick_message_to_user') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <label for="quick_msg_for_user_{{ $user->id }}">متن پیام:</label>
                                <textarea name="message" class="form-control" id="quick_msg_for_user_{{ $user->id }}"
                                          cols="30" rows="5" placeholder="متن پیام را وارد کنید..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-default">ریست کردن فرم</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('scripts')
    <script>
        $('[data-quick-edit-user-button]').click(function () {
            var $this = $(this);
            var modal = $($this.attr('data-target'));
            $.ajax({
                type: 'GET',
                url: '{{ url()->to('/admin/users/') }}/' + modal.data('user-id') + '/info',
                success: function (response) {
                    modal.find('#user_' + modal.data('user-id') + '_first_name').val(response.first_name);
                    modal.find('#user_' + modal.data('user-id') + '_last_name').val(response.last_name);
                    modal.find('#user_' + modal.data('user-id') + '_shaba_code').val(response.shaba_code);
                    modal.find('#user_' + modal.data('user-id') + '_email').val(response.email);
                }
            })
        });
    </script>
@endsection