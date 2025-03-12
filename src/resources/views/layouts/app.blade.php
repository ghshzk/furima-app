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
            @yield('header-nav')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>