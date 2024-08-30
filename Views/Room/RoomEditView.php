<?php

namespace KlintDev\WPBooking\Views\Room;

use KlintDev\WPBooking\Components\Container;
use KlintDev\WPBooking\Components\Form;
use KlintDev\WPBooking\Components\Input;
use KlintDev\WPBooking\Controllers\RoomController;
use KlintDev\WPBooking\DTO\Room\RoomCreateRequest;
use KlintDev\WPBooking\DTO\Room\RoomGetRequest;
use KlintDev\WPBooking\DTO\Room\RoomUpdateRequest;
use KlintDev\WPBooking\MenuHandler;
use KlintDev\WPBooking\Scripts\ScriptCollection;
use KlintDev\WPBooking\Services\RoomService;
use KlintDev\WPBooking\Views\ContentDependency;
use KlintDev\WPBooking\Views\ContentDependencyLoadingStyle;
use KlintDev\WPBooking\Views\ContentDependencyType;
use KlintDev\WPBooking\Views\PartialPage;
use ReflectionException;

class RoomEditView extends PartialPage {

	protected static self $instance;

	public static function getInstance(): object {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected const PREFIX = "edit-room-";
	protected const HIDDEN_ROOM_ID = self::PREFIX . "hidden-id";
	protected const FORM_ID = self::PREFIX . "form";
	protected const FIELDSET_ID = self::PREFIX . "fieldset";
	protected const ALERT_CONTAINER_ID = self::PREFIX . "alert-container";
	protected const NAME_ID = self::PREFIX . "name";
	protected const DESCRIPTION_ID = self::PREFIX . "description";
	protected const DELETE_BTN_ID = self::PREFIX . "delete-button";
	protected const SUBMIT_BTN_ID = self::PREFIX . "submit-button";
	protected const CANCEL_BTN_ID = self::PREFIX . "cancel-button";
	protected const ADD_IMAGES_BTN_ID = self::PREFIX . "add-images-button";
	protected const SORTABLE_CONTAINER_ID = self::PREFIX . "sortable-container";
	protected const IMAGE_TEMPLATE_ID = self::PREFIX . "image-template";

	/**
	 * @throws ReflectionException
	 */
	public static function render(): string|false {
		$roomId = null;
		/** @var RoomGetRequest $room */
		$room = RoomGetRequest::createDTO();
		if ( isset( $_GET["id"] ) ) {
			$roomId = $_GET["id"];
			$room   = RoomService::getRoomById( $roomId );
		}

		ob_start();

		?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">

					<?php
					echo Container::beginDashboardContainer();
					echo Container::header(
						( $roomId ? "Opdater" : "Opret" ) . " lokale",
						false
					);
					echo Form::beginForm(
						self::FORM_ID,
						self::FIELDSET_ID,
						true,
						self::ALERT_CONTAINER_ID,
						false
					);
					echo Input::hiddenInput(
						self::HIDDEN_ROOM_ID,
						$roomId
					);

					echo Input::text(
						self::NAME_ID,
						"Navn",
						$room->getPropertyValue( RoomGetRequest::NAME_STR ),
						null,
						3,
						250,
					);

					echo Input::textarea(
						self::DESCRIPTION_ID,
						"Beskrivelse",
						$room->getPropertyValue( RoomGetRequest::DESCRIPTION_STR ),
						null,
						0,
						2500,
						null,
						6
					);

					?>
                    <button type="button" id="<?= self::ADD_IMAGES_BTN_ID ?>" class="btn btn-outline-primary">Tilføj
                        billeder
                    </button>

                    <div id="<?= self::IMAGE_TEMPLATE_ID ?>" class="hidden position-relative">
                        <button type="button" class="btn btn-outline-danger remove-image-btn m-3">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                        <img alt="room image" src="" class="rounded img-thumbnail border-box m-2"
                             style="height: 200px; width: 200px; object-fit: scale-down;"

                        >
                    </div>
					<?= self::imageTemplate( self::IMAGE_TEMPLATE_ID ) ?>

                    <div id="<?= self::SORTABLE_CONTAINER_ID ?>" class="d-flex mt-3 mb-3">
						<?php foreach ( $room->getPropertyValue( RoomGetRequest::IMAGE_ARR ) as $priority => $postId ) {
							echo self::imageTemplate(
								"",
								$priority,
								$postId
							);
						} ?>
                    </div>

					<?php

					echo Form::formControls(
						self::CANCEL_BTN_ID,
						self::SUBMIT_BTN_ID,
						$roomId ? "Opdater" : "Opret",
						"Annuller",
						false,
						$roomId ? self::DELETE_BTN_ID : null,
					);

					echo Form::endForm();
					echo Container::endDashboardContainer(); ?>
                </div>
            </div>
        </div>

		<?php return ob_get_clean();
	}

	protected static function imageTemplate(
		string $templateId,
		?int $index = null,
		?int $imagePostId = null
	): string|false {
		$strIndex   = $index ?? "";
		$strImageId = $imagePostId ?? "";
		ob_start(); ?>
        <div id="<?= $templateId ?>" class="<?= $strImageId ? "" : "hidden" ?> position-relative">
            <button type="button" class="btn btn-outline-danger remove-image-btn m-3">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
            <img
				<?php if ( $strImageId != null ) { ?>
                    src="<?= wp_get_attachment_image_url( $imagePostId ) ?>"
				<?php } ?>
                    class="rounded img-thumbnail border-box m-2"
                    style="height: 200px; width: 200px; object-fit: scale-down;"
                    alt="room image"
                    index="<?= $strIndex ?>" image-id="<?= $strImageId ?>"
            >
        </div>
		<?php return ob_get_clean();
	}

	protected static function inlineScript(): string|false {
		ob_start(); ?>
        <script>
            jQuery(() => {
                const roomId = jQuery("#<?= self::HIDDEN_ROOM_ID ?>").val();
                const updateEndpoint = "<?= RoomController::getEndpointUrl( RoomController::UPDATE_ENDPOINT ) ?>";
                const createEndpoint = "<?= RoomController::getEndpointUrl( RoomController::CREATE_ENDPOINT ) ?>";

                const deleteEndpoint = new URL("<?= RoomController::getEndpointUrl( RoomController::DELETE_ENDPOINT ) ?>");
                deleteEndpoint.searchParams.append("id", roomId);

                const imageIdAttr = "image-id";
                const indexAttr = "index"
                jQuery("#<?= self::SORTABLE_CONTAINER_ID ?>").sortable({
                    items: "div",
                    update: updateImageIndexes
                }).disableSelection();

                jQuery("#<?= self::ADD_IMAGES_BTN_ID ?>").on("click", () => {
                    MediaSelector.wpMediaSelector(
                        [],
                        "Vælg billeder",
                        "Tilføj billeder",
                        true
                    ).then((data) => {
                        let startIndex = jQuery("#<?= self::SORTABLE_CONTAINER_ID?>")
                            .find("div")
                            .length;
                        data.forEach((att) => {
                            const template = jQuery("#<?= self::IMAGE_TEMPLATE_ID ?>").clone();
                            template.prop("id", "");
                            template.find("img")
                                .attr("src", att.url)
                                .attr(imageIdAttr, att.id)
                                .attr(indexAttr, startIndex++);
                            template.find("button").on("click", (e) => {
                                e.preventDefault();
                                template.remove();
                            })
                            jQuery("#<?= self::SORTABLE_CONTAINER_ID ?>").append(template);
                            template.removeClass("hidden");
                        });
                    });
                });

                jQuery("#<?= self::SORTABLE_CONTAINER_ID ?>").find("div")
                    .each((_, elem) => {
                        jQuery(elem).find("button").on("click", () => jQuery(elem).remove());
                    })

                function updateImageIndexes() {
                    jQuery("#<?= self::SORTABLE_CONTAINER_ID ?>")
                        .find("div")
                        .each((index, elem) => {
                            jQuery(elem).find("img")
                                .attr(indexAttr, index);
                        })
                }

                new FormController(
                    "<?= wp_create_nonce( "wp_rest" ) ?>",
                    new FormElementSelectorOptions("<?= self::FORM_ID ?>"),
                    new FormActionRedirectOptions("<?= self::CANCEL_BTN_ID ?>", {
                        redirectTo: "<?= MenuHandler::getInstance()->SubMenuRooms->getUrl() ?>"
                    }),
                    new FormActionRedirectOptions("<?= self::SUBMIT_BTN_ID ?>", {
                        redirectTo: "<?= MenuHandler::getInstance()->SubMenuRooms->getUrl() ?>",
                        endpoint: roomId ? updateEndpoint : createEndpoint,
                        dtoCreator: roomId ? dtoUpdate : dtoCreate
                    }),
                    null,
                    new FormActionRedirectOptions("<?= self::DELETE_BTN_ID ?>", {
                        redirectTo: "<?= MenuHandler::getInstance()->SubMenuRooms->getUrl() ?>",
                        endpoint: deleteEndpoint.toString()
                    }),
                    new AlertFormOptions(""),
                );

                function dtoCreate() {
                    const images = {};
                    jQuery("#<?= self::SORTABLE_CONTAINER_ID ?>")
                        .find("div")
                        .each((_, elem) => {
                            const image = jQuery(elem).find("img");
                            images[image.attr(indexAttr)] = image.attr(imageIdAttr);
                        });

                    return {
						<?= RoomCreateRequest::NAME_STR?>: jQuery("#<?= self::NAME_ID ?>").val(),
						<?= RoomCreateRequest::DESCRIPTION_STR ?>: jQuery("#<?= self::DESCRIPTION_ID ?>").val(),
						<?= RoomCreateRequest::IMAGE_ARR ?>: images
                    }
                }

                function dtoUpdate() {
                    const images = {};
                    jQuery("#<?= self::SORTABLE_CONTAINER_ID ?>")
                        .find("div")
                        .each((_, elem) => {
                            const image = jQuery(elem).find("img");
                            images[image.attr(indexAttr)] = image.attr(imageIdAttr);
                        });

                    return {
						<?= RoomUpdateRequest::ID_INT ?>: jQuery("#<?= self::HIDDEN_ROOM_ID ?>").val(),
						<?= RoomUpdateRequest::NAME_STR?>: jQuery("#<?= self::NAME_ID ?>").val(),
						<?= RoomUpdateRequest::DESCRIPTION_STR ?>: jQuery("#<?= self::DESCRIPTION_ID ?>").val(),
						<?= RoomUpdateRequest::IMAGE_ARR ?>: images
                    }
                }

            });
        </script>
		<?php return ob_get_clean();
	}

	public static function getRequiredContent(): array {
		return [
			new ContentDependency(
				"",
				fn() => wp_enqueue_media(),
				ContentDependencyType::Script,
				ContentDependencyLoadingStyle::EnqeueFunctinon,
				[]
			),
			new ContentDependency(
				ContentDependency::INLINE_SCRIPT_HANDLE,
				self::inlineScript(),
				ContentDependencyType::Script,
				ContentDependencyLoadingStyle::InlineContent,
				[
					ScriptCollection::FORM_CONTROLLER,
					ScriptCollection::JQUERY_SORTABLE,
					ScriptCollection::MEDIA_SELECTOR
				],
			),
		];
	}
}