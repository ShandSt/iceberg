<div class="row">
    <div class="col-lg-12">
        <div class="baner-wrapper">
            @if(count(Request()->all()) === 0 || Request()->input('category') == 7)
                <div class="baner left">
                    <div class="baner-left-slider">
                        <a href="{{ route('want') }}">
                            <div class="baner-left-slider__item">
                                <img src="img/hochu_1.jpg" class="img-responsive" alt="Хочу стать клиентом">
                            </div>
                        </a>
                        <a href="#">
                            <div class="baner-left-slider__item">
                                <img src="img/2.png" class="img-responsive" alt="">
                            </div>
                        </a>
                        <a href="#">
                            <div class="baner-left-slider__item">
                                <img src="img/3.jpg" class="img-responsive" alt="">
                            </div>
                        </a>
                        <a href="https://instagram.com/voda_iceberg?igshid=xfxdmvsgi7wr">
                            <div class="baner-left-slider__item">
                                <img src="img/4.png" class="img-responsive" alt="Инстаграм">
                            </div>
                        </a>
                    </div>
                </div>
                <div class="baner right">
                    <div class="easy-order-img">
                        <img src="{{ $mainProduct->preview_picture }}" alt="Легкий заказ" width="140">
                    </div>
                    <div class="easy-order-info">
                        <div class="info-caption">Легкий заказ</div>
                        <a href="#" onclick="event.preventDefault(); showProductDetails({{ $mainProduct->id }})">
                            <div class="info-product-name">{{ $mainProduct->name }}</div>
                        </a>
                        <div class="info-product-price">
                            <span id="product-price-banner" data-price="{{ number_format($mainProduct->price, 0) }}">{{ number_format($mainProduct->price, 0) }}</span>&nbsp;<span>р.&nbsp;за&nbsp;шт.</span>
                        </div>
                        <hr>
                        <div class="in-cart product-in-cart-{{ $mainProduct->id }}" style="{{ $cart->has($mainProduct) ? '' : 'display: none;' }};">
                            <a href="{{ route('cart') }}" class="btn__blue">В корзине</a>
                        </div>
                        @if(!$cart->has($mainProduct))
                        <div class="in-cart product-{{ $mainProduct->id }}">
                            <div class="btn__yellow" data-product-id="{{ $mainProduct->id }}">В корзину</div>
                            <div class="pm-widget">
                                <input type="text" class="product-count" value="2" data-banner="true">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
