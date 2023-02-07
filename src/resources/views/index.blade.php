@extends('layouts.app')
@section('navbar')
<div class="col text-end">
        <div class="btn-group">
            <button class="btn dropdown-toggle selectedLang" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Config::get('languages')[App::getLocale()]['src'] }}" alt="flag" class="selectedLangImg">
                {{ Config::get('languages')[App::getLocale()]['display'] }}
            </button>
            <ul class="dropdown-menu">
                @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                <li>
                    <a class="dropdown-item language" id="{{ $lang }}">
                        <img src="{{ $language['src'] }}" alt="flag" class="langImg" >
                        {{$language['display']}}
                    </a>
                </li>
                    @endif
                @endforeach
            </ul>
        </div>
</div>
@endsection
@section('main')

<!-- premium features -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header justify-content-between">
                <div class="d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-star"></i>
                    <h5 class="modal-title m-1">{{ __('celebration.special_features') }}</h5>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
          </div>
          <div class="modal-body">
              <p>{{ __('celebration.new_features') }}</p>
          </div>
      </div>
  </div>
</div>

<main class="pt-2" id="indexMain" data-aos="zoom-in-down">
    <div class="container">
        <div class="row d-flex justify-content-center mt-4">
            <div class="col-12 col-lg-5">
                {{-- Error div --}}
                <div class="alert alert-warning alert-dismissible fade show" role="alert" id="Oops">
                    <strong>{{ __('celebration.oops') }}</strong> {{ __('celebration.check_the_information') }}
                    <button type="button" class="btn-close" aria-label="Close" id="closeAlert"></button>
                </div>
                {{-- Error div end --}}

                <div class="card">
                    <div class="card-header  text-center">
                        {{-- Create a celebration --}}
                       {{ __('celebration.title') }}
                    </div>
                    <div class="card-body">
                        <div class="form-floating">
                            <select class="form-select mt-2 mb-3 ps-2 celebration-types" id="floatingSelect" aria-label="Floating label select example">
                                @foreach ($types as $type )
                                <option value="{{ $type -> id }}" {{ $type->id }} == 3 ? selected>{{ $type -> name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect">{{ __('celebration.celebration_type') }}</label>
                        </div>
                        <!-- name and message input codes -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('celebration.recipients_name') }}</span>
                            <input type="text" aria-label="First name" maxlength="14" class="form-control FirstName" placeholder="{{ __('celebration.first_name') }}"
                                required>
                            <input type="text" aria-label="Last name" maxlength="14" class="form-control LastName" placeholder="{{ __('celebration.last_name') }}"
                                required>
                        </div>
                        <div class="input-group mb-3">
                            <textarea class="form-control Message" maxlength="130" aria-label="With textarea"
                            placeholder="{{ __('celebration.type_your_messsage') }}"></textarea>
                        </div>
                        <!-- example templates -->
                        <h6 class="mb-2">{{ __('celebration.choose_your_template') }}</h6>
                        <div class="row templates-row mb-3">
                            @foreach ($templates as $template )
                            <div class="col-4 col-lg-3 mt-2 mb-4">
                                <div class="wrapper d-flex justify-content-center align-items-center">
                                    <i class="fa-solid fa-circle-check" id="checked"></i>
                                    <img src="{{ asset('images/' . $template->name) }}" class="card-img rounded" alt="image" id="{{ $template->id }}">
                                    <div class="Views d-flex justify-content-center align-items-center">
                                        <p>
                                            <i class="fa-solid fa-eye"></i>
                                            <span>{{ $template->usedTimes }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-4 col-lg-3 mt-2 mb-4 " data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">
                                <div class="moreTempaletDiv d-flex justify-content-center align-items-center">
                                    <i class="fa-solid fa-plus moreTempaletButton"></i>
                                </div>
                            </div>
                        </div>
                        <!-- add youtube link -->
                        <div class="accordion mb-3" id="linkAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <!-- <i class="fa-solid fa-link me-2"></i> Add youtube Link -->
                                    <i class="fa-brands fa-youtube"></i>{{ __('celebration.add_youtube_link') }}
                                </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#linkAccordion">
                                    <div class="accordion-body">
                                        <h6>
                                            {{ __('celebration.by_adding_video_link_here') }}
                                        </h6>
                                        <div class="add_video_section">
                                            <input class="form-control mb-2 youtubeLink" type="text" placeholder="https://youtu.be/UQGmsezfMSs" aria-label=".form-control-sm example" maxlength="28">
                                            <!-- video timing section codes -->
                                            <div class="row mb-3" id="video_timing_section">
                                                <div class="col">
                                                    <label for="StartsAt" class="form-label" >{{ __('celebration.starts_at') }}</label>
                                                    <div class="input-group" id="StartsAt">
                                                        <input type="tel" class="form-control startMin" placeholder="{{ __('celebration.min') }}" aria-label="min" id="start_min" maxlength="2">
                                                        <span class="input-group-text">:</span>
                                                        <input type="tel" class="form-control startSec" placeholder="{{ __('celebration.sec') }}" aria-label="sec" id="start_sec" maxlength="2">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="EndsAt" class="form-label">{{ __('celebration.ends_at') }}</label>
                                                    <div class="input-group" id="EndsAt">
                                                        <input type="text" class="form-control finishMin" placeholder="{{ __('celebration.min') }}" aria-label="min" id="end_min" maxlength="2">
                                                        <span class="input-group-text">:</span>
                                                        <input type="text" class="form-control finishSec" placeholder="{{ __('celebration.sec') }}" aria-label="sec" id="end_sec" maxlength="2">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- video timing section codes end-->
                                            <div class="alert alert-warning" role="alert">
                                                {{ __('celebration.you_can_copy_link') }}
                                            </div>
                                        </div>

                                        <div class="form-check unuse_link">
                                            <input class="form-check-input" type="checkbox" id="checkbox_id" name="checkbox_name">
                                            <label class="form-check-label" for="checkbox_id">
                                                {{ __('celebration.i_dont_want_to_add_music') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- add youtube link codes end -->
                    </div>

                    <!-- create button -->
                    <div class="card-footer text-muted  text-center">
                        <a class="btn createBtn">{{ __('celebration.create_button') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('scripts')

 <!-- initialize AOS -->
 <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
 <script>
     AOS.init();
 </script>

<script>
    $(document).ready(function(){

        // close alert box
        $('#closeAlert').click(function (e) {
            $('#Oops').slideUp(200);
        });

       // close add video section
       $('#checkbox_id').click(function (e) {
            if($('input[name="checkbox_name"]').is(':checked'))
            {
                $('.add_video_section').slideUp(300);
            }else
            {
                $('.add_video_section').slideDown(300);
            }
        });


        $('.startMin , .startSec , .finishMin , .finishSec').on('keyup',function(){

            if(!$.isNumeric($(this).val())){
                $(this).val( $(this).val().slice(0,-1));
            }else{
                $(this).val($(this).val() % 60);
            }
        });

        $('.youtubeLink').on('change',function(){

            $('#Oops').hide();

            if(!$(this).val().startsWith("https://youtu.be/")){
                $('#Oops').slideDown(500);
                      window.scrollTo(0, 0);

            }

        });

        $('.createBtn').on('click',function(){

            var link = "";
            var startMin = "";
            var startSec = "";
            var finishMin = "";
            var finishSec = "";
            // var startMin = 0;
            // var startSec = 0;
            // var finishMin = 0;
            // var finishSec = 0;

            if(!$('input[name="checkbox_name"]').is(':checked')){
                var link = $('.youtubeLink').val() == "" ? "" : $('.youtubeLink').val();
                var startMin = $('.startMin').val() == "" ? 0 : $('.startMin').val();
                var startSec = $('.startSec').val() == "" ? 0 : $('.startSec').val();
                var finishMin = $('.finishMin').val() == "" ? 0 : $('.finishMin').val();
                var finishSec = $('.finishSec').val() == "" ? 0 : $('.finishSec').val();

            }

            var type = $('.celebration-types').val();
            var FirstName = $('.FirstName').val();
            var LastName = $('.LastName').val();
            var Message = $('.Message').val();

            startMin = startMin == "" ? parseInt(0) : startMin;
            startSec = startSec == "" ? parseInt(0) : startSec;
            finishMin = finishMin == "" ? parseInt(0) : finishMin;
            finishSec = finishSec == "" ? parseInt(0) : finishSec;

            var youtubeCode = link.replace('https://youtu.be/','').trim();
            var startTime = parseInt(startMin*60) + parseInt(startSec);
            var finishTime = parseInt(finishMin*60) + parseInt(finishSec);

            // get selected template
            var selected_Svg =$(".templates-row").find("svg:visible");
            var selected_image= selected_Svg.parent().find("img");
            var template=selected_image.attr('id');
            var mainUrl= window.location.origin;

            
            $.ajax({
                url:"{{ route('celebrations.store') }}",
                type:"POST",
                data:{
                    "mainUrl": mainUrl,
                    "_token": "{{ csrf_token() }}",
                    'type' : type,
                    'FirstName' : FirstName,
                    'LastName' : LastName,
                    'Message' : Message,
                    'template' : template,
                    'youtubeCode' : link,
                    'startTime' : startTime,
                    'finishTime' : finishTime,
                    'lang' : "{{ app()->getLocale() }}"
                },
                success:function(data){
                    window.location.replace(data.mainUrl + data.id);
                },
                error:function(data){
                    // show alert box for error
                    $('#Oops').slideDown(500);
                      window.scrollTo(0, 0);

                }
            });
        });

    });
</script>
@endsection

