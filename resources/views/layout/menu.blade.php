<style>
    @media only screen and (min-width: 900px) {
        .social-links{
            background-image:linear-gradient(to bottom, #64005f, #ff00f2);
            height:100vh;
        }
        .social-links a {
            border:none !important;
            background-color:transparent !important;
            box-shadow:none !important;
        }
    }
</style>


<header id="ytmenu">
    <div class="row">
        <div class="col-12 col-lg-10 order-lg-2 d-flex flex-column">
            <div class="profile mb-3">
                <a href="/"><img src="/images/rkelectricalgrid-logo-new.png" alt="" class="img-fluid rounded-circle"></a>
                <h1><a href="/">RKELECTRICALGRID</a></h1>
            </div>
            <a href="/user/profile" class="btn btn-outline-primary loginbtn"><span><i class="fa fa-user" aria-hidden="true"></i></span> &nbsp;&nbsp;<span>
                @if(auth()->check())
                    Profile
                @else
                    Login
                @endif
            </span></a>
            <hr>
            <nav id="navbar" class="nav-menu navbar">
                <ul>
                    <li><a href="/" class="nav-link scrollto"><span><i class="fa fa-home" aria-hidden="true"></i></span> <span>Home</span></a></li>
                    <!-- <li><a href="/course/" class="nav-link scrollto"><span><i class="fa fa-graduation-cap" aria-hidden="true"></i></span> <span>Course</span></a></li> -->
                    <li><a href="/practice/" class="nav-link scrollto"><span><i class="fa fa-book" aria-hidden="true"></i></span> <span>Practice</span></a></li>
                    <!-- <li><a href="/test-series/" class="nav-link scrollto"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <span>Test Series</span></a></li> -->
                    <li><a href="/about/" class="nav-link scrollto"><span><i class="fa fa-user"></i></span> <span>About</span></a></li>
                    <li><a href="/contact/" class="nav-link scrollto"><span><i class="fa fa-envelope"></i></span> <span>Contact</span></a></li>
                    <!-- <li><a href="/blog/" class="nav-link scrollto"><span><i class="fa fa-newspaper-o"></i></span> <span>Blog</span></a></li> -->
                </ul>
            </nav>
        </div>
        <div class="col-12 col-lg-2 order-lg-1 d-flex flex-lg-column gap-lg-3 justify-content-center align-items-center social-links  text-center bg-lg-primary">
          <a href="https://www.youtube.com/c/RASUKKUTTIV/" target="_blank" class="border-lg-0 bg-lg-transparent"><i class="fa fa-youtube text-white bg-transparent"></i></a>
          <a href="https://wa.me/91735892951" target="_blank" class="border-lg-0 bg-lg-transparent"><i class="fa fa-whatsapp"></i></a>
          <a href="https://t.me/rkelectricalgrid" target="_blank" class="border-lg-0 bg-lg-transparent"><i class="fa fa-telegram"></i></a>
          <a href="https://www.facebook.com/rkelectricalgrid/" target="_blank" class="border-lg-0 bg-lg-transparent"><i class="fa fa-facebook"></i></a>
          <a href="https://www.instagram.com/rkelectricalgrid/?hl=en" target="_blank" class="border-lg-0 bg-lg-transparent"><i class="fa fa-instagram"></i></a>
        </div>
    </div>
</header>
<main id="main">
<nav class="navbar navbar-expand-lg navbar-light bg-light d-xl-none">
    <div class="container" style="position:relative">
        <a class="navbar-brand" href="/" style="color:#8f00a7; font-weight:bold"><img src="/images/rkelectricalgrid-logo-new.png" style="height:60px; padding:2px; margin-right:10px; margin-left:10px"> RKELECTRICALGRID</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        </div>
        <span id="ytsm" class="ytsn d-xl-none"><i class="fa fa-bars"></i></span>
    </div>
</nav>
