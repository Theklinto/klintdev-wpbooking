<?php

namespace KlintDev\WPBooking\Views\Package;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Form;
use KlintDev\WPBooking\Components\Input;
use KlintDev\WPBooking\Controllers\PackageController;
use KlintDev\WPBooking\DTO\Package\PackageCreateRequest;
use KlintDev\WPBooking\DTO\Package\PackageGetRequest;
use KlintDev\WPBooking\DTO\Package\PackageUpdateRequest;
use KlintDev\WPBooking\MenuHandler;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\PackageService;
use KlintDev\WPBooking\Services\RoomService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;
use ReflectionException;

class PackageEditView extends PartialPage {

	protected static self $instance;

	public static function getInstance(): object {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	protected const PREFIX = "package-edit-";
	protected const FORM_ID = self::PREFIX . "form";
	protected const FIELD_ID = self::PREFIX . "field-id";
	protected const HIDDEN_ID_INPUT = self::PREFIX . "hidden-id";
	protected const ACTIVE_INPUT_ID = self::PREFIX . "active-id";
	protected const NAME_INPUT_ID = self::PREFIX . "name-id";
	protected const ROOM_INPUT_ID = self::PREFIX . "room-id";
	protected const PRICE_INPUT_ID = self::PREFIX . "price-id";
	protected const DEPOSIT_INPUT_ID = self::PREFIX . "deposit-id";
	protected const DURATION_INPUT_ID = self::PREFIX . "duration-id";
	protected const MONDAY_ACTIVE_ID = self::PREFIX . "monday-active-id";
	protected const TUESDAY_ACTIVE_ID = self::PREFIX . "tuesday-active-id";
	protected const WEDNESDAY_ACTIVE_ID = self::PREFIX . "wednesday-active-id";
	protected const THURSDAY_ACTIVE_ID = self::PREFIX . "thursday-active-id";
	protected const FRIDAY_ACTIVE_ID = self::PREFIX . "friday-active-id";
	protected const SATURDAY_ACTIVE_ID = self::PREFIX . "saturday-active-id";
	protected const SUNDAY_ACTIVE_ID = self::PREFIX . "sunday-active-id";
	protected const SUBMIT_BTN_ID = self::PREFIX . "submit-btn";
	protected const DELETE_BTN_ID = self::PREFIX . "delete-btn";
	protected const CANCEL_BTN_ID = self::PREFIX . "cancel-btn";
	protected const START_TIME_ID = self::PREFIX . "start-time-id";

	/**
	 * @throws ReflectionException
	 */
	public static function render(): string|false {
		$packageId = $_GET["id"] ?? null;
		$package   = $packageId ? PackageService::getPackageById( $packageId ) : PackageCreateRequest::createDTO();
		if ( ! isset( $package ) && isset( $packageId ) ) {
			return "PackageId not found";
		}

		/** @var array<string, string> $rooms */
		$rooms = [ "Vælg lokale" => null ] + RoomService::getRoomSelectOptions();

		ob_start() ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
					<?php
					echo Container::beginDashboardContainer();

					echo Container::header(
						( isset( $packageId ) ? "Opdater" : "Opret" ) . " pakke",
						false,
					);

					echo Form::beginForm(
						self::FORM_ID,
						self::FIELD_ID,
						true,
						null,
						false
					);

					echo Input::hiddenInput(
						self::HIDDEN_ID_INPUT,
						$packageId
					);

					echo Input::checkbox(
						self::ACTIVE_INPUT_ID,
						"Aktiv",
						$package->getPropertyValue( PackageGetRequest::ACTIVE_BOOL )
					);

					echo Input::text(
						self::NAME_INPUT_ID,
						"Navn",
						$package->getPropertyValue( PackageGetRequest::NAME_STR )
					);

					echo Input::select(
						self::ROOM_INPUT_ID,
						"Lokale",
						$rooms,
						$package->getPropertyValue( PackageGetRequest::ROOM_ID_INT )
					);
					?>
                    <div class="row">
                        <div class="col-6">
							<?= Input::text(
								self::PRICE_INPUT_ID,
								"Pris",
								$package->getPropertyValue( PackageGetRequest::PRICE_FLOAT ),
								null,
								null,
								null,
								null,
								"number"
							) ?>
                        </div>
                        <div class="col-6">
							<?= Input::text(
								self::DEPOSIT_INPUT_ID,
								"Depositum",
								$package->getPropertyValue( PackageGetRequest::DEPOSIT_FLOAT ),
								null,
								null,
								null,
								null,
								"number"
							) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
							<?= Input::text(
								self::START_TIME_ID,
								"Start tidspunkt",
								$package->getPropertyValue( PackageGetRequest::START_TIME_STR ),
								null,
								null,
								null,
								null,
								"time"
							);
							?>
                        </div>
                        <div class="col-6">
							<?= Input::text(
								self::DURATION_INPUT_ID,
								"Udlejningslængde i timer",
								$package->getPropertyValue( PackageGetRequest::DURATION_IN_HOURS_INT ),
								null,
								null,
								null,
								null,
								"number"
							) ?>
                        </div>
                    </div>
                    <label>Vælg nedenfor hvilke dage pakken skal kunne bookes på:</label>
					<?php

					echo Input::checkbox(
						self::MONDAY_ACTIVE_ID,
						"Mandag",
						$package->getPropertyValue( PackageGetRequest::MONDAY_BOOL )
					);
					echo Input::checkbox(
						self::TUESDAY_ACTIVE_ID,
						"Tirsdag",
						$package->getPropertyValue( PackageGetRequest::TUESDAY_BOOL )
					);
					echo Input::checkbox(
						self::WEDNESDAY_ACTIVE_ID,
						"Onsdag",
						$package->getPropertyValue( PackageGetRequest::WEDNESDAY_BOOL )
					);
					echo Input::checkbox(
						self::THURSDAY_ACTIVE_ID,
						"Torsdag",
						$package->getPropertyValue( PackageGetRequest::THURSDAY_BOOL )
					);
					echo Input::checkbox(
						self::FRIDAY_ACTIVE_ID,
						"Fredag",
						$package->getPropertyValue( PackageGetRequest::FRIDAY_BOOL )
					);
					echo Input::checkbox(
						self::SATURDAY_ACTIVE_ID,
						"Lørdag",
						$package->getPropertyValue( PackageGetRequest::SATURDAY_BOOL )
					);
					echo Input::checkbox(
						self::SUNDAY_ACTIVE_ID,
						"Søndag",
						$package->getPropertyValue( PackageGetRequest::SUNDURDAY_BOOL )
					);

					echo Form::formControls(
						self::CANCEL_BTN_ID,
						self::SUBMIT_BTN_ID,
						isset( $packageId ) ? "Opdater" : "Opret",
						"Annuller",
						false,
						self::DELETE_BTN_ID,
					);

					echo Form::endForm();

					echo Container::endDashboardContainer();
					?>
                </div>
            </div>
        </div>

		<?php return ob_get_clean();
	}

	protected static function inlineScript(): string|false {
		ob_start() ?>
        <script>
            jQuery(() => {
                const packageId = jQuery("#<?= self::HIDDEN_ID_INPUT ?>").val();

                const updateEndpoint = "<?= PackageController::getEndpointUrl( PackageController::UPDATE_ENDPOINT ) ?>";
                const createEndpoint = "<?= PackageController::getEndpointUrl( PackageController::CREATE_ENDPOINT ) ?>";
                const deleteEndpoint = new URL("<?= PackageController::getEndpointUrl( PackageController::DELETE_ENDPOINT ) ?>");
                const redirectToUrl = "<?= MenuHandler::getInstance()->SubMenuPackages->getUrl() ?>";
                deleteEndpoint.searchParams.append("id", packageId);

                new FormController(
                    "<?= wp_create_nonce( "wp_rest" ) ?>",
                    new FormElementSelectorOptions("<?= self::FORM_ID ?>"),
                    new FormActionRedirectOptions("<?= self::CANCEL_BTN_ID ?>", {
                        redirectTo: redirectToUrl
                    }),
                    new FormActionRedirectOptions("<?= self::SUBMIT_BTN_ID ?>", {
                        redirectTo: redirectToUrl,
                        endpoint: packageId ? updateEndpoint : createEndpoint,
                        dtoCreator: packageId ? dtoUpdate : dtoCreate
                    }),
                    null,
                    new FormActionRedirectOptions("<?= self::DELETE_BTN_ID ?>", {
                        redirectTo: redirectToUrl,
                        endpoint: deleteEndpoint
                    }),
                    new AlertFormOptions("Pakke " + (packageId ? "opdateret" : "oprettet"))
                );

                function dtoUpdate() {
                    const dto = {
						<?= PackageUpdateRequest::ID_INT ?>: ObjectParser.getValue("<?= self::HIDDEN_ID_INPUT ?>"),
	                    <?= PackageCreateRequest::ACTIVE_BOOL ?>: ObjectParser.getValue("<?= self::ACTIVE_INPUT_ID ?>"),
	                    <?= PackageCreateRequest::NAME_STR ?>: ObjectParser.getValue("<?= self::NAME_INPUT_ID ?>"),
	                    <?= PackageCreateRequest::ROOM_ID_INT ?>: ObjectParser.getValue("<?= self::ROOM_INPUT_ID ?>", "number"),
	                    <?= PackageCreateRequest::PRICE_FLOAT ?>: ObjectParser.getValue("<?= self::PRICE_INPUT_ID ?>"),
	                    <?= PackageCreateRequest::DEPOSIT_FLOAT ?>: ObjectParser.getValue("<?= self::DEPOSIT_INPUT_ID ?>"),
	                    <?= PackageCreateRequest::START_TIME_STR ?>: ObjectParser.getValue("<?= self::START_TIME_ID ?>"),
	                    <?= PackageCreateRequest::DURATION_IN_HOURS_INT ?>: ObjectParser.getValue("<?= self::DURATION_INPUT_ID ?>"),
	                    <?= PackageCreateRequest::MONDAY_BOOL ?>: ObjectParser.getValue("<?= self::MONDAY_ACTIVE_ID ?>"),
	                    <?= PackageCreateRequest::TUESDAY_BOOL ?>: ObjectParser.getValue("<?= self::TUESDAY_ACTIVE_ID ?>"),
	                    <?= PackageCreateRequest::WEDNESDAY_BOOL ?>: ObjectParser.getValue("<?= self::WEDNESDAY_ACTIVE_ID ?>"),
	                    <?= PackageCreateRequest::THURSDAY_BOOL ?>: ObjectParser.getValue("<?= self::THURSDAY_ACTIVE_ID ?>"),
	                    <?= PackageCreateRequest::FRIDAY_BOOL ?>: ObjectParser.getValue("<?= self::FRIDAY_ACTIVE_ID ?>"),
	                    <?= PackageCreateRequest::SATURDAY_BOOL ?>: ObjectParser.getValue("<?= self::SATURDAY_ACTIVE_ID ?>"),
	                    <?= PackageCreateRequest::SUNDURDAY_BOOL ?>: ObjectParser.getValue("<?= self::SUNDAY_ACTIVE_ID ?>"),
                    }
                    return ObjectParser.makeEmptyStringsNull(dto);
                }

                function dtoCreate() {
                    return {
						<?= PackageCreateRequest::ACTIVE_BOOL ?>: ObjectParser.getValue("<?= self::ACTIVE_INPUT_ID ?>"),
						<?= PackageCreateRequest::NAME_STR ?>: ObjectParser.getValue("<?= self::NAME_INPUT_ID ?>"),
						<?= PackageCreateRequest::ROOM_ID_INT ?>: ObjectParser.getValue("<?= self::ROOM_INPUT_ID ?>", "number"),
						<?= PackageCreateRequest::PRICE_FLOAT ?>: ObjectParser.getValue("<?= self::PRICE_INPUT_ID ?>"),
						<?= PackageCreateRequest::DEPOSIT_FLOAT ?>: ObjectParser.getValue("<?= self::DEPOSIT_INPUT_ID ?>"),
						<?= PackageCreateRequest::START_TIME_STR ?>: ObjectParser.getValue("<?= self::START_TIME_ID ?>"),
						<?= PackageCreateRequest::DURATION_IN_HOURS_INT ?>: ObjectParser.getValue("<?= self::DURATION_INPUT_ID ?>"),
						<?= PackageCreateRequest::MONDAY_BOOL ?>: ObjectParser.getValue("<?= self::MONDAY_ACTIVE_ID ?>"),
						<?= PackageCreateRequest::TUESDAY_BOOL ?>: ObjectParser.getValue("<?= self::TUESDAY_ACTIVE_ID ?>"),
						<?= PackageCreateRequest::WEDNESDAY_BOOL ?>: ObjectParser.getValue("<?= self::WEDNESDAY_ACTIVE_ID ?>"),
						<?= PackageCreateRequest::THURSDAY_BOOL ?>: ObjectParser.getValue("<?= self::THURSDAY_ACTIVE_ID ?>"),
						<?= PackageCreateRequest::FRIDAY_BOOL ?>: ObjectParser.getValue("<?= self::FRIDAY_ACTIVE_ID ?>"),
						<?= PackageCreateRequest::SATURDAY_BOOL ?>: ObjectParser.getValue("<?= self::SATURDAY_ACTIVE_ID ?>"),
						<?= PackageCreateRequest::SUNDURDAY_BOOL ?>: ObjectParser.getValue("<?= self::SUNDAY_ACTIVE_ID ?>"),
                    }
                }
            });
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
					ScriptCollection::JQUERY,
					ScriptCollection::FORM_CONTROLLER,
					ScriptCollection::OBJECT_PARSER
				]
			)
		];
	}
}