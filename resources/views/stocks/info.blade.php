@extends("layout")

@section("title")
    @if(isset($stock))
        {{$stock["name"]}}
    @else
        Stoklar
    @endif
@endsection

@section("content")

    <div class="double-column-layout">
        <div class="padding">
            <div class="field large prefix round fill active">
                <i class="front">search</i>
                @if(isset($stock))
                    <input data-search="#stock_search" value="{{$stock["name"]}}">
                @else
                    <input data-search="#stock_search" value="Arama yapmak için buraya tıklayın">
                @endif
                <menu class="min">
                    <div class="field large prefix suffix no-margin fixed">
                        <a href="/stocks" class="front"><i>arrow_back</i></a>
                        <input id="stock_search"
                               onkeyup="updateSearchSuggestions(this.value, this.parentElement.parentElement.querySelectorAll('a'))"
                               onkeydown="onSearchStocks(event)">
                        <i class="front">close</i>
                    </div>
                    @foreach($stocks as $searchStock)
                        <a class="row" href="/stocks?s={{$searchStock->id}}"
                           data-searchtext="{{$searchStock->code}} {{$searchStock->name}}">
                            <label>{{$searchStock->code}}</label>
                            <div>{{$searchStock->name}}</div>
                        </a>
                    @endforeach
                </menu>
            </div>

            @if (isset($stock))
                <article class="round">
                    <h5>
                        <span>{{$stock["name"]}}</span>
                        <label>{{$stock["code"]}}</label>
                    </h5>
                    <h6>Son iki haftalık durum</h6>

                    <canvas id="myChart"></canvas>
                    <script>
                        const ctx = document.getElementById('myChart');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($chart["labels"]) !!},
                                datasets: [{
                                    label: "Birim Fiyat",
                                    data: {!! json_encode($chart["values"]) !!}
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: false
                                    }
                                }
                            }
                        });
                    </script>

                    @if (isset($curPrice))
                        <h6>Mevcut Fiyat</h6>
                        <table>
                            <tbody>
                            @foreach(array_chunk($curPrice, 2, true) as $chunkedCP)
                                <tr>
                                    @foreach($chunkedCP as $curr => $price)
                                        <th class="min">{{strtoupper($curr)}}</th>
                                        <td>{{ number_format($price, 2, '.', ',') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </article>
            @endif

            @if (isset($acc))
                <article class="round">
                    <h5>Sizin durumunuz</h5>
                    <p>Elinizde <b>{{$userStockAmount * $curPrice["usd"]}} USD</b> değerinde
                        <b>{{$userStockAmount}} {{$stock["code"]}}</b> stoğu bulunmakta</p>
                    <p>Bakiyeniz <b>{{ $userBalance }} {{ $balanceCurrencyText }}</b></p>
                    <button onclick="buy({{$acc['id']}}, {{$stockId}})">Stok al</button>
                    <button onclick="sell({{$acc['id']}}, {{$stockId}})">Stok sat</button>
                </article>
            @endif

            @if (isset($portfolyo))
                <article class="secondary-container padding">
                    <h5 class="bottom-margin center-align">Portfolyöm</h5>
                    <canvas id="myChart"></canvas>
                    <br>
                    <small>* Değerler USD üzerinden hesaplanmaktadır</small>
                    <script>
                        const ctx = document.getElementById('myChart');
                        const labels = {!! json_encode($chart["labels"]) !!};
                        var graphClick = (e, f) => {
                            if (f.length > 0)
                                document.location = "/stocks?code=" + labels[f[0].index];
                        }

                        const c = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "Sahip olunan değer",
                                    data: {!! json_encode($chart["values"]) !!}
                                }]
                            },
                            options: {
                                onClick: graphClick
                            }
                        });
                    </script>
                </article>
            @endif
        </div>
        <div class="padding" id="postsContainer">
            @if(isset($stockId))
                <h2 class="small">İlgili Haberler</h2>
                <script id="newsLoader">
                    loadNews("{{$stockId}}", 1);
                    document.getElementById("newsLoader").remove()
                </script>
            @endif

            @if(isset($portfolyo))
                <h2 class="bottom-margin">Tüm Stoklarınız</h2>
                @foreach($portfolyo as $p)
                    <a class="row padding surface-container" href="/stocks?s={{ $p["id"] }}">
                        <div class="button circle tertiary" style="font-size: 0.75rem">
                            {{ $p["code"] }}
                        </div>
                        <div class="max">
                            <h5 class="small">
                                {{ $p["name"] }} &bull; {{ $p["amnt"] }}x
                            </h5>
                            <div>
                                Tanesi <b>{{ $p["singleValue"] }} USD</b> üzerinden toplam <b>{{ $p["value"] }} USD</b>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        const csrf = "{{@csrf_token()}}";
        @if (isset($curPrice))
        const stockPrice = {{$curPrice["usd"]}};
        @endif
    </script>
    <script src="/js/stock.js"></script>
@endsection
