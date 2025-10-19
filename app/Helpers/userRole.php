<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('adminRole')) {
    function adminRole()
    {
        return Auth::user()->hasRole('admin');
    }
}

if (! function_exists('bendaharaRole')) {
    function bendaharaRole()
    {
        return Auth::user()->hasRole('bendahara');
    }
}

if (! function_exists('superAdminRole')) {
    function superAdminRole()
    {
        return Auth::user()->hasRole('super_admin');
    }
}
