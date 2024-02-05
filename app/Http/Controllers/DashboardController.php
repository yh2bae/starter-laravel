<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->routePath = 'dashboard';
        $this->prefix = 'dashboard';
        $this->pageName = 'Dashboard';

    }

    public function index()
    {
        $data['pageTitle'] = $this->pageName;
        $data['pageDescription'] = '';
        $data['breadcrumbItems'] = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
        ];

        return view($this->prefix . '.index', $data);
    }
}
