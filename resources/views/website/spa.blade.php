<!DOCTYPE html>
<html>
<head>

    <!-- Meta Details -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicon.png')}}">

    <title>{{env('APP_NAME')}}</title>
    <meta name="title" content="{{config('app.APP_NAME')}}">
    <meta name="description" content="Create professional invoices effortlessly, share them seamlessly and send with ease.">
    <meta name="keywords" content="invoice,management,simple,smart,secure,professional,system,effortlessly,share">
    <meta property="og:title" content="{{config('app.APP_NAME')}}">
    <meta property="og:description" content="Create professional invoices effortlessly, share them seamlessly and send with ease.">
    <meta property="og:image" content="{{asset('assets/images/banner/home-banner.jpg')}}">
    <meta property="og:url" content="{{url()->current()}}">
    <meta name="twitter:title" content="{{config('app.APP_NAME')}}">
    <meta name="twitter:description" content="Create professional invoices effortlessly, share them seamlessly and send with ease.">
    <meta name="twitter:image" content="{{asset('assets/images/banner/home-banner.jpg')}}">
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
