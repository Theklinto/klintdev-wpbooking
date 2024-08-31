<?php

namespace KlintDev\WPBooking\Controllers;

use KlintDev\WPBooking\GlobalSettings;

abstract class ControllerBase
{
    abstract public static function getEndpointUrl(string $method, array $queryParams = []): string;

    protected static function baseGetEndpointUrl(string $controller, string $method, array $queryParams = []): string
    {
        $basePath = GlobalSettings::API_BASE_PATH . "/$controller/$method";
        $query = http_build_query($queryParams, "", "&");
        return get_rest_url(null, join("?", [$basePath, $query]));
    }
}