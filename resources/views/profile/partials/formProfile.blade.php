<form id="formAccountSettings" action="{{ route('profile.update', $user->uuid) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row mt-2 gy-4">
        <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
                <img src="{{ $profile->avatar }}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded"
                    id="uploadedAvatar" />
                <div class="button-wrapper">
                    <x-form.input.avatar id="upload" accept="image/png, image/jpeg" name="avatar">
                        Upload new photo
                    </x-form.input.avatar>
                    <button type="button" class="btn btn-outline-danger account-image-reset mb-3">
                        <i class="mdi mdi-reload d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Reset</span>
                    </button>

                    <div class="small">Allowed JPG, GIF or PNG. Max size of 2MB</div>
                    @error('avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <x-form.input.input type="text" id="name" name="name" value="{{ $user->name }}">
                Name
            </x-form.input.input>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <x-form.input.input type="email" id="email" name="email" label="E-mail" disabled
                value="{{ $user->email }}">
                E-mail
            </x-form.input.input>
        </div>
        <div class="col-md-6">
            <x-form.input.input type="text" id="address" name="address" value="{{ $profile->address ?? '' }}">
                Address
            </x-form.input.input>
            @error('address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-merge">
                <span class="input-group-text">ID (+62)</span>
                <div class="form-floating form-floating-outline">
                    <x-form.input.phone id="phoneNumber" name="phone_number" placeholder="89xxxxxx"
                        value="{{ $profile->phone_number }}">
                        Phone Number
                    </x-form.input.phone>
                    @error('phone_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary me-2">Save changes</button>
        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
    </div>
</form>
