<?php

namespace KlintDev\WPBooking\Views\Settings;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Form;
use KlintDev\WPBooking\Components\Input;
use KlintDev\WPBooking\Controllers\BlockedDurationController;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationCreateRequest;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationGetRequest;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationUpdateRequest;
use KlintDev\WPBooking\MenuHandler;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\BlockedDurationService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;
use ReflectionException;

class EditBlockedDurationView extends PartialPage
{
    protected static int $blocked_duration_id;
    protected static string $blocked_duration_hidden_input_id = "blocked-duration-id";
    protected static string $active_checkbox_id = "blocked-duration-active";
    protected static string $form_id = "blocked-duration-form";
    protected static string $fieldset_id = "blocked-duration-fieldset";
    protected static string $alert_container_id = "blocked-duration-form-alert";
    protected static string $cancel_button_id = "blocked-duration-form-cancel";
    protected static string $submit_button_id = "blocked-duration-form-submit";
    protected static string $description_id = "blocked-duration-description";
    protected static string $start_date_id = "blocked-duration-start-date";
    protected static string $end_date_id = "blocked-duration-end-date";
    protected static string $chk_monday_id = "blocked-duration-chk-monday";
    protected static string $chk_tuesday_id = "blocked-duration-chk-tuesday";
    protected static string $chk_wednesday_id = "blocked-duration-chk-wednesday";
    protected static string $chk_thursday_id = "blocked-duration-chk-thursday";
    protected static string $chk_friday_id = "blocked-duration-chk-friday";
    protected static string $chk_saturday_id = "blocked-duration-chk-saturday";
    protected static string $chk_sunday_id = "blocked-duration-chk-sunday";
    protected static string $delete_btn_id = "blocked-duration-delete";

    protected static EditBlockedDurationView $instance;

    public static function getInstance(): object
    {
        if (!isset(self::$instance)) {
            self::$instance = new EditBlockedDurationView();
        }
        return self::$instance;
    }

	/**
	 * @throws ReflectionException
	 */
	public static function render(): string|false
    {
        if (isset($_GET["id"])) {
            self::$blocked_duration_id = $_GET["id"];
            $blockedDuration = BlockedDurationService::getBlockedDuration(self::$blocked_duration_id);
        } else {
            $blockedDuration = BlockedDurationGetRequest::createDTO();
        }
        ob_start();

        ?>
        <div class="row">
            <div class="col-6">
                <?php

                echo Container::beginDashboardContainer();

                echo Container::header(
                    (isset(self::$blocked_duration_id) ? "Opdater" : "Opret") . " låst periode",
                    false,
                );

                echo Form::beginForm(
                    self::$form_id,
                    self::$fieldset_id,
                    true,
                    self::$alert_container_id,
                    false
                );
                ?>
                <input type="hidden" id="<?= self::$blocked_duration_hidden_input_id ?>"
                    <?php if (isset(self::$blocked_duration_id)) { ?>
                        value="<?= self::$blocked_duration_id ?>"
                    <?php } ?>
                >
                <?php
                echo Input::checkbox(
                    self::$active_checkbox_id,
                    "Aktiv",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::ACTIVE_BOOL)
                );
                ?>
                <div class="row">
                    <div class="col-6">
                        <?= Input::text(
                            self::$start_date_id,
                            "Start dato",
                            $blockedDuration->getPropertyValue(BlockedDurationGetRequest::START_DATE_STR),
                            null,
                            null,
                            null,
                            null,
                            "date"
                        );
                        ?>
                    </div>
                    <div class="col-6">
                        <?= Input::text(
                            self::$end_date_id,
                            "Slut dato",
                            $blockedDuration->getPropertyValue(BlockedDurationGetRequest::END_DATE_STR),
                            "Hvis ikke slut dato angives, kører perioden til den slettes eller en slut dato angives.",
                            null,
                            null,
                            null,
                            "date",
                            false
                        );
                        ?>
                    </div>
                </div>
                <?php

