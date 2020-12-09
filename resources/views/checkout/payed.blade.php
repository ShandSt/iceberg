@extends('layout.main')

@section('content')
    <section class="shop" style="margin-top: 80px;">
        <div class="container">
            <div class="text-center" style="margin-top: 100px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h1 class="text-center" style="margin: 26px 0;">ВАШ ЗАКАЗ<br>УСПЕШНО ОПЛАЧЕН</h1>
            <h4 class="text-center">Номер заказа: {{ $order->id }}</h4>
            <div class="text-center" style="margin-top: 32px;">
                <a href="{{ route('catalog') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        </div>
    </section>
@endsection