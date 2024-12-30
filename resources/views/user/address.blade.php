@extends("modalLayout")

@section("content")
    <h5>Adres Ekle</h5>
    <form method="post">
        <div class="top-padding">
            <div class="field label suffix border" id="inputSehir">
                <select required name="sehirId" onchange="updateIlceler(this)" id="selectSehir">
                    @foreach($sehirler as $sehir)
                        <option value="{{$sehir->key}}">{{$sehir->name}}</option>
                    @endforeach
                </select>
                <label for="email">Şehir</label>
                <i>arrow_drop_down</i>
            </div>
            <div class="row">
                <div class="field label suffix border max" id="inputIlce">
                    <select required name="ilceId" disabled id="selectIlce" onchange="updateMahalleler(this)">
                    </select>
                    <label for="email">İlçe</label>
                    <i>arrow_drop_down</i>
                </div>
                <div class="field label suffix border max" id="inputMahalle">
                    <select required name="mahalleId" disabled id="selectMahalle">
                    </select>
                    <label for="email">Mahalle</label>
                    <i>arrow_drop_down</i>
                </div>
            </div>
            <div class="field label border" id="inputAddress">
                <input type="text" id="address" name="address" onchange="markFieldValid(this.parentElement)"
                       value="{{old("address")}}">
                <label for="password">Sokak / Bina no.</label>
            </div>
        </div>
        @csrf
        <nav class="right-align no-space">
            <div class="max"></div>
            <button type="submit" onclick="spinner(this);this.parentElement.parentElement.submit()">
                <progress style="display:none" class="circle small"></progress>
                Ekle
            </button>
        </nav>
    </form>
    <script src="/js/addressSelects.js">

    </script>
@endsection
