@extends('layout.main')

@section('content')
    <section class="shop" style="margin-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <h2 style="margin-bottom: 15px; margin-left: 0;">
                        Ваша корзина
                    </h2>
                </div>
                <div class="col-lg-9">
                    <p style="margin-bottom: 15px; margin-top: 28px; color: rgba(255, 75, 81, 1); display: {{ $cart->isAllowProceedCheckout() ? 'none' : 'block' }};"
                       id="cart-limit-warning">
                        Чтобы продолжить оформление заказа, сумма должна превышать 500 руб, или в заказе должен быть хотя бы один баллон воды 18,9 л. или два баллона воды 12,5 л.
                        <a href="{{ route('catalog') }}">Вернуться в каталог</a>
                    </p>
                </div>
            </div>

            <div>
                <div class="row hidden-xs cart-items-header">
                    <div class="col-sm-5 col-sm-offset-1">
                        <strong>Продукт</strong>
                    </div>
                    <div class="col-sm-1 text-center">
                        <strong>Цена</strong>
                    </div>
                    <div class="col-sm-3 text-center">
                        <strong>Кол-во</strong>
                    </div>
                    <div class="col-sm-1 text-center">
                        <strong>Сумма</strong>
                    </div>
                </div>
                @foreach($cart->items() as $item)
                    <div class="row cart-item" id="product-{{ $item['id'] }}">
                        <div class="col-sm-1 col-xs-12">
                            <div class="cart-image">
                                @if($item['discount'])
                                    <span class="product-sale-cart">{{ $item['discount'] }}%</span>
                                @endif
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-responsive" style="max-height: 150px;">
                            </div>
                        </div>
                        <div class="col-sm-5 col-xs-12">
                            <div class="cart-name">
                                {{ $item['name'] }}
                            </div>
                        </div>
                        <div class="col-sm-1 col-xs-6">
                            <div class="cart-price">
                                <strong class="visible-xs-inline">Цена:</strong>
                                @if($item['old_price'])
                                    <span style="text-decoration: line-through;">{{ $item['old_price'] }}</span>
                                @endif
                                <span id="product-{{ $item['id'] }}-price" style="{{ $item['old_price'] ? 'color: #EB5757;' : '' }}">{{ $item['price'] }}</span>&nbsp;<span style="{{ $item['old_price'] ? 'color: #EB5757;' : '' }}">руб</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="cart-qty">
                                <div>
                                    <span class="cart-product-dec" onclick="cart.dec({{ $item['id'] }})">-</span>
                                    <strong id="product-{{ $item['id'] }}-qty">{{ $item['qty'] }}</strong>
                                    <span class="cart-product-inc" onclick="cart.inc({{ $item['id'] }})">+</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 col-xs-9">
                            <div class="cart-amount">
                                <strong class="visible-xs-inline">Сумма:</strong>
                                <span id="product-{{ $item['id'] }}-amount">{{ $item['amount'] }}</span> руб
                            </div>
                        </div>
                        <div class="col-sm-1 col-xs-3">
                            <div class="cart-product-remove">
                                <span onclick="cart.remove({{ $item['id'] }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="row cart-items-footer">
                    <div class="col-xs-12 col-sm-2 col-sm-offset-10 text-right">
                        <strong>ИТОГО:</strong>
                        <strong id="product-cart-amount">{{ $cart->amount }}</strong> <strong>руб</strong>
                    </div>
                </div>
            </div>

            <form action="{{ route('order-confirmation') }}" method="post" style="display: {{ $cart->isAllowProceedCheckout() ? 'block' : 'none' }};" id="checkout-form">
                {{ csrf_field() }}
                <div class="row checkout">
                    <div class="col-md-4">
                        <h2>Контактная информация</h2>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <input name="name"
                                   type="text"
                                   class="form-control"
                                   placeholder="Ф.И.О. / Название организации"
                                   value="{{ old('name') ?? ($customer->name ?? null) }}">
                        </div>
                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <input name="phone"
                                   type="tel"
                                   class="form-control"
                                   placeholder="Номер телефона"
                                   value="{{ old('phone') ?? ($customer->phone ?? null) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h2>Адрес</h2>
                        <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                            <select name="city_id" id="city" class="form-control">
                                <option value=""></option>
                                @foreach(\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}" {{ $city->id == (old('city_id') ?? $customer->city_id ?? null) ? 'selected' : '' }}>
                                        {{ $city->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group {{ $errors->has('street_id') ? 'has-error' : '' }}"
                             id="street-select-input"
                             style="display: {{ old('street_manual') ?? ($customer->street_manual ?? null) ? 'none' : 'block' }};">
                            <select name="street_id"
                                    id="street"
                                    class="form-control"
                                    data-streets="{{ json_encode($streets, JSON_UNESCAPED_UNICODE) }}"
                                    data-old-street="{{ old('street_id') ?? ($customer->street_id ?? null) }}"></select>
                        </div>
                        <div class="form-group {{ $errors->has('street') ? 'has-error' : '' }}"
                             id="street-text-input"
                             style="display: {{ old('street_manual') ?? ($customer->street_manual ?? null) ? 'block' : 'none' }};">
                            <input id="street_name"
                                   name="street"
                                   type="text"
                                   class="form-control"
                                   placeholder="Улица для доставки"
                                   value="{{ old('street') ?? ($customer->street ?? null) }}">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="street_manual" type="hidden" value="0">
                                <input name="street_manual"
                                       value="1"
                                       type="checkbox"
                                       onclick="setStreetView($(this).is(':checked'))"
                                        {{ old('street_manual') ?? ($customer->street_manual ?? null) ? 'checked' : '' }}> Моей улицы нет в списке
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group {{ $errors->has('house') ? 'has-error' : '' }}">
                                    <input name="house"
                                           type="text"
                                           class="form-control"
                                           placeholder="Дом"
                                           value="{{ old('house') ?? ($customer->house ?? null) }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input name="apartment"
                                           type="text"
                                           class="form-control"
                                           placeholder="Кв. / Офис"
                                           value="{{ old('apartment') ?? ($customer->apartment ?? null) }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input name="floor"
                                           type="text"
                                           class="form-control"
                                           placeholder="Этаж"
                                           value="{{ old('floor') ?? ($customer->floor ?? null) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input name="entrance"
                                           type="text"
                                           class="form-control"
                                           placeholder="Код подъезда"
                                           value="{{ old('entrance') ?? ($customer->entrance ?? null) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="accept_terms_container" class="{{ $errors->has('accept_terms') ? 'has-error' : '' }}">
                                    <div class="checkbox" style="margin-top: 0; margin-bottom: 15px;">
                                        <label id="accept_terms" class="need-attention" style="white-space: nowrap; min-height: auto;">
                                            <input type="checkbox" name="accept_terms" style="margin-top: 2px;">
                                            Я согласен с <a href="{{ route('page.policy') }}">"Политикой конфиденциальности"</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h2>Комментарий к заказу</h2>
                        <div class="checkbox">
                            <label>
                                <input name="delivery_sms" type="hidden" value="0">
                                <input type="checkbox"
                                       name="delivery_sms"
                                       value="1"
                                    {{ old('delivery_sms') ?? ($customer->delivery_sms ?? "1") ? 'checked' : '' }}>
                                СМС &laquo;Доставка в течение 1 часа&raquo;
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="back_call" type="hidden" value="0">
                                <input type="checkbox"
                                       name="back_call"
                                       value="1"
                                    {{ old('back_call') ?? ($customer->back_call ?? null) ? 'checked' : '' }}>
                                Перезвоните мне
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="intercom_does_not_work" type="hidden" value="0">
                                <input type="checkbox"
                                       name="intercom_does_not_work"
                                       value="1"
                                    {{ old('intercom_does_not_work') ?? ($customer->intercom_does_not_work ?? null) ? 'checked' : '' }}>
                                Домофон не работает
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="contactless" type="hidden" value="0">
                                <input type="checkbox"
                                       name="contactless"
                                       value="1"
                                    {{ old('contactless') ?? ($customer->contactless ?? null) ? 'checked' : '' }}>
                                Бесконтактная доставка, оплата ТОЛЬКО на сайте
                            </label>
                            <div style="margin-left: 20px;">
                                <a href="#"
                                   style="display: inline-block; margin-top: 6px; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px dashed #337ab7;"
                                   onclick="event.preventDefault();$('#contactless-more').toggle();">Подробнее</a>
                                <div style="display: none;" id="contactless-more">
                                    <p>Бесконтактная доставка  осуществляется только при выполнении заказов для физических лиц и при оплате безналичным расчетом</p>
                                    <ul style="padding-left: 20px;">
                                        <li style="display: list-item">Водитель-экспедитор делает звонок за несколько минут до доставки или звонит в домофон</li>
                                        <li style="display: list-item">Клиент выставляет пустые баллоны (в количестве равном заказанных с водой) за дверь</li>
                                        <li style="display: list-item">Водитель оставляет воду и прочие товары за дверью и забирает баллоны</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a href="#"
                           style="display: inline-block; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 1px dashed #337ab7;"
                           onclick="event.preventDefault();$('#comment-input').toggle();">Прочее</a>
                        <div class="form-group" style="display: {{ old('comment') ? 'block' : 'none' }};" id="comment-input">
                            <textarea name="comment"
                                      rows="6"
                                      class="form-control"
                                      maxlength="150"
                                      placeholder="Ваши пожелания к заказу">{{ old('comment') }}</textarea>
                            <div class="text-right" id="comment-length">
                                <small></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn__yellow" style="width: 258px;">Оформить заказ</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/i18n/ru.js"></script>
    <script src="{{ mix('js/checkout.js') }}"></script>
@endpush
