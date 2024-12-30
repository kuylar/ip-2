<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="/css/beer.min.css">
    <script type="module" src="/js/beer.min.js"></script>
    <script src="/js/utils.js"></script>
</head>
<body>
<script>
    function markFieldInvalid(element, message) {
        element.classList.add('invalid');

        const errEl = element.querySelector(".error");
        if (errEl) {
            errEl.style.display = 'block';
            errEl.innerText = message;
        }
    }

    function markFieldValid(element) {
        if (element.classList.contains('invalid'))
            element.classList.remove('invalid');

        const errEl = element.querySelector(".error");
        if (errEl) {
            if (errEl.style.display !== 'none') {
                errEl.style.display = 'none';
            }
        }
    }

    function loginSpinner(show) {
        document.querySelector("progress").style.display = show ? "block" : "none";
        document.querySelector("button").disabled = show;
    }
</script>
<dialog class="modal active">
    @yield("content")
</dialog>
</body>
</html>
