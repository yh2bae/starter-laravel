@php
    function isMenuItemActive($item)
    {
        if (isset($item['route']) && $item['route'] == request()->route()->getName()) {
            return true;
        }

        if (isset($item['uri_segment']) && $item['uri_segment'] == request()->segment(1)) {
            return true;
        }

        if (isset($item['submenu'])) {
            foreach ($item['submenu'] as $submenuItem) {
                if (isMenuItemActive($submenuItem)) {
                    return true;
                }
            }
        }

        return false;
    }

    function hasPermission($item)
    {
        if (isset($item['permission'])) {
            return auth()
                ->user()
                ->can($item['permission']);
        }

        if (isset($item['permissionany'])) {
            foreach ($item['permissionany'] as $permission) {
                if (auth()->user()->can($permission)) {
                    return true;
                }
            }
            return false; // Return false if none of the 'permissionany' are granted
        }

        return true; // Assume permission is granted if no specific permissions are set
    }
@endphp

@foreach ($menuItems as $item)
    @if (isset($item['header']) && $item['header'])
        @if (hasPermission($item))
            <!-- Use hasPermission for uniform permission checking -->
            <li class="menu-header fw-medium mt-4">
                <span class="menu-header-text">{{ $item['label'] }}</span>
            </li>
        @endif
    @else
        @if (hasPermission($item))
            <li
                class="menu-item{{ isMenuItemActive($item) ? ' active' : '' }}{{ isset($item['submenu']) && isMenuItemActive($item) ? ' open' : '' }}">
                @if (isset($item['route']) && $item['route'])
                    <a href="{{ route($item['route']) }}"
                        class="menu-link{{ isMenuItemActive($item) ? ' active open' : '' }}">
                    @else
                        <a href="javascript:void(0);"
                            class="menu-link menu-toggle{{ isMenuItemActive($item) ? ' active' : '' }}">
                @endif
                @if (isset($item['icon']))
                    <i class="menu-icon tf-icons {{ $item['icon'] }}"></i>
                @endif
                <div data-i18n="{{ $item['label'] }}">{{ $item['label'] }}</div>
                @if (isset($item['badge']))
                    <div class="badge {{ $item['badge']['class'] }} rounded-pill ms-auto">{{ $item['badge']['value'] }}
                    </div>
                @endif
                </a>

                @if (isset($item['submenu']))
                    <ul class="menu-sub">
                        @foreach ($item['submenu'] as $submenuItem)
                            @if (hasPermission($submenuItem))
                                <li class="menu-item{{ isMenuItemActive($submenuItem) ? ' active' : '' }}">
                                    <a href="{{ route($submenuItem['route']) }}"
                                        class="menu-link{{ isMenuItemActive($submenuItem) ? ' active' : '' }}">
                                        <div data-i18n="{{ $submenuItem['label'] }}">{{ $submenuItem['label'] }}</div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @endif
@endforeach
