@extends("layout")

@section("title")
    @if(isset($stock))
        {{$stock["name"]}}
    @else
        Kullanıcı Bilgileri
    @endif
@endsection

@section("content")

    <div class="double-column-layout">
        <div class="padding">
            <article>
                <h5>{{ ucwords($user->first_name) }} {{ ucwords($user->last_name) }}</h5>
                <table style="width: max-content">
                    <tbody>
                    <tr>
                        <th>Kullanıcı No</th>
                        <td>#{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>E-Posta Adresi</th>
                        <td>{{ $user->email }} ({{ $user->email_verified_at != null ? "onaylanmış" : "onaylanmamış" }})
                        </td>
                    </tr>
                    <tr>
                        <th>Hesap Oluşturma Tarihi</th>
                        <td>{{ $user->created_at }} UTC</td>
                    </tr>
                    </tbody>
                </table>
            </article>

            <article>
                <h5 class="bottom-margin">Cüzdanlar</h5>
                @foreach($accounts as $w)
                    <a class="bottom-margin row padding wave secondary-container round" href="/wallet?id={{$w->id}}">
                        <div class="button circle">{{strtoupper($currencies[$w->currency_id]->code)}}</div>
                        <div class="max">
                            {{$currencies[$w->currency_id]->name}} Hesabı
                        </div>
                    </a>
                @endforeach
            </article>

            <article>
                <h5 class="bottom-margin">
                    <span>Fatura Adresleri</span>
                    <a class="button" href="/user/addAddress">
                        <i>add</i>
                        <span>Ekle</span>
                    </a>
                </h5>
                @foreach($addresses as $a)
                    <div class="bottom-margin row padding secondary-container round" id="userAddress-{{ $a->id }}">
                        <div class="max">
                            <div>{{ $a->address }}</div>
                            <div>{{ ucwords(strtolower($mahalleler[$a->mahalle_id])) }}
                                , {{ ucwords(strtolower($ilceler[$a->ilce_id])) }}/{{ $sehirler[$a->sehir_id] }}</div>
                        </div>
                        <button class="circle" onclick="deleteAddr({{$a->id}})">
                            <i>delete</i>
                        </button>
                    </div>
                @endforeach
            </article>
        </div>
        <div class="padding" id="postsContainer">
            <h5 class="bottom-margin">Son Giriş/Çıkış İşlemleri</h5>
            @foreach($logins as $login)
                <div class="row padding surface-container">
                    @if($login->login_type == "login")
                        <div class="button circle tertiary-container">
                            <i>login</i>
                        </div>
                        <div class="max">
                            <h5 class="small">
                                Giriş İşlemi
                            </h5>
                            <div>
                                {{ $login->created_at }} UTC tarihinde
                                {{ $login->parsed_params["browser"]["os_title"] }} sisteminde
                                {{ $login->parsed_params["browser"]["browser_title"] }} tarayıcısı ile giriş yapıldı
                            </div>
                        </div>
                    @else
                        <div class="button circle secondary-container">
                            <i>logout</i>
                        </div>
                        <div class="max">
                            <h5 class="small">
                                Çıkış İşlemi
                            </h5>
                            <div>
                                {{ $login->created_at }} UTC
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <script src="/js/user.js"></script>
@endsection
