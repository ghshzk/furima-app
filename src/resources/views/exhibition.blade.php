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
<div class="item">
    <div class="item-content">
        <img class="item-content__img" src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
    </div>

    <div class="item-content">
        <div class="item-content__inner">
            <h2 class="item-content__name">{{ $item->name }}</h2>
            <p class="item-content__brand">{{ $item->brand }}</p>
            <p class="item-content__price">¥<span>{{ number_format($item->price) }} </span>(税込)</p>

            <div class="item-content__count">
                <!-- いいねアイコン -->
                <div class="item-content__count-group">
                    <form class="like-form"  action="{{ route('item.like', ['item_id' => $item->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="like-form__btn {{ Auth::check() && $item->likedBy(Auth::user()) ? 'liked' : '' }}">
                            <img class="like-form__icon" src="{{ asset('images/star.png') }}" alt="いいね">
                        </button>
                    </form>
                    <span class="like-form__count">{{ $likeCount }}</span>
                </div>

                <!-- コメントアイコン -->
                <div class="item-content__count-group">
                    <div>
                        <img class="comment__icon" src="{{ asset('images/comment.png') }}" alt="コメント">
                    </div>
                    <span class="comment__count">{{ $commentCount }}</span>
                </div>
            </div>

            <a class="item-content__btn btn" href="">購入手続きへ</a>
            <h3 class="item-content__ttl">商品説明</h3>
            <p class="item-content__description">{{ $item->description }}</p>
    
            <h3 class="item-content__ttl">商品の情報</h3>
            <div class="item-content__group">
                <strong class="item-content__label">カテゴリー</strong>
                <div class="item-content__categories">
                    @foreach($item->categories as $category)
                    <p class="item-content__category">{{ $category->content }}</p>
                    @endforeach
                </div>
            </div>
            <div class="item-content__group">
                <strong class="item-content__label">商品の状態</strong>
                <p class="item-content__condition">
                    @if($item['condition'] == 1)
                    良好
                    @elseif($item['condition'] == 2)
                    目立った傷や汚れはなし
                    @elseif($item['condition'] == 3)
                    やや傷や汚れあり
                    @elseif($item['condition'] == 4)
                    状態が悪い
                    @endif
                </p>
            </div>
            
            <h3 class="item-content__ttl">コメント({{ $commentCount }})</h3>
            @foreach($item->comments as $comment)
            <div class="item-content__comment">
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->content }}</p>
            </div>
            @endforeach
            
            <form class="comment-form" action="" >
                @csrf
                <strong class="comment-form__label">商品へのコメント</strong>
                <textarea class="comment-form__input" name="description"></textarea>
                <button class="comment-form__btn btn" type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection