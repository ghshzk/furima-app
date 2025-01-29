@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading">会員登録</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="">
            <div class="register-form__group">
                <label class="register-form__label" for=""></label>
                <input class="register-form__input" type="text">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for=""></label>
                <input class="register-form__input" type="text">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for=""></label>
                <input class="register-form__input" type="text">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for=""></label>
                <input class="register-form__input" type="text">
                <p class="register-form__error-message">
                    エラー
                </p>
            </div>
            <input class="registre-form__btn" type="submit" value="登録">
        </form>
    </div>
</div>
@endsection