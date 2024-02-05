<div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
        <x-brand-logo />
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <x-brand-logo-collapsed />
    </a>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
    @php
        $menuItems = [
            [
                'label' => 'Dashboard',
                'icon' => 'tf-icons mdi mdi-home-outline',
                'route' => 'dashboard',
            ],
            // //submenu
            // [
            //     'label' => 'Users',
            //     'icon' => 'tf-icons mdi mdi-account-multiple-outline',
            //     'route' => '',
            //     'submenu' => [
            //         [
            //             'label' => 'Users',
            //             'icon' => 'tf-icons mdi mdi-account-multiple-outline',
            //             'route' => 'users.index',
            //             'permission' => 'Users'
            //         ],
            //         [
            //             'label' => 'Profile Settings',
            //             'icon' => 'tf-icons mdi mdi-account-outline',
            //             'route' => 'profile.index',
            //             'permission' => 'Profile',
            //         ],
            //     ]
            // ],
            [
                'header' => true,
                'label' => 'Roles & Permissions',
                'permissionany' => ['Role', 'Permission']
            ],
            [
                'label' => 'Permissions',
                'icon' => 'tf-icons mdi mdi-account-key-outline',
                'route' => 'permissions.index',
                'permission' => 'Permission'
            ],
            [
                'label' => 'Roles',
                'icon' => 'tf-icons mdi mdi-account-check-outline',
                'route' => 'roles.index',
                'permission' => 'Role'
            ],
            [
                'header' => true,
                'label' => 'Settings',
                'permissionany' => ['Users', 'Profile']
            ],
            [
                'label' => 'Users',
                'icon' => 'tf-icons mdi mdi-account-multiple-outline',
                'route' => 'users.index',
                'permission' => 'Users'
            ],
            [
                'label' => 'Profile Settings',
                'icon' => 'tf-icons mdi mdi-account-outline',
                'route' => 'profile.index',
                'permission' => 'Profile',
                'uri_segment' => 'profile',
            ],
        ];
    @endphp

    <x-Menu :menuItems="$menuItems" />
</ul>
