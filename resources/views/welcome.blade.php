<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{asset('css/dist/css/style.min.css') }}"/>
        <link rel="stylesheet" href="{{asset('css/dist/css/font-awesome.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('css/dist/bootstrap4.3.1/css/bootstrap.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('css/app.css') }}"/>
        <title>Exo dev laravel and vue js</title>
    </head>
    <body>
        <div id="app" class="row col-sm-12">
            <front-page></front-page>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>

