@extends('admin.master')
@section('styles')
    <style>
        .options-list {
            padding-top: 20px;
        }

        .options-list span {
            background-color: #ccc;
            border: 1px solid #aaa;
            padding: 4px 10px;
            display: inline-block;
            margin-left: 15px;
            font-weight: bold;
            color: #000;
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
                            <h4 class="m-t-0 header-title"><b>تنظیمات</b></h4><br>
                            @include('frontend.errors')
                            <form role="form" action="{{ url()->to('/setting') }}" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reagent"> مبلغ کاربر معرفی شده (عادی)<span
                                                        style="color: red;">*</span></label>
                                            <input name="reagent" value="{{ $setting->reagent_user_fee }}" type="number"
                                                   class="form-control input-sm" id="reagent" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="reagentCreateStore"> مبلغ کاربر معرف وقتی کاربر معرفی شده
                                                فروشگاه میسازد <span style="color: red;">*</span></label>
                                            <input data-toggle="tooltip"
                                                   title="این مبلغ زمانیکه کاربر معرفی شده توسط کاربر معرف فروشگاه خود را ایجاد میکند، به کاربر معرف داده میشود."
                                                   name="reagentCreateStore"
                                                   value="{{ $setting->reagent_user_create_store }}" type="number"
                                                   class="form-control input-sm" id="reagentCreateStore" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="reagented">مبلغ کاربر معرف<span
                                                        style="color: red;">*</span></label>
                                            <input data-toggle="tooltip" name="reagented"
                                                   title="این مبلغ به کاربری که توسط کد معرف او، کاربر دیگری داخل سایت ثبت نام میکند به کاربر داده میشود."
                                                   value="{{ $setting->reagented_user_fee }}" type="number"
                                                   class="form-control input-sm" id="reagented" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="register_gift">مبلغ هدیه به هنگام ثبت نام<span
                                                        style="color: red;">*</span></label>
                                            <input data-toggle="tooltip" name="register_gift"
                                                   title="این مبلغ به عنوان هدیه به فردی که به تازگی ثبت نام کرده است داده می شود."
                                                   value="{{ $setting->register_gift }}" type="number"
                                                   class="form-control input-sm" id="register_gift" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="app_version">ورژن اپلیکیشن
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input data-toggle="tooltip" name="app_version"
                                                   title="این مورد به هنگام جهت به روز رسانی اپلیکیشن مورد استفاده قرار می گیرد."
                                                   value="{{ $setting->app_version }}" type="text"
                                                   class="form-control input-sm" id="app_version" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="first_buy_gift">مبلغ هدیه به ازای اولین خرید<span
                                                        style="color: red;">*</span></label>
                                            <input data-toggle="tooltip" name="first_buy_gift"
                                                   title="این مبلغ به عنوان هدیه به فردی که کاربر معرفی شده اش اولین خرید را انجام میدهد داده میشود."
                                                   value="{{ $setting->first_buy_gift }}" type="number"
                                                   class="form-control input-sm" id="first_buy_gift" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="first_sell_gift">مبلغ هدیه به ازای اولین فروش
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input data-toggle="tooltip" name="first_sell_gift"
                                                   title="این مبلغ به عنوان هدیه به فردی که کاربر معرفی شده اش اولین فروش فروشگاهش را انجام میدهد داده میشود."
                                                   value="{{ $setting->first_sell_gift }}" type="text"
                                                   class="form-control input-sm" id="first_sell_gift" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="ads_expire_days">بازه زمانی انقضای آگهی
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input data-toggle="tooltip" name="ads_expire_days"
                                                   value="{{ $setting->ads_expire_days }}" type="number"
                                                   class="form-control input-sm" id="ads_expire_days" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="welcome_msg">متن پیام خوش آمد گویی: <span
                                                        style="color: red;">*</span></label>
                                            <textarea name="welcome_msg" class="form-control" id="welcome_msg" cols="30"
                                                      rows="5">{{ $setting->welcome_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                                <span>%gift_price%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="approve_store">پیام تایید فروشگاه:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="approve_store_msg" class="form-control" id="approve_store" cols="30"
                                                      rows="5">{{ $setting->approve_store_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                                <span>%store_name%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="reject_store">پیام رد فروشگاه:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="reject_store_msg" class="form-control" id="reject_store" cols="30"
                                                      rows="5">{{ $setting->reject_store_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                                <span>%store_name%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="new_comment_msg">پیام کامنت جدید:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="new_comment_msg" class="form-control" id="new_comment_msg" cols="30"
                                                      rows="5">{{ $setting->new_comment_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="checkout_msg">پیام تسویه حساب:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="checkout_msg" class="form-control" id="checkout_msg" cols="30"
                                                      rows="5">{{ $setting->checkout_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="product_without_photo_msg">پیام محصول ثبت شده بدون عکس:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="product_without_photo_msg" class="form-control" id="product_without_photo_msg" cols="30"
                                                      rows="5">{{ $setting->product_without_photo_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                                <span>%product_name%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="finishing_subscription_plan_message">پیام اتمام اشتراک:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="finishing_subscription_plan_message" class="form-control" id="finishing_subscription_plan_message" cols="30"
                                                      rows="5">{{ $setting->finishing_subscription_plan_message }}</textarea>
                                            <div class="options-list">
                                                <span>%full_name%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="wallet_page_help_text">متن راهنمای صفحه کیف پول:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="wallet_page_help_text" class="form-control" id="wallet_page_help_text" cols="30"
                                                      rows="5">{{ $setting->wallet_page_help_text }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="support_page_help_text">متن راهنمای صفحه پشتبانی:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="support_page_help_text" class="form-control" id="support_page_help_text" cols="30"
                                                      rows="5">{{ $setting->support_page_help_text }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="ads_page_help_text">متن راهنمای صفحه تبلیغات :<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="ads_page_help_text" class="form-control" id="ads_page_help_text" cols="30"
                                                      rows="5">{{ $setting->ads_page_help_text }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="chat_rules">متن قوانین گفت و گو ها :<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="chat_rules" class="form-control" id="chat_rules" cols="30"
                                                      rows="5">{{ $setting->chat_rules }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="no_chat_message">متن خالی بودن گفت و گو ها :<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="no_chat_message" class="form-control" id="no_chat_message" cols="30"
                                                      rows="5">{{ $setting->no_chat_message }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="no_messages">متن خالی بودن چت :<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="no_messages" class="form-control" id="no_messages" cols="30"
                                                      rows="5">{{ $setting->no_messages }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="discount_msg">پیام ثبت تخفیف درصدی:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="discount_msg" class="form-control" id="discount_msg" cols="30"
                                                      rows="5">{{ $setting->discount_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%name%</span>
                                                <span>%code%</span>
                                                <span>%apply_on%</span>
                                                <span>%amount%</span>
                                                <span>%start_date%</span>
                                                <span>%end_date%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="discount_rial_msg">پیام ثبت تخفیف ریالی:<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="discount_rial_msg" class="form-control" id="discount_rial_msg" cols="30"
                                                      rows="5">{{ $setting->discount_rial_msg }}</textarea>
                                            <div class="options-list">
                                                <span>%name%</span>
                                                <span>%code%</span>
                                                <span>%apply_on%</span>
                                                <span>%amount%</span>
                                                <span>%start_date%</span>
                                                <span>%end_date%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="share_text">متن معرفی برنامه :<span
                                                        style="color: red;">*</span></label>
                                            <textarea name="share_text" class="form-control" id="share_text" cols="30"
                                                      rows="5">{{ $setting->share_text }}</textarea>
                                            <div class="options-list">
                                                <span>%code%</span>
                                                <span>%next_line%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-pinterest">ویرایش</button>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- Page-Title -->
            </div> <!-- container -->
        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection
