@extends('layout.main')

@section('content')
    <section class="shop" style="margin-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <h2 class="text-center" style="margin-bottom: 15px;">
                        Подтверждение заказа
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <form action="{{ route('checkout') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="hash" value="{{ $hash }}">
                        <div class="form-group">
                            <label>Способ оплаты</label>
                            <select name="payment_method" class="form-control">
                                @if(!$order->contactless)
                                <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>
                                    Наличными курьеру
                                </option>
                                @endif
                                <option value="Card" {{ old('payment_method') === 'Card' ? 'selected' : '' }}>
                                    Картой на сайте
                                </option>
                                <option value="Bill" {{ old('payment_method') === 'Bill' ? 'selected' : '' }}>
                                    Оплата по договору
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Время доставки</label>
                            <select name="date_of_delivery" class="form-control {{ $errors->has('date_of_delivery') ? 'has-error' : '' }}">
                                @foreach($order->date_of_delivery_variants as $variant)
                                    <option value="{{ $variant['Name'].'|'.$variant['date'] }}"
                                            {{ old('date_of_delivery') === $variant['Name'].'|'.$variant['date'] ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($variant['date'])->format('d.m.Y') }} {{ $variant['Name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button class="btn__yellow" style="width: 258px;">Подтвердить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
