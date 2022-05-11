<?php

use Pharaonic\Laravel\Users\Classes\Actions;

if (!function_exists('entrusted')) {
    function entrusted(...$roles)
    {
        return Actions::action('entrusted', ...$roles);
    }
}

if (!function_exists('entrustedAny')) {
    function entrustedAny(...$roles)
    {
        return Actions::action('entrustedAny', ...$roles);
    }
}

if (!function_exists('distrusted')) {
    function distrusted(...$roles)
    {
        return Actions::action('distrusted', ...$roles);
    }
}

if (!function_exists('distrustedAny')) {
    function distrustedAny(...$roles)
    {
        return Actions::action('distrustedAny', ...$roles);
    }
}

if (!function_exists('permitted')) {
    function permitted(...$permissions)
    {
        return Actions::action('permitted', ...$permissions);
    }
}

if (!function_exists('permittedAny')) {
    function permittedAny(...$permissions)
    {
        return Actions::action('permittedAny', ...$permissions);
    }
}

if (!function_exists('forbad')) {
    function forbad(...$permissions)
    {
        return Actions::action('forbad', ...$permissions);
    }
}

if (!function_exists('forbadAny')) {
    function forbadAny(...$permissions)
    {
        return Actions::action('forbadAny', ...$permissions);
    }
}
