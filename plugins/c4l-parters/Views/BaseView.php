<?php

namespace C4lPartners\Views;

use C4lPartners\Constants;

class BaseView
{
    function add_query_arg($key, $value)
    {
        $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return add_query_arg($key, $value, $current_url);
    }

    function admin_add_to_root_url($key, $value)
    {
        return add_query_arg($key, $value, $this->admin_root_url());
    }

    function admin_root_url()
    {
        return admin_url('admin.php?page=' . Constants::C4l_ADMIN_PAGE);
    }


    static function add_query_arg2($key, $value)
    {
        $current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return add_query_arg($key, $value, $current_url);
    }

    static function admin_add_to_root_url2($key, $value)
    {
        return add_query_arg($key, $value, self::admin_root_url2());
    }

    static function admin_root_url2()
    {
        return admin_url('admin.php?page=' . Constants::C4l_ADMIN_PAGE);
    }
}
