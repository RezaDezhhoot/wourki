@extends('admin.master')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form action="{{ route('admin.commissions.add') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h4>اضافه کردن پورسانت جدید</h4>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="amount" class="control-label">درصد پورسانت</label>
                                            <input class="form-control" autocomplete="off" type="number" value="{{ old('amount') }}" name="amount" id="amount" min="0" max="100">
                                            @if($errors->has('amount'))
                                                <b class="text-danger">{{ $errors->first('amount') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="category" class="control-label">دسته بندی</label>
                                            <select name="category" id="category" class="form-control js-example-basic-single">
                                                @foreach ($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('category'))
                                                <b class="text-danger">{{ $errors->first('category') }}</b>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-facebook">ثبت</button>
                                            <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-sm btn-pinterest">انصراف</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>دسته بندی</th>
                                                <th>درصد</th>
                                                <th>عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($commissions as $row)
                                                <tr>
                                                    <td>{{ $row->category->name }}</td>
                                                    <td>{{ strval($row->amount) . '%' }}</td>
                                                    <td><a href="{{ route('admin.commissions.delete' , $row->id) }}"
                                                        class="btn btn-danger btn-xs">حذف</a>
                                                    <button type="button" data-toggle="modal"
                                                        data-target="#update_{{ $row->id }}"
                                                        class="btn btn-info btn-xs">ویرایش</button></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $commissions->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($commissions as $commission )
        <div class="modal fade" id="update_{{ $commission->id }}" tabindex="-1" role="dialog"
             data-user-id="{{ $commission->id }}">
            <div class="modal-dialog" role="document">
                <form action="{{route('admin.commissions.update' , ['commission_id' => $commission->id]) }}" method="post">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">ویرایش پورسانت {{$commission->category->name}}</h4>
                    </div>
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="amount" class="control-label">درصد پورسانت</label>
                                            <input type="number" name="amount" id="amount"
                                                   placeholder="درصد پورسانت را وارد نمایید"
                                                   value="{{ $commission->amount }}" class="form-control input-sm" min="0" max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

{{--@section('styles')

    <style>

    </style>
@endsection--}}

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

    <script>
        $('#store_id').select2({
            placeholder: 'جستجوی فروشگاه...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('stores.get_via_ajax.in_support_page') }}',
                dataType: 'json',
                data: function(params){
                    return {
                        q: params.term
                    };
                },
                processResults: function(data){
                    return {
                        results: data.map(function(dt){
                            return {
                                id: dt.id,
                                text: dt.name
                            };
                        })
                    }
                }
            }
        });

        $('#product_id').select2({
            placeholder: 'جستجوی محصول...',
            minimumInputLength: 3,
            ajax: {
                delay: 200,
                url: '{{ route('getProductsListViaAjax') }}',
                dataType: 'json',
                data: function(params){
                    return {
                        q: params.term
                    };
                },
                processResults: function(data){
                    return {
                        results: data.map(function(dt){
                            return {
                                id: dt.id,
                                text: dt.name
                            };
                        })
                    }
                }
            }
        });
    </script>
@endsection