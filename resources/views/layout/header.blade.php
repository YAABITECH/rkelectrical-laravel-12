<head>
    <title>@yield('xmt_tit')</title>
    <meta name="description" content="@yield('xmt_des')">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="UTF-8">
    @hasSection('xmt_can')
        <link rel="canonical" href="{{config('app.url')}}@yield('xmt_can')">
    @endif
    <meta name="robots" content="@yield('xmt_rob')">
    <meta name="google" content="notranslate">
    <link rel="stylesheet" href="{{URL::asset('/css/rk-css-v1-2.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/bootstrap-v2-0.css')}}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{URL::asset('/image/favicon/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::asset('/image/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{URL::asset('/image/favicon/android-chrome-48x48.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{URL::asset('/image/favicon/android-chrome-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="144x144" href="{{URL::asset('/image/favicon/android-chrome-144x144.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{URL::asset('/image/favicon/android-chrome-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{URL::asset('/image/favicon/android-chrome-512x512.png')}}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{URL::asset('/image/favicon/apple-touch-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{URL::asset('/image/favicon/apple-touch-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{URL::asset('/image/favicon/apple-touch-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{URL::asset('/image/favicon/apple-touch-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{URL::asset('/image/favicon/apple-touch-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{URL::asset('/image/favicon/apple-touch-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{URL::asset('/image/favicon/apple-touch-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{URL::asset('/image/favicon/apple-touch-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{URL::asset('/image/favicon/apple-touch-icon-180x180.png')}}">
    <link rel="mask-icon" href="{{URL::asset('/image/favicon/safari-pinned-tab.svg')}}" color="#330066">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{config('app.name')}}">
    <meta name="apple-mobile-web-app-title" content="{{config('app.name')}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="msapplication-tooltip" content="{{config('app.name')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta name="msapplication-square70x70logo" content="{{URL::asset('/image/favicon/mstile-70x70.png')}}">
    <meta name="msapplication-square150x150logo" content="{{URL::asset('/image/favicon/mstile-150x150.png')}}">
    <meta name="msapplication-wide310x150logo" content="{{URL::asset('/image/favicon/mstile-310x150.png')}}">
    <meta name="msapplication-square310x310logo" content="{{URL::asset('/image/favicon/mstile-310x310.png')}}">
    <meta name="msapplication-TileImage" content="{{URL::asset('/image/favicon/mstile-144x144.png')}}">
    <meta name="msapplication-config" content="{{URL::asset('/browserconfig.xml')}}">
    <link rel="manifest" href="{{URL::asset('/site.webmanifest')}}">

    <meta property="og:url" content="{{ config('app.url') }}{{ (trim($__env->yieldContent('xmt_can'))) ? trim($__env->yieldContent('xmt_can')) : $_SERVER['REQUEST_URI'] }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{config('app.name')}}">
    <meta property="fb:app_id" content="">
    <meta property="og:title" content="@yield('xmt_tit')">
    <meta property="og:description" content="@yield('xmt_des')">
    <meta property="og:image" content="{{ config('app.url') }}{{ (trim($__env->yieldContent('xmt_img'))) ? trim($__env->yieldContent('xmt_img')) : '/image/'.config('app.urlname').'-ogimage.jpg' }}">
    <meta property="og:image:alt" content="image">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@{{config('app.urlname')}}">
    <meta name="twitter:creator" content="@{{config('app.urlname')}}">
    <meta name="twitter:title" content="@yield('xmt_tit')">
    <meta name="twitter:description" content="@yield('xmt_des')">
    <meta name="twitter:image" content="{{ config('app.url') }}{{ (trim($__env->yieldContent('xmt_img'))) ? trim($__env->yieldContent('xmt_img')) : '/image/'.config('app.urlname').'-ogimage.jpg' }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-87672596-4">
    </script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-87672596-4');
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('headcss')
    <style>
        .fw-semibold {
            font-weight: 500;
        }
    </style>
</head>
