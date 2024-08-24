<?php

namespace KlintDev\WPBooking\Views\Settings;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Form;
use KlintDev\WPBooking\Components\Input;
use KlintDev\WPBooking\Controllers\SettingsController;
use KlintDev\WPBooking\DTO\StripeSettings\UpdateStripeSettingsRequest;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\OptionService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;


class StripeSettingsView extends PartialPage
{
    protected static string $apiKeyId = "stripe-api-key";
    protected static string $apiKeySecret = "stripe-api-secret";
    protected static string $editBtnId = "stripe-edit-btn";
    protected static string $cancelBtnId = "stripe-cancel-btn";
    protected static string $submitBtnId = "stripe-submit-btn";
    protected static string $formId = "stripe-form";
    protected static string $alertContainerId = "stripe-alert-container";
    protected static string $fieldsetId = "stripe-fieldset";
    protected static StripeSettingsView $instance;

    public static function render(): string|false
    {
        ob_start();
        echo Container::beginDashboardContainer();
        echo Container::header(
            "Stripe opsætning",
            true,
            self::$editBtnId,
            "Rediger"
        );
        echo Form::beginForm(
            self::$formId,
            self::$fieldsetId,
            true,
            self::$alertContainerId,
            true
        );
        echo Input::text(
            self::$apiKeyId,
            "API Key",
            OptionService::stripeApiKey(),
            null,
            0,
            250
        );
        echo Input::text(
            self::$apiKeySecret,
            "API Secret",
            OptionService::stripeApiSecret(),
            null,
            0,
            250
        );
        echo Form::formControls(
            self::$cancelBtnId,
            self::$submitBtnId
        );

        echo Form::endForm();
        echo Container::endDashboardContainer();
        return ob_get_clean();
    }

    protected static function inlineScript(): string
    {
        ob_start();
        ?>
        <script>
            jQuery(() => {

                const controller = new FormController(
                    "<?= wp_create_nonce("wp_rest") ?>",
                    new FormElementSelectorOptions("<?= self::$formId ?>"),
                    new FormActionRedirectOptions("<?= self::$cancelBtnId ?>"),
                    new FormActionRedirectOptions("<?= self::$submitBtnId ?>", {
                        endpoint: "<?= SettingsController::getEndpointUrl(SettingsController::UPDATE_STRIPE_SETTINGS_ENDPOINT) ?>",
                        dtoCreator: dtoCreator
                    }),
                    new FormActionOptions("<?= self::$editBtnId ?>"),
                    null,
                    new AlertFormOptions("Stripe indstillinger blev opdateret")
                )

                /**
                 *
                 * @returns {{}}
                 */
                function dtoCreator() {
                    return {
                        <?= UpdateStripeSettingsRequest::API_KEY ?>: jQuery("#<?= self::$apiKeyId ?>").val(),
                        <?= UpdateStripeSettingsRequest::API_SECRET ?>: jQuery("#<?= self::$apiKeySecret ?>").val(),
                    };
                }
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public static function getRequiredContent(): array
    {
        return [
            new ContentDependency(ContentDependency::INLINE_SCRIPT_HANDLE, self::inlineScript(), ContentDependencyType::Script, ContentDependencyLoadingStyle::InlineContent, ["jquery-core"]),
            new ContentDependency(ScriptCollection::FORM_CONTROLLER, "", ContentDependencyType::Script, ContentDependencyLoadingStyle::RegisteredContent),
        ];
    }

    public static function getInstance(): object
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}