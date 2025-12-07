<head>
    <title>@yield('xmt_tit')</title>
    <meta name="description" content="@yield('xmt_des','RKElectricalGrid Admin Panel')">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    @php
        $xmt_can = null;
        if(array_key_exists('xmt_can', app()->view->getSections())){
            $xmt_can = app()->view->getSections()['xmt_can'];
        }
        $xmt_img = null;
        if(array_key_exists('xmt_img', app()->view->getSections())){
            $xmt_img = app()->view->getSections()['xmt_img'];
        }
    @endphp
    @if(!empty($xmt_can))
    <link rel="canonical" href="{{config('app.url').$xmt_can}}">
    @endif
    <meta name="robots" content="@yield('xmt_rob','noindex,follow')">
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
    <meta name="msapplication-TileColor" content="#330066">
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

    <link href="{{URL::asset('/assets/fontawesome/css/fontawesome.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/assets/fontawesome/css/brands.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/assets/fontawesome/css/solid.css')}}" rel="stylesheet">
    @stack('headcss')
</head>
