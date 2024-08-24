<?php

namespace KlintDev\WPBooking\Services;

use KlintDev\WPBooking\GlobalSettings;

class OptionService
{
    protected const API_KEY_OPTION = GlobalSettings::PLUGIN_PREFIX . "_stripe_api_key";
    protected const API_SECRET_OPTION = GlobalSettings::PLUGIN_PREFIX . "_stripe_api_secret";

    public static function stripeApiKey(?string $value = null): bool|string
    {
        if ($value === null) {
            return get_option(self::API_KEY_OPTION);
        } else {
            return update_option(self::API_KEY_OPTION, $value);
        }
    }

    public static function stripeApiSecret(?string $value = null): bool|string
    {
        if ($value === null) {
            return get_option(self::API_SECRET_OPTION);
        } else {
            return update_option(self::API_SECRET_OPTION, $value);
        }
    }
}