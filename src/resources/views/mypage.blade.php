@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="mypage__group">
        <div class="mypage__item">
            @if ($user->image_path)
                <img class="mypage__img" src="{{ Storage::url($user->image_path) }}" alt="プロフィール画像">
            @else
                <img class="mypage__img" src="{{ asset('img/default_icon.png') }}" alt="NoImage">
            @endif
            <div class="mypage__info">
                <h2 class="mypage__name"> {{ $user->name }}</h2>

                @if(!is_null($averageRating))
                    <div class="review-form__rating">
                        <span class="star-rating" data-rate="{{ $averageRating }}"></span>
                    </div>
                @endif
            </div>
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
                <li class="tab-nav__item tab-nav__item--transaction">
                    <a class="{{ $tab == 'transaction' ? 'active' : '' }}" href="{{ route('mypage',['tab' => 'transaction']) }}">
                        取引中の商品
                    </a>
                    <span class="total-message__count">{{ $unreadCount }}</span>
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
                                <img class="item-card__img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                                <p class="item-card__name">{{ $item->name }}</p>
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
                                <img class="item-card__img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                                <p class="item-card__name">{{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @elseif ($tab === 'transaction')
                <!-- 取引中の商品の一覧表示 -->
                <div class="item-container">
                    @foreach($orders as $order)
                        <div class="item-card">
                            @if ($order->unread_messages_count > 0)
                            <span class="message__count">{{ $order->unread_messages_count }}</span>
                            @endif
                            <a class="item-card__link" href="{{ url('/transaction/' . $order->id) }}">
                                <img class="item-card__img" src="{{ Storage::url($order->item->image_path) }}" alt="{{ $order->item->name }}">
                                <p class="item-card__name">{{ $order->item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection