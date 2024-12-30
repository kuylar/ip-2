@extends("modalLayout")

@section("content")
    <h5>Üye Ol</h5>
    <form method="post">
        <div class="top-padding">
            <div class="row bottom-padding">
                <div class="field label prefix border max" id="inputFn">
                    <i>person</i>
                    <input type="text" id="firstName" name="firstName" onchange="markFieldValid(this.parentElement)"
                           value="{{old("firstName")}}">
                    <label for="firstName">İsim</label>
                    <span style="display:none;" class="error"></span>
                </div>
                <div class="field label border max" id="inputLn">
                    <input type="text" id="lastName" name="lastName" onchange="markFieldValid(this.parentElement)"
                           value="{{old("lastName")}}">
                    <label for="lastName">Soyisim</label>
                    <span style="display:none;" class="error"></span>
                </div>
            </div>
            <div class="field label prefix border" id="inputEmail">
                <i>email</i>
                <input type="text" id="email" name="email" onchange="markFieldValid(this.parentElement)"
                       value="{{old("email")}}">
                <label for="email">E Posta</label>
                <span style="display:none;" class="error"></span>
            </div>
            <div class="field label prefix border" id="inputPass">
                <i>lock</i>
                <input type="password" id="password" name="password" onchange="markFieldValid(this.parentElement)"
                       value="{{old("password")}}">
                <label for="password">Şifre</label>
                <span style="display:none;" class="error"></span>
            </div>
        </div>
        @csrf
        <nav class="right-align no-space">
            <a href="/auth/login" class="transparent button link ripple">Giriş Yap</a>
            <div class="max"></div>
            <button type="submit" onclick="loginSpinner(true);this.parentElement.parentElement.submit()">
                <progress style="display:none" class="circle small"></progress>
                Üye Ol
            </button>
        </nav>
    </form>
    @if ($errors->any())
        <script>
            @if ($errors->has("firstName"))
            markFieldInvalid(document.getElementById("inputFn"), "{{str_replace("\"", "'", $errors->first("firstName"))}}");
            @endif
            @if ($errors->has("lastName"))
            markFieldInvalid(document.getElementById("inputLn"), "{{str_replace("\"", "'", $errors->first("lastName"))}}");
            @endif
            @if ($errors->has("email"))
            markFieldInvalid(document.getElementById("inputEmail"), "{{str_replace("\"", "'", $errors->first("email"))}}");
            @endif
            @if ($errors->has("password"))
            markFieldInvalid(document.getElementById("inputPass"), "{{str_replace("\"", "'", $errors->first("password"))}}");
            @endif
        </script>
    @endif
@endsection
