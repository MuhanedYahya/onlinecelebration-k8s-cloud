@extends('layouts.app')
@section('main')
<main class="not_found mt-5">
    <div class="container text-center">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <h1>404<span>!</span></h1>
                <p>
                   oops! page not found
                </p>
                <button class="btn rounded-2 homePageBtn mt-2">
                    <a href="{{ route('celebrations.create') }}">
                        Back to home page
                    </a>
                </button>
            </div>
        </div>
    </div>
</main>
@endsection
