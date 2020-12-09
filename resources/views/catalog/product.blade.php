<div class="modal product fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <h2 class="mob">{{ $product->name }}</h2>
                        <div class="modal-product-slider">
                            <div class="product-slider__item">
                                <img src="{{ $product->detailsPhoto }}" alt="Название товара" class="img-responsive">
                            </div>
                        </div>
                        <div class="modal-product-nav-slider">
                            <div class="modal-product-nav-slider__item">
                                <img src="{{ $product->detailsPhoto }}" alt="Название товара" class="img-responsive">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <h2>{{ $product->name }}</h2>
                        <div class="modal-product-price">
                            <span id="product-price-modal" data-price="{{ number_format($product->price, 0, ',', '') }}">{{ number_format($product->price, 0, ',', '') }}</span> <span>р.&nbsp;за шт.</span>
                        </div>
                        <hr>
                        <div class="in-cart product-in-cart-{{ $product->id }}" style="{{ $cart->has($product) ? '' : 'display: none;' }};">
                            <a href="{{ route('cart') }}" class="btn__blue">В корзине</a>
                        </div>
                        @if(!$cart->has($product))
                        <div class="in-cart product-{{ $product->id }}"">
                            <div class="btn__yellow" data-product-id="{{ $product->id }}">В корзину</div>
                            <div class="pm-widget">
                                <input type="text" class="product-count" value="{{ $product->isMain() ? 2 : 1 }}" data-modal="true" data-main="{{ $product->isMain() ? 'true' : 'false' }}">
                            </div>
                        </div>
                        @endif
                        <div class="modal-product-discription">
                            {!! nl2br($product->description) !!}
                            @if($product->tags->count() > 0)
                                <br><br>
                                @foreach($product->tags as $tag)
                                    <a href="{{ route('catalog', ['tag' => $tag->id]) }}">#{{ $tag->name }}</a>&nbsp;&nbsp;
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="modal-product-recommended">Рекомендуется с данным товаром</h3>
                        <div class="modal-product-recommended-slider-wrapper">
                            <div class="modal-product-recommended-slider">
                                @foreach($relatedProducts as $relatedProduct)
                                    <div class="product-item">
                                        <a href="#" onclick="event.preventDefault(); showProductDetails({{ $relatedProduct->id }});">
                                            @if($relatedProduct->discount)
                                                <span class="product-sale">{{ number_format($relatedProduct->discount, 0, '', '') }}%</span>
                                            @endif
                                            <div class="product-img">
                                                <img src="{{ $relatedProduct->smallPhoto }}" alt="{{ $relatedProduct->name }}" class="img-responsive" style="max-width: 148px; max-height: 176px;">
                                            </div>
                                            <div class="product-name">
                                                {{ $relatedProduct->name }}
                                            </div>
                                        </a>
                                        <div class="product-price">
                                            @if ($relatedProduct->old_price && $relatedProduct->old_price > $relatedProduct->price)
                                                <span class="old-prace">{{ number_format($relatedProduct->old_price, 0, ',', '') }}</span>&nbsp;<span id="product-price-{{ $relatedProduct->id }}" class="new-parce">{{ number_format($relatedProduct->price, 0, ',', '') }}</span>&nbsp;<span class="new-parce-val">р.&nbsp;за&nbsp;шт.</span>
                                            @else
                                                <span id="product-price-{{ $relatedProduct->id }}">{{ number_format($relatedProduct->price, 0, ',', '') }}</span>&nbsp;<span>р.&nbsp;за&nbsp;шт.</span>
                                            @endif
                                        </div>
                                        <hr>
                                        <div class="in-cart product-in-cart-{{ $relatedProduct->id }}" style="{{ $cart->has($relatedProduct) ? '' : 'display: none;' }};">
                                            <a href="{{ route('cart') }}" class="btn__blue" style="margin-top: 43px; width: 100%;">В корзине</a>
                                        </div>
                                        @if(!$cart->has($relatedProduct))
                                        <div class="in-cart product-{{ $relatedProduct->id }}">
                                            <div class="pm-widget">
                                                <input type="text" class="product-count" data-product-catalog-id="{{ $relatedProduct->id }}"  value="{{ $relatedProduct->isMain() ? 2 : 1 }}">
                                            </div>
                                            <div class="btn__yellow" data-product-id="{{ $relatedProduct->id }}">В корзину</div>
                                        </div>
                                        @endif
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
