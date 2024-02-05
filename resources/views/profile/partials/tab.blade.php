<ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}"
            href="{{ route('profile.index') }}">
            <i class="mdi mdi-account-outline mdi-20px me-1"></i>
            Profile
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('profile.security') ? 'active' : '' }}" href="{{ route('profile.security') }}"><i
                class="mdi mdi-lock-open-outline mdi-20px me-1"></i>Security</a>
    </li>
</ul>
