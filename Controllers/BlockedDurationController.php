<?php

namespace KlintDev\WPBooking\Controllers;

use Exception;
use KlintDev\WPBooking\Attributes\RouteAttribute;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationCreateRequest;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationUpdateRequest;
use KlintDev\WPBooking\Interfaces\IController;
use KlintDev\WPBooking\Services\BlockedDurationService;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

#[RouteAttribute(BlockedDurationController::CONTROLLER_PREFIX)]
class BlockedDurationController extends ControllerBase implements IController
{
    public const CONTROLLER_PREFIX = "blockedduration";

    public const CREATE_ENDPOINT = "create";
    public const DELETE_ENDPOINT = "delete";
    public const UPDATE_ENDPOINT = "update";

    #[RouteAttribute(self::CREATE_ENDPOINT, WP_REST_Server::CREATABLE, true)]
    public function createBlockedDuration(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $dto = BlockedDurationCreateRequest::dtoFromArray($request->get_json_params());
            $dto->validate();
            BlockedDurationService::createBlockedDuration($dto);
            return new WP_REST_Response([]);
        } catch (Exception $e) {
            return new WP_REST_Response($e->getMessage(), 500);
        }
    }

    #[RouteAttribute(self::UPDATE_ENDPOINT, WP_REST_Server::EDITABLE, true)]
    public function updateBlockedDurations(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $dto = BlockedDurationUpdateRequest::dtoFromArray($request->get_json_params());

            BlockedDurationService::updateBlockedDuration($dto);
            return new WP_REST_Response([]);
        } catch (Exception $e) {
            return new WP_REST_Response($e->getMessage(), 500);
        }
    }

    #[RouteAttribute(self::DELETE_ENDPOINT, WP_REST_Server::DELETABLE, true)]
    public function deleteBlockedDurations(WP_REST_Request $request): WP_REST_Response
    {
        try {
            /** @var int $id */
            $id = $request->get_param("id");
            BlockedDurationService::deleteBlockedDurationById($id);
            return new WP_REST_Response([]);
        } catch (Exception $e) {
            return new WP_REST_Response($e->getMessage(), 500);
        }
    }


    public static function getEndpointUrl(string $method, array $queryParams = []): string
    {
        return self::baseGetEndpointUrl(self::CONTROLLER_PREFIX, $method, $queryParams);
    }
}