<header id="header" class="{{ Request::segment(1) === null || Request::segment(1) === 'search' ? '' : 'active' }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-container">

                    <div class="hambur">
                        <button class="hamburger hamburger--squeeze js-hamburger" type="button" aria-label="Menu" aria-controls="navigation">
							<span class="hamburger-box">
								<span class="hamburger-inner"></span>
							</span>
                        </button>
                    </div>

                    <div class="header-logo">
                        <a href="/">
                            <img src="{{ asset('img/logo.svg') }}" alt="logo" class="img-fluid">
                        </a>
                    </div>

                    <div class="header-menu-wrapper">
                        <ul class="header-menu">
                            <li>
                                <a href="https://www.instagram.com/voda_iceberg/" style="position:relative;">
                                    <span>О воде</span>
                                    <svg style="position:absolute; top: 1px;" width="20" height="20" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.072 0H7.502C3.572 0 .376 3.219.376 7.175v11.65C.376 22.78 3.573 26 7.502 26h11.57c3.93 0 7.126-3.219 7.126-7.175V7.175C26.198 3.22 23 0 19.072 0zm4.835 18.825c0 2.684-2.17 4.868-4.835 4.868H7.502c-2.666 0-4.835-2.184-4.835-4.868V7.175c0-2.684 2.169-4.868 4.835-4.868h11.57c2.666 0 4.835 2.184 4.835 4.868v11.65z" fill="#000"/><path d="M13.286 6.3c-3.668 0-6.653 3.006-6.653 6.7 0 3.694 2.985 6.7 6.653 6.7 3.67 0 6.654-3.006 6.654-6.7 0-3.694-2.985-6.7-6.654-6.7zm0 11.093c-2.405 0-4.362-1.97-4.362-4.393 0-2.422 1.957-4.393 4.362-4.393 2.406 0 4.363 1.971 4.363 4.393 0 2.422-1.957 4.393-4.363 4.393zM20.22 4.345c-.442 0-.876.18-1.188.495-.313.314-.493.75-.493 1.196a1.687 1.687 0 0 0 1.68 1.692 1.69 1.69 0 0 0 1.188-.495 1.705 1.705 0 0 0 0-2.393 1.681 1.681 0 0 0-1.188-.495z" fill="#000"/></svg>
                                </a>
                            </li>
                            <li><a href="{{ route('catalog') }}">Каталог</a></li>
                            <li><a href="{{ route('page.delivery') }}">Доставка и оплата</a></li>
                        </ul>

                        <div class="header-delivery">Доставка по<br><span>Калиниграду<br>и области</span></div>

                        <a href="tel:+74012577750" class="header-tel mob">+7 (4012) 57-77-50</a>
                    </div>

                    <form class="header-form" action="{{ route('search') }}" id="serch_form">
                        <input type="text" name="q" class="order__input" placeholder="" required="" id="name" aria-required="true">
                        <input type="submit" value="">
                    </form>

                    <a href="{{ route('want') }}" class="btn__blue">Хочу стать клиентом</a>

                    <a href="tel:+74012577750" class="header-tel"><span>+7 (4012) 57-77-50</span></a>

                    <div class="header-delivery">Доставка по<br><span>Калиниграду<br>и области</span></div>

                    <div class="cart">
                        <div class="cart-caption">Корзина товаров</div>
                        <a href="{{ route('cart') }}" style="color: #000;">
                            <div class="cart-info">
                                <div class="cart-info__price">{{ $cart->amount }}р</div>
                                <div class="cart-info__icon">
                                    <img src="{{ asset('img/icon/basket.svg') }}" alt="basket">
                                    <div class="quantity">{{ $cart->count }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
