@props(['id', 'accept', 'name'])

<label for="{{ $id }}" class="btn btn-primary me-2 mb-3" tabindex="0">
    <span class="d-none d-sm-block">
        {{ $slot }}
    </span>
    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
    <input type="file" id="{{ $id }}" class="account-file-input" hidden accept="{{ $accept }}" name={{ $name }} />
</label>