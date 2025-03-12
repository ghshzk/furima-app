@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
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
<div class="tabs">
    <div class="tab-nav">
        <ul class="tab-nav__list">
            <li class="tab-nav__item">
                <a class="{{ $tab == 'recommend' ? 'active' : '' }}" href="{{ route('top', ['tab' => 'recommend']) }}">
                    おすすめ
                </a>
            </li>
            <li class="tab-nav__item">
                <a class="{{ $tab == 'mylist' ? 'active' : '' }}" href="{{ route('top',['tab' => 'mylist']) }}">
                    マイリスト
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        @if ($tab == 'recommend')
        <!-- おすすめ商品一覧を表示-->
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
        @elseif ($tab == 'mylist')
        <!-- マイリストの一覧表示 -->
        <div class="item-container">
            @foreach($items as $item)
                <div class="item-card">
                    <img class="item-card__img" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                    <p class="item-card__content">{{ $item->name }}</p>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection