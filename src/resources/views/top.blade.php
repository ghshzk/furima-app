@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection

@section('content')
<div class="tabs">
    <div class="tab-nav">
        <ul class="tab-nav__list">
            <li class="tab-nav__item">
                <a class="{{ $tab == 'recommend' ? 'active' : '' }}" href="{{ route('top', ['tab' => 'recommend']) }}">
                    おすすめ
                </a>
            </li>
            <li class="tab-nav__item">
                <a class="{{ $tab == 'mylist' ? 'active' : '' }}" href="{{ route('top', ['tab' => 'mylist']) }}">
                    マイリスト
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        @if ($tab == 'recommend')
        <!-- おすすめ商品一覧を表示-->
        <div class="item-container">
            @foreach($items as $item)
                <div class="item-card">
                    <a class="item-card__link" href="{{ url('/item/' . $item->id) }}">
                        <img class="item-card__img {{ $item->is_sold ? 'sold' : '' }}" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                        <p class="item-card__content">{{ $item->name }}</p>
                    </a>
                    @if($item->isSold())
                    <div class="sold-overlay">SOLD</div>
                    @endif
                </div>
            @endforeach
        </div>
        @elseif ($tab == 'mylist')
        <!-- マイリストの一覧表示 -->
        <div class="item-container">
            @foreach($items as $item)
                <div class="item-card">
                    <a class="item-card__link" href="{{ url('/item/' . $item->id) }}">
                        <img class="item-card__img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                        <p class="item-card__content">{{ $item->name }}</p>
                    </a>
                    @if($item->isSold())
                    <div class="sold-overlay">SOLD</div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection