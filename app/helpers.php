<?php

if (!function_exists('highlight')) {
    function highlight($text, $search) {
        if (!$search) return e($text);
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', e($text));
    }
} 