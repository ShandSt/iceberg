@extends('layout.main')

@section('content')
    <section class="shop">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 ">
                    @include('catalog.categories')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                    @include('catalog.banners')
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>
                                {{ $title }}
                            </h2>
                        </div>
                    </div>
                    <div class="row shop-wrapper" {!! $products->count() === 0 ? 'style="min-height: 300px;"' : '' !!}>
                        @foreach($products as $product)
                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                                <div class="product-item">
                                    <a href="#" onclick="event.preventDefault(); showProductDetails({{ $product->id }});">
                                        @if($product->discount)
                                            <span class="product-sale">{{ number_format($product->discount, 0, '', '') }}%</span>
                                        @endif
                                        <div class="product-img">
                                            <img src="{{ $product->smallPhoto }}" alt="{{ $product->name }}" class="img-responsive">
                                        </div>
                                        <div class="product-name">
                                            {{ $product->name }}
                                        </div>
                                    </a>
                                    <div class="product-price">
                                        @if ($product->old_price && $product->old_price > $product->price)
                                            <span class="old-prace">{{ number_format($product->old_price, 0, ',', '') }}</span>&nbsp;<span id="product-price-{{ $product->id }}" class="new-parce" data-price="{{ number_format($product->price, 0, ',', '') }}">{{ number_format($product->price, 0, ',', '') }}</span>&nbsp;<span class="new-parce-val">р.&nbsp;за&nbsp;шт.</span>
                                        @else
                                            <span id="product-price-{{ $product->id }}" data-price="{{ number_format($product->price, 0, ',', '') }}">{{ number_format($product->price, 0, ',', '') }}</span>&nbsp;<span>р.&nbsp;за&nbsp;шт.</span>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="in-cart product-in-cart-{{ $product->id }}" style="{{ $cart->has($product) ? '' : 'display: none;' }};">
                                        <a href="{{ route('cart') }}" class="btn__blue">В корзине</a>
                                    </div>
                                    @if(!$cart->has($product))
                                    <div class="in-cart product-{{ $product->id }}">
                                        <div class="btn__yellow" data-product-id="{{ $product->id }}">В корзину</div>
                                        <div class="pm-widget">
                                            <input type="text" class="product-count" data-product-catalog-id="{{ $product->id }}" value="{{ $product->isMain() ? 2 : 1 }}">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-lg-12 pagination-sm">
                            {{ $products->appends($filters)->links('layout.partials.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
