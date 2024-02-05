@props(['id', 'name', 'placeholder', 'attributes' => []])

<div class="input-group input-group-merge">
    <div class="form-floating form-floating-outline">
        <input class="form-control" type="password" name="{{ $name }}" id="{{ $id }}"
            placeholder="{{ $placeholder }}" {{ $attributes }}>
        <label for="{{ $id }}">{{ $slot }}</label>
    </div>
    <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility('{{ $id }}')">
        <i class="mdi mdi-eye-off-outline"></i>
    </span>
</div>
