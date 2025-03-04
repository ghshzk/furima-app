@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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
            <a class="header-nav__link" href="/login">ログアウト</a>
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
    <div class="profile__group">
        
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

        </div>
    </div>
</div>

@endsection