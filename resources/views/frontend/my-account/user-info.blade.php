@extends('frontend.master')
@section('style')
    <style>
        #referral-code-container{
            border:2px solid #fc2a23;
            border-radius:5px;
            width:230px;
            margin:35px auto;
            padding:20px 5px;
        }
        #referral-code-container i{
            color: #fc2a23;
            font-size: 28px;
            display:inline-block;
            margin-bottom:13px;
        }
        #referral-code-container p.title{
            color:#444;
            font-size:16px;
            font-weight:bold;
        }
        #referral-code-container p.body{
            color: #fc2a23;
            font-size:20px;
            font-weight:bold;
            margin-top:10px;
            margin-bottom:10px;
        }
        #profilePhoto{
            display: flex;
            justify-content: center;
            align-items: center;
            width : 100%;
        }
    </style>
    <title>وورکی | حساب کاربری من | ویرایش اطلاعات کاربری</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid my-account-tabs-content">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-address-card"></i>اطلاعات کاربری
                                @if(\App\Marketer::where('user_id', auth()->guard('web')->user()->id)->exists())
                                    <span class="pull-left">بازاریاب<i class="fas fa-medal"></i></span>
                                @endif
                            </div>

                            <div class="panel-body">
                                @include('frontend.errors')
                                <div id="referral-code-container" class="text-center">
                                    <i class="fas fa-users"></i>
                                    <p class="title">کد معرف:</p>
                                    <p class="body">{{ $user->reagent_code }}</p>
                                </div>
                                
                                <form action="{{ route('update.profile') }}" enctype="multipart/form-data" method="post" id="userInformationForm">
                                    {{ csrf_field() }}
                                    {{ method_field('put') }}
                                    <div id="profilePhoto">
                                        <label for="thumbnail" class="thumbnail-photo-file-input-wrapper text-center"
                                                id="userAccountStoreCreatePageThumbnailWrapper">
                                            <img src="{{ $user->thumbnail_photo ? url()->to('/image/store_photos/'.$user->thumbnail_photo) :url()->to('/img/thumbnail_placeholder.png') }}"
                                                    alt="thumbnail photo"
                                                    height="220"
                                                    width="220"
                                                    id="thumbnailPhotoPlaceholderImage"
                                                    class="img-responsive img-circle thumbnail-photo-placeholder">
                                            <input type="file" style="visibility: hidden" name="profile_photo" id="thumbnail">
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="first_name">نام </label>
                                                <div class="form-control-wrapper">
                                                    <i class="fa fa-user-alt"></i>
                                                    <input type="text" name="first_name" required id="first_name" value="{{ $user->first_name }}"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">نام خانوادگی </label>
                                                <div class="form-control-wrapper">
                                                    <i class="fa fa-user-alt"></i>
                                                    <input type="text" required name="last_name" id="last_name" value="{{ $user->last_name }}"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="mobile">تلفن همراه </label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-mobile-alt"></i>
                                                    <input type="tel" disabled name="mobile" id="mobile" value="{{ $user->mobile }}"
                                                           class="form-control disabled">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="email">ایمیل</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-envelope"></i>
                                                    <input type="email" disabled name="email" id="email"
                                                           value="{{ $user->email }}"
                                                           class="form-control disabled">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="sheba">شبا (فقط عدد):</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fa fa-bank"></i>
                                                    <input type="text" name="shaba_code" id="sheba"
                                                           value="{{ $user->shaba_code }}"
                                                           class="form-control" maxlength="24" minlength="24">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="card">شماره کارت (فقط عدد): </label>
                                                <div class="form-control-wrapper">
                                                    <i class="fa fa-bank"></i>
                                                    <input type="number" name="card" id="card" value="{{ $user->card }}"
                                                           class="form-control" maxlength="16" minlength="16">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                <label for="first_name">نوع بازپرداخت وجه : </label>
                                                <div class="form-control-wrapper">
                                                    <i class="fa fa-user-alt"></i>
                                                    <select name="returnPayType" required id="first_name"
                                                            class="form-control">
                                                        <option value="0" selected>پرداخت به حساب بانکی</option>
                                                        <option value="1" {{ $user->returnPayType ===1 ? 'selected' :'' }}>
                                                            شارژ کیف پول
                                                        </option>
                                                    </select>
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="about">درباره من : </label>
                                                <div class="form-control-wrapper">
                                                       <textarea id="about" class="form-control" rows ="5" name="about">{{$user->about}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 text-center">
                                            <button type="submit" class="btn btn-pink btn-bordered">ویرایش</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-lock"></i>
                                تغییر رمز عبور
                            </div>
                            <div class="panel-body">
                                <form action="{{ route('user.change.password') }}" method="post" id="changePasswordForm">
                                    {{ csrf_field() }}
                                    {{ method_field('put') }}
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="old_password">رمز عبور قبلی</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-lock-open"></i>
                                                    <input type="password" name="old_password" id="old_password"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="password">رمز عبور جدید </label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-key"></i>
                                                    <input type="password" name="password" id="password"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <div class="form-group">
                                                <label for="password_confirmation">تکرار رمز عبور جدید</label>
                                                <div class="form-control-wrapper">
                                                    <i class="fas fa-key"></i>
                                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                                           class="form-control">
                                                </div>
                                                <p class="text-danger error-container"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 text-center">
                                            <button type="submit" class="btn btn-pink btn-bordered">تغییر رمز عبور</button>
                                        </div>
                                    </div>
                                </form>
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
        $('#userInformationForm').validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 255
                },
                last_name:{
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                first_name: {
                    required: 'نام الزامی است.',
                    maxlength: 'نام طولانی تر از حد مجاز است.'
                },
                last_name: {
                    required: 'نام خانوادگی الزامی است.',
                    maxlength: 'نام خانوادگی طولانی تر از حد مجاز است.'
                }
            },
            errorPlacement: function(error , element){
                var placeholder = element.closest('.form-control-wrapper').next('.text-danger.error-container');
                placeholder.html(error);
            }
        });
        $('#userAccountStoreCreatePageThumbnailWrapper #thumbnail').change(function () {
            var input = this;
            var $this = $(input);
            var placeholder = $('#thumbnailPhotoPlaceholderImage');
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    placeholder.attr('src', e.target.result);
                    placeholder.css('width', '220px').css('height', '220px');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                placeholder.attr('src', '{{ url()->to('/img/thumbnail_placeholder.png') }}')
            }
        });
        $('#changePasswordForm').validate({
            rules: {
                old_password: {
                    required: true,
                },
                password: {
                    required: true,
                    equalTo: '#password_confirmation'
                }
            },
            messages: {
                old_password: {
                    required: 'رمز عبور قبلی الزامی است.'
                },
                password: {
                    required: 'رمز عبور جدید الزامی است.',
                    equalTo: 'رمز های عبور وارد شده مطابقت ندارند.'
                }
            },
            errorPlacement: function(error , element){
                var placeholder = element.closest('.form-control-wrapper').next('.text-danger.error-container');
                placeholder.html(error);
            }
        });
    </script>
@endsection