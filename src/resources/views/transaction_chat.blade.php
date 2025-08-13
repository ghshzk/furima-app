@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <!-- 左のサイドバー -->
    <aside class="sidebar">
        <div class="sidebar-content">
            <h3 class="sidebar__heading">
                その他の取引
            </h3>
            @foreach($otherTransactions as $otherTransaction)
            <a class="other-item__link" href="{{ url('/transaction/' . $otherTransaction->id) }}">
                {{ $otherTransaction->item->name }}
            </a>
            @endforeach
        </div>
    </aside>

    <!-- メインコンテンツ -->
    <main class="main">
        <div class="other-user-container">
            @if ($otherUser->image_path)
                <img class="other-user__img" src="{{ asset('storage/profile/' . $otherUser->image_path) }}" alt="{{ $otherUser->name }}">
            @else
                <img class="other-user__img" src="{{ asset('storage/images/default_icon.png') }}" alt="NoImage">
            @endif
            <h1 class="other-user__heading">「{{ $otherUser->name }}」さんとの取引画面</h1>

            <!-- 購入側のみに表示 -->
            @if($transaction->buyer_id === auth()->id())
                <form class="completed-form" action="">
                    @csrf
                    <button class="completed-form__btn" type="submit">取引を完了する</button>
                </form>
            @endif
        </div>

        <div class="item-container">
            <img class="item__img" src="{{ Storage::url($transaction->item->image_path) }}" alt="{{ $transaction->item->name }}">
            <h2 class="item__name">{{ $transaction->item->name }}</h2>
            <p class="item__price">{{ $transaction->item->price }}</p>
        </div>

        <div class="chat-container">
            @foreach($orderMessages as $orderMessage)
                <div class="chat {{ $orderMessage->sender_id == auth()->id() ? 'chat-right' : 'chat-left' }}">
                    <div class="chat-message">
                        @if($orderMessage->sender_id === Auth::id())
                        <div class="user-card">
                            <strong class="user-card__name">{{ $orderMessage->sender->name }}</strong>
                            @if ($orderMessage->sender->image_path)
                                <img class="user-card__img" src="{{ asset('storage/profile/' . $orderMessage->sender->image_path) }}" alt="{{ $orderMessage->sender->name }}">
                            @else
                                <img class="user-card__img" src="{{ asset('storage/images/default_icon.png') }}" alt="NoImage">
                            @endif
                        </div>
                        <!--p class="chat-message__content">{{ $orderMessage->content }}</!--p-->

                        <form class="chat-update-form" action="{{ route('transaction.update', ['message_id' => $orderMessage->id]) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="update-form">
                                <input type="text" class="update-form__input" name="content" value="{{ $orderMessage->content }}">
                                <input type="hidden" name="id" value="{{ $orderMessage['id'] }}">
                            </div>
                            <button class="update-form__btn" type="submit">編集</button>
                        </form>

                        <form class="chat-delete-form" action="{{ route('transaction.delete', ['message_id' => $orderMessage->id]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <div class="delete-form__btn">
                                <input type="hidden" name="id" value="{{ $orderMessage['id'] }}">
                                <button class="delete-form__btn-submit" type="submit">削除</button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="chat-message">
                        <div class="user-card">
                            @if ($orderMessage->sender->image_path)
                                <img class="user-card__img" src="{{ asset('storage/profile/' . $orderMessage->sender->image_path) }}" alt="{{ $orderMessage->sender->name }}">
                            @else
                                <img class="user-card__img" src="{{ asset('storage/images/default_icon.png') }}" alt="NoImage">
                            @endif
                            <strong class="user-card__name">{{ $orderMessage->sender->name }}</strong>
                        </div>
                        <p class="chat-message__content">{{ $orderMessage->content }}</p>
                    </div>
                    @endif
                </div>
            @endforeach

            <p class="chat-form__error-message">
                @error('content')
                {{ $message }}
                @enderror
                @error('image_path')
                {{ $message }}
                @enderror
            </p>
            <form class="chat-form" action="{{ route('transaction.send', ['order_id' => $transaction->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <textarea class="chat-form__textarea" id="chatTextarea" name="content" placeholder="取引メッセージを入力してください">{{ old('content') }}</textarea>
                <div class="chat-form__upload-inner">
                    <input type="file" id="fileInput" name="image_path" accept="image/*" style="display:none;">
                    <label class="chat-form__upload" for="fileInput">画像を追加</label>
                </div>
                <button class="chat-form__btn" type="submit">
                    <img class="chat-form__icon" src="{{ asset('img/inputbutton.png') }}" alt="">
                </button>
            </form>

            <script>
                const textarea = document.getElementById('chatTextarea');
                const storageKey = 'chatContent_transaction_{{ $transaction->id }}';

                window.addEventListener('load', () => {
                    const saved = localStorage.getItem(storageKey);
                    if (saved) {
                        textarea.value = saved;
                    }
                });

                textarea.addEventListener('input', () => {
                    localStorage.setItem(storageKey, textarea.value);
                });

                document.querySelector('.chat-form').addEventListener('submit', () => {
                    locakStorage.removeItem(storageKey);
                });
            </script>
        </div>
    </main>
</div>
@endsection