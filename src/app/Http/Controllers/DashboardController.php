<?php

namespace Idev\EasyAdmin\app\Http\Controllers;

use Idev\EasyAdmin\app\Models\Role;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $title;
    private $generalUri;

    public function __construct()
    {
        $this->title = 'Dashboard';
        $this->generalUri = 'dashboard';
    }


    public function index()
    {
        $data['title'] = $this->title;

        $layout = (request('from_ajax') && request('from_ajax') == true) ? 'easyadmin::backend.idev.dashboard_ajax' : 'easyadmin::backend.idev.dashboard';

        return view($layout, $data);
    }

}