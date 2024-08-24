<?php /*
  * Plugin Name:       WP Booking
  */

namespace KlintDev\WPBooking;

//TODO: Check for ABSPATH in all files

use KlintDev\WPBooking\Controllers\ControllerInitializer;
use KlintDev\WPBooking\DB\DBHandler;
use KlintDev\WPBooking\Scripts\ScriptCollection;

define("KDWPB_PATH", plugin_dir_path(__FILE__));
define("KDWPB_URL", plugin_dir_url(__FILE__));

require_once KDWPB_PATH . 'vendor/autoload.php';

add_action("admin_menu", fn() => MenuHandler::getInstance()->registerMenus());
add_action("rest_api_init", fn() => ControllerInitializer::registerRestEndpoints());

add_action("admin_enqueue_scripts", function () {
    wp_enqueue_style("bootstrap", "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css");
    wp_enqueue_style(GlobalSettings::PLUGIN_PREFIX . "-style", KDWPB_URL . "style.css");

    ScriptCollection::registerScriptCollection();
    MenuHandler::getInstance()->enqueueContent();
});

register_activation_hook(__FILE__, function () {
    GlobalSettings::registerCapabilities();

    DBHandler::createTables();
});

