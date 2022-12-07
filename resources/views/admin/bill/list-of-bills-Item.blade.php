@extends('admin.master')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <div class="row">
                                <div bgcolor='#e4e4e4' text='#ff6633' link='#666666' vlink='#666666' alink='#ff6633' style='margin:0;border-bottom:1'>
                                    <table id="table" bgcolor='#e4e4e4' width='100%' style='padding:20px 0 20px 0' cellspacing='0' border='0' align='center' cellpadding='0'>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table width='620' border='0' align='center' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF' style='border-radius: 5px;border: 1px solid #c7c7c7;'>
                                                    <tbody>

                                                    <tr align='left' >
                                                        <td style='color:#404041;font-size:12px;line-height:16px;padding:10px 16px 20px 18px'>
                                                            <table width='0' border='0' align='center' cellpadding='0' cellspacing='0'>

                                                                <span><h2 style='color: #848484; font-weight: 200; text-align: center;'>صورتحساب</h2></span>
                                                                <br>

                                                                <tbody>
                                                                <tr>
                                                                    <td width='0' align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:0px 0px 3px 0px'>
                                                                        <strong>نام:</strong>
                                                                    </td>
                                                                    <td width='0' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:0px 5px 3px 5px'>
                                                                        {{ $billQuery->user_first_name }} {{ $billQuery->user_last_name }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>کدپستی:</strong>
                                                                    </td>
                                                                    <td width='62' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;'>
                                                                        {{ $billQuery->postal_code }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>وضیعت محصول:</strong>
                                                                    </td>
                                                                    <td width='120' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;'>
                                                                        @if($billQuery->status == 'bought') <p class="text-secondary bold">خریداری شده</p> @endif
                                                                        @if($billQuery->status == 'shipping') <p class="text-info bold">درحال ارسال</p> @endif
                                                                        @if($billQuery->status == 'delivered') <p class="text-success bold">تحویل داده شده</p> @endif
                                                                        @if($billQuery->status == 'returned') <p class="text-warning bold">بازگشت شده</p> @endif
                                                                        @if($billQuery->status == 'rejected') <p class="text-danger bold">پذیرفته نشده</p> @endif
                                                                    </td>
                                                                </tr>

                                                                </tbody>

                                                                <tbody>
                                                                <tr>
                                                                    <td width='0' align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:15px 0px 3px 0px'>
                                                                        <strong>نوع پرداخت:</strong>
                                                                    </td>
                                                                    <td width='0' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:15px 5px 3px 5px'>
                                                                        @if ($billQuery->pay_type === 'online')آنلاین@endif
                                                                        @if ($billQuery->pay_type === 'venal')پستی@endif
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>استان:</strong>
                                                                    </td>
                                                                    <td width='62' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;'>
                                                                        {{ $billQuery->province_name }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>شهر:</strong>
                                                                    </td>
                                                                    <td width='120' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;'>
                                                                        {{ $billQuery->city_name }}
                                                                    </td>
                                                                </tr>

                                                                </tbody>

                                                                <tbody>
                                                                <tr>
                                                                    <td width='0' align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:15px 0px 3px 0px'>
                                                                        <strong>کد ارجاعی پرداخت:</strong>
                                                                    </td>
                                                                    <td width='0' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:15px 5px 3px 5px'>
                                                                        {{ $billQuery->pay_referral_code }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>تاریخ صورتحساب:</strong>
                                                                    </td>
                                                                    <td width='62' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;'>
                                                                        {{ jdate($billQuery->created_at)->format('%B %d، %Y') }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>آدرس:</strong>
                                                                    </td>
                                                                    <td width='120' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;'>
                                                                        {{ $billQuery->address }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align='left' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 0px 3px 0px;'>
                                                                        <strong>توضیحات:</strong>
                                                                    </td>
                                                                    <td width='120' align='right' valign='top' style='color:#404041;font-size:12px;line-height:16px;padding:5px 5px 3px 5px;width: 310px;'>
                                                                        {{ $billQuery->description }}
                                                                    </td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <br><br><br>


                            <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="m-t-0 header-title"><b>لیست اقلام محصول</b></h4>
                                            <p class="text-muted font-13">
                                            </p>

                                            <div class="p-20">
                                                <div class="table-responsive">
                                                    <table class="table m-0">
                                                        <thead style="background-color: #ccc;">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>صورتحساب</th>
                                                            <th>محصول</th>
                                                            <th>تعداد</th>
                                                            <th>قیمت</th>
                                                            <th>تخفیف</th>
                                                            <th>قیمت نهایی</th>
                                                            <th>تاریخ سفارش</th>
                                                        </thead>
                                                        <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach($billItems as $billItem)
                                                            <tr>
                                                                <th scope="row">{{ $i }}</th>
                                                                <td>{{ $billItem->bill_id }}</td>
                                                                <td>{{ $billItem->product_name }}</td>
                                                                <td>{{ $billItem->discount }}</td>
                                                                <td>{{ number_format($billItem->price) }}</td>
                                                                <td>{{ $billItem->discount }}</td>
                                                                <td>
                                                                    <button class="btn btn-success btn-xs">
                                                                        {{ number_format($billItem->price - ($billItem->price * ($billItem->discount / 100))) }}
                                                                    </button>
                                                                </td>
                                                                <td>{{ jdate($billItem->created_at)->format('%B %d، %Y') }}</td>
                                                            </tr>
                                                            <?php $i++; ?>
                                                        @endforeach
                                                        </tbody>
                                                        <tr class="total">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>

                                                            <td>
                                                                <button type="button" class="btn btn-xs waves-light btn-info">قیمت نهایی : {{ number_format($sumPrice) }}</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div class="form-group">
                                                        <a href="{{ route('showListOfBill') }}">
                                                            <button type="button" class="btn waves-effect waves-light btn-primary"> بازگشت <i class="ti-arrow-left"></i></button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('styles')
    <style>
        #table td{text-align : right;}
        .bold{font-weight: bold;}
    </style>
@endsection



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




@endsection