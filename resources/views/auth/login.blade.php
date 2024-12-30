@extends("modalLayout")

@section("content")
    <h5>Giriş Yap</h5>
    <form method="post">
        <div class="top-padding">
            <div class="field label prefix border" id="inputEmail">
                <i>email</i>
                <input type="text" id="email" name="email" onchange="markFieldValid(this.parentElement)"
                       value="{{old("email")}}">
                <label for="email">E Posta</label>
                <span class="error"></span>
            </div>
            <div class="field label prefix border" id="inputPass">
                <i>lock</i>
                <input type="password" id="password" name="password" onchange="markFieldValid(this.parentElement)"
                       value="{{old("password")}}">
                <label for="password">Şifre</label>
                <span class="error"></span>
            </div>
        </div>
        @csrf
        <nav class="right-align no-space">
            <a href="/auth/register" class="transparent button link ripple">Üye Ol</a>
            <div class="max"></div>
            <button type="submit" onclick="loginSpinner(true);this.parentElement.parentElement.submit()">
                <progress style="display:none" class="circle small"></progress>
                Giriş Yap
            </button>
        </nav>
    </form>
    @if ($errors->any())
        <script>
            @if ($errors->has("email"))
            markFieldInvalid(document.getElementById("inputEmail"), "{{str_replace("\"", "'", $errors->first("email"))}}");
            @endif
            @if ($errors->has("password"))
            markFieldInvalid(document.getElementById("inputPass"), "{{str_replace("\"", "'", $errors->first("password"))}}");
            @endif
        </script>
    @endif
@endsection
