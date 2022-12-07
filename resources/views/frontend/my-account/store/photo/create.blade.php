@extends('frontend.master')
@section('style')

@endsection
@section('content')
    @include('frontend.my-account.tabs')
    <section class="container-fluid save-photo-for-store">
        <div class="row">
            <div class="wrapper">
                <div class="row">

                    <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-images"></i>
                                آپلود تصویر برای فروشگاه
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <h6 class="text-center">
                                        در این صفحه می توانید تابلوی فروشگاه خود را آپلود کنید.
                                    </h6>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
                                        <form action="{{ route('upload.store.photo') }}" method="post" class="form-inline" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="store_id" value="{{ $store->id }}">
                                            <div class="form-group">
                                                <label for="select-file">انتخاب
                                                    فایل</label>
                                                <input type="file" name="photo" id="select-file"
                                                       class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-pink">ارسال
                                                <img src="{{ url()->to('/img/button-ajax-loader.gif') }}"
                                                     alt="ajax-loader" width="15px">
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @include('frontend.errors')
                                <div class="row">
                                    <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
                                        <div class="row photos-list-wrapper">
                                            @if($photo)
                                                <div class="col-xs-12">
                                                    <a href="{{ route('delete.store.photo' , $photo->id) }}">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                    <img src="{{ url()->to('/image/store_photos/') }}/{{ $photo->photo_name }}" alt="store photo" class="img-rounded img-fluid">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
