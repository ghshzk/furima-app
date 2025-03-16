<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <a href="{{ url('/') }}">
                <img class="header-logo" src="{{ asset('images/logo.svg') }}" alt="サイトロゴ">
            </a>

            @if(!in_array(Route::currentRouteName(),['login','register']))
                <div class="header-search-form">
                    <form class="header-search-form__form" action="{{ route('item.search') }}" method="get" popovertarget="search-result">
                        @csrf
                        <input class="header-search-form__input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                    </form>
                </div>

                <nav class="header-nav">
                    <ul class="header-nav__list">
                        <li class="header-nav__item">
                            @auth
                            <form action="/logout" method="post">
                                @csrf
                                <input class="header-nav__link" type="submit" value="ログアウト">
                            </form>
                            @else
                            <a class="header-nav__link" href="/login">ログイン</a>
                            @endauth
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/mypage">マイページ</a>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link header-nav__link-sell" href="/sell">出品</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </header>
        <div class="content">
            @yield('content')

            <div class="modal" id="search-result" popover>
                <div class="search-result__content" id="search-result__content">
                    @if(isset($searchResults))
                        @if(count($searchResults) > 0)
                            @foreach($searchResults as $item)
                                <a class="search-result__link" href="{{ url('/item/' . $item->id) }}">
                                    <img class="search-result__img {{ $item->is_sold ? 'sold' : '' }}" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
                                    <p class="search-result__name">{{ $item->name }}</p>
                                </a>
                            @endforeach
                        @else
                            該当する商品が見つかりませんでした。
                        @endif
                    @endif
                </div>
                <script>
                    const searchForm = document.querySelector('.header-search-form__form');
                    const searchResultContent = document.getElementById('search-result__content');
                    const searchResultContainer = document.getElementById('search-result');

                    searchForm.addEventListener('submit', function(event) {
                        event.preventDefault(); // ページリロードを防止

                        const keyword = document.querySelector('.header-search-form__input').value;

                        // Ajaxリクエストで検索結果を取得
                        fetch("{{ route('item.search') }}?keyword=" + keyword, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'  // Ajaxリクエストを示すヘッダー
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // 検索結果があれば表示
                            if (data.searchResults.length > 0) {
                                searchResultContent.innerHTML = '';
                                data.searchResults.forEach(item => {
                                    const itemLink = document.createElement('a');
                                    itemLink.classList.add('search-result__link');
                                    itemLink.href = '/item/' + item.id;

                                    const itemImage = document.createElement('img');
                                    itemImage.classList.add('search-result__img');
                                    itemImage.src = "{{ asset('storage') }}/" + item.image_path;  // 画像パス
                                    itemImage.alt = item.name;

                                    const itemContent = document.createElement('p');
                                    itemContent.classList.add('search-result__name');
                                    itemContent.innerText = item.name;

                                    itemLink.appendChild(itemImage);
                                    itemLink.appendChild(itemContent);
                                    searchResultContent.appendChild(itemLink);
                                });

                                // 検索結果の表示
                                searchResultContainer.style.display = 'block';
                            } else {
                                searchResultContent.innerHTML = '該当する商品が見つかりませんでした。';
                                searchResultContainer.style.display = 'block';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                </script>


            </div>
        </div>
    </div>
</body>

</html>