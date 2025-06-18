@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-form">
    <h2 class="address-form__heading">住所の変更</h2>
    <div class="address-form__inner">
        <form class="address-form__form" action="{{ route('purchase.updateAddress',['item_id' => $item->id]) }}" method="post">
            @csrf
            <div class="address-form__group">
                <label class="address-form__label" for="postcode">郵便番号</label>
                <input class="address-form__input" type="text" id="postcode" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                <p class="address-form__error-message">
                    @error('postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-form__group">
                <label class="address-form__label" for="address">住所</label>
                <input class="address-form__input" type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
                <p class="address-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-form__group">
                <label class="address-form__label" for="building">建物名</label>
                <input class="address-form__input" type="text" id="building" name="building" value="{{ old('building', $user->building) }}">
                <p class="address-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="address-form__btn btn" type="submit">更新する</button>
        </form>
    </div>
</div>
@endsection