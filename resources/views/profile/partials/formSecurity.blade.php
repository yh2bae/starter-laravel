<form id="formAccountSettings" action="{{ route('profile.security.update', $user->uuid) }}" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        <div class="mb-3 col-md-6 form-password-toggle">
            <x-form.input.password id="current_password" name="current_password" placeholder="******************">
                Current Password
            </x-form.input.password>
            @error('current_password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-6 form-password-toggle">
            <x-form.input.password id="password" name="password" placeholder="******************">
                New Password
            </x-form.input.password>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 form-password-toggle">
            <x-form.input.password id="password_confirmation" name="password_confirmation" placeholder="******************">
                Confirm New Password
            </x-form.input.password>
        </div>
    </div>
    <h6 class="text-body">Password Requirements:</h6>
    <ul class="ps-3 mb-0">
        <li class="mb-1">Minimum 8 characters long - the more, the better</li>
    </ul>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary me-2">Save changes</button>
        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
    </div>
</form>
