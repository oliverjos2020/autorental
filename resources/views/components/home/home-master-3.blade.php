<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard | D’PRESIDENTIAL LUXXETOUR</title>
    <meta content="D’PRESIDENTIAL LUXXETOUR" name="description">
    <meta content="D’PRESIDENTIAL LUXXETOUR" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <link rel="stylesheet" href="{{asset('assets-ii/css/master.css')}}">
    <!-- SWITCHER-->
    <link href="{{asset('assets-ii/plugins/switcher/css/switcher.css')}}" rel="stylesheet" id="switcher-css">
    <link href="{{asset('assets-ii/plugins/switcher/css/color1.css')}}" rel="alternate stylesheet" title="color1">
    <link href="{{asset('assets-ii/plugins/switcher/css/color2.css')}}" rel="alternate stylesheet" title="color2">
    <link href="{{asset('assets-ii/plugins/switcher/css/color3.css')}}" rel="alternate stylesheet" title="color3">
    <script src="{{asset('assets-ii/plugins/switcher/js/dmss.js')}}"></script>
    <link href="{{asset('css/toastr.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    @livewireStyles
    <style>
        .home-input {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 2px;
            color:#888;
        } 
        .review-input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 2px;
            color:#888;
        } 
        .b-goods__img img{
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .ui-slider-nav img{
            width: 100% !important;
            height: 100px;
            object-fit: cover;
        }
        .ui-slider-main img{
            width: 100% !important;
            height: 500px;
            object-fit: cover;
        }
        .pagination{
            margin-bottom:50px !important;
        }
    </style>


    <link rel="icon" type="image/x-icon" href="{{ asset('logo/icon-dark.png') }}">
    <!--[if lt IE 9 ]>
<script src="/assets/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script><meta content="no" http-equiv="imagetoolbar">
<![endif]-->
</head>

<body class="page">
    <!-- Loader-->
    <div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
    <!-- Loader end-->
    <div class="l-theme animated-css" data-header="sticky" data-header-top="200" data-canvas="container">

        <!-- Start Switcher-->
        <div class="switcher-wrapper">
            <div class="demo_changer">
                <div class="demo-icon text-primary"><i class="fa fa-cog fa-spin fa-2x"></i></div>
                <div class="form_holder">
                    <div class="predefined_styles">
                        <div class="skin-theme-switcher">
                            <h4>Color</h4><a class="styleswitch" href="javascript:void(0);" data-switchcolor="color1"
                                style="background-color:#e3740e;"></a><a class="styleswitch" href="javascript:void(0);"
                                data-switchcolor="color2" style="background-color:#9e8a2b;"></a><a class="styleswitch"
                                href="javascript:void(0);" data-switchcolor="color3"
                                style="background-color:#28af0f;"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end switcher-->


        <div class="header-search open-search">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 offset-sm-2 col-10 offset-1">
                        <div class="navbar-search">
                            <form class="search-global">
                                <input class="search-global__input" type="text" placeholder="Type to search"
                                    autocomplete="off" name="s" value="" />
                                <button class="search-global__btn"><i class="icon stroke icon-Search"></i></button>
                                <div class="search-global__note">Begin typing your search above and press return to
                                    search.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <button class="search-close close" type="button"><i class="fa fa-times"></i></button>
        </div>
        <!-- ==========================-->
        <!-- MOBILE MENU-->
        <!-- ==========================-->
        <div data-off-canvas="mobile-slidebar left overlay">
            <a class="navbar-brand scroll" href="/"><img class="scroll-logo" src="{{asset('logo/d-logo-light.png')}}"
                    height="40" alt="logo" /></a>

            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item ">
                    <a class="nav-link" href="#">About</a>
                    
                </li>
                <li class="nav-item ">

                    <a class="nav-link" href="/listing">Car Rentals</a>
                    
                </li>
                <li class="nav-item "><a class="nav-link" href="/register">Register</a>
                  
                </li>
                
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>

                <form method="POST" action="{{ route('logout') }}">
                    <li class="nav-item">
                        @if(Auth::check())
                        <li class="nav-item">
                            <a class="nav-link" href="/mybooking-orders">My Trips</a>
                        </li>
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm">Logout</button>
                        @else
                        <a class="nav-link" href="/login">Login</a>
                        @endif
                    </li>
                </form>
            </ul>
        </div>
        <header class="header">
            <div class="top-bar">
                <div class="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <div class="top-bar__item">
                                <a class="top-bar__link" href="mailto:support@dpresidentialluxxetour.com">
                                    <i class="ic fas fa-envelope text-primary"></i> support@dpresidentialluxxetour.com
                                </a>
                            </div>
                            <div class="top-bar__item"><i class="ic fas fa-map-marker-alt text-primary"></i> Fairview
                                Ave, El
                                Monte, CA 91732</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                        <div class="col-auto">
                            <div class="top-bar__item">
                                @if(Auth::user())
                                
                                    @csrf
                                    
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="ic fas fa-user text-primary"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                {{-- </form> --}}
                                <a href="/mybooking-orders" class="top-bar__btn" style="color:#fff; background:transparent;">
                                    <i class="ic icon-list"></i> My Trips
                                </a>
                                @else
                                <a class="top-bar__link" href="/login">
                                    <i class="ic fas fa-user text-primary"></i> Login
                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a class="top-bar__link" href="/register">
                                    <i class="ic fas fa-user text-primary"></i> Register
                                </a>
                                @endif
                            </div>
                            <a class="top-bar__btn" style="color:#fff; background:transparent;">
                                {{-- <i class="ic icon-list-x">Register</i> --}}
                            </a>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="header-main">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto"><a class="navbar-brand navbar-brand_light scroll" href="/"><img
                                    class="normal-logo" src="{{asset('logo/d-logo-dark.png')}}" height="40"
                                    alt="logo" /></a><a class="navbar-brand navbar-brand_dark scroll" href="/"><img
                                    class="normal-logo" src="{{asset('logo/d-logo-dark.png')}}" height="40"
                                    alt="logo" /></a></div>
                        <div class="col-auto">
                            <div class="header-contacts d-none d-md-block d-lg-none d-xl-block"><i
                                    class="ic text-primary fas fa-phone"></i><span class="header-contacts__inner"><span
                                        class="header-contacts__info">Call us for help</span><a
                                        class="header-contacts__number" href="%2b2584037961.html">(258) 403
                                        7961</a></span></div>
                            <!-- Mobile Trigger Start-->
                            <button class="menu-mobile-button js-toggle-mobile-slidebar toggle-menu-button d-lg-none"><i
                                    class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
                            <!-- Mobile Trigger End-->
                        </div>
                        <div class="col-lg d-none d-lg-block">
                            <nav class="navbar navbar-expand-md justify-content-end" id="nav">
                                <ul class="yamm main-menu navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="/">Home</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#">About</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="/listing">Car Rentals</a>
                                    </li>
                                    
                                    @if(!Auth::check())
                                    {{-- <li class="nav-item ">
                                        <a class="nav-link" href="/login">Login</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link" href="/register">Register</a>
                                    </li> --}}
                                    @endif
                                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                                </ul>
                                {{-- <span class="header-main__link btn_header_search"><i
                                        class="ic fas fa-search"></i></span> --}}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="section-title-page area-bg area-bg_dark area-bg_op_60">
            <div class="area-bg__inner">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md">
                            <h1 class="b-title-page">Vehicle Listing</h1>
                            <div class="ui-decor bg-primary"></div>
                        </div>
                        {{-- <div class="col-md-auto"><a class="b-title-page__btn bg-primary" href="#">Smarter Way to
                                Buy or
                                Sell
                                Cars</a></div> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- end .b-title-page-->

        <div class="xyz">
                {{ $slot }}
            </div>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-5">
                        <div class="footer-section footer-section_info">
                            <div class="footer__title"><img src="{{asset('logo/d-logo-light.png')}}" height="40"></div>
                            {{-- <div class="footer__slogan">autos dealers</div> --}}
                            <div class="footer-info">Eipisicing elit sed do eiusmod tempor laboe dolore magna aliqa Ut
                                enim ad
                                minim veniam quis nostrud exercitation ullam.</div>
                            <div class="footer-contacts">
                                <div class="footer-contacts__item"><i class="ic fas fa-map-marker-alt text-primary"></i>Fairview
                                    Ave, El Monte, CA 91732</div>
                                <div class="footer-contacts__item"><i class="ic fas fa-envelope text-primary"></i><a
                                        href="mailto:support@domain.com">support@domain.com</a></div>
                                <div class="footer-contacts__item"><i class="ic far fa-clock text-primary"></i>Mon to
                                    Fri :
                                    9:00am to 6:00pm</div><a class="footer-contacts__phone" href="tel:2584037961">(258)
                                    403
                                    7961</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-7 col-sm-3">
                        <div class="row">
                            <div class="col-md-6">
                                <section class="footer-section footer-section_link">
                                    <h3 class="footer-section__title">About Isnaider</h3><i class="ui-decor bg-primary"></i>
                                    <ul class="footer-list list-unstyled">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Services</a></li>
                                        <li><a href="#">About us</a></li>
                                        <li><a href="#">Inventory</a></li>
                                        <li><a href="#">Parts Shop</a></li>
                                        <li><a href="#">Contact</a></li>
                                        <li><a href="#">Sitemap</a></li>
                                    </ul>
                                </section>
                            </div>
                            <div class="col-md-6">
                                <section class="footer-section footer-section_link">
                                    <h3 class="footer-section__title">Customer Links</h3><i class="ui-decor bg-primary"></i>
                                    <ul class="footer-list list-unstyled">
                                        <li><a href="#">Latest Cars</a></li>
                                        <li><a href="#">Featured Cars</a></li>
                                        <li><a href="#">Sell Your Car</a></li>
                                        <li><a href="#">Buy a Car</a></li>
                                        <li><a href="#">Reviews</a></li>
                                        <li><a href="#">Latest News</a></li>
                                        <li><a href="#">Car Inspection</a></li>
                                    </ul>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <section class="footer-section footer-section_subscribe">
                            <h3 class="footer-section__title">Subscribe Newsletter</h3><i class="ui-decor bg-primary"></i>
                            <form class="footer-form">
                                <div class="footer-form__info">Get our weekly nwsletter for latest car news exclusive
                                    offers and
                                    deals and more.</div>
                                <div class="form-group">
                                    <input class="footer-form__input form-control" type="email" placeholder="your email">
                                </div>
                                <button class="btn btn-sm btn-primary">Subscribe</button>
                            </form>
                        </section>
                    </div>
                </div>
                <div class="text-center">
                    <ul class="footer-soc list-unstyled">
                        <li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i
                                    class="ic fab fa-twitter"></i></a></li>
                        <li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i
                                    class="ic fab fa-facebook"></i></a></li>
                        <li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i
                                    class="ic fab fa-linkedin"></i></a></li>
                        <li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i
                                    class="ic fab fa-google-plus-g"></i></a></li>
                        <li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i
                                    class="ic fab fa-pinterest"></i></a></li>
                        <li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i
                                    class="ic fas fa-play"></i></a></li>
                    </ul>
                </div>
                <div class="footer-copyright">
                    Copyrights (c) {{ date('Y')}} D’PRESIDENTIAL LUXXETOUR. All rights reserved.
                    <a class="footer-copyright__link" href="privacy-policy.html">Privacy Policy</a>
                </div>
            </div>
        </footer>
        <!-- .footer-->
    </div>
    <!-- end layout-theme-->


    <!-- ++++++++++++-->
    <!-- MAIN SCRIPTS-->
    <!-- ++++++++++++-->
    <script src="{{asset('assets-ii/js/jquery-3.3.1.min.js')}}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{asset('assets-ii/js/jquery-migrate-1.4.1.min.js')}}"></script>
    <!-- Bootstrap-->
    <script src="{{asset('assets-ii/js/popper.min.js')}}"></script>
    <script src="{{asset('assets-ii/js/bootstrap.min.js')}}"></script>
    <!---->
    <!-- Color scheme-->
    <script src="{{asset('assets-ii/plugins/switcher/js/dmss.js')}}"></script>
    <!-- Select customization & Color scheme-->
    <script src="{{asset('assets-ii/js/bootstrap-select.min.js')}}"></script>
    <!-- Pop-up window-->
    <script src="{{asset('assets-ii/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <!-- Headers scripts-->
    <script src="{{asset('assets-ii/plugins/headers/slidebar.js')}}"></script>
    <script src="{{asset('assets-ii/plugins/headers/header.js')}}"></script>
    <!-- Mail scripts-->
    <script src="{{asset('assets-ii/plugins/jqBootstrapValidation.js')}}"></script>
    {{-- <script src="{{asset('assets-ii/plugins/contact_me.js')}}"></script> --}}
    <!-- Filter and sorting images-->
    <script src="{{asset('assets-ii/plugins/isotope/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('assets-ii/plugins/isotope/imagesLoaded.js')}}"></script>
    <!-- Progress numbers-->
    <script src="{{asset('assets-ii/plugins/rendro-easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
    <script src="{{asset('assets-ii/plugins/rendro-easy-pie-chart/jquery.waypoints.min.js')}}"></script>
    <!-- Animations-->
    <script src="{{asset('assets-ii/plugins/scrollreveal/scrollreveal.min.js')}}"></script>
    <!-- Scale images-->
    <script src="{{asset('assets-ii/plugins/ofi.min.js')}}"></script>
    <!-- Main slider-->
    <script src="{{asset('assets-ii/plugins/slider-pro/jquery.sliderPro.min.js')}}"></script>
    <!-- Sliders-->
    <script src="{{asset('assets-ii/plugins/slick/slick.js')}}"></script>
    <!-- Slider number-->
    <script src="{{asset('assets-ii/plugins/noUiSlider/wNumb.js')}}"></script>
    <script src="{{asset('assets-ii/plugins/noUiSlider/nouislider.min.js')}}"></script>
    <!-- User customization-->
    <script src="{{asset('assets-ii/js/custom.js')}}"></script>
    <!-- Main slider-->
    <script src="{{asset('assets-ii/plugins/slider-pro/jquery.sliderPro.min.js')}}"></script>
    <!-- Sliders-->
    <script src="{{asset('assets-ii/plugins/slick/slick.js')}}"></script>
    <!-- Slider number-->
    <script src="{{asset('assets-ii/plugins/noUiSlider/wNumb.js')}}"></script>
    <script src="{{asset('assets-ii/plugins/noUiSlider/nouislider.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    

    <script>
     document.addEventListener('livewire:load', function () {
         window.addEventListener('notify', event => {
             toastr[event.detail.type](event.detail.message);
         });
     });
     
 </script>
    @livewireScripts
</body>

</html>