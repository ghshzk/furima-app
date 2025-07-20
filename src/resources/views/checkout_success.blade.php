@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/success.css') }}">
@endsection

@section('content')
<div class="success-container">
    <h1 class="success__heading">決済が完了いたしました</h1>

    <div class="success__info">
        <h3 class="info-title"></h3>
        <ul class="info-list">
            <li class="info-list__item">決済ID：{{ $session->id }}</li>
            <li class="info-list__item">合計：¥{{ number_format($session->amount_total) }}</li>
            <li class="info-list__item">決済方法：{{ $displayPaymentMethod }}</li>
        </ul>
    </div>

    <div class="link">
        <a href="{{ route('top') }}" class="checkout-link">トップページへ</a>
    </div>
</div>
@endsection
