@extends('admin.master')
@section('styles')
    <link rel="stylesheet" href="{{ url()->to('/admin/assets/css/bootstrap-datepicker.min.css') }}">
    <style>
        .delete {margin-top: 25px;height: 30px;width: 30px;}
        .delete i {margin-top: 5px;}
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
                            <h4 class="m-t-0 header-title"><b>لیست طرح های تشویقی</b></h4><br>
                            @include('frontend.errors')
                            <form role="form" action="{{ route('exciting.design.store') }}" method="post">
                                {{ csrf_field() }}
                                @if(count($excitingDesign) == 0)
                                    <div class="row record-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="from_date"> از تاریخ <span style="color: red;">*</span></label>
                                                <input name="from_date[]" type="text" class="form-control input-sm datepicker" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="to_date"> تا تاریخ <span style="color: red;">*</span></label>
                                                <input name="to_date[]" type="text" class="form-control input-sm datepicker" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="price"> مبلغ خرید <span style="color: red;">*</span></label>
                                                <input name="price[]" type="number" class="form-control input-sm" id="price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="gift"> مبلغ هدیه <span style="color: red;">*</span></label>
                                                <input name="gift[]" type="number" class="form-control input-sm"
                                                       id="gift" required>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach($excitingDesign as $item)
                                        <div class="row record-row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="from_date"> از تاریخ <span style="color: red;">*</span></label>
                                                    <input name="from_date[]"
                                                           value="{{ \Morilog\Jalali\Jalalian::forge($item->from_date)->format('d/m/Y') }}"
                                                           type="text" class="form-control input-sm datepicker"
                                                           autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="to_date"> تا تاریخ <span
                                                                style="color: red;">*</span></label>
                                                    <input name="to_date[]"
                                                           value="{{ \Morilog\Jalali\Jalalian::forge($item->to_date)->format('d/m/Y') }}"
                                                           type="text" class="form-control input-sm datepicker"
                                                           autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="price"> مبلغ خرید <span
                                                                style="color: red;">*</span></label>
                                                    <input name="price[]" value="{{ $item->price }}" type="number"
                                                           class="form-control input-sm" id="price" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="gift"> مبلغ هدیه <span style="color: red;">*</span></label>
                                                    <input name="gift[]" value="{{ $item->gift }}" type="number" class="form-control input-sm" id="gift" required>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <a title="حذف طرح" href="{{ route('exciting.design.delete' , ['exciting-design' => $item->id]) }}" class="btn btn-xs btn-pinterest delete">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <button type="submit" class="btn btn-sm btn-pinterest">افزودن</button>
                                <button type="button" class="btn btn-sm btn-facebook" id="add_new_exciting_plan">افزودن طرح جدید</button>
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

@section('scripts')
    <script src="{{ url()->to('/admin/assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url()->to('/admin/assets/js/bootstrap-datepicker.fa.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".datepicker").datepicker();
        });

        $('#add_new_exciting_plan').click(function () {
            var recordRow = $('.record-row').last();
            recordRow.after(recordRow.html());
        });
    </script>
@endsection