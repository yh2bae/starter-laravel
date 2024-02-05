@props(['items' => []])
<div class="pb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1">
            @foreach ($items as $key => $item)
                <li class="breadcrumb-item @if ($loop->last) active @endif"
                    aria-current="{{ $loop->last ? 'page' : '' }}">
                    @if ($loop->last)
                        {{ $item['name'] }}
                    @else
                        <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
    <hr />
</div>
