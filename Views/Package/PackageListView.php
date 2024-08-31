<?php

namespace KlintDev\WPBooking\Views\Package;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Formatter;
use KlintDev\WPBooking\Components\Table;
use KlintDev\WPBooking\DTO\Package\PackageListRequest;
use KlintDev\WPBooking\MenuHandler;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\PackageService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;

class PackageListView extends PartialPage {

	protected static self $instance;

	protected const PREFIX = "package-list-";
	protected const ADD_BTN_ID = self::PREFIX . "add-button";

	public static function getInstance(): object {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function render(): string|false {

		$packages = PackageService::getPackagesList();

		ob_start(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
					<?php
					echo Container::beginDashboardContainer();

					echo Container::header(
						"Pakker",
						true,
						self::ADD_BTN_ID,
						"Tilføj",
					);

					?>

                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10%;">Aktiv</th>
                            <th style="width: 30%;">Navn</th>
                            <th style="width: 30%;">Lokale</th>
                            <th style="width: 10%;">Varighed</th>
                            <th style="width: 10%;">Pris</th>
                            <th style="width: 10%;">Depositum</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ( $packages as $package ) { ?>
                            <tr>
                                <td><?= Formatter::formatBooleanIcon( $package->getPropertyValue( PackageListRequest::ACTIVE_BOOL ) ) ?></td>
                                <td><?= $package->getPropertyValue( PackageListRequest::NAME_STR ) ?></td>
                                <td><?= $package->getPropertyValue( PackageListRequest::ROOM_NAME ) ?></td>
                                <td><?= $package->getPropertyValue( PackageListRequest::DURATION_IN_HOURS_INT ) . " t" ?></td>
                                <td><?= $package->getPropertyValue( PackageListRequest::PRICE_FLOAT ) ?></td>
                                <td><?= $package->getPropertyValue( PackageListRequest::DEPOSIT_FLOAT ) ?></td>
								<?= Table::editButton(
									MenuHandler::getInstance()->HiddenMenuPackageEdit->getUrl( [
										"id" => $package->getPropertyValue( PackageListRequest::ID_INT )
									] )
								) ?>
                            </tr>
						<?php } ?>
						<?= Table::noRowsText( "Der er ingen pakker", $packages ) ?>
                        </tbody>
                    </table>

					<?php

					echo Container::endDashboardContainer();

					?>
                </div>
            </div>
        </div>

		<?php return ob_get_clean();
	}

	protected static function inlineScript(): string|false {
		ob_start(); ?>
        <script>
            jQuery(() => {
                jQuery("#<?= self::ADD_BTN_ID ?>").on("click", () => {
                    window.location.href = "<?= MenuHandler::getInstance()->HiddenMenuPackageEdit->getUrl() ?>"
                });
            })
        </script>
		<?php return ob_get_clean();
	}

	public static function getRequiredContent(): array {
		return [
			new ContentDependency(
				ContentDependency::INLINE_SCRIPT_HANDLE,
				self::inlineScript(),
				ContentDependencyType::Script,
				ContentDependencyLoadingStyle::InlineContent,
				[
					ScriptCollection::JQUERY
				]
			)
		];
	}
}