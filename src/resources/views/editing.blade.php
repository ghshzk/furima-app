@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/editing.css') }}">
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
<div class="profile-form">
    <h2 class="profile-form__heading">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/mypage/profile" method="post" enctype="multipart/form-data">
            @csrf
            <div class="profile-form__group--img">
                @if ($user->image_path)
                <img class="profile-form__img" src="{{ asset('storage/profile/' . $user->image_path) }}" alt="プロフィール画像">
                @else
                <img class="profile-form__img" src="{{ asset('storage/images/default_icon.png') }}" alt="NoImage">
                @endif
                <label class="profile-form__upload" for="fileInput">画像を選択する</label>
                <input type="file" id="fileInput" accept="image/*" style="display:none;">
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                <p class="profile-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="postcode">郵便番号</label>
                <input class="profile-form__input" type="text" id="postcode" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                <p class="profile-form__error-message">
                    @error('postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
                <p class="profile-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" id="building" name="building" value="{{ old('building', $user->building) }}">
                <p class="profile-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="profile-form__btn btn" type="submit">更新する</button>
        </form>

    </div>
</div>
@endsection