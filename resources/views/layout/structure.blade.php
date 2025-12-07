<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body>
    @include('layout.menu')
        @yield('content')
    @include('layout.footer')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/slick.js"></script>
    <script type="text/javascript" src="/js/slick.min.js"></script>
    <script type="text/javascript" src="/js/rkelectrical-js-v1.js"></script>
    <script src="/js/side.js"></script>
    @stack('endjs')
</body>
</html>