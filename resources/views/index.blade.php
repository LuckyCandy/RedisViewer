<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gaea</title>
    </head>
    <body>
        <div id="app">
            <index-component></index-component>
        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
