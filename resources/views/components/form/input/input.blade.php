@props(['id', 'name', 'class', 'placeholder', 'value', 'type', 'attributes' => []])

<div class="form-floating form-floating-outline">
    <input type="{{ $type }}" class="form-control {{ $class ?? '' }}" id="{{ $id }}" name="{{ $name }}"
        placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? '' }}" {{ $attributes }}>
    <label for="{{ $id }}">{{ $slot }}</label>
</div>
