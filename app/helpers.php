<?php

if (!function_exists('set_active')) {
    function set_active($route) {
        if (is_array($route)) {
            return in_array(Request::path(), $route) ? 'active' : '';
        }
        return Request::path() == $route ? 'active' : '';
    }
}
