@extends("layout")

@section("title")
    Yeni Cüzdan
@endsection

@section("content")
    <form method="post" class="padding">
        <p>Yeni cüzdan açmak için lütfen aşağıdaki tüm boşlukları doldurun</p>

        <div class="top-margin" style="width: max-content">
            <div class="field label suffix border">
                <select name="currency">
                    @foreach($currencies as $curr)
                        <option value="{{$curr->id}}" @if($curr->code == "try") selected @endif>
                            {{strtoupper($curr->code)}} - {{$curr->name}}
                        </option>
                    @endforeach
                </select>
                <label>Para Birimi</label>
                <i>arrow_drop_down</i>
            </div>
        </div>
        @csrf
        <button onclick="spinner(this);this.setAttribute('disabled', '');document.querySelector('form').submit()">
            <progress style="display:none" class="circle small"></progress>
            <i>add</i>
            <span>Cüzdan oluştur</span>
        </button>
    </form>
@endsection
