<?php

namespace KlintDev\WPBooking;

use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;
use KlintDev\WPBooking\Views\Room\RoomEditView;
use KlintDev\WPBooking\Views\Room\RoomListView;
use KlintDev\WPBooking\Views\Settings\EditBlockedDurationView;
use KlintDev\WPBooking\Views\Settings\SettingsOverviewView;

require_once KDWPB_PATH . "views/vue_page_resolver.php";

class MenuHandler
{
    public MenuPage $MenuParentItem;
    public MenuPage $SubMenuOverview;

    public MenuPage $SubMenuRooms;
    public MenuPage $HiddenMenuRoomsEdit;

    public MenuPage $SubMenuPackages;
    public MenuPage $HiddenMenuPackageEdit;

    public MenuPage $SubMenuSettings;
    public MenuPage $HiddenEditBlockedDuration;

    protected static MenuHandler $instance;

    public static function getInstance(): MenuHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new MenuHandler();
        }

        return self::$instance;
    }

    /**
     * @var MenuPage[]
     */
    protected static array $menus = [];

    public static array $TransformScriptsToModules = [];

    public function __construct()
    {
        $this->MenuParentItem = new MenuPage(
            "WP Booking",
            "overview",
            SettingsOverviewView::getInstance(),
            'dashicons-layout',
        );

        $this->SubMenuSettings = new MenuPage(
            "Indstillinger",
            "settings-overview",
            SettingsOverviewView::getInstance(),
            "dashicons-gear",
            $this->MenuParentItem->Slug,
        );

        $this->HiddenEditBlockedDuration = new MenuPage(
            "Låst periode",
            "edit-blocked-duration",
            EditBlockedDurationView::getInstance(),
            "",
            $this->SubMenuSettings->Slug,
        );

//        $this->SubMenuOverview = new MenuPage(
//            "Overblik",
//            "overview",
//            __DIR__ . "/views/vue_page_resolver.php",
//            "",
//            $this->MenuParentItem->Slug
//        );
//
        $this->SubMenuRooms = new MenuPage(
            "Lokaler",
            "rooms",
            RoomListView::getInstance(),
            "building",
            $this->MenuParentItem->Slug,
        );
//        $this->SubMenuPackages = new MenuPage(
//            "Pakker",
//            "packages",
//            fn() => VuePageResolver::GetPage(GlobalSettings::PLUGIN_PREFIX . "-package-list-vue"),
//            "",
//            $this->MenuParentItem->Slug,
//            [
//                KDWPB_URL . "vue_assets/list_package.js"
//            ]
//        );
//
        $this->HiddenMenuRoomsEdit = new MenuPage(
            "Rediger lokale",
            "edit-room",
            RoomEditView::getInstance(),
            "",
            $this->SubMenuRooms->Slug,
        );
//
//
//
//        $this->HiddenMenuPackageEdit = new MenuPage(
//            "Rediger pakke",
//            "edit-package",
//            fn() => VuePageResolver::GetPage(GlobalSettings::PLUGIN_PREFIX . "-edit-package-vue"),
//            "",
//            $this->SubMenuPackages->Slug,
//            [
//                KDWPB_URL . "vue_assets/edit_package.js"
//            ]
//        );

        $this::$menus = [
            $this->MenuParentItem,
            $this->SubMenuSettings,
            $this->HiddenEditBlockedDuration,
//            $this->SubMenuOverview,
            $this->SubMenuRooms,
//            $this->SubMenuPackages,
            $this->HiddenMenuRoomsEdit,
//            $this->HiddenMenuPackageEdit,
        ];
    }

    public function registerMenus(): void
    {
        foreach ($this::$menus as $menu) {
            $menu->register_menu();
        }
    }

    public function enqueueContent(): void
    {
        foreach ($this::$menus as $menu) {
            $menu->enqueue_required_content();
        }
    }
}

class MenuPage
{
    public function __construct(
        public string      $Title,
        public string      $Slug,
        public PartialPage $Page,
        public string      $DashIcon = "",
        public string|null $ParentSlug = null,
    )
    {
        $this->Slug = GlobalSettings::PLUGIN_PREFIX . '-' . $Slug;
    }

    public function enqueue_required_content(): void
    {
        if (!isset($_GET["page"]) || $_GET["page"] !== $this->Slug) {
            return;
        }

        $requiredContent = $this->Page::getRequiredContent();

        $inlineScriptsDependencies = [];
        $inlineScripts = "";

        foreach ($requiredContent as $content) {
            switch ($content->type) {
                case ContentDependencyType::Script:
                {
                    switch ($content->LoadingStyle) {
                        case ContentDependencyLoadingStyle::InlineContent:
                        {
                            $inlineScripts .= " " . $this->cleanInlineScript($content->Content);
                            $inlineScriptsDependencies = array_merge($inlineScriptsDependencies, $content->RequiredDependencies);
                            break;
                        }
                        case ContentDependencyLoadingStyle::RegisteredContent:
                        {
                            wp_enqueue_script($content->Handle);
                            break;
                        }
                        case ContentDependencyLoadingStyle::EnqeueFunctinon:
                        {
                            ($content->Content)();
                            break;
                        }
                    }
                    break;
                }
                case ContentDependencyType::Style:
                {

                    $this->enqueue_required_style($content);
                    break;
                }
            }
        }

        wp_register_script(ContentDependency::INLINE_SCRIPT_HANDLE, false, $inlineScriptsDependencies);
        $result = wp_add_inline_script(ContentDependency::INLINE_SCRIPT_HANDLE, $inlineScripts);
        wp_enqueue_script(ContentDependency::INLINE_SCRIPT_HANDLE);
    }

    protected function cleanInlineScript(string $script): string
    {
        $strippedContent = preg_replace("/<script>/", "", $script);
        $strippedContent = preg_replace("#</script>#", "", $strippedContent);
//        $strippedContent = preg_replace("/\r\n\s*/", " ", $strippedContent);
        return trim($strippedContent);
    }

    protected function enqueue_required_style(ContentDependency $content): void
    {
        switch ($content->LoadingStyle) {
            case ContentDependencyLoadingStyle::InlineContent:
            {
                wp_add_inline_style($content->Handle, $content->Content);
            }
            case ContentDependencyLoadingStyle::RegisteredContent:
            {
                wp_enqueue_style($content->Handle);
            }
            case ContentDependencyLoadingStyle::EnqeueFunctinon:
            {
                ($content->Content)();
            }
        }
    }

    public function register_menu(): void
    {
        if (isset($this->ParentSlug)) {
            add_submenu_page(
                $this->ParentSlug,
                $this->Title,
                $this->Title,
                GlobalSettings::PLUGIN_CAPABILITY,
                $this->Slug,
                fn() => print $this->Page::render(),
            );
        } else {
            add_menu_page(
                $this->Title,
                $this->Title,
                GlobalSettings::PLUGIN_CAPABILITY,
                $this->Slug,
                fn() => print $this->Page::render(),
                $this->DashIcon
            );
        }
    }

    public function getUrl(array $queryParams = []): string
    {
        $query = http_build_query($queryParams, "", '&');
        return get_admin_url(null, "admin.php?page=" . join("&", [$this->Slug, $query]));
    }
}