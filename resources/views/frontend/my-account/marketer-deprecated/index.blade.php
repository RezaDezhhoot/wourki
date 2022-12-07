@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | بازاریابی</title>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid checkout-records-list">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-medal"></i>
                                پنل بازاریابان
                            </div>
                            <div class="panel-body">
                                @if(!\App\Marketer::where('user_id', $user->id)->exists())
                                <div class="text-center">
                                    <div class="alert alert-danger text-center">
                                        <p style="line-height:30px;color: #0b0b0b;font-weight: bold;">
                                            مزایای بازاریاب در وورکی:<br/>
                                            کسب درآمد ثابت داشته باش<br/>
                                            هدیه بگیر :<br/>
                                            معرفی کاربر : 1000 تومان<br/>
                                            معرفی فروشگاه : 5000 تومان<br/>
                                            واریز هدیه به شماره شبا<br/>
                                        </p>
                                        <form action="{{ route('become.marketer' , ['user' => auth()->guard('web')->user()]) }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('put') }}
                                            <p class="text-center">
                                                @if($user->become_marketer == 0)
                                                    <button type="submit" class="btn btn-pink" style="margin-top:20px;">می خواهم بازاریاب شوم</button>
                                                @else
                                                    <button disabled="disabled" class="btn btn-pink" style="margin-top:20px;">درخواست ارسال شده</button>
                                                @endif
                                            </p>
                                        </form>
                                    </div>
                                </div>
                                @else
                                    @include('frontend.errors')
                                    <form id="shaba-form" style="display: none;" action="{{ route('store.shaba.code.marketer' , $user->id) }}" method="post" class="form-inline">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="sheba_number">شماره شبای بانکی خود را بدون IR ابتدای آن وارد کنید</label>
                                            <input name="shaba_code" required type="text" id="shaba-number" value="{{ $user->shaba_code }}"
                                                   class="form-control" placeholder="شروع به نوشتن کنید...">
                                        </div>
                                        <button type="submit" id="shaba-submit" class="btn btn-pink btn-bordered btn-sm">ثبت</button>
                                    </form>
                                    <button id="shaba-btn" style="display: block;" type="button" class="btn btn-secondary btn-sm btn-pink btn-bordered">ثبت شماره شبای بانکی </button>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>کاربر معرفی کرده</th>
                                                <th>نوع</th>
                                                <th>مبلغ دریافتی</th>
                                                <th>تسویه شده؟</th>
                                                <th>تاریخ معرفی</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($lists as $user)
                                                <tr>
                                                    <td>{{ $user->referrer_first_name .' '. $user->referrer_last_name }}</td>
                                                    <td>{{ $user->type == 'reagent' ? 'معرفی کاربر' : 'ایجاد فروشگاه توسط کاربر معرفی شده' }}</td>
                                                    <td>{{ $user->reagented_user_fee }}</td>
                                                    <td>{{ $user->checkout == 1 ? 'بله' : 'خیر' }}</td>
                                                    <td>{{ \Morilog\Jalali\Jalalian::forge($user->created_at)->format("%d %B %Y") }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td style="text-align: left;font-weight: bold;" colspan="3">جمع کل:</td>
                                                <td style="background-color: #00AA00;color: #fff;font-weight: bold;">{{ $lists->sum('reagented_user_fee') }}
                                                    تومان
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
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
        $('#shaba-btn').click(function () {
            $('#shaba-form').css('display' , 'block');
            $(this).css('display' , 'none');
        });

        {{--$('#shaba-submit').click(function (e) {--}}
            {{--e.preventDefault();--}}
            {{--var shaba = $('#shaba-number').val();--}}

            {{--$.ajax({--}}
                {{--type : 'post' ,--}}
                {{--url : '{{ route('store.shaba.code.marketer' , $user->id) }}' ,--}}
                {{--data : {--}}
                    {{--'shaba' : shaba ,--}}
                    {{--'_token' : '{{ csrf_token() }}' ,--}}
                {{--} ,--}}

                {{--success : function () {--}}
                    {{--swal('موفقیت آمیز.' , 'کد شبا با موفقیت ثبت شد.' , 'success');--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    </script>
@endsection