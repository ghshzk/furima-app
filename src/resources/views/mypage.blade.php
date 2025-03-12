@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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
<div class="mypage-container">
    <div class="mypage__group">
        <div class="mypage__item">
            @if ($user->image_path)
            <img class="mypage__img" src="{{ asset('storage/profile/' . $user->image_path) }}" alt="プロフィール画像">
            @else
            <img class="mypage__img" src="{{ asset('storage/images/default_icon.png') }}" alt="NoImage">
            @endif
            <h2 class="mypage__name"> {{ $user->name }}</h2>
        </div>
        <div class="mypage__item">
            <form action="/mypage/profile" method="GET">
                @csrf
                <button class="mypage__btn" type="submit">プロフィールを編集</button>
            </form>
        </div>
    </div>

    <div class="tabs">
        <div class="tab-nav">
            <ul class="tab-nav__list">
                <li class="tab-nav__item">
                    <a class="{{ $tab == 'sell' ? 'active' : '' }}" href="{{ route('mypage', ['tab' => 'sell']) }}">
                        出品した商品
                    </a>
                </li>
                <li class="tab-nav__item">
                    <a class="{{ $tab == 'buy' ? 'active' : '' }}" href="{{ route('mypage',['tab' => 'buy']) }}">
                        購入した商品
                    </a>
                </li>
            </ul>
        </div>
    
        <div class="tab-content">
        @if ($tab == 'sell')
        <!-- 出品した商品一覧を表示-->
        <div class="item-container">
            @foreach($items as $item)
                <div class="item-card">
                    <a class="item-card__link" href="{{ url('/item/' . $item->id) }}">
                        <img class="item-card__img" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                        <p class="item-card__content">{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        @elseif ($tab == 'buy')
        <!-- 購入した商品の一覧表示 -->
        <div class="item-container">
            @foreach($items as $item)
                <div class="item-card">
                    <a class="item-card__link" href="{{ url('/item/' . $item->id) }}">
                        <img class="item-card__img" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                        <p class="item-card__content">{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    </div>
</div>

@endsection