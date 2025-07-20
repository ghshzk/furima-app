@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
<div class="item">
    <div class="item-content">
        <img class="item-content__img" src="{{ Storage::url($item->image_path) }}" alt="商品画像">
    </div>

    <div class="item-content">
        <div class="item-content__inner">
            <h2 class="item-content__name">{{ $item->name }}</h2>
            <p class="item-content__brand">{{ $item->brand }}</p>
            <p class="item-content__price">¥<span>{{ number_format($item->price) }} </span>(税込)</p>

            <div class="item-content__count">
                <!-- いいねアイコン -->
                <div class="item-content__count-group">
                    <div class="like-form__wrapper">
                        @if(Auth::check() && Auth::id() === $item->user_id)
                            <button class="like-form__btn disabled" disabled>
                                <img class="like-form__icon" src="{{ asset('img/star.png') }}" alt="いいね">
                            </button>
                        @else
                            <form class="like-form"  action="{{ route('item.like', ['item_id' => $item->id]) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="like-form__btn {{ Auth::check() && $item->likedBy(Auth::user()) ? 'liked' : '' }}">
                                    <img class="like-form__icon" src="{{ asset('img/star.png') }}" alt="いいね">
                                </button>
                            </form>
                        @endif
                        <span class="like-form__count">{{ $likeCount }}</span>
                    </div>
                </div>

                <!-- コメントアイコン -->
                <div class="item-content__count-group">
                    <div>
                        <img class="comment__icon" src="{{ asset('img/comment.png') }}" alt="コメント">
                    </div>
                    <span class="comment__count">{{ $commentCount }}</span>
                </div>
            </div>

            <a class="item-content__btn btn" href="/purchase/{{ $item->id }}">購入手続きへ</a>
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

            <!-- コメント表示 -->
            <h3 class="item-content__ttl">コメント({{ $commentCount }})</h3>
            @foreach($item->comments as $comment)
            <div class="item-content__comment">
                <div class="comment-user">
                    @if ($comment->user->image_path)
                    <img class="comment-user__img" src="{{ asset('storage/profile/' . $comment->user->image_path) }}" alt="{{ $comment->user->name }}">
                    @else
                    <img class="comment-user__img" src="{{ asset('storage/images/default_icon.png') }}" alt="NoImage">
                    @endif
                    <strong class="comment-user__name">{{ $comment->user->name }}</strong>
                </div>
                <p class="comment-user__content">{{ $comment->content }}</p>
            </div>
            @endforeach

            <form class="comment-form" action="{{ route('item.comment', ['item_id' => $item->id]) }}" method="post">
                @csrf
                @method('PUT')
                <strong class="comment-form__label">商品へのコメント</strong>
                <textarea class="comment-form__input" name="content"></textarea>
                <p class="comment-form__error-message">
                    @error('content')
                    {{ $message }}
                    @enderror
                </p>
                <button class="comment-form__btn btn" type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection