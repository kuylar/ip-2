@extends("layout")

@section("title")
    Ana Ekran
@endsection

@section("content")
    <h1>test test test</h1>
    <p>test test</p>
    <button onclick="m3Alert('testDialog', 'test teeest TEST')">test</button>
    <button onclick="m3Prompt('testDialog', 'test teeest TEST').then(x => m3Snackbar('result: ' + x))">test</button>
    <button onclick="m3Confirm('testDialog', 'test teeest TEST').then(x => m3Snackbar('result: ' + x))">test</button>
@endsection
