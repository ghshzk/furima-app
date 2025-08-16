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
            <div class="other-user-card">
                @if ($otherUser->image_path)
                    <img class="other-user-card__img" src="{{ Storage::url($otherUser->image_path) }}" alt="{{ $otherUser->name }}">
                @else
                    <img class="other-user-card__img" src="{{ asset('img/default_icon.png') }}" alt="NoImage">
                @endif
                <h1 class="other-user-card__heading">「{{ $otherUser->name }}」さんとの取引画面</h1>
            </div>

            <!-- 購入側のみに表示 -->
            @if($transaction->buyer_id === auth()->id() && $transaction->status == 'trading')
                <form action="{{ route('transaction.mail', ['order_id' => $transaction->id]) }}" class="completed-action" method='post'>
                    @csrf
                    <button class="completed-action__btn" id="open-modal-btn" type="submit">取引を完了する</button>
                </form>
            @elseif($transaction->status === 'pending_complete')
                <div class="completed-action">
                    <button class="completed-action__btn" disabled>取引完了済み</button>
                </div>
            @endif
        </div>

        <div class="item-container">
            <img class="item__img" src="{{ Storage::url($transaction->item->image_path) }}" alt="{{ $transaction->item->name }}">
            <div class="item-info">
                <h2 class="item__name">{{ $transaction->item->name }}</h2>
                <p class="item__price">¥{{ number_format($transaction->item->price) }}</p>
            </div>
        </div>

        <div class="chat-container">
            @foreach($orderMessages as $orderMessage)
                <div class="chat {{ $orderMessage->sender_id == auth()->id() ? 'chat-right' : 'chat-left' }}">
                    @if($orderMessage->sender_id === Auth::id())
                        <div class="chat-message">
                            <div class="user-card">
                                <strong class="user-card__name">{{ $orderMessage->sender->name }}</strong>
                                @if ($orderMessage->sender->image_path)
                                    <img class="user-card__img" src="{{ Storage::url($orderMessage->sender->image_path) }}" alt="{{ $orderMessage->sender->name }}">
                                @else
                                    <img class="user-card__img" src="{{ asset('img/default_icon.png') }}" alt="NoImage">
                                @endif
                            </div>

                            @if ($orderMessage->image_path)
                            <div class="chat-message__img">
                                <img class="chat-message__img-path" src="{{ Storage::url($orderMessage->image_path) }}" alt="">
                            </div>
                            @endif

                            <form class="update-form" id="update-form-{{ $orderMessage->id }}" action="{{ route('transaction.update', ['message_id' => $orderMessage->id]) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <textarea class="update-form__textarea" name="content" id="">{{ $orderMessage->content }}</textarea>
                            </form>
                            <div class="chat-message__actions">
                                <button class="update-form__btn chat-btn" form="update-form-{{ $orderMessage->id }}" type="submit">編集</button>
                                <form class="delete-form" action="{{ route('transaction.delete', ['message_id' => $orderMessage->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-form__btn chat-btn" type="submit">削除</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="chat-message">
                            <div class="user-card">
                                @if ($orderMessage->sender->image_path)
                                    <img class="user-card__img" src="{{ Storage::url($orderMessage->sender->image_path) }}" alt="{{ $orderMessage->sender->name }}">
                                @else
                                    <img class="user-card__img" src="{{ asset('img/default_icon.png') }}" alt="NoImage">
                                @endif
                                <strong class="user-card__name">{{ $orderMessage->sender->name }}</strong>
                            </div>
                            @if ($orderMessage->image_path)
                            <div class="chat-message__img">
                                <img class="chat-message__img-path" src="{{ Storage::url($orderMessage->image_path) }}" alt="">
                            </div>
                            @endif
                            <p class="chat-message__content">{{ $orderMessage->content }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="chat-form-container">
            @if ($errors->any())
                <div class="error-wrapper">
                    @foreach ($errors->all() as $error)
                    <p class="chat-form__error-message">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form class="chat-form" action="{{ route('transaction.send', ['order_id' => $transaction->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <textarea class="chat-form__textarea" id="chatTextarea" name="content" placeholder="取引メッセージを入力してください">{{ old('content') }}</textarea>
                <div class="chat-form__upload-inner">
                    <input type="file" id="fileInput" name="image_path" accept="image/*" style="display:none;">
                    <label class="chat-form__upload" for="fileInput">画像を追加</label>
                </div>
                <button class="chat-form__btn" type="submit">
                    <img class="chat-form__icon" src="{{ asset('img/inputbutton.png') }}" alt="">
                </button>
            </form>
        </div>

        <div class="modal" id="completed-modal" style="display:{{ $showReviewModal ? 'block' : 'none' }};">
            <div class="modal-content">
                <h2 class="modal__heading">
                    取引が完了しました。
                </h2>
                <form class="review-form" action="{{ route('transaction.review', ['order_id' => $transaction->id]) }}" method="post">
                    @csrf
                    <div class="review-form__item">
                        <p class="review-form__message">今回の取引相手はどうでしたか？</p>
                        <div class="review-form__rating">
                            <span class="star-rating" data-rate="1"></span>
                            <span class="star-rating" data-rate="2"></span>
                            <span class="star-rating" data-rate="3"></span>
                            <span class="star-rating" data-rate="4"></span>
                            <span class="star-rating" data-rate="5"></span>
                        </div>
                        <input type="hidden" name="rating" id="rating-input" required>
                    </div>
                    <div class="review-form__btn">
                        <button class="review-form__btn-submit" type="submit">送信する</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
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
            localStorage.removeItem(storageKey);
        });

        const openModalBtn = document.getElementById('open-modal-btn');
        const modal = document.querySelector('.modal');

        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => {
                modal.style.display = 'block';
            });
        }

        const stars = document.querySelectorAll('.star-rating');
        const ratingInput = document.getElementById('rating-input');
        let selectedRate = 0;

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const rate = parseInt(star.dataset.rate);
                highlightStars(rate);
            });

            star.addEventListener ('mouseleave', () => {
                restoreSelectedStars();
            });

            star.addEventListener ('click', () => {
                const rate = parseInt(star.dataset.rate);
                selectedRate = rate;
                ratingInput.value = rate;
                restoreSelectedStars();
            });
        });

        function highlightStars(rate) {
            stars.forEach(star => {
                if (parseInt(star.dataset.rate) <= rate) {
                    star.classList.add('hover');
                } else {
                    star.classList.remove('hover');
                }
            });
        }

        function restoreSelectedStars() {
            stars.forEach(star => {
                star.classList.remove('hover');
                if (parseInt(star.dataset.rate) <= selectedRate) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }

        /*window.addEventListener('DOMContentLoaded', function () {
            @if(
                $transaction->seller_id === auth()->id() &&
                $transaction->buyer_rated &&
                !$transaction->seller_rated
            )
            document.getElementById('completed-modal').style.display = 'block';

            @endif
        });*/
        window.addEventListener('DOMContentLoaded', function () {
            @if(
                $transaction->status === 'pending_complete' &&
                $transaction->buyer_id === auth()->id() && !$transaction->buyer_rated
            )
            document.getElementById('complete-modal').style.display = 'block';
            @elseif(
                $transaction->status === 'pending_complete' &&
                $transaction->seller_id === auth()->id() && !$transaction->seller_rated
            )
            document.getElementById('completed-modal').style.display = 'block';
            @endif
        });
    </script>
</div>
@endsection