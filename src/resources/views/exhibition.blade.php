@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
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
            @auth
            <form action="/logout" method="post">
                @csrf
                <input class="header-nav__link" type="submit" value="ログアウト">
            </form>
            @else
            <a class="header-nav__link" href="/login">ログイン</a>
            @endauth
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
<div class="item-form">
    <div class="item-form__inner">
        <form class="item-form__form" action="/" method="post" novalidate>
            @csrf
            <h3 class="item-form__heading">{{ $item->name }}</h3>
            <h3 class="item-form__ttl"></h3>
            <div class="item-form__group">
                <label class="item-form__label" for="">カテゴリー</label>
            </div>
            <div class="item-form__group">
                <label class="item-form__label" for="">商品状態</label>
                </div>
            </div>
            <button class="item-form__btn btn" type="submit">購入の手続きへ</button>

            <h4 class="item-form__ttl">商品説明</h4>
            <div class="item-form__group">
                <label class="item-form__label" for="name">商品名</label>
                <input class="item-form__input" type="text" id="name" name="name">
            </div>
            <div class="item-form__group">
                <label class="item-form__label" for="brand">ブランド名</label>
                <input class="item-form__input" type="text" id="brand" name="brand">
            </div>
            <div class="item-form__group">
                <label class="item-form__label" for="price">販売価格</label>
                <div class="item-form__price-container">
                    <span class="item-form__price-symbol">¥</span>
                    <input class="item-from__price-input" type="text" id="price" name="price">
                </div>
            </div>
            <h4 class="item-form__ttl">商品の情報</h4>
        </form>
        <form action="">
            <div class="comment-form__group">
                <label class="comment-form__label" for="comment">商品へのコメント</label>
                <textarea name="description" id="description" class="item-form__input"></textarea>
            </div>
            <button class="comment-form__btn btn" type="submit">コメントを送信する</button>
        </form>
    </div>
</div>
@endsection