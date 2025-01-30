@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form__heading">ログイン</h2>
    <div class="login-form__inner">
        <form class="login-form__form" action="/login" method="post">
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="email">ユーザー名/メールアドレス</label>
                <input class="login-form__input" type="email" id="email" name="email">
                <p class="login-form__error-message">
                    エラー
                </p>
            </div>
            <div class="login-form__group">
                <label class="login-form__label" for="password">パスワード</label>
                <input class="login-form__input" type="password" id="password" name="password">
                <p class="login-form__error-message">
                    エラー
                </p>
            </div>
            <button class="login-form__btn btn" type="submit">ログインする</button>
        </form>
        <a class="login-form__link link" href="/register">会員登録はこちら</a>
    </div>
</div>
@endsection
