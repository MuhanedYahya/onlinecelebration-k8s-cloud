@extends('layouts.app')
@section('main')
    {{ app()->setlocale($celebrate->Lang) }}

<div class="playParent">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="d-flex justify-content-center align-items-center">
                <button id="playBtn" class="rounded-circle d-flex flex-column justify-content-center align-items-center" >
                    <i class="fa-solid fa-gift"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- youtube iframe div-->
<div id="player"></div>

<main class="m-4">
    <div class="container templateParent">
        <div class="row align-items-center justify-content-center ">
            <!-- template codes -->
            <div class="col-12 col-md-5 position-relative imageContainer">
                <img src="{{ asset('images/' . $celebrate->template->name) }}" alt="template" class="card-img overflow-hidden">
                <div class="imageContent d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="celebrate-header">
                        <h2>{{ $celebrate->created_at->format('d/m/Y') }}</h2>
                        @php
                            if (App::getLocale() == 'en') {
                                $message = $celebrate->type->message_en;
                            }else if(App::getLocale() == 'ar'){
                                $message = $celebrate->type->message_ar;
                            }else if(App::getLocale() == 'tr') {
                                $message = $celebrate->type->message_tr;
                            }
                        @endphp
                        <h1>{{ $message }}</h1>
                        <p>{{ $celebrate->FirstName}} {{ $celebrate->LastName}}</p>
                    </div>
                    <div class="celebrate-message">
                        <p>
                            @if (app()->getLocale() == "ar")
                            <i class="fa-solid fa-quote-right"></i>
                            {{ $celebrate->Message }}
                            <i class="fa-solid fa-quote-left"></i>
                            @else
                            <i class="fa-solid fa-quote-left"></i>
                            {{ $celebrate->Message }}
                            <i class="fa-solid fa-quote-right"></i>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')

@if ($celebrate->youtubeCode != "")

<script>

    //This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // This function creates an <iframe> (and YouTube player)
    // after the API code downloads.
    var player;
    function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        width:0,
        height:0,
        videoId: '{{ $celebrate->youtubeCode }}', //buraya videonun embed kodu girilecek
        playerVars: {
            'playsinline': 1,
            'rel':0,
            'start':{{ $celebrate->startTime }},
            'end':{{ $celebrate->finishTime }}
          },
        events: {
        'onReady': onPlayerReady
        }
    });
    }
    function onPlayerReady(e) {
        $('#playBtn').click(function() {
            var timoutFunc1 = setTimeout(() => {
            e.target.playVideo();
            }, 1400);
        });
    }

</script>

@endif


<script>
    // play first and second confetti on button click
    $('#playBtn').click(function() {
        // hide gift parent
        var timoutFunc2 = setTimeout(() => {
            $('.playParent').animate({opacity:0});
            $('.playParent').remove();
            // first confetti func
            // var end = Date.now() + (15 * 1000);
            var end = Date.now() + (17 * 1000);
            var colors = ['#d05278', '#ff0a54'];
            // dont change function name
            function frame() {
                confetti({
                    particleCount: 2,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: colors
                });
                confetti({
                    particleCount: 2,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: colors
                });
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            };
            frame();
            // adding second confetti func if page is visible
            if (document.visibilityState == "visible") {
                var timoutFunc3 = setTimeout(() => {
                second_catdad();
                }, 14000);
            }
            
        }, 1500);
    });
</script>


<!-- adding second catdad confetti -->
<script>
    function second_catdad() {
        var interval= setInterval(function () {
            var count = 100;
            var defaults = {
            origin: { y: 0.7 }
            };
            function fire(particleRatio, opts) {
            confetti(Object.assign({}, defaults, opts, {
                particleCount: Math.floor(count * particleRatio)
            }));
            }
            fire(0.25, {
            spread: 26,
            startVelocity: 55,
            });
            fire(0.2, {
            spread: 60,
            });
            fire(0.35, {
            spread: 100,
            decay: 0.91,
            scalar: 0.8
            });
            fire(0.1, {
            spread: 120,
            startVelocity: 25,
            decay: 0.92,
            scalar: 1.2
            });
            fire(0.1, {
            spread: 120,
            startVelocity: 45,
            });
        }, 7000)

        document.addEventListener("visibilitychange", (event) => {
            if (document.visibilityState == "visible") {
                second_catdad();
            } else {
                clearInterval(interval);
            }
        });
    };
</script>


@endsection
