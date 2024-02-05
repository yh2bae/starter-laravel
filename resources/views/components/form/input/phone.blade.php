@props(['id', 'name', 'placeholder', 'value'])

<input type="text" id="{{ $id }}" name="{{ $name }}" class="form-control"
    placeholder="{{ $placeholder }}" value="{{ $value ?? '' }}" />
<label for="{{ $id }}">{{ $slot }}</label>
