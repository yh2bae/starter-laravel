<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenu()
    {
        $menu = [
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
                'permissionany' => ['Role', 'Permission'],
            ],
            [
                'label' => 'Permissions',
                'icon' => 'tf-icons mdi mdi-account-key-outline',
                'route' => 'permissions.index',
                'permission' => 'Permission',
            ],
            [
                'label' => 'Roles',
                'icon' => 'tf-icons mdi mdi-account-check-outline',
                'route' => 'roles.index',
                'permission' => 'Role',
            ],
            [
                'header' => true,
                'label' => 'Settings',
                'permissionany' => ['Users', 'Profile'],
            ],
            [
                'label' => 'Users',
                'icon' => 'tf-icons mdi mdi-account-multiple-outline',
                'route' => 'users.index',
                'permission' => 'Users',
            ],
            [
                'label' => 'Profile Settings',
                'icon' => 'tf-icons mdi mdi-account-outline',
                'route' => 'profile.index',
                'permission' => 'Profile',
                'uri_segment' => 'profile',
            ],
        ];

        return $menu;
    }
}