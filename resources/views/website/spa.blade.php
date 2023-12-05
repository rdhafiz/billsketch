<!DOCTYPE html>
<html>
<head>

    <!-- Meta Details -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicon.png')}}">

    <title>Bilify</title>
    <meta name="title" content="{{config('app.APP_NAME')}}">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta property="og:title" content="{{config('app.APP_NAME')}}">
    <meta property="og:description" content="">
    <meta property="og:image" content="{{asset('assets/images/icon.png')}}">
    <meta property="og:url" content="{{url()->current()}}">
    <meta name="twitter:title" content="{{config('app.APP_NAME')}}">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="{{asset('assets/images/icon.png')}}">
    <!-- Meta Details -->

    <!-- Google Fonts (Nunito+Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,700;6..12,1000&display=swap" rel="stylesheet">
    <!-- Google Fonts (Nunito+Sans) -->

    <!-- MOT4AI Stylesheet -->
    @vite('resources/scss/website/app.scss')
    <!-- MOT4AI Stylesheet -->
</head>
<body>
<div id="app">
    <app></app>
</div>
@vite('resources/js/website/app.js')
</body>
</html>
