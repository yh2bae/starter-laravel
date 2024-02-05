@extends('layouts.error')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('content')
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="font-size: 6rem">
            403
        </h1>
        <h4 class="mb-2">{{ __($exception->getMessage() ?: 'Forbidden') }} 🔐</h4>
        <p class="mb-2 mx-2"></p>
        <div class="d-flex justify-content-center mt-5">
            <img src="{{ asset('assets/img/illustrations/misc-not-authorized-object.png') }}" alt="misc-not-authorized"
                class="img-fluid misc-object d-none d-lg-inline-block" width="190" />
            <img src="{{ asset('assets/img/illustrations/misc-bg-light.png') }}" alt="misc-not-authorized"
                class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png"
                data-app-dark-img="illustrations/misc-bg-dark.png" />
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/illustrations/misc-not-authorized-illustration.png') }}" alt="misc-not-authorized"
                    class="img-fluid zindex-1" width="160" />
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary text-center my-4">Back to home</a>
                </div>
            </div>
        </div>
    </div>
@endsection
