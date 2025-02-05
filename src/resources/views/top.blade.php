@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection

@section('header-nav')
<div class="header-search-form">
    <form class="header-search-form__form" action="" method="get">
        @csrf
        <input class="header-search-form__input" type="text" value="{{ request('search') }}" placeholder="なにをお探しですか？">
        <button class="header-search-form__button" type="submit">検索</button>
    </form>
</div>

<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item">
            <a class="header-nav__link" href="/login">ログイン</a>
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
        <p>おすすめの商品</p>
        <!-- おすすめ商品一覧を表示-->
        @elseif ($tab == 'mylist')
        <p>マイリストの商品</p>
        <!-- マイリストの一覧表示 -->
        @endif
    </div>
</div>
@endsection