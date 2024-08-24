<?php

namespace KlintDev\WPBooking\Controllers;

use Exception;
use KlintDev\WPBooking\Attributes\RouteAttribute;
use KlintDev\WPBooking\DTO\Room\RoomCreateRequest;
use KlintDev\WPBooking\DTO\Room\RoomUpdateRequest;
use KlintDev\WPBooking\Interfaces\IController;
use KlintDev\WPBooking\Services\RoomService;
use WP_REST_Response;
use WP_REST_Request;
use WP_REST_Server;

#[RouteAttribute(self::CONTROLLER_PREFIX)]
class RoomController extends ControllerBase implements IController
{
    protected const CONTROLLER_PREFIX = "room";
    public const CREATE_ENDPOINT = "create";
    public const UPDATE_ENDPOINT = "update";
    public const DELETE_ENDPOINT = "delete";

    #[RouteAttribute(self::CREATE_ENDPOINT, WP_REST_Server::CREATABLE, true)]
    public function createRoom(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $params = $request->get_json_params();
            $dto = RoomCreateRequest::dtoFromArray($params);
            RoomService::createRoom($dto);

            return new WP_REST_Response([]);

        } catch (Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), 500);
        }
    }

    #[RouteAttribute(self::UPDATE_ENDPOINT, WP_REST_Server::EDITABLE, true)]
    public function updateRoom(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $params = $request->get_json_params();
            $dto = RoomUpdateRequest::dtoFromArray($params);
            RoomService::updateRoom($dto);

            return new WP_REST_Response([]);

        } catch (Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), 500);
        }
    }

    #[RouteAttribute(self::DELETE_ENDPOINT, WP_REST_Server::DELETABLE, true)]
    public function deleteRoom(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $roomId = $request->get_param("id");
            if ($roomId == null)
                throw new Exception("Room id not provided");
            RoomService::deleteRoom($roomId);

            return new WP_REST_Response([]);

        } catch (Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), 500);
        }
    }

    public static function getEndpointUrl(string $method, array $queryParams = []): string
    {
        return self::baseGetEndpointUrl(self::CONTROLLER_PREFIX, $method, $queryParams);
    }
}