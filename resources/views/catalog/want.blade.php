@extends('layout.main')

@section('content')
    <section class="wish">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <h1>Хочу стать клиентом</h1>
                    <ul class="packages">
                        <li class="{{ $package === 'standard' ? 'active' : '' }}">
                            <a href="{{ route('want') }}" style="color: inherit;">Пакет «Стандарт»</a>
                        </li>
                        <li class="{{ $package === 'business' ? 'active' : '' }}">
                            <a href="{{ route('want', ['package' => 'business']) }}"  style="color: inherit;">Пакет «Бизнес»</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="action-product-wrapper active animated fadeIn">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1">
                        <div class="action-product">
                            <div class="action-product-hint">
                                <img src="img/icon/icon-grey.svg" alt="Подсказка" class="grey">
                                <img src="img/icon/icon-blue.svg" alt="Подсказка" class="blue">
                                <div class="action-product-hint__block animated fadeIn">
                                    <a href="https://www.youtube.com/watch?v=507MgRs3rGU" target="_blank">
                                        Почему Айсберг?
                                    </a>
                                </div>
                            </div>
                            <div class="action-product-img">
                                <img src="img/zakaz.png" alt="{{ $fullBottle->name }}">
                            </div>
                            <div class="action-product-info">
                                <a href="#" onclick="event.preventDefault(); showProductDetails({{ $fullBottle->id }});">
                                    <h2 class="action-product-info__caption">
                                        Вода Янтарный Айсберг 18,9 литров
                                    </h2>
                                </a>
                                <div class="action-product-info__description">
                                    {{ $fullBottle->description }}<br><br>
                                </div>
                                <div class="action-product-info-cart">
                                    <div class="in-cart">
                                        <div class="in-cart-price">
                                            <span id="product-want-full-price" data-price="{{ number_format($defaultCart['fullBottle']['price'], 0, ',', '') }}"">{{ number_format($defaultCart['fullBottle']['price'], 0, ',', '') }}</span> р.&nbsp;<span>за шт.</span>
                                        </div>
                                        <div class="pm-widget">
                                            <input type="text"
                                                   id="product-want-full-count"
                                                   value="{{ $defaultCart['fullBottle']['qty'] }}"
                                                   data-want="true"
                                                   data-want-full="true">
                                        </div>
                                    </div>
                                    <div class="action-product-info-cart__price">
                                        Цена:&nbsp;<span id="product-want-full-amount">{{ number_format($defaultCart['fullBottle']['amount'], 0, ',', '') }}</span>р
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action-product-slider">
                            @foreach($equipments as $equipment)
                                <div class="action-product" data-equipment-id="{{ $equipment->id }}">
                                    <div class="action-product-img">
                                        <img src="{{ $equipment->smallPhoto }}" alt="{{ $equipment->name }}">
                                    </div>
                                    <div class="action-product-info">
                                        <a href="#" onclick="event.preventDefault(); showProductDetails({{ $equipment->id }});">
                                            <h2 class="action-product-info__caption">
                                                {{ $equipment->name }}
                                            </h2>
                                        </a>
                                        <div class="action-product-info__description">
                                            {{ $equipment->description }}&nbsp;
                                        </div>
                                        <div class="action-product-info-cart">
                                            <div class="in-cart">
                                                <div class="in-cart-price">
                                                    <span class="product-want-equipment-price">{{ number_format($equipment->price, 0, ',', '') }}</span> р.&nbsp;<span>за шт.</span>
                                                </div>
                                                <div class="pm-widget">
                                                    <input type="text"
                                                           id="money_field"
                                                           value="1"
                                                           data-want="true"
                                                           class="product-want-equipment-count">
                                                </div>
                                            </div>
                                            <div class="action-product-info-cart__price">
                                                Цена:&nbsp;<span class="product-want-equipment-amount">{{ number_format($equipment->price, 0, ',', '') }}</span>р
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="action-product">
                            <div class="action-product-hint">
                                <img src="img/icon/icon-grey.svg" alt="Подсказка" class="grey">
                                <img src="img/icon/icon-blue.svg" alt="Подсказка" class="blue">
                                <div class="action-product-hint__block animated fadeIn">
                                    <a href="https://www.youtube.com/watch?v=GT3LuPx8yHk" target="_blank">Почему
                                        поликарбонат?</a>
                                </div>
                            </div>
                            <div class="action-product-img">
                                <img src="img/tara.png" alt="{{ $emptyBottle->name }}">
                            </div>
                            <div class="action-product-info">
                                <a href="#" onclick="event.preventDefault(); showProductDetails({{ $emptyBottle->id }});">
                                    <h2 class="action-product-info__caption">
                                        Возвратная поликарбонатная тара (залоговая стоимость)
                                    </h2>
                                </a>
                                <div class="action-product-info__description">
                                    {{ $emptyBottle->description }}&nbsp;
                                </div>
                                <div class="action-product-info-cart">
                                    <div class="in-cart">
                                        <div class="in-cart-price">
                                            <span id="product-want-empty-price">{{ number_format($defaultCart['emptyBottle']['price'], 0, ',', '') }}</span> р.&nbsp;<span>за шт.</span>
                                        </div>
                                        <div>
                                            {{--<input type="text"--}}
                                                   {{--id="product-want-empty-count"--}}
                                                   {{--value="{{ $defaultCart['emptyBottle']['qty'] }}"--}}
                                                   {{--data-want="true">--}}
                                            <span id="product-want-empty-count" data-want="true" class="want-empty-count">
                                                {{ $defaultCart['emptyBottle']['qty'] }} шт
                                            </span>
                                        </div>
                                    </div>
                                    <div class="action-product-info-cart__price">
                                        Цена:&nbsp;<span id="product-want-empty-amount">{{ number_format($defaultCart['emptyBottle']['amount'], 0, ',', '') }}</span>р
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="action-product-total">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-lg-offset-1">
                            <div class="action-product-total-wrapper">
                                <div class="action-product-total-price">
                                    <span class="action-product-total-price__text">Итого за первый заказ:</span>
                                    <span class="action-product-total-price__volume">
                                        <span id="product-want-amount">{{ number_format($defaultCart['amount'], 0, ',', '') }}</span> р. за <span id="product-want-count">{{ $defaultCart['qty'] }}</span> товаров<br>
                                        в том числе <span id="product-want-empty">{{ number_format($defaultCart['emptyBottle']['amount'], 0, ',', '') }}</span> р. возвратные
                                    </span>
                                </div>
                                <form action="{{ route('want.order') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="full_count" value="2">
                                    <input type="hidden" name="empty_count" value="2">
                                    <input type="hidden" name="equipment_id" value="{{ $equipments->first()->id }}">
                                    <input type="hidden" name="equipment_count" value="1">
                                    <button class="btn__yellow">Оформить заказ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
