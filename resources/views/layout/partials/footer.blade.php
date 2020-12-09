<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-content">
                    <div class="footer-logo" style="text-align: center;">
                        <a href="/">
                            <img src="{{ asset('img/logo.png') }}" alt="logo" class="img-fluid">
                            <div class="rights">{{ today()->year }} / Все права защищены</div>
                        </a>
                        <span style="font-size: 10px;">ОГРН 1053900089572</span>
                    </div>

                    <div class="footer-menu-wrapper">
                        <div class="footer-menu-caption">РУБРИКИ</div>
                        <ul class="footer-menu one animated fadeIn">
                            <li>
                                <a href="https://www.instagram.com/voda_iceberg/" style="display: inline-flex; justify-content: center;">
                                    <img src="{{ asset('img/icon/instagram-logo.svg') }}" alt="instagram" style="width: 16px; height: 16px; margin-right: 4px;">
                                    О воде
                                </a>
                            </li>
                            <li><a href="{{ route('page.delivery') }}">Доставка и оплата</a></li>
                            <li><a href="{{ route('page.contacts') }}">Контакты</a></li>
                        </ul>
                    </div>

                    <div class="footer-menu-wrapper">
                        <div class="footer-menu-caption">ИНФОРМАЦИЯ</div>
                        <ul class="footer-menu two animated fadeIn">
                            <li><a href="{{ route('page.policy') }}">Политика конфиденциальности</a></li>
                            <li><a href="http://old.iceberg-aqua.ru/novosti/">Информация для клиента</a></li>
                            <li><a href="http://app.iceberg-aqua.ru/">Мобильное приложение</a></li>
                        </ul>
                    </div>

                    <div class="footer-contacts">
                        <a href="tel:+74012577750" class="tel one">+7 (4012) 577-750</a>
                        <a href="tel:+74012539595" class="tel">+7 (4012) 53-95-95</a>

                        <div class="text-center">
                            <a href="https://play.google.com/store/apps/details?id=rewarded.mobiap.com.icebegr">
                                <img src="{{ asset('img/googlePlay.png') }}" style="width: 135px; height: 42px;">
                            </a>

                            <a href="https://itunes.apple.com/ru/app/%D0%B0%D0%B9%D1%81%D0%B1%D0%B5%D1%80%D0%B3/id1288250696?mt=8&ign-mpt=uo%3D4">
                                <img src="{{ asset('img/appStore.png') }}" style="width: 135px; height: 42px;">
                            </a>
                        </div>
                    </div>

                    <ul class="footer-socials">
                        <li><a href="https://www.facebook.com/iceberg3939"><img src="{{ asset('img/icon/facebook.svg') }}" alt="facebook"></a></li>
                        <li><a href="https://vk.com/voda39"><img src="{{ asset('img/icon/vk.svg') }}" alt="vk"></a></li>
                        <li><a href="https://www.instagram.com/voda_iceberg/"><img src="{{ asset('img/icon/instagram-logo.svg') }}" alt="instagram"></a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</footer>
