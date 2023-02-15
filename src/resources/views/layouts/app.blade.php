<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="#">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ secure_asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/all.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">

    @if( app()->getLocale()  == "ar")
    <link rel="stylesheet" href="{{ secure_asset('css/ar.css') }}">
    @elseif( app()->getLocale() == "tr")
    <link rel="stylesheet" href="{{ secure_asset('css/tr.css') }}">
    @endif

    <title>Online Celebration</title>
    <link rel="icon" href="{{ secure_asset('images/logo.png') }}" type="image/x-icon">
    <!-- jquery included -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- confetti include -->
    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
    <!-- bootstrap files -->
    <script src="{{ secure_asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ secure_asset('js/all.min.js') }}"></script>
    <!-- custome functions -->
    <script src="{{ secure_asset('js/function.js') }}"></script>
    <!-- aos include -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- catdad confetti include -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-02VG04DTGB"></script> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-02VG04DTGB');
    </script>
</head>

<body>
    <!-- spinner and overlay codes -->
<div class="overlay d-flex justify-content-center align-items-center">
    <div class="spinner-border m-5 spinner-loader" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

    <!-- navbar codes -->
    <nav class="navbar">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('celebrations.create') }}">
                        <img src="{{ secure_asset('images/logo.png') }}" alt="logo" width="50" height="50" >
                        <div class="ps-3 brandName">
                            Online Celebration
                        </div>
                    </a>
                </div>
               @yield('navbar')
            </div>
        </div>
    </nav>

    @yield('main')

    <!-- footer codes -->
    <footer class="bg-light">
        <div class="container-fluid">
            <div class="row text-center p-4 footer-text">
                <div class="ps-3 brandName pb-3">
                    Online Celebration
                </div>
                <p class="text-muted">{{ __('celebration.all_rights_reserved') }} &copy;
                    <script>
                        var CurrentYear = new Date().getFullYear()
                        document.write(CurrentYear)
                    </script>
                </p>
                <div class="d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:contact@onlinecelebration.com" class="text-muted">
                        contact@online-celebration.com
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function(){
            $('.language').on('click',function(){
               var lang =  $(this).attr('id');
               lang = lang.trim();
                $.ajax({
                    url:"{{ route('changeLanguage') }}",
                    type:"Post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        'lang' : lang
                    },
                    success:function(data){
                        location.reload();
                    }
                });
            });

        });
    </script>

    @yield('scripts')

</body>
