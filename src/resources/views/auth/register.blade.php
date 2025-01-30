@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading">会員登録</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="/register" method="post">
            @csrf
            <div class="register-form__group">
                <label class="register-form__label" for="name">ユーザー名</label>
                <input class="register-form__input" type="text" id="name" name="name">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="email">メールアドレス</label>
                <input class="register-form__input" type="email" id="email" name="email">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="password">パスワード</label>
                <input class="register-form__input" type="password" id="password" name="password">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
                <input class="register-form__input" type="password" id="password_confirmation" name="password_confirmation">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <button class="register-form__btn btn" type="submit">登録する</button>
        </form>
        <div class="register-form__link-container">
            <a class="register-form__link link" href="/login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection