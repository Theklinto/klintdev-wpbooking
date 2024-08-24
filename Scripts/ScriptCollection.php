<?php

namespace KlintDev\WPBooking\Scripts;

use KlintDev\WPBooking\GlobalSettings;

class ScriptCollection
{
    public const JQUERY = "jquery-core";
    public const JQUERY_SORTABLE = "jquery-ui-sortable";
    public const ALERT = GlobalSettings::PLUGIN_PREFIX . "-alert";
    public const FORM_CONTROLLER = globalSettings::PLUGIN_PREFIX . "-form-controller";
    public const OBJECT_PARSER = GlobalSettings::PLUGIN_PREFIX . "-object-parser";
    public const MEDIA_SELECTOR = GlobalSettings::PLUGIN_PREFIX . "-media-selector";
    protected const SCRIPT_FOLDER = KDWPB_URL . "Scripts/";

    public static function registerScriptCollection(): void
    {
        wp_register_script(self::ALERT, self::SCRIPT_FOLDER . "Alert.js", [self::JQUERY], "1.0");
        wp_register_script(self::FORM_CONTROLLER, self::SCRIPT_FOLDER . "FormController.js", [self::JQUERY, self::ALERT], "1.0");
        wp_register_script(self::OBJECT_PARSER, self::SCRIPT_FOLDER . "ObjectParser.js", [], "1.0");
        wp_register_script(self::MEDIA_SELECTOR, self::SCRIPT_FOLDER . "MediaSelector.js", [], "1.0");
    }
}