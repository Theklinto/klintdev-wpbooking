<?php

namespace KlintDev\WPBooking;

class GlobalSettings
{
    public const PLUGIN_PREFIX = "kdwpb";
    public const PLUGIN_CAPABILITY = "kdwpb_admin";
    public const API_BASE_PATH = "kdwpb/v1";

    public static function registerCapabilities(): void
    {
        $adminRole = get_role("administrator");
        $adminRole?->add_cap(GlobalSettings::PLUGIN_CAPABILITY);
    }
}