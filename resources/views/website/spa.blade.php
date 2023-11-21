<!DOCTYPE html>
<html>
<head>

    <!-- Meta Details -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/icon.png')}}">

    <title>{{config('app.APP_NAME')}}</title>
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

    <!-- Google Recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Google Recaptcha -->

    <!--jQuery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!--jQuery-->

    <!--    owl carousel-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!--    owl carousel-->

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
