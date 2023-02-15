@extends('layouts.app')
@section('main')
{{ app()->setlocale($celebrate->Lang) }}

<main class="m-4">
    <div class="container templateParent">
        <div class="row align-items-center justify-content-sm-around">
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
            <!-- copy to clipboard codes -->
            <div class="col-12 col-md-4 " data-aos="fade-up">
                <div class="row align-items-center justify-content-center flex-column mt-4 mt-md-0">
                    <div class="col">
                        <div class="field d-flex align-items-center justify-content-between">
                            <i class="fas fa-link text-center m-2"></i>
                            <input id="myInput" type="text" value="{{ $celebrate->PageLink }}">
                            <button id="myBtn">{{ __('celebration.copy_button') }}</button>
                        </div>
                    </div>
                    <div class="col mt-4">
                        <div class="alert d-flex align-items-center hintBox" role="alert">
                            <i class="fa-solid fa-lightbulb fs-5"></i>
                            {{ __('celebration.copy_and_send_this_link') }}
                        </div>
                        {{-- <div class="alert d-flex align-items-center hintBox" role="alert">
                            <i class="fa-solid fa-clock-rotate-left fs-6"></i>
                                this link will be invalid after 24 hours
                        </div> --}}
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
 <!-- adding confetti -->
 <script>
    const jsConfetti = new JSConfetti()
    jsConfetti.addConfetti()
</script>

<!-- copy to clipboard template preview page -->
<script>
    function copy() {
        var copyText = document.querySelector("#myInput");
        copyText.select();
        document.execCommand("copy");
    }
    document.querySelector("#myBtn").addEventListener("click", copy);
</script>

<!-- initialize AOS -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
</script>

@endsection
