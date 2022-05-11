<?php

namespace Pharaonic\Laravel\Users\Classes;

final class Actions
{
    public static function action(string $action, ...$list)
    {
        if (empty($list)) return;

        return !auth()->check() ? false : auth()->user()->{$action}(self::prepareParamsArray($list));
    }

    /**
     * Prepare Roles & Permissions
     *
     * @param array $params
     * @return array
     */
    private static function prepareParamsArray($params)
    {
        if (is_array($params[0])) $params = $params[0];
        if (is_string($params)) $params = explode(',', $params);

        return $params;
    }
}
