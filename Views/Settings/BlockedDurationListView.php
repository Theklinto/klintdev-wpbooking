<?php

namespace KlintDev\WPBooking\Views\Settings;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Formatter;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationListRequest;
use KlintDev\WPBooking\MenuHandler;
use KlintDev\WPBooking\Services\BlockedDurationService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;
use ReflectionException;

class BlockedDurationListView extends PartialPage
{
    protected const ADD_BTN_ID = "add-blocked-duration-btn";

	/**
	 * @throws ReflectionException
	 */
	public static function render(): string|false
    {
        ob_start();

        $blockedDurations = BlockedDurationService::getBlockedDurations();

        echo Container::beginDashboardContainer();

        echo Container::header(
            "Låste perioder",
            true,
            self::ADD_BTN_ID,
            "Tilføj"
        ); ?>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <td style="width: 5%; text-align: center">Aktiv</td>
                <td style="width: 15%">Startdato</td>
                <td style="width: 15%">Slutdato</td>
                <td style="width: 3%; text-align: center">M</td>
                <td style="width: 3%; text-align: center">T</td>
                <td style="width: 3%; text-align: center">O</td>
                <td style="width: 3%; text-align: center">T</td>
                <td style="width: 3%; text-align: center">F</td>
                <td style="width: 3%; text-align: center">L</td>
                <td style="width: 3%; text-align: center">S</td>
                <td style="width: 59%">Beskrivelse</td>
                <td style="width: 10%"></td>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($blockedDurations) === 0) { ?>
                <tr>
                    <td colspan="100" style="text-align: center">
                        <span class="text-secondary">
                            Ingen låste perioder fundet
                        </span>
                    </td>
                </tr>
            <?php }

            foreach ($blockedDurations as $blockedDuration) {
                ?>
                <tr>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::ACTIVE_BOOL)) ?></td>
                    <td><?= Formatter::formatDate($blockedDuration->getPropertyValue(BlockedDurationListRequest::START_DATE_STR)) ?></td>
                    <td><?= Formatter::formatDate($blockedDuration->getPropertyValue(BlockedDurationListRequest::END_DATE_STR)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::MONDAY_BOOL)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::TUESDAY_BOOL)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::WEDNESDAY_BOOL)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::THURSDAY_BOOL)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::FRIDAY_BOOL)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::SATURDAY_BOOL)) ?></td>
                    <td><?= Formatter::formatBooleanIcon($blockedDuration->getPropertyValue(BlockedDurationListRequest::SUNDAY_BOOL)) ?></td>
                    <td><?= Formatter::maxLengthText($blockedDuration->getPropertyValue(BlockedDurationListRequest::DESCRIPTION_STR), 100) ?></td>
                    <td style="text-align: center; vertical-align: middle">
                        <a
                                href="<?= MenuHandler::getInstance()->HiddenEditBlockedDuration->getUrl(["id" => $blockedDuration->getPropertyValue(BlockedDurationListRequest::ID_INT)]) ?>"
                                class="btn btn-outline-primary"
                                style="border: 0">
                            <span class="dashicons dashicons-edit-large"></span>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?php echo Container::endDashboardContainer();

        return ob_get_clean();
    }

    public static function inlineScript(): string|false
    {
        ob_start(); ?>
        <script>
            jQuery(() => {
                jQuery("#<?= self::ADD_BTN_ID ?>").on("click", () => {
                    window.location.href = '<?= MenuHandler::getInstance()->HiddenEditBlockedDuration->getUrl() ?>';
                });
            });
        </script>
        <?php
        return ob_get_clean(); // TODO: Change the autogenerated stub
    }

    public static function getRequiredContent(): array
    {
        return [
            new ContentDependency(ContentDependency::INLINE_SCRIPT_HANDLE, self::inlineScript(), ContentDependencyType::Script, ContentDependencyLoadingStyle::InlineContent, ["jquery-core"])
        ];
    }

    protected static BlockedDurationListView $instance;

    public static function getInstance(): object
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}