@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-group">
        <div class="purchase-content">
            <div class="item-card">
                <img class="item-card__img" src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
            </div>
            <div class="item-card">
                <h2 class="item-card__name">{{ $item->name }}</h2>
                <p class="item-card__price">¥<span>{{ number_format($item->price) }}</span></p>
            </div>
        </div>

        <div class="purchase-content">
            <div class="payment">
                <h3 class="purchase-content__ttl">支払い方法</h3>
                <div class="purchase-content__inner">
                    <form class="payment-form" action="{{ route('purchase.updatePayment',['item_id' => $item->id]) }}" method="post">
                        @csrf
                        <div class="payment-form__select-wrap">
                            <select class="payment-form__select-inner" name="payment_method" onchange="this.form.submit()">
                                <option value="" hidden>選択してください</option>
                                <option value="コンビニ支払い" {{ $payment_method === 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                                <option value="カード支払い" {{ $payment_method === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                            </select>
                            <div class="payment-form__select-arrow"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="purchase-content">
            <div class="address">
                <div class="address__ttl">
                    <h3  class="purchase-content__ttl">配送先</h3>
                    <a class="purchase-content__link" href="/purchase/address/{{ $item->id }}">変更する</a>
                </div>
                <div class="purchase-content__inner">
                    <p>〒{!! nl2br(e($shippingAddress)) !!}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="purchase-group">
        <form class="purchase-form" action="{{ route('purchase.order', ['item_id' => $item->id]) }}" method="post">
            @csrf
            <input type="hidden" name="payment_method" value="{{ session('payment_method') }}">
            <input type="hidden" name="shipping_address" value="{{ session('shipping_address', $user->postcode . "\n" . $user->address . "\n" . $user->building) }}">

            <div class="purchase-form__summary">
                <div class="purchase-form__price">
                    <p class="purchase-form__label">商品代金</p>
                    <p class="purchase-form__data">¥ <span> {{ number_format($item->price) }}</span></p>
                </div>
                <div class="purchase-form__payment">
                    <p class="purchase-form__label">支払い方法</p>
                    <p class="purchase-form__data">{{ !empty(session('payment_method')) ? session('payment_method') : '未選択' }}</p>
                </div>
            </div>
            @if ($errors->any())
                <div class="error">
                    <p class="error-message">
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                    </p>
                </div>
            @endif
            <button class="purchase-btn btn" type="submit">購入する</button>
        </form>
    </div>
</div>

@endsection