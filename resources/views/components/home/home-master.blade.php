<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from duruthemes.com/demo/html/renax/light/index4.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Jun 2024 18:47:07 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>RENAX</title>
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&amp;display=swap">
    <link rel="stylesheet" href="{{asset('css/plugins.css')}}" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}" />
    @livewireStyles
    <style>
        .myinput{
            border-radius:30px;
            width: 100%;
            background: #fff;
            margin-bottom: 15px;
            border: 1px solid transparent;
            padding: 1rem;
            color: #a0a0a0;
        }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader-bg"></div>
    <div id="preloader">
        <div id="preloader-status">
            <div class="preloader-position loader"> <span></span> </div>
        </div>
    </div>
    <!-- Progress scroll totop -->
    <div class="progress-wrap cursor-pointer">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <div class="logo-wrapper">
                <a class="logo" href="/"> <img src="logo/logo-light.png" class="logo-img" alt=""> </a>
                <!-- <a class="logo" href="index.html"><h2>Renta<span>x</span></h2></a> -->
            </div>
            <!-- Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"> <span
                    class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span> </button>
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown"> 
                        <a class="nav-link active" href="/">Home
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/listing">Listing</a></li>
                    {{-- <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Services <i
                                class="ti-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="services.html" class="dropdown-item"><span>Services 01</span></a></li>
                            <li><a href="services2.html" class="dropdown-item"><span>Services 02</span></a></li>
                            <li><a href="service-details.html" class="dropdown-item"><span>Service Details</span></a>
                            </li>
                        </ul>
                    </li> --}}
                    {{-- <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Cars <i
                                class="ti-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="cars.html" class="dropdown-item"><span>Car Grid 01</span></a></li>
                            <li><a href="cars2.html" class="dropdown-item"><span>Car Grid 02</span></a></li>
                            <li><a href="cars3.html" class="dropdown-item"><span>Car Grid 03</span></a></li>
                            <li><a href="cars4.html" class="dropdown-item"><span>Car Listing</span></a></li>
                            <li><a href="car-types.html" class="dropdown-item"><span>Car Types 01</span></a></li>
                            <li><a href="car-types2.html" class="dropdown-item"><span>Car Types 02</span></a></li>
                            <li><a href="car-types3.html" class="dropdown-item"><span>Car Types 03</span></a></li>
                            <li><a href="car-types4.html" class="dropdown-item"><span>Car Types 04</span></a></li>
                            <li><a href="car-details.html" class="dropdown-item"><span>Car Details 01</span></a></li>
                            <li><a href="car-details2.html" class="dropdown-item"><span>Car Details 02</span></a></li>
                        </ul>
                    </li> --}}
                    {{-- <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Pages <i
                                class="ti-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="team.html" class="dropdown-item"><span>Team</span></a></li>
                            <li><a href="gallery-image.html" class="dropdown-item"><span>Image Gallery</span></a></li>
                            <li><a href="gallery-video.html" class="dropdown-item"><span>Video Gallery</span></a></li>
                            <li><a href="price.html" class="dropdown-item"><span>Pricing Plan</span></a></li>
                            <li><a href="faq.html" class="dropdown-item"><span>FAQ</span></a></li>
                            <li><a href="testiominals.html" class="dropdown-item"><span>Testiominals</span></a></li>
                            <li><a href="team-single.html" class="dropdown-item"><span>Team Single</span></a></li>
                            <li><a href="404.html" class="dropdown-item"><span>404 Page</span></a></li>
                        </ul>
                    </li> --}}
                    {{-- <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Register <i
                                class="ti-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="blog.html" class="dropdown-item"><span>Register for car rental</span></a></li>
                            <li><a href="blog2.html" class="dropdown-item"><span>Register</span></a></li>
                            <li><a href="blog3.html" class="dropdown-item"><span>Blog List</span></a></li>
                            <li><a href="post.html" class="dropdown-item"><span>Post Single</span></a></li>
                        </ul>
                    </li> --}}
                    <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="/login">login</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                </ul>
                <div class="navbar-right">
                    <div class="wrap">
                        <div class="icon"> <i class="flaticon-phone-call"></i> </div>
                        <div class="text">
                            <p>Need help?</p>
                            <h5><a href="tel:8551004444">855 100 4444</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    {{ $slot }}
    <!-- Kenburns SlideShow -->
 
    
    <!-- Footer -->
    <footer class="footer clearfix">
        <div class="container">
            <!-- first footer -->
            <div class="first-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="links dark footer-contact-links">
                            <div class="footer-contact-links-wrapper">
                                <div class="footer-contact-link-wrapper">
                                    <div class="image-wrapper footer-contact-link-icon">
                                        <div class="icon-footer"> <i class="flaticon-phone-call"></i> </div>
                                    </div>
                                    <div class="footer-contact-link-content">
                                        <h6>Call us</h6>
                                        <p>+971 52-333-4444</p>
                                    </div>
                                </div>
                                <div class="footer-contact-links-divider"></div>
                                <div class="footer-contact-link-wrapper">
                                    <div class="image-wrapper footer-contact-link-icon">
                                        <div class="icon-footer"> <i class="omfi-envelope"></i> </div>
                                    </div>
                                    <div class="footer-contact-link-content">
                                        <h6>Write to us</h6>
                                        <p>info@renax.com</p>
                                    </div>
                                </div>
                                <div class="footer-contact-links-divider"></div>
                                <div class="footer-contact-link-wrapper">
                                    <div class="image-wrapper footer-contact-link-icon">
                                        <div class="icon-footer"> <i class="omfi-location"></i> </div>
                                    </div>
                                    <div class="footer-contact-link-content">
                                        <h6>Address</h6>
                                        <p>Dubai, Water Tower, Office 123</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- second footer -->
            <div class="second-footer">
                <div class="row">
                    <!-- about & social icons -->
                    <div class="col-md-4 widget-area">
                        <div class="widget clearfix">
                            <div class="footer-logo"><img src="logo/logo-light.png" alt=""></div>
                            <!-- <div class="footer-logo"><h2>CARE<span>X</span></h2></div> -->
                            <div class="widget-text">
                                <p>Rent a car imperdiet sapien porttito the bibenum ellentesue the commodo erat nesuen.
                                </p>
                                <div class="social-icons">
                                    <ul class="list-inline">
                                        <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- quick links -->
                    <div class="col-md-3 offset-md-1 widget-area">
                        <div class="widget clearfix usful-links">
                            <h3 class="widget-title">Quick Links</h3>
                            <ul>
                                <li><a href="about.html">About</a></li>
                                <li><a href="cars.html">Cars</a></li>
                                <li><a href="car-types.html">Car Types</a></li>
                                <li><a href="team.html">Team</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- subscribe -->
                    <div class="col-md-4 widget-area">
                        <div class="widget clearfix">
                            <h3 class="widget-title">Subscribe</h3>
                            <p>Want to be notified about our services. Just sign up and we'll send you a notification by
                                email.</p>
                            <div class="widget-newsletter">
                                <form action="#">
                                    <input type="email" placeholder="Email Address" required>
                                    <button type="submit"><i class="ti-arrow-top-right"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- bottom footer -->
            <div class="bottom-footer-text">
                <div class="row copyright">
                    <div class="col-md-12">
                        <p class="mb-0">&copy;2024 <a href="#">DuruThemes</a>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- RentNow Popup -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Booking Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-box">
                        <div class="booking-inner clearfix">
                            <form method="post" action="#0" class="form1 contact__form clearfix">
                                <!-- form message -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-success contact__msg" style="display: none"
                                            role="alert"> Your message was sent successfully. </div>
                                    </div>
                                </div>
                                <!-- form elements -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <input name="name" type="text" placeholder="Full Name *" required>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <input name="email" type="email" placeholder="Email *" required>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <input name="phone" type="text" placeholder="Phone *" required>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="select1_wrapper">
                                            <label>Choose Car Type</label>
                                            <div class="select1_inner">
                                                <select class="select2 select" style="width: 100%">
                                                    <option value="0">Choose Car Type</option>
                                                    <option value="1">All</option>
                                                    <option value="2">Luxury Cars</option>
                                                    <option value="3">Sport Cars</option>
                                                    <option value="4">SUVs</option>
                                                    <option value="5">Convertible</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="select1_wrapper">
                                            <label>Pick Up Location</label>
                                            <div class="select1_inner">
                                                <select class="select2 select" style="width: 100%">
                                                    <option value="0">Pick Up Location</option>
                                                    <option value="1">Dubai</option>
                                                    <option value="2">Abu Dhabi</option>
                                                    <option value="3">Sharjah</option>
                                                    <option value="4">Alain</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="input1_wrapper">
                                            <label>Pick Up Date</label>
                                            <div class="input1_inner">
                                                <input type="text" class="form-control input datepicker"
                                                    placeholder="Pick Up Date" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="select1_wrapper">
                                            <label>Drop Off Location</label>
                                            <div class="select1_inner">
                                                <select class="select2 select" style="width: 100%">
                                                    <option value="0">Drop Off Location</option>
                                                    <option value="1">Alain</option>
                                                    <option value="2">Sharjah</option>
                                                    <option value="3">Abu Dhabi</option>
                                                    <option value="4">Dubai</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="input1_wrapper">
                                            <label>Return Date</label>
                                            <div class="input1_inner">
                                                <input type="text" class="form-control input datepicker"
                                                    placeholder="Return Date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 form-group">
                                        <textarea name="message" id="message" cols="30" rows="4"
                                            placeholder="Additional Note"></textarea>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <button type="submit" class="booking-button mt-15">Rent Now</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    
    <script src="{{asset('js-home/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('js-home/jquery-migrate-3.4.1.min.js')}}"></script>
    <script src="{{asset('js-home/modernizr-2.6.2.min.js')}}"></script>
    <script src="{{asset('js-home/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('js-home/jquery.isotope.v3.0.2.js')}}"></script>
    <script src="{{asset('js-home/popper.min.js')}}"></script>
    <script src="{{asset('js-home/bootstrap.min.js')}}"></script>
    <script src="{{asset('js-home/scrollIt.min.js')}}"></script>
    <script src="{{asset('js-home/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('js-home/owl.carousel.min.js')}}"></script>
    <script src="{{asset('js-home/jquery.stellar.min.js')}}"></script>
    <script src="{{asset('js-home/jquery.magnific-popup.js')}}"></script>
    <script src="{{asset('js-home/select2.js')}}"></script>
    <script src="{{asset('js-home/datepicker.js')}}"></script>
    <script src="{{asset('js-home/YouTubePopUp.js')}}"></script>
    <script src="{{asset('js-home/vegas.slider.min.js')}}"></script>
    <script src="{{asset('js-home/custom.js')}}"></script>
    
    <!-- Vegas Background Slideshow (vegas.slider kenburns) -->
    <script>
        $(document).ready(function() {
            $('#kenburnsSliderContainer').vegas({
                slides: [{
                    src: "{{asset('img/slider/1.jpg')}}"
                }, {
                    src: "{{asset('img/slider/3.jpg')}}"
                }, {
                    src: "{{asset('img/slider/11.jpg')}}"
                }],
                overlay: true,
                transition: 'fade2',
                animation: 'kenburnsUpRight',
                transitionDuration: 1000,
                delay: 10000,
                animationDuration: 20000
            });
        });
    </script>
    @livewireScripts
</body>

<!-- Mirrored from duruthemes.com/demo/html/renax/light/index4.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Jun 2024 18:47:08 GMT -->

</html>