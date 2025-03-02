@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
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
<div class="sell-form">
    <h2 class="sell-form__heading">商品の出品</h2>
    <div class="sell-form__inner">
        <form class="sell-form__form" action="/sell" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="sell-form__group">
                <label class="sell-form__label">商品画像</label>
                <div class="sell-form__upload-inner">
                    <input type="file" id="fileInput" name="image" accept="image/*" style="display:none;">
                    <label class="sell-form__upload" for="fileInput">画像を選択する</label>
                </div>
                <p class="sell-form__error-message">
                    @error('image_path')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <h3 class="sell-form__ttl">商品詳細</h3>
            <div class="sell-form__group">
                <label class="sell-form__label" for="">カテゴリー</label>
                <div class="sell-form__checkbox-inner">
                    @foreach($categories as $category)
                    <input class="checkbox" type="checkbox" name="categories[]" id="category_{{ $category->id }}" value="{{ $category->id }}" style="display:none; [input[type="checkbox"]:checked + label]">
                    <label class="sell-form__checkbox" for="category_{{ $category->id }}">{{ $category->content }}</label>
                    @endforeach
                </div>
                <p class="sell-form__error-message">
                    @error('category_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="">商品状態</label>
                <div class="sell-form__select-wrap">
                    <select class="sell-form__select-inner" name="condition" required>
                        <option value="" hidden>選択してください</option>
                        <option value="1">良好</option>
                        <option value="2">目立った傷や汚れなし</option>
                        <option value="3">やや傷や汚れあり</option>
                        <option value="4">状態が悪い</option>
                    </select>
                    <div class="sell-form__select-arrow"></div>
                </div>
                <p class="sell-form__error-message">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <h3 class="sell-form__ttl">商品名と説明</h3>
            <div class="sell-form__group">
                <label class="sell-form__label" for="name">商品名</label>
                <input class="sell-form__input" type="text" id="name" name="name">
                <p class="sell-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="brand">ブランド名</label>
                <input class="sell-form__input" type="text" id="brand" name="brand">
                <p class="sell-form__error-message">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="description">商品の説明</label>
                <textarea name="description" id="description" class="sell-form__input"></textarea>
                <p class="sell-form__error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="price">販売価格</label>
                <input class="sell-form__input" type="number" id="price" name="price">
                <p class="sell-form__error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="sell-form__btn btn" type="submit">出品する</button>
        </form>
    </div>
</div>
@endsection