                echo Input::textarea(
                    self::$description_id,
                    "Beskrivelse",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::DESCRIPTION_STR),
                    "Beskrivelsen vil ikke blive vist under booking.",
                    3,
                    1000,
                );

                ?>
                <label>
                    Vælg nedenfor hvilke dage der skal være utilgængelige i perioden:
                </label>
                <?php

                echo Input::checkbox(
                    self::$chk_monday_id,
                    "Mandag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::MONDAY_BOOL)
                );
                echo Input::checkbox(
                    self::$chk_tuesday_id,
                    "Tirsdag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::TUESDAY_BOOL)
                );
                echo Input::checkbox(
                    self::$chk_wednesday_id,
                    "Onsdag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::WEDNESDAY_BOOL)
                );
                echo Input::checkbox(
                    self::$chk_thursday_id,
                    "Torsdag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::THURSDAY_BOOL)
                );
                echo Input::checkbox(
                    self::$chk_friday_id,
                    "Fredag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::FRIDAY_BOOL)
                );
                echo Input::checkbox(
                    self::$chk_saturday_id,
                    "Lørdag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::SATURDAY_BOOL)
                );
                echo Input::checkbox(
                    self::$chk_sunday_id,
                    "Søndag",
                    $blockedDuration->getPropertyValue(BlockedDurationGetRequest::SUNDAY_BOOL)
                );

                echo form::formControls(
                    self::$cancel_button_id,
                    self::$submit_button_id,
                    isset(self::$blocked_duration_id) ? "Opdater" : "Opret",
                    "Annuller",
                    false,
                    isset(self::$blocked_duration_id) ? self::$delete_btn_id : null
                );

                echo form::endForm();

                echo Container::endDashboardContainer();

                ?>
            </div>
        </div>
        <?php


        return ob_get_clean();
    }

    protected static function inlineScript(): string|false
    {
        ob_start(); ?>
        <script>
            jQuery(() => {
                const blockedDurationId = jQuery("#<?= self::$blocked_duration_hidden_input_id ?>").val();
                const deleteEndpoint = new URL("<?= BlockedDurationController::getEndpointUrl(BlockedDurationController::DELETE_ENDPOINT) ?>");
                deleteEndpoint.searchParams.append("id", blockedDurationId);
                const updateEndpoint = new URL("<?= BlockedDurationController::getEndpointUrl(BlockedDurationController::UPDATE_ENDPOINT) ?>");
                const createEndpoint = new URL("<?= BlockedDurationController::getEndpointUrl(BlockedDurationController::CREATE_ENDPOINT) ?>");

                const controller = new FormController(
                    "<?= wp_create_nonce("wp_rest") ?>",
                    new FormElementSelectorOptions("<?= self::$form_id ?>"),
                    new FormActionRedirectOptions("<?= self::$cancel_button_id ?>", {
                        redirectTo: "<?= MenuHandler::getInstance()->SubMenuSettings->getUrl() ?>"
                    }),
                    new FormActionRedirectOptions("<?= self::$submit_button_id ?>", {
                        endpoint: blockedDurationId ? updateEndpoint.toJSON() : createEndpoint.toString(),
                        dtoCreator: blockedDurationId ? dtoUpdate : dtoCreate,
                    }),
                    null,
                    new FormActionRedirectOptions("<?= self::$delete_btn_id ?>", {
                        endpoint: deleteEndpoint.toString(),
                        redirectTo: "<?= MenuHandler::getInstance()->SubMenuSettings->getUrl() ?>"
                    }),
                    new AlertFormOptions("")
                );

                function dtoCreate() {
                    const dto = {
                        <?= BlockedDurationCreateRequest::ACTIVE_BOOL ?>: jQuery("#<?= self::$active_checkbox_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::DESCRIPTION_STR ?>: jQuery("#<?= self::$description_id ?>").val(),
                        <?= BlockedDurationCreateRequest::START_DATE_STR ?>: jQuery("#<?= self::$start_date_id ?>").val(),
                        <?= BlockedDurationCreateRequest::END_DATE_STR ?>: jQuery("#<?= self::$end_date_id ?>").val(),
                        <?= BlockedDurationCreateRequest::MONDAY_BOOL ?>: jQuery("#<?= self::$chk_monday_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::TUESDAY_BOOL ?>: jQuery("#<?= self::$chk_tuesday_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::WEDNESDAY_BOOL ?>: jQuery("#<?= self::$chk_wednesday_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::THURSDAY_BOOL ?>: jQuery("#<?= self::$chk_thursday_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::FRIDAY_BOOL ?>: jQuery("#<?= self::$chk_friday_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::SATURDAY_BOOL ?>: jQuery("#<?= self::$chk_saturday_id ?>").is(":checked"),
                        <?= BlockedDurationCreateRequest::SUNDAY_BOOL ?>: jQuery("#<?= self::$chk_sunday_id ?>").is(":checked"),
                    }
                    return ObjectParser.makeEmptyStringsNull(dto);
                }


                function dtoUpdate() {
                    const dto = {
                        <?= BlockedDurationUpdateRequest::ID_INT ?>: jQuery("#<?= self::$blocked_duration_hidden_input_id ?>").val(),
                        <?= BlockedDurationUpdateRequest::ACTIVE_BOOL ?>: jQuery("#<?= self::$active_checkbox_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::DESCRIPTION_STR ?>: jQuery("#<?= self::$description_id ?>").val(),
                        <?= BlockedDurationUpdateRequest::START_DATE_STR ?>: jQuery("#<?= self::$start_date_id ?>").val(),
                        <?= BlockedDurationUpdateRequest::END_DATE_STR ?>: jQuery("#<?= self::$end_date_id ?>").val(),
                        <?= BlockedDurationUpdateRequest::MONDAY_BOOL ?>: jQuery("#<?= self::$chk_monday_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::TUESDAY_BOOL ?>: jQuery("#<?= self::$chk_tuesday_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::WEDNESDAY_BOOL ?>: jQuery("#<?= self::$chk_wednesday_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::THURSDAY_BOOL ?>: jQuery("#<?= self::$chk_thursday_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::FRIDAY_BOOL ?>: jQuery("#<?= self::$chk_friday_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::SATURDAY_BOOL ?>: jQuery("#<?= self::$chk_saturday_id ?>").is(":checked"),
                        <?= BlockedDurationUpdateRequest::SUNDAY_BOOL ?>: jQuery("#<?= self::$chk_sunday_id ?>").is(":checked"),
                    }
                    return ObjectParser.makeEmptyStringsNull(dto);
                }
            })
        </script>
        <?php return ob_get_clean();
    }

    public static function getRequiredContent(): array
    {
        return [
            new ContentDependency(ContentDependency::INLINE_SCRIPT_HANDLE, self::inlineScript(), ContentDependencyType::Script, ContentDependencyLoadingStyle::InlineContent,
                [
                    "jquery-core",
                    ScriptCollection::FORM_CONTROLLER,
                    ScriptCollection::OBJECT_PARSER
                ])
        ];
    }
}