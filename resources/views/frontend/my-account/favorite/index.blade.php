@extends('frontend.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="favorite-product-page">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fas fa-heart"></i>
                                    لیست علاقمندی ها
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        @foreach($products as $product)
                                            <div class="col-xs-6">
                                                <div class="product-container-box">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-3">
                                                            @if($product->photo != null)
                                                                <img style="max-width:100%;" src="{{ url()->to('/image/product_seller_photo/') }}/{{ $product->photo }}" alt="" class="img-thumbnail">
                                                            @else
                                                                <img src="{{ url()->to('/image/product_seller_photo/not-found.png') }}" alt="وورکی" class="img-thumbnail">
                                                            @endif
                                                        </div>
                                                        <div class="col-xs-12 col-md-9">
                                                            <div class="body">
                                                                <h4>{{ $product->name }}</h4>
                                                                <div class="price">
                                                                    @if($product->discount == 0)
                                                                        <span class="final-price">{{ number_format($product->price) }} تومان </span>
                                                                    @else
                                                                        <del>{{ number_format($product->price) }} تومان</del>
                                                                        <span class="final-price">{{ number_format($product->discountPrice) }} تومان </span>
                                                                    @endif
                                                                </div>

                                                                <a href="{{ route('show.product.seller' , $product->id) }}" class="btn btn-pink btn-xs">مشاهده محصول</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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

@section('script')
    <script>
        $(function () {

            $(".rateyo").rateYo({
                starWidth: "15px",
                readOnly: true,
                rating: 3,
                ratedFill: '#FF9800'
            });

        });
    </script>
@endsection