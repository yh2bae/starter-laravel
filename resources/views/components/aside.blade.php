<div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
        <x-brand-logo />
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <x-brand-logo-collapsed />
    </a>
</div>

<div class="menu-inner-shadow"></div>
@php
    $menu = MenuItems::getMenu();
@endphp
<ul class="menu-inner py-1">
    <x-Menu :menuItems="$menu" />
</ul>
