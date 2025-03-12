@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
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
<div class="address-form">
    <h2 class="address-form__heading">住所の変更</h2>
    <div class="address-form__inner">
        <form class="address-form__form" action="/purchase/:item_id" method="post">
            @csrf
            <div class="address-form__group">
                <label class="address-form__label" for="postcode">郵便番号</label>
                <input class="address-form__input" type="text" id="postcode" name="postcode" value="{{ old('postcode', $order->postcode) }}">
                <p class="address-form__error-message">
                    @error('postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-form__group">
                <label class="address-form__label" for="address">住所</label>
                <input class="address-form__input" type="text" id="address" name="address" value="{{ old('address', $order->address) }}">
                <p class="address-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-form__group">
                <label class="address-form__label" for="building">建物名</label>
                <input class="address-form__input" type="text" id="building" name="building" value="{{ old('building', $order->building) }}">
                <p class="address-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="address-form__btn btn" type="submit">更新する</button>
        </form>

    </div>
</div>
@endsection