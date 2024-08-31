<?php

namespace KlintDev\WPBooking\Views\Settings;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Form;
use KlintDev\WPBooking\Components\Formatter;
use KlintDev\WPBooking\Components\Input;
use KlintDev\WPBooking\Controllers\SettingsController;
use KlintDev\WPBooking\DTO\Deposit\GetDepositSettingsRequest;
use KlintDev\WPBooking\DTO\Deposit\GetStripeDepositSettings;
use KlintDev\WPBooking\DTO\Deposit\UpdateDepositSettingsRequest;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\OptionService;
use KlintDev\WPBooking\Services\StripeService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;

class DepositSettingsView extends PartialPage {

	protected const PREFIX = "deposit-settings-";
	protected const EDIT_BTN_ID = self::PREFIX . "edit-btn";
	protected const FORM_ID = self::PREFIX . "form";
	protected const DEPOSIT_FIELD_ID = self::PREFIX . "deposit-field";
	protected const SUBMIT_BTN_ID = self::PREFIX . "submit-btn";
	protected const CANCEL_BTN_ID = self::PREFIX . "cancel-btn";

	protected static self $instance;

	public static function getInstance(): object {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function render(): string|false {

		$settings              = OptionService::getDepositSettings();
		$stripeDepositSettings = StripeService::getStripeDepositSettings();

		ob_start();

		echo Container::beginDashboardContainer();
		echo Container::header(
			"Depositum",
			true,
			self::EDIT_BTN_ID,
			"Rediger",
		);
		echo Form::beginForm(
			self::FORM_ID,
			"",
			true,
			null,
			true
		);

		if ( $stripeDepositSettings !== null ) {
			?>
            <table class="table">
                <thead>
                <tr>
                    <th colspan="2">Oplysninger fra Stripe</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Produktnavn:</td>
                    <td><?= $stripeDepositSettings->getPropertyValue( GetStripeDepositSettings::DEPOSIT_PRODUCT_NAME_STR ) ?></td>
                </tr>
                <tr>
                    <td>Pris:</td>
                    <td><?= Formatter::formatNumber( $stripeDepositSettings->getPropertyValue( GetStripeDepositSettings::DEPOSIT_PRICE_FLOAT ) ) ?></td>
                </tr>
                </tbody>
            </table>
			<?php
		}

		echo Input::text(
			self::DEPOSIT_FIELD_ID,
			"Depositum",
			number_format( $settings->getPropertyValue( GetDepositSettingsRequest::DEPOSIT_FLAOT ), 2, ".", "" ),
			"Angiv standard depositum der vil blive brugt ved bookinger.",
			null,
			null,
			null,
			"number"
		);

		echo Form::formControls(
			self::CANCEL_BTN_ID,
			self::SUBMIT_BTN_ID
		);
		echo Form::endForm();
		echo Container::endDashboardContainer();

		return ob_get_clean();
	}

	protected static function inlineScript(): string|false {
		ob_start(); ?>
        <script>
            jQuery(() => {

                const c = new FormController(
                    "<?= wp_create_nonce( "wp_rest" ) ?>",
                    new FormElementSelectorOptions("<?= self::FORM_ID ?>"),
                    new FormActionRedirectOptions("<?= self::CANCEL_BTN_ID ?>"),
                    new FormActionRedirectOptions("<?= self::SUBMIT_BTN_ID ?>", {
                        dtoCreator: dtoUpdate,
                        endpoint: new URL("<?= SettingsController::getEndpointUrl( SettingsController::UPDATE_DEPOSIT_ENDPOINT ) ?>")
                    }),
                    new FormActionOptions("<?= self::EDIT_BTN_ID ?>"),
                    null,
                    new AlertFormOptions("Depositum opdateret.")
                );

                function dtoUpdate() {
                    return {
                        "<?= UpdateDepositSettingsRequest::DEPOSIT_FLAOT ?>": ObjectParser.getValue("<?= self::DEPOSIT_FIELD_ID ?>")
                    }
                }

                ((selector) => {
                    selector.on("change", () => {
                        selector.val(parseFloat(selector.val()).toFixed(2));
                    })
                })(jQuery("#<?= self::DEPOSIT_FIELD_ID ?>"));
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
					ScriptCollection::OBJECT_PARSER,
					ScriptCollection::FORM_CONTROLLER,
					ScriptCollection::JQUERY
				]
			)
		];
	}
}