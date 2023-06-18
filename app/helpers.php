<?php

if (!function_exists('get_cart_items_count')) {
    function get_cart_items_count()
    {
        $cartItems = session('cart_items', []);

        return count($cartItems);
    }
}

if (!function_exists('get_cart_total')) {
    function get_cart_total()
    {
        $cartItems = session('cart_items', []);

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'];
        }

        return $total;
    }
}

if (!function_exists('get_directory_size')) {
    function get_directory_size($path)
    {
        $size = 0;

        foreach (glob(rtrim($path, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : get_directory_size($each);
        }

        return $size;
    }
}
