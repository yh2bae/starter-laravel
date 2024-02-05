@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
    <x-breadcrumb :items="$breadcrumbItems" />
    <div class="row">
        <div class="col-md-12">
            @include('profile.partials.tab')
            <div class="card mb-4">
                <h5 class="card-header">Change Password</h5>
                <div class="card-body">
                    <h6 class="text-body">Last changed: <span class="text-muted">
                            {{ $user->last_password_change ? \Carbon\Carbon::parse($user->last_password_change)->diffForHumans() : 'Never' }}
                        </span></h6>
                    @include('profile.partials.formSecurity')
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
@endpush
