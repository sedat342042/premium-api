<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- SEO Meta Tags -->
        <meta name="description" content="Laravel 9, AdminLTE3 Starter Kit">
        <meta name="author" content="API APP">

        <!-- OG Meta Tags to improve the way the post looks when you share the page on Facebook, Twitter, LinkedIn -->
        <meta property="og:site_name" content="API APP" /> <!-- website name -->
        <meta property="og:site" content="" /> <!-- website link -->        
        <!-- Webpage Title -->
        <title>API App</title>
        
        <!-- Styles -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
        <link href="{{asset('./web/css/bootstrap.css')}}" rel="stylesheet">
        <link href="{{asset('./web/css/fontawesome-all.css')}}" rel="stylesheet">
        <link href="{{asset('./web/css/swiper.css')}}" rel="stylesheet">
        <link href="{{asset('./web/css/magnific-popup.css')}}" rel="stylesheet">
        <link href="{{asset('./web/css/styles.css')}}" rel="stylesheet">
        
        <!-- Favicon  -->
        <link rel="icon" href="{{asset('/images/favicon.png')}}">
    </head>
    <body data-spy="scroll" data-target=".fixed-top">
    
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg fixed-top navbar-light">
            <div class="container">
                
                <!-- Text Logo - Use this if you don't have a graphic logo -->
                <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Blink</a> -->

                <!-- Image Logo -->
                <a class="navbar-brand logo-image" href="/" style="text-decoration:none;">
                   <h2 class="h2-heading">API APP</h2>
                </a>

                <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                    <ul class="navbar-nav ml-auto">

                        @auth
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="{{ url('/dashboard') }}">Dashboard <span class="sr-only">(current)</span></a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="{{ route('login') }}">Login</a>
                        </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link page-scroll" href="{{ route('register') }}">Register</a>
                            </li>
                            @endif
                        @endauth
                        </ul>
                </div> <!-- end of navbar-collapse -->
            </div> <!-- end of container -->
        </nav> <!-- end of navbar -->
        <!-- end of navigation -->


        <!-- Header -->
        <header id="header" class="header">
            <img class="decoration-line-blue" src="{{asset('./web/images/decoration-line-blue.svg')}}" alt="alternative">
            <img class="decoration-line-green" src="{{asset('./web/images/decoration-line-green.svg')}}" alt="alternative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-container">
                            <h1 class="h1-large">Laravel 9, AdminLTE3 Starter Kit</h1>
                            <p class="p-large p-heading">With Complete User Permission & Roles Management</p>
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="image-container">
                            <img class="img-fluid" src="{{asset('./web/images/header-tablet.png')}}" alt="alternative">
                        </div> <!-- end of image-container -->
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </header> <!-- end of header -->
        <!-- end of header -->       
        <!-- Scripts -->
        <script src="{{asset('./vendor/adminlte/jquery/js/jquery.min.js')}}"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
        <script src="{{asset('./vendor/adminlte/bootstrap/bootstrap.min.js')}}"></script> <!-- Bootstrap framework -->
        <script src="{{asset('./web/js/jquery.easing.min.js')}}"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
        <script src="{{asset('./web/js/jquery.magnific-popup.js')}}"></script> <!-- Magnific Popup for lightboxes -->
        <script src="{{asset('./web/js/swiper.min.js')}}"></script> <!-- Swiper for image and text sliders -->
        <script src="{{asset('./web/js/scripts.js')}}"></script> <!-- Custom scripts -->
    </body>
</html>