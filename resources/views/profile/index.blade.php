@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
    <x-breadcrumb :items="$breadcrumbItems" />
    <div class="row">
        <div class="col-md-12">
            @include('profile.partials.tab')
            <div class="card mb-4">
                <h4 class="card-header">Profile Details</h4>
                <div class="card-body pt-2 mt-1">
                    @include('profile.partials.formProfile')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let accountUserImage = document.getElementById('uploadedAvatar');
        const fileInput = document.querySelector('.account-file-input'),
            resetFileInput = document.querySelector('.account-image-reset');

        if (accountUserImage) {
            const resetImage = accountUserImage.src;
            fileInput.onchange = () => {
                if (fileInput.files[0]) {
                    accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            };
            resetFileInput.onclick = () => {
                fileInput.value = '';
                accountUserImage.src = resetImage;
            };
        }

        const phoneNumber = document.querySelector('#phoneNumber');
        if (phoneNumber) {
            new Cleave(phoneNumber, {
                phone: true,
                phoneRegionCode: 'ID',
            });
        }

    </script>
@endpush

