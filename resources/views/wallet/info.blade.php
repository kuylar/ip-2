@extends("layout")

@section("title")
    Cüzdanım
@endsection

@section("content")
    <div class="double-column-layout">
        <div class="padding">
            <article class="large-padding round">
                <h5 class="small secondary-text">
                    Cüzdan değeri
                </h5>
                <h1><span
                        id="balance">{{ number_format($balance, 2, '.', ',') }}</span> {{strtoupper($currencies[$thisAcc->currency_id]->code)}}
                </h1>
                <div class="margin"></div>
                <button onclick="deposit({{$thisAcc->id}})">Para Yükle</button>
                <button onclick="withdraw({{$thisAcc->id}})">Para Çek</button>
            </article>

            <hr class="top-margin bottom-margin">
            <h4 class="small bottom-margin">Diğer hesaplar</h4>
            @foreach($accounts as $w)
                @if($w->id != $thisAcc->id)
                    <a class="row padding surface-container wave" href="/wallet?id={{$w->id}}">
                        <div class="button circle">{{strtoupper($currencies[$w->currency_id]->code)}}</div>
                        <div class="max">
                            {{$currencies[$w->currency_id]->name}} Hesabı
                        </div>
                    </a>
                @endif
            @endforeach
            <a href="/wallet/new" class="button responsive top-margin">
                <i>add</i>
                <span>Yeni Cüzdan</span>
            </a>
        </div>

        <div class="padding" id="transactions">
            <h5 class="bottom-margin">Geçmiş İşlemler</h5>
        </div>
    </div>


    <script>
        const csrf = "{{csrf_token()}}";

        const stockNames = {!! json_encode($stockInfos) !!};

        document.addEventListener("DOMContentLoaded", () => {
            loadTransactions({{$thisAcc->id}}, 1);
        });
    </script>
    <script src="/js/wallet.js"></script>
@endsection
