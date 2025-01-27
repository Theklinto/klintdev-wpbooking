<?php

namespace KlintDev\WPBooking;

use Exception;
use KlintDev\WPBooking\Logging\Logger;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\Package\PackageEditView;
use KlintDev\WPBooking\Views\Package\PackageListView;
use KlintDev\WPBooking\Views\PartialPage;
use KlintDev\WPBooking\Views\Room\RoomEditView;
use KlintDev\WPBooking\Views\Room\RoomListView;
use KlintDev\WPBooking\Views\Settings\EditBlockedDurationView;
use KlintDev\WPBooking\Views\Settings\SettingsOverviewView;

class MenuHandler {
	public MenuPage $MenuParentItem;
	public MenuPage $SubMenuRooms;
	public MenuPage $HiddenMenuRoomsEdit;
	public MenuPage $SubMenuPackages;
	public MenuPage $HiddenMenuPackageEdit;
	public MenuPage $SubMenuSettings;
	public MenuPage $HiddenEditBlockedDuration;

	protected static self $instance;

	public static function getInstance(): self {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new MenuHandler();
		}

		return self::$instance;
	}

	/**
	 * @var MenuPage[]
	 */
	protected static array $menus = [];

	public function __construct() {
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
		$this->SubMenuRooms    = new MenuPage(
			"Lokaler",
			"rooms",
			RoomListView::getInstance(),
			"building",
			$this->MenuParentItem->Slug,
		);
		$this->SubMenuPackages = new MenuPage(
			"Pakker",
			"packages",
			PackageListView::getInstance(),
			"",
			$this->MenuParentItem->Slug,
		);

		$this->HiddenMenuRoomsEdit = new MenuPage(
			"Rediger lokale",
			"edit-room",
			RoomEditView::getInstance(),
			"",
			$this->SubMenuRooms->Slug,
		);

		$this->HiddenMenuPackageEdit = new MenuPage(
			"Rediger pakke",
			"edit-package",
			PackageEditView::getInstance(),
			"",
			$this->SubMenuPackages->Slug,
		);

		$this::$menus = [
			$this->MenuParentItem,
			$this->SubMenuSettings,
			$this->HiddenEditBlockedDuration,
//            $this->SubMenuOverview,
			$this->SubMenuRooms,
			$this->SubMenuPackages,
			$this->HiddenMenuRoomsEdit,
			$this->HiddenMenuPackageEdit,
		];
	}

	public function registerMenus(): void {
		foreach ( $this::$menus as $menu ) {
			$menu->register_menu();
		}
		Logger::log_info( "Menus registered", [ "count" => count( $this::$menus ) ] );
	}

	public function enqueueContent(): void {
		foreach ( $this::$menus as $menu ) {
			$menu->enqueue_required_content();
		}
	}
}

class MenuPage {
	public function __construct(
		public string $Title,
		public string $Slug,
		public PartialPage $Page,
		public string $DashIcon = "",
		public string|null $ParentSlug = null,
	) {
		$this->Slug = GlobalSettings::PLUGIN_PREFIX . '-' . $Slug;
	}

	public function enqueue_required_content(): void {
		if ( ! isset( $_GET["page"] ) || $_GET["page"] !== $this->Slug ) {
			return;
		}

		$requiredContent = $this->Page::getRequiredContent();

		$inlineScriptsDependencies = [];
		$inlineScripts             = "";

		foreach ( $requiredContent as $content ) {
			switch ( $content->type ) {
				case ContentDependencyType::Script:
				{
					switch ( $content->LoadingStyle ) {
						case ContentDependencyLoadingStyle::InlineContent:
						{
							$inlineScripts             .= " " . $this->cleanInlineScript( $content->Content );
							$inlineScriptsDependencies = array_merge( $inlineScriptsDependencies, $content->RequiredDependencies );
							break;
						}
						case ContentDependencyLoadingStyle::RegisteredContent:
						{
							wp_enqueue_script( $content->Handle );
							break;
						}
						case ContentDependencyLoadingStyle::EnqeueFunctinon:
						{
							( $content->Content )();
							break;
						}
					}
					break;
				}
				case ContentDependencyType::Style:
				{

					$this->enqueue_required_style( $content );
					break;
				}
			}
		}

		wp_register_script( ContentDependency::INLINE_SCRIPT_HANDLE, false, $inlineScriptsDependencies );
		wp_add_inline_script( ContentDependency::INLINE_SCRIPT_HANDLE, $inlineScripts );
		wp_enqueue_script( ContentDependency::INLINE_SCRIPT_HANDLE );
	}

	protected function cleanInlineScript( string $script ): string {
		$strippedContent = preg_replace( "/<script>/", "", $script );
		$strippedContent = preg_replace( "#</script>#", "", $strippedContent );

//        $strippedContent = preg_replace("/\r\n\s*/", " ", $strippedContent);
		return trim( $strippedContent );
	}

	protected function enqueue_required_style( ContentDependency $content ): void {
		switch ( $content->LoadingStyle ) {
			case ContentDependencyLoadingStyle::InlineContent:
			{
				wp_add_inline_style( $content->Handle, $content->Content );
			}
			case ContentDependencyLoadingStyle::RegisteredContent:
			{
				wp_enqueue_style( $content->Handle );
			}
			case ContentDependencyLoadingStyle::EnqeueFunctinon:
			{
				( $content->Content )();
			}
		}
	}

	public function register_menu(): void {
		if ( isset( $this->ParentSlug ) ) {
			add_submenu_page(
				$this->ParentSlug,
				$this->Title,
				$this->Title,
				GlobalSettings::PLUGIN_CAPABILITY,
				$this->Slug,
				fn() => $this->renderPage($this->Page),
			);
		} else {
			add_menu_page(
				$this->Title,
				$this->Title,
				GlobalSettings::PLUGIN_CAPABILITY,
				$this->Slug,
				fn() => $this->renderPage( $this->Page ),
				$this->DashIcon
			);
		}
	}

	public function renderPage( PartialPage $page ): void {
		try {
			echo $page->render();
		} catch ( Exception $exception ) {
			Logger::log_error( "Failed to load page", [
				"page"      => $page::class,
				"exception" => $exception
			] );

			echo "Der skete en fejl, siden kunne ikke indlæses!";
		}
	}

	public function getUrl( array $queryParams = [] ): string {
		$query = http_build_query( $queryParams, "", '&' );

		return get_admin_url( null, "admin.php?page=" . join( "&", [ $this->Slug, $query ] ) );
	}
}