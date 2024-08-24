<?php

namespace KlintDev\WPBooking\Views\Room;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Formatter;
use KlintDev\WPBooking\DTO\Room\RoomListRequest;
use KlintDev\WPBooking\MenuHandler;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\RoomService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;

class RoomListView extends PartialPage
{
    protected static RoomListView $instance;

    public static function getInstance(): object
    {
        if (!isset(static::$instance)) {
            self::$instance = new self();
        }
        return static::$instance;
    }

    protected const PREFIX = "room-list-";
    protected const ADD_BTN_ID = self::PREFIX . "add";

    public static function render(): string|false
    {
        ob_start();

        $rooms = RoomService::getRooms();

        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">

                    <?php
                    echo Container::beginDashboardContainer();
                    echo Container::header(
                        "Lokaler",
                        true,
                        self::ADD_BTN_ID,
                        "Tilføj"
                    );
                    ?>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width: 30%;">Billede</th>
                            <th style="width: 30%;">Navn</th>
                            <th style="width: 40%;">Beskrivelse</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rooms as $room) { ?>
                            <tr>
                                <td>
                                    <img
                                            style="max-height: 200px; max-width: 200px; object-fit: scale-down;"
                                            src="<?= wp_get_attachment_image_url($room->getPropertyValue(RoomListRequest::IMAGE_POST_ID_INT)) ?>"
                                    >
                                </td>
                                <td><?= $room->getPropertyValue(RoomListRequest::NAME_STR) ?></td>
                                <td><?= Formatter::maxLengthText($room->getPropertyValue(RoomListRequest::DESCRIPTION_STR), 100) ?></td>
                                <td style="text-align: center; vertical-align: middle">
                                    <a
                                            href="<?= MenuHandler::getInstance()->HiddenMenuRoomsEdit->getUrl(["id" => $room->getPropertyValue(RoomListRequest::ID_INT)]) ?>"
                                            class="btn btn-outline-primary"
                                            style="border: 0">
                                        <span class="dashicons dashicons-edit-large"></span>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?= Container::endDashboardContainer(); ?>
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    }

    protected static function inlineScript(): string|false
    {
        ob_start(); ?>
        <script>
            jQuery(() => {
                jQuery("#<?= self::ADD_BTN_ID ?>")
                    .on("click", () => {
                        window.location.href = "<?= MenuHandler::getInstance()->HiddenMenuRoomsEdit->getUrl() ?>";
                    });
            });
        </script>
        <?php return ob_get_clean();
    }

    public static function getRequiredContent(): array
    {
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