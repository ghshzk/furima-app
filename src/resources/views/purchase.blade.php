@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('header-nav')
<div class="header-search-form">
    <form class="header-search-form__form" action="" method="get">
        @csrf
        <input class="header-search-form__input" type="text" value="{{ request('search') }}" placeholder="なにをお探しですか？">
    </form>
</div>

<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item">
            <form action="/logout" method="post">
                @csrf
                <input class="header-nav__link" type="submit" value="ログアウト">
            </form>
        </li>
        <li class="header-nav__item">
            <a class="header-nav__link" href="/mypage">マイページ</a>
        </li>
        <li class="header-nav__item">
            <a class="header-nav__link header-nav__link-sell" href="/sell">出品</a>
        </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase__group">
        <div class="purchase-form__group--img">
            <img class="purchase-form__img" src="{{ asset('storage/items/' . $item->image_path) }}" alt="商品画像">
            <p class="purchase-form">{{ $item->name }}</p>
            <p class="purchase-form">¥{{ number_format($item->price) }}</p>
        </div>
    </div>

    <form action="/" method="POST">
        @csrf
        <label>支払い方法</label>
        <select name="payment_method" onchange="this.form.submit()">
            <option value="">選択してください</option>
            <option value="コンビニ支払い">コンビニ支払い</option>
            <option value="カード支払い">カード支払い</option>
        </select>
    </form>

    <h3>配送先</h3>
    <p>〒 {{ $order->postcode }}</p>
    <p>{{ $order->address }} {{ $order->building }}</p>
    <a href="">変更する</a>

    <div class="summary">
        <h4>商品代金</h4>
        <p>¥{{ number_format($item->price) }}</p>

        <h4>支払い方法</h4>
        <p>{{ session('payment_method', '未選択') }}</p>
    </div>

    <button class="purchase-btn">購入する</button>
</div>


@endsection