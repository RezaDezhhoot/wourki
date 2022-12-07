@extends('admin.master')
@section('styles')
    <style>
        .slider-wrapper .card-box{
            position:relative;;
        }
        .slider-wrapper .options{
            position:absolute;
            top:10px;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    @foreach($sliders as $slider)
                        <div class="col-sm-3 slider-wrapper">
                            <div class="card-box p-b-0">
                                <div class="options">
                                    <a href="{{ route('editSlider' , $slider->id) }}" class="btn btn-xs btn-primary">ویرایش</a>
                                    <a href="{{ route('deleteSlider' , $slider->id) }}" class="btn btn-xs btn-danger">حذف</a>
                                    <span class="btn btn-xs btn-warning">در {{$slider->getPersianType()}}</span>
                                </div>
                                <img src="{{ url()->to('/image') }}/slider/{{ $slider->pic }}"
                                     style="width: 100%;height: 160px;" alt="">
                                {{--<i style="position: absolute;" class="fa fa-ellipsis-v fa-2x" aria-hidden="true"></i>--}}
                                <div style="padding: 5px !important;" class="bg-custom pull-in-card p-20 widget-box-two m-b-0 list-inline text-center row">
                                    <div class="col-xs-12">
                                        <p class="text-white m-b-0">{{ $slider->alt }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>





@endsection

@section('scripts')
    <script src="{{ url()->to('/admin') }}/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="{{ url()->to('/admin') }}/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
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
    <script type="text/javascript"
            src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="{{ url()->to('/admin') }}/assets/plugins/autocomplete/countries.js"></script>
    <script type="text/javascript" src="{{ url()->to('/admin') }}/assets/pages/autocomplete.js"></script>
    <script>
        $('.toggle-menu-on-click').click(function (e) {
            e.stopPropagation();
            var $this = $(this);
            var menu = $this.closest('div').find('ul');
            menu.slideDown(200);
        });

        $(document).on('click', function (e) {
            var menu = $('.dropdown-menu-on-slider');
            menu.slideUp(200);
        });
    </script>
@endsection