<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/beer.min.css">
    <link rel="stylesheet" href="/css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/beer.min.js" type="module"></script>
    <script src="/js/utils.js"></script>
    <title>@yield("title") - Stok Uygulama</title>
</head>
<body>
<!-- App bar -->
<nav class="top">
    <div class="m l" style="width:80px"></div>
    <h5 class="max left-align">@yield("title")</h5>
    <button class="circle border" onclick="ui('mode') == 'dark' ? ui('mode', 'light') : ui('mode', 'dark')">
        <i>light_mode</i>
    </button>
    <button class="" data-ui="#menu-menu">
        <i>person</i>
        <span>{{ ucwords($user->first_name) }} {{ ucwords($user->last_name) }}</span>
        <i>arrow_drop_down</i>
        <menu class="no-wrap left no-wrap" id="menu-menu" data-ui="#menu-menu">
            <a class="row" href="/auth/logout">
                <i>logout</i>
                <div class="max">Çıkış Yap</div>
            </a>
        </menu>
    </button>
</nav>

<!-- Navigation rail -->
<nav class="left m l">
    <a href="/wallet" class="{{Request::path() == "wallet" ? "active" : ""}}">
        <i>wallet</i>
        <span class="max">Cüzdanım</span>
    </a>
    <a href="/stocks" class="{{Request::path() == "stocks" ? "active" : ""}}">
        <i>waterfall_chart</i>
        <span>Stoklar</span>
    </a>
    <a href="/user" class="{{Request::path() == "user" ? "active" : ""}}">
        <i>person</i>
        <span class="max">Hesabım</span>
    </a>
</nav>

<!-- Navigation rail -->
<nav class="bottom s">
    <a href="/wallet" class="{{Request::path() == "wallet" ? "active" : ""}}">
        <i>wallet</i>
        <span class="max">Cüzdanım</span>
    </a>
    <a href="/stocks" class="{{Request::path() == "stocks" ? "active" : ""}}">
        <i>waterfall_chart</i>
        <span>Stoklar</span>
    </a>
    <a href="/user" class="{{Request::path() == "user" ? "active" : ""}}">
        <i>person</i>
        <span class="max">Hesabım</span>
    </a>
</nav>

<div class="app">
    @yield("content")
</div>

</body>
</html>